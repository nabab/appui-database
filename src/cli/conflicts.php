<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
use \bbn\File\Dir;
use \bbn\Str;
$current_db = $ctrl->db->getCurrent();
if (dbsync::isInit()
  && ($tables = array_map(function($t){
    return substr($t, Strpos($t, '.') + 1);
  }, Dbsync::$tables))
  && ($dbs = array_keys($ctrl->inc->options->codeIds('sync', 'database', 'appui')))
  && ($path = $ctrl->dataPath('appui-database') . 'sync/conflicts/')
) {
  $t1 = [];
  $t2 = [];
  if ($files = Dir::getFiles($path)) {
    foreach ($tables as $table) {
      $found = false;
      foreach ($files as $f){
        preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.yml)$/', basename($f), $ff);
        if (!empty($ff) && ($table === $ff[1])) {
          $t2[] = $table;
          $found = true;
          break;
        }
      }
      if (!$found) {
        $t1[] = $table;
      }
    }
  }
  $tables = array_merge($t1, $t2);
  $filesCreated = 0;
  $numConflicts = 0;
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Number of databases: %d'), count($dbs)) . PHP_EOL;
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Number of tables: %d'), count($tables)) . PHP_EOL;
  foreach ($tables as $table) {
    if (($primaries = $ctrl->db->getPrimary($table))
      && ($structure = $ctrl->db->modelize($table))
    ) {
      $res = [];
      $tables_data = [];
      $custom = $ctrl->getPluginModel('conflicts', [], $ctrl->pluginUrl('appui-database'));
      foreach ($dbs as $db) {
        $ctrl->db->change($db);
        if ($db === $ctrl->db->getCurrent()) {
          echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Getting data of the table %s'), $ctrl->db->cfn($table, $db)) . PHP_EOL;
          $fields = array_keys($structure['fields']);
          if (!empty($custom)
            && !empty($custom['excluded'])
            && !empty($custom['excluded'][$table])
          ) {
            $excluded = $custom['excluded'][$table];
            $fields = array_values(array_filter($fields, function($field) use($excluded){
              return !in_array($field, $excluded);
            }));
          }
          $tables_data[$db] = $ctrl->db->rselectAll($table, $fields);
        }
      }
      $ctrl->db->change($current_db);
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scanning data of the tables %s'), $table) . PHP_EOL;
      foreach ($tables_data as $db => $rows) {
        foreach ($rows as $irow => $row) {
          $id = array_intersect_key($row, array_combine($primaries,  $primaries));
          if (($idx = X::find($res, ['id' => $id])) === null) {
            $toadd = false;
            $tmp = ['id' => $id];
            foreach ($dbs as $d) {
              if ($d !== $db) {
                if (($i = X::find($tables_data[$d], $id)) === null) {
                  $tmp[$d] = false;
                  $tmp[$db] = $row;
                  $toadd = true;
                }
                elseif (json_encode($tables_data[$d][$i]) === json_encode($row)) {
                  $tmp[$d] = true;
                }
                else {
                  $diff = false;
                  foreach ($row as $rf => $r) {
                    if (!is_null($r)
                      && !is_null($tables_data[$d][$i][$rf])
                      && (($structure['fields'][$rf]['type'] === 'json')
                        || (str::isJson($r) && Str::isJson($tables_data[$d][$i][$rf])))
                    ){
                      if (json_decode($r, true) != json_decode($tables_data[$d][$i][$rf], true)){
                        $diff = true;
                        break;
                      }
                    }
                    elseif ($r !== $tables_data[$d][$i][$rf]) {
                      $diff = true;
                      break;
                    }
                  }
                  $tmp[$d] = $diff ? $tables_data[$d][$i] : true;
                  if ($diff) {
                    $tmp[$db] = $row;
                    $toadd = true;
                  }
                }
              }
            }
            if ($toadd) {
              $res[] = $tmp;
            }
          }
        }
      }
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scan of the tables %s completed, found %d conflicts'), $table, count($res)) . PHP_EOL;
      if (dir::createPath($path)) {
        $file = $path.$table.'_'.date('Ymd_His').'.yml';
        echo date('d/m/Y H:i:s') . ' - ' . _('Checking an old data file') . PHP_EOL;
        if ($files = Dir::getFiles($path)) {
          foreach ($files as $f){
            preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.yml)$/', basename($f), $ff);
            if (!empty($ff) && ($table === $ff[1])) {
              Dir::delete($f);
              echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Removed %s'), $f) . PHP_EOL;
            }
          }
        }
        if (!empty($res)) {
          $numConflicts += count($res);
          yaml_emit_file($file, $res);
          $filesCreated++;
          echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %s'), $file) . PHP_EOL;
        }
      }
    }
  }
}
echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %d files'), $filesCreated) . PHP_EOL;
if ($current_db !== $ctrl->db->getCurrent()) {
  $ctrl->db->change($current_db);
}
echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Completed, found %s in total'), $numConflicts);