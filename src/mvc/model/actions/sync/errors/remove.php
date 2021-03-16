<?php
use bbn\Appui\Dbsync;
if (dbsync::isInit() && $model->hasData('id')) {
  if (!is_array($model->data['id'])) {
    $model->data['id'] = [$model->data['id']];
  }
  foreach ($model->data['id'] as $id) {
    if (!dbsync::$dbs->delete(dbsync::$dbs_table, ['id' => $id])) {
      return ['success' => false];
    }
  }
  return ['success' => true];
}
return ['success' => false];