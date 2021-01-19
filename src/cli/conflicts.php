<?php
use \bbn\appui\dbsync;
use \bbn\x;
use \bbn\file\dir;
use \bbn\str;
$current_db = $ctrl->db->get_current();
if (dbsync::is_init()
  && ($tables = array_map(function($t){
    return substr($t, strpos($t, '.') + 1);
  }, dbsync::$tables))
  && ($dbs = array_keys($ctrl->inc->options->code_ids('sync', 'databases', 'appui')))
  && ($path = $ctrl->data_path('appui-databases') . 'sync/conflicts/')
) {
  foreach ($tables as $table) {
    if (($primaries = $ctrl->db->get_primary($table))
      && ($structure = $ctrl->db->modelize($table))
    ) {
      $res = [];
      $tables_data = [];
      foreach ($dbs as $db) {
        $ctrl->db->change($db);
        if ($db === $ctrl->db->get_current()) {
          $tables_data[$db] = $ctrl->db->rselect_all($table);
        }
      }
      $ctrl->db->change($current_db);
      foreach ($tables_data as $db => $rows) {
        foreach ($rows as $irow => $row) {
          $id = array_intersect_key($row, array_combine($primaries,  $primaries));
          if (($idx = x::find($res, ['id' => $id])) === null) {
            $toadd = false;
            $tmp = [
              'id' => $id
            ];
            foreach ($dbs as $d) {
              if ($d !== $db) {
                if (($i = x::find($tables_data[$d], $id)) === null) {
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
                        || (str::is_json($r) && str::is_json($tables_data[$d][$i][$rf])))
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
      if (dir::create_path($path)) {
        $file = $path.$table.'_'.date('Ymd_His').'.json';
        if ($files = dir::get_files($path)) {
          foreach ($files as $f){
            preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.j{1}s{1}o{1}n{1})$/', basename($f), $ff);
            if (!empty($ff) && ($table === $ff[1])) {
              dir::delete($f);
            }
          }
        }
        if (!empty($res)) {
          file_put_contents($file, json_encode($res, JSON_PRETTY_PRINT));
        }
      }
    }
  }
}
if ($current_db !== $ctrl->db->get_current()) {
  $ctrl->db->change($current_db);
}