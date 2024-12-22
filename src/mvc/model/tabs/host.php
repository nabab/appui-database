<?php
/** @var bbn\Mvc\Model $model */
if ( $host_id = $model->inc->dbc->hostId($model->data['host']) ){
  $o = $model->inc->options->option($host_id);
  $tmp = gethostbyname($o['text']);
  $id = '';
  if (\bbn\Str::isIp($tmp)) {
    $id = $tmp;
  }

  $dbs = $model->inc->dbc->fullDbs($host_id);

  try {
    /** @var bbn\Db $conn */
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine']);
  }
  catch (Exception $e) {
    return ['error' => $e->getMessage()];
  }

  if (empty($o['databases'])) {
    $dbs = $model->getModel($model->pluginUrl('appui-database').'/data/dbs', [
      'host_id' => $host_id
    ]);
  }

  try {
    $size = $conn ? $conn->dbSize($model->data['db'] ?? $conn->getCurrent()) : 0;
  }
  catch (Exception $e) {
    $size = 0;
  }

  return [
    'success' => true,
    'size' => $size,
    'types' => bbn\Db\Languages\Sql::$types,
    'predefined' => $model->inc->options->fullOptions('pcolumns', $model->data['engine'], 'database', 'appui'),
    'dbs' => $dbs,
    'ip' => $id,
    'host' => $model->data['host'],
    'info' => $o,
    'engine' => $model->data['engine']
  ];
}

return ['success' => false];