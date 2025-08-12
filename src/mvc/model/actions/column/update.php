<?php
use bbn\X;

$res = ['success' => false];
if ($model->hasData(['engine', 'db', 'host', 'table', 'data'], true)) {
  $engine = $model->data['engine'];
  $db = $model->data['db'];
  $host = $model->data['host'];
  $table = $model->data['table'];
  $data = $model->data['data'];
  try {
    $conn = $model->inc->dbc->connection($host, $engine, $db);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
    return $res;
  }

  $currentCfg = $conn->modelize($table);
  die(var_dump($currentCfg, $data));
  $cfg = [];
  $colEndName = $model->data['data']['name'];

  if ($model->data['name'] === '') {
    $cfg['fields'][$colEndName] = $model->data['data'];
  }
  else {
    $cfg['fields'][$model->data['name']] = $model->data['data'];
    $cfg['fields'][$model->data['name']]['alter_type'] = 'modify';
  }

  try {
    if ($conn->alter($model->data['table'], $cfg)) {
      $conn->clearCache($table, 'columns');
      $res = [
        'success' => true
      ];
    }
  }
  catch (\Exception $e) {
    $res = [
      'error' => $e->getMessage(),
      'data' => $model->data,
      'mod' => $mod
    ];
  }
}

return $res;
