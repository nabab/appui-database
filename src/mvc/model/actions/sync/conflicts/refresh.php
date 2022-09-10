<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
use \bbn\File\Dir;
use \bbn\Str;
if (!Dbsync::isInit()) {
  throw new Exception(_('Dbsync is not initialized'));
}
if (!$model->hasData('table', true)) {
  throw new Exception(_('The "table" property is mandatory'));
}
$currentDb = $model->db->getCurrent();
$fileCreated = false;
$numConflicts = 0;
if (($dbs = array_keys($model->inc->options->codeIds('sync', 'database', 'appui')))
  && ($path = $model->dataPath('appui-database') . 'sync/conflicts/')
) {
  $table = $model->data['table'];
  $custom = $model->getPluginModel('conflicts', [], $model->pluginUrl('appui-database'));
  $excluded = [];
  if (!empty($custom)
    && !empty($custom['excluded'])
    && !empty($custom['excluded'][$table])
  ) {
    $excluded = $custom['excluded'][$table];
  }
  if (($primaries = $model->db->getPrimary($dbs[0] . '.' . $table))
    || ($primaries = $model->db->getUniqueKeys($dbs[0] . '.' . $table))
  ) {
    $fields = array_keys($model->db->getColumns($dbs[0] . '.' . $table));
    if (!empty($excluded)) {
      $fields = array_values(array_filter($fields, function($field) use($excluded){
        return !in_array($field, $excluded);
      }));
    }
    $res = [];
    $tablesData = [];
    foreach ($dbs as $db) {
      $model->db->change($db);
      if ($db === $model->db->getCurrent()) {
        echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Getting data of the table %s'), $model->db->cfn($table, $db));
        $tablesData[$db] = $model->db->rselectAll($table, $primaries);
        echo sprintf(_(' (%d rows)'), count($tablesData[$db])) . PHP_EOL;
      }
    }

    $is_single = count($primaries) === 1;
    $model->db->change($currentDb);
    $done = [];
    foreach ($tablesData as $db => $rows) {
      $model->db->change($db);
      $tot = count($rows);
      $num = 0;
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scanning data of the tables %s (%d rows to analyze)'), $db . '.' .$table, $tot) . PHP_EOL;
      X::log(date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scanning data of the tables %s (%d rows to analyze)'), $db . '.' .$table, $tot), 'sync');
      foreach ($rows as $i => $row) {
        if (!($i % 1000)) {
          if ($i) {
            X::log("$db : $num / $i / $tot", 'sync');
            $model->db->flush();
          }
        }

        if (!in_array($is_single ? current($row) : $row, $done, true)) {
          $done[] = $is_single ? current($row) : $row;
          $toadd = false;
          $tmp = ['id' => $row];
          $tmpIdx = [];
          $data = $model->db->rselect($table, $fields, $row);
          foreach ($dbs as $d) {
            if ($d !== $db) {
              $model->db->change($d);
              if (in_array($row, $tablesData[$d])) {
                $ddata = $model->db->rselect($table, $fields, $row);
                if ($ddata === $data) {
                  $tmp[$d] = true;
                }
                else {
                  if ($diff = array_diff_assoc($data, $ddata)) {
                    $tmp[$d] = $diff;
                    $toadd = true;
                  }
                }
              }
              else {
                $tmp[$d] = $data;
                $toadd = true;
              }

              $model->db->change($db);
            }
          }

          if ($toadd) {
            $num++;
            $res[] = $tmp;
          }
        }
        else {
          echo '*';
        }
      }
    }

    echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scan of the tables %s completed, found %d conflicts'), $table, count($res)) . PHP_EOL;
    if (Dir::createPath($path)) {
      $file = $path.$table.'_'.date('Ymd_His').'.yml';
      echo date('d/m/Y H:i:s') . ' - ' . _('Checking an old data file') . PHP_EOL;
      X::log(date('d/m/Y H:i:s') . ' - ' . _('Checking an old data file'), 'sync');
      if ($files = Dir::getFiles($path)) {
        foreach ($files as $f){
          preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.yml)$/', basename($f), $ff);
          if (!empty($ff) && ($table === $ff[1])) {
            Dir::delete($f);
            echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Removed %s'), $f) . PHP_EOL;
            X::log(date('d/m/Y H:i:s') . ' - ' . sprintf(_('Removed %s'), $f), 'sync');
          }
        }
      }
      if (!empty($res)) {
        $numConflicts = count($res);
        yaml_emit_file($file, $res);
        $fileCreated = true;
        echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %s'), $file) . PHP_EOL;
        X::log(date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %s'), $file), 'sync');
      }
    }
  }
  if ($currentDb !== $model->db->getCurrent()) {
    $model->db->change($currentDb);
  }
  return [
    'success' => true,
    'fileCreated' => $fileCreated,
    'numConflicts' => $numConflicts
  ];
}
return [
  'success' => false
];
