<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
use \bbn\File\Dir;
use \bbn\Str;
if (!dbsync::isInit()) {
  throw new Exception(_('Dbsync is not initialized'));
}
if (!$model->hasData('table', true)) {
  throw new Exception(_('The "table" property is mandatory'));
}
$currentDb = $model->db->getCurrent();
$filesCreated = 0;
$numConflicts = 0;
$custom = $model->getPluginModel('conflicts', [], $model->pluginUrl('appui-database'));
if (($dbs = array_keys($model->inc->options->codeIds('sync', 'database', 'appui')))
  && ($path = $model->dataPath('appui-database') . 'sync/conflicts/')
) {
  $table = $model->data['table'];
  if (($primaries = $model->db->getPrimary($table))
    && ($structure = $model->db->modelize($table))
  ) {
    $res = [];
    $tables_data = [];
    foreach ($dbs as $db) {
      $model->db->change($db);
      if ($db === $model->db->getCurrent()) {
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
        $tables_data[$db] = $model->db->rselectAll($table, $fields);
      }
    }
    $model->db->change($currentDb);
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
    if (dir::createPath($path)) {
      $file = $path.$table.'_'.date('Ymd_His').'.yml';
      if ($files = Dir::getFiles($path)) {
        foreach ($files as $f){
          preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.yml)$/', basename($f), $ff);
          if (!empty($ff) && ($table === $ff[1])) {
            Dir::delete($f);
          }
        }
      }
      if (!empty($res)) {
        $numConflicts += count($res);
        yaml_emit_file($file, $res);
        $filesCreated++;
      }
    }
  }
}
if ($currentDb !== $model->db->getCurrent()) {
  $model->db->change($currentDb);
}
return [
  'success' => !!$filesCreated,
  'num' => $numConflicts
];