<?php
use bbn\appui\dbsync;
if (dbsync::is_init() && $model->has_data('id')) {
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