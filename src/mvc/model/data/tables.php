<?php
use bbn\X;
use bbn\Str;

$res = [
  'data' => [],
  'total' => 0
];
if ($model->hasData(['host_id', 'db', 'engine'], true)) {
  $engine = Str::isUid($model->data['engine']) ?
    $model->inc->dbc->engineCode($model->data['engine']) :
    $model->data['engine'];
  $engineId = Str::isUid($model->data['engine']) ?
    $model->data['engine'] :
    $model->inc->dbc->engineId($engine);
  $hostId = $model->inc->dbc->hostId($model->data['host_id'], $engine);
  $db = $model->data['db'];
  $dbId = $model->inc->dbc->dbId($db, $hostId, $engine);
  $tables = [];
  if (!empty($engine)
    && !empty($engineId)
    && !empty($hostId)
  ) {
    $isSqlite = $engine === 'sqlite';
    try {
      $conn = $model->inc->dbc->connection($hostId, $engine, $db);
      $tables = $conn->getTables($db);
    }
    catch (\Exception $e) {
      if (!$isSqlite) {
        $res['error'] = $e->getMessage();
      }
    }

    $otables = array_map(fn($d) => $d['name'], $model->inc->dbc->tables($db, $hostId, $engine) ?: []);
    array_push($tables, ...array_values(array_filter($otables, fn($d) => !in_array($d, $tables))));
    $res['total'] = count($tables);
    sort($tables);
    if (isset($model->data['start'], $model->data['limit'])) {
      $tables = array_slice($tables, $model->data['start'], $model->data['limit']);
    }

    $res['data'] = array_map(
      fn($a)  => $model->inc->dbc->infoTable($a, $db, $hostId, $engine),
      $tables
    );
  }
}

return $res;