<?php
use bbn\Appui\Dbsync;
use \bbn\X;
$current_db = $model->db->getCurrent();
if (dbsync::isInit()
  && $model->hasData(['id', 'source'])
  && ($dbs = array_values($model->inc->options->getCodes('sync', 'database', 'appui')))
) {
  if (!is_array($model->data['id'])) {
    $model->data['id'] = [$model->data['id']];
  }
  sort($model->data['id'], SORT_NUMERIC);
  $todo = 0;
  $did = 0;
  foreach ($model->data['id'] as $id) {
    if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
        'id' => $id,
        'dbs' => $dbs
      ]))
      && !empty($m['success'])
      && !empty($m['diff'])
      && ($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/db', [
        'id' => $id,
        'db' => $dbs
      ]))
      && !empty($m['success'])
      && !empty($m['data'])
      && ($sync = Dbsync::$dbs->select(dbsync::$dbs_table, [], ['id' => $id]))
    ) {
      $todo++;
      if ($model->data['source'] === 'sync') {
        $data = X::jsonBase64Decode($sync->vals);
      }
      else if (($idx = X::find($m['data'], ['db' => $model->data['source']])) !== null) {
        $data = $m['data'][$idx]['data'];
      }
      foreach ($dbs as $db) {
        if ($db !== $model->data['source']) {
          $model->db->change($db);
          if (($db === $model->db->getCurrent())
            && (($idx = X::find($m['data'], ['db' => $db])) !== null)
          ) {
            $filters = $m['data'][$idx]['filters'];
            $cdata = $m['data'][$idx]['data'];
            $is_enabled = Dbsync::isEnabled();
            Dbsync::disable();
            // Delete
            if (empty($data)) {
              if (!empty($cdata) && !$model->db->delete($sync->tab, $filters)) {
                throw new Error("Impossibile to delete the row on '$db.{$sync->tab}' with these filters: " . X::getDump($filters));
              }
            }
            // Insert or update
            else {
              // Insert
              if (empty($cdata)) {
                if (!$model->db->insert($sync->tab, $data)) {
                  throw new Error("Impossibile to insert the row on '$db.{$sync->tab}' with this data: " . X::getDump($data));
                }
              }
              // Update
              else if ((json_encode($data) !== json_encode($cdata))
                && !$model->db->update($sync->tab, $data, $filters)
              ) {
                throw new Error("Impossibile to update the row on '$db.{$sync->tab}' with this data: " . X::getDump($data) . PHP_EOL . "and these filters: " . X::getDump($filters));
              }
            }
            if ($is_enabled) {
              Dbsync::enable();
            }
          }
        }
      }
      if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
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
if ($current_db !== $model->db->getCurrent()) {
  $model->db->change($current_db);
}
return $model->data['res'];