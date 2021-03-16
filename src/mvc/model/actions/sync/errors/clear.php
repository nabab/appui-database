<?php
use bbn\Appui\Dbsync;
$current_db = $model->db->getCurrent();
$deleted = 0;
if (dbsync::isInit()
  && ($dbs = array_values($model->inc->options->getCodes('sync', 'database', 'appui')))
  && ($rows = Dbsync::$dbs->selectAll(dbsync::$dbs_table, [], ['state' => 5]))
) {
  foreach ($rows as $row) {
    if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
        'id' => $row->id,
        'dbs' => $dbs
      ]))
      && !empty($m['success'])
      && empty($m['diff'])
      && Dbsync::$dbs->delete(dbsync::$dbs_table, ['id' => $row->id])
    ) {
      $deleted++;
    }
  }
  return [
    'success' => true,
    'deleted' => $deleted
  ];
}
return ['success' => false];