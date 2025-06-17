<?php
use bbn\X;

$res = ['success' => false];
if ( $host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']) ){
  $o = $model->inc->options->option($host_id);
  $tmp = gethostbyname($o['text']);
  $id = '';
  if (\bbn\Str::isIp($tmp)) {
    $id = $tmp;
  }

  $dbs = $model->inc->dbc->fullDbs($host_id, $model->data['engine']);

  try {
    /** @var bbn\Db $conn */
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine']);
  }
  catch (Exception $e) {
    //$res['error'] = $e->getMessage();
  }

  if (empty($o['databases'])) {
    $dbs = $model->getModel($model->pluginUrl('appui-database').'/data/dbs', [
      'host_id' => $host_id,
      'engine' => $model->data['engine']
    ]);
  }

  try {
    $size = $conn ? $conn->dbSize($model->data['db'] ?? $conn->getCurrent()) : 0;
  }
  catch (Exception $e) {
    $size = 0;
  }

  $c = 'bbn\\Db\\Languages\\'.ucfirst($model->data['engine']);
  return X::mergeArrays($res, [
    'success' => true,
    'size' => $size,
    'types' => $c::$types,
    'predefined' => $model->inc->options->fullOptions('pcolumns', $model->data['engine'], 'engines', 'database', 'appui'),
    'dbs' => $dbs,
    'ip' => $id,
    'host' => $model->data['host'],
    'info' => $o,
    'engine' => $model->data['engine']
  ]);
}

return $res;