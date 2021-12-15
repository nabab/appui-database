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
  if (($primaries = $model->db->getPrimary($table))
    || ($primaries = $model->db->getUniqueKeys($table))
  ) {
    $res = [];
    $tablesData = [];
    foreach ($dbs as $db) {
      $model->db->change($db);
      if ($db === $model->db->getCurrent()) {
        echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Getting data of the table %s'), $model->db->cfn($table, $db));
        $fields = array_keys($model->db->getColumns($db . '.' . $table));
        if (!empty($excluded)) {
          $fields = array_values(array_filter($fields, function($field) use($excluded){
            return !in_array($field, $excluded);
          }));
        }
        $tablesData[$db] = $model->db->rselectAll($table, $fields);
        echo sprintf(_(' (%d rows)'), count($tablesData[$db])) . PHP_EOL;
      }
    }
    $model->db->change($currentDb);
    foreach ($tablesData as $db => $rows) {
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scanning data of the tables %s (%d rows to analyze)'), $db . '.' .$table, count($tablesData[$db])) . PHP_EOL;
      foreach ($tablesData[$db] as $row) {
        $id = array_intersect_key($row, array_combine($primaries,  $primaries));
        if (($idx = X::find($res, ['id' => $id])) === null) {
          $toadd = false;
          $tmp = ['id' => $id];
          $tmpIdx = [];
          foreach ($dbs as $d) {
            if ($d !== $db) {
              if (($i = X::find($tablesData[$d], $id)) === null) {
                $tmp[$d] = false;
                $tmp[$db] = $row;
                $toadd = true;
                $tmpIdx[$d] = false;
              }
              else {
                $tmpIdx[$d] = $i;
                if (json_encode($tablesData[$d][$i]) === json_encode($row)) {
                  $tmp[$d] = true;
                }
                else {
                  $diff = false;
                  $fieldsStructure = $model->db->getColumns($db . '.' . $table);
                  foreach ($row as $rf => $r) {
                    if (!is_null($r)
                      && !is_null($tablesData[$d][$i][$rf])
                      && (($fieldsStructure[$rf]['type'] === 'json')
                        || (str::isJson($r) && Str::isJson($tablesData[$d][$i][$rf])))
                    ){
                      if (json_decode($r, true) != json_decode($tablesData[$d][$i][$rf], true)){
                        $diff = true;
                        break;
                      }
                    }
                    elseif ($r !== $tablesData[$d][$i][$rf]) {
                      $diff = true;
                      break;
                    }
                  }
                  $tmp[$d] = $diff ? $tablesData[$d][$i] : true;
                  if ($diff) {
                    $tmp[$db] = $row;
                    $toadd = true;
                  }
                }
              }
            }
          }
          if ($toadd) {
            $res[] = $tmp;
          }
          foreach ($tmpIdx as $tdb => $tidx) {
            if ($tidx !== false) {
              array_splice($tablesData[$tdb], $tidx, 1);
            }
          }
        }
      }
    }
    echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scan of the tables %s completed, found %d conflicts'), $table, count($res)) . PHP_EOL;
    if (Dir::createPath($path)) {
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
        $numConflicts = count($res);
        yaml_emit_file($file, $res);
        $fileCreated = true;
        echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %s'), $file) . PHP_EOL;
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
