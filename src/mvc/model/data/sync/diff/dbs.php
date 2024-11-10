<?php
/** @var bbn\Mvc\Model $model */
use bbn\Appui\Dbsync;
use bbn\X;
if (dbsync::isInit()
    && $model->hasData(['dbs', 'id'])
    && ($sync = Dbsync::$dbs->select(dbsync::$dbs_table, [], ['id' => $model->data['id']]))
) {
  if (!is_array($model->data['dbs'])) {
    $model->data['dbs'] = [$model->data['dbs']];
  }
  if ($dbs = array_values(array_filter($model->data['dbs'], function($d) use($sync){
    return $d !== $sync->db;
  }))) {
    if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/db', [
        'db' => $sync->db,
        'id' => $model->data['id']
      ]))
      && !empty($m['success'])
      && !empty($m['data'])
    ) {
      $model->data['res']['from'] = $m['data'][0];
    }
    if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/db', [
        'db' => $dbs,
        'id' => $model->data['id']
      ]))
      && !empty($m['success'])
      && !empty($m['data'])
    ) {
      $model->data['res']['data'] = $m['data'];
    }
  }
  $model->data['res']['success'] = !empty($model->data['res']['from']) && !empty($model->data['res']['data']);
}
return $model->data['res'];