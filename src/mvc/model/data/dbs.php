<?php
use bbn\X;

$res = [
  'data' => []
];

if ($model->hasData('host_id', true)) {
  $hostId = $model->data['host_id'];
  $engine = $model->data['engine'] ?? '';
  try {
    $conn = $model->inc->dbc->connection($hostId, $engine);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
  }

  $dbsExcluded = [
    'sys',
    'test',
    'lost+found',
    'performance_schema',
    'mysql',
    '#mysql50#lost+found'
  ];
  $default = [
    'name' => null,
    'text' => null,
    'is_real' => false,
    'is_virtual' => false,
    'num_connections' => null,
    'num_procedures' => null,
    'num_tables' => null,
    'size' => null,
    'is_bbn' => null,
    'has_history' => null,
    'last_check' => null,
    'engine' => $conn ? $conn->getEngine() : ($engine ?: null),
  ];
  $dbs = $conn ? $conn->getDatabases() : [];
  $dbsOptions = $model->inc->dbc->fullDbs($hostId, $engine);
  $res['data'] = array_map(
    function ($a) use (&$dbs, $default, $conn) {
      $idx = array_search($a['name'], $dbs);
      $a['is_real'] = $idx !== false;
      if ($a['is_real']) {
        array_splice($dbs, $idx, 1);
        $a['size'] = $conn ? $conn->dbSize($a['name']) : 0;
      }

      $a['is_virtual'] = true;
      return array_merge($default, $a);
    },
    $dbsOptions
  );

  foreach ($dbs as $db) {
    if (!in_array($db, $dbsExcluded)) {
      $res['data'][] = array_merge($default, [
        'name' => $db,
        'text' => $db,
        'is_real' => true,
        'size' => $conn ? $conn->dbSize($db) : 0
      ]);
    }
  }

  X::sortBy($res['data'], 'name');
}

$res['total'] = \count($res['data']);
//$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;