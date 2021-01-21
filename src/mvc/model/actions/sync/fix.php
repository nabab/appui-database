<?php
use bbn\appui\dbsync;
use \bbn\x;
$current_db = $model->db->get_current();
if (dbsync::is_init()
  && $model->has_data(['id', 'source'])
  && ($dbs = array_map(function($a){
    return $a['code'];
  }, $model->inc->options->full_options('sync', 'database', 'appui')))
) {
  if (!is_array($model->data['id'])) {
    $model->data['id'] = [$model->data['id']];
  }
  sort($model->data['id'], SORT_NUMERIC);
  $todo = 0;
  $did = 0;
  foreach ($model->data['id'] as $id) {
    if (($m = $model->get_model(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
        'id' => $id,
        'dbs' => $dbs
      ]))
      && !empty($m['success'])
      && !empty($m['diff'])
      && ($m = $model->get_model(APPUI_DATABASES_ROOT.'data/sync/diff/db', [
        'id' => $id,
        'db' => $dbs
      ]))
      && !empty($m['success'])
      && !empty($m['data'])
      && ($sync = dbsync::$dbs->select(dbsync::$dbs_table, [], ['id' => $id]))
    ) {
      $todo++;
      if ($model->data['source'] === 'sync') {
        $data = x::json_base64_decode($sync->vals);
      }
      else if (($idx = x::find($m['data'], ['db' => $model->data['source']])) !== null) {
        $data = $m['data'][$idx]['data'];
      }
      foreach ($dbs as $db) {
        if ($db !== $model->data['source']) {
          $model->db->change($db);
          if (($db === $model->db->get_current())
            && (($idx = x::find($m['data'], ['db' => $db])) !== null)
          ) {
            $filters = $m['data'][$idx]['filters'];
            $cdata = $m['data'][$idx]['data'];
            $is_enabled = dbsync::is_enabled();
            dbsync::disable();
            // Delete
            if (empty($data)) {
              if (!empty($cdata) && !$model->db->delete($sync->tab, $filters)) {
                throw new Error("Impossibile to delete the row on '$db.{$sync->tab}' with these filters: " . x::get_dump($filters));
              }
            }
            // Insert or update
            else {
              // Insert
              if (empty($cdata)) {
                if (!$model->db->insert($sync->tab, $data)) {
                  throw new Error("Impossibile to insert the row on '$db.{$sync->tab}' with this data: " . x::get_dump($data));
                }
              }
              // Update
              else if ((json_encode($data) !== json_encode($cdata)) && !$model->db->update($sync->tab, $data, $filters)) {
                throw new Error("Impossibile to update the row on '$db.{$sync->tab}' with this data: " . x::get_dump($data) . PHP_EOL . "and these filters: " . x::get_dump($filters));
              }
            }
            if ($is_enabled) {
              dbsync::enable();
            }
          }
        }
      }
      if (($m = $model->get_model(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
          'id' => $id,
          'dbs' => $dbs
        ]))
        && !empty($m['success'])
        && empty($m['diff'])
      ){
        $did++;
      }
    }
  }
  $model->data['res']['success'] = $todo === $did;
}
if ($current_db !== $model->db->get_current()) {
  $model->db->change($current_db);
}
return $model->data['res'];