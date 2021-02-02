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
  foreach ($tables as $table) {
    if (($primaries = $ctrl->db->getPrimary($table))
      && ($structure = $ctrl->db->modelize($table))
    ) {
      $res = [];
      $tables_data = [];
      foreach ($dbs as $db) {
        $ctrl->db->change($db);
        if ($db === $ctrl->db->getCurrent()) {
          $tables_data[$db] = $ctrl->db->rselectAll($table);
        }
      }
      $ctrl->db->change($current_db);
      foreach ($tables_data as $db => $rows) {
        foreach ($rows as $irow => $row) {
          $id = array_intersect_key($row, array_combine($primaries,  $primaries));
          if (($idx = X::find($res, ['id' => $id])) === null) {
            $toadd = false;
            $tmp = [
              'id' => $id
            ];
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
        $file = $path.$table.'_'.date('Ymd_His').'.json';
        if ($files = Dir::getFiles($path)) {
          foreach ($files as $f){
            preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.j{1}s{1}o{1}n{1})$/', basename($f), $ff);
            if (!empty($ff) && ($table === $ff[1])) {
              Dir::delete($f);
            }
          }
        }
        if (!empty($res)) {
          file_put_contents($file, Json_encode($res, JSON_PRETTY_PRINT));
        }
      }
    }
  }
}
if ($current_db !== $ctrl->db->getCurrent()) {
  $ctrl->db->change($current_db);
}