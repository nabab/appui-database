<?php
/*
 * Describe what it does
 *
 **/
use bbn\X;

/** @var bbn\Mvc\Model $model */

$res = [
  'data' => []
];

if ($model->hasData('host_id', true)) {
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id']);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }
  $real_dbs = $conn->getDatabases();
  $db_excluded = [
    'sys',
    'test',
    'lost+found',
    'performance_schema',
    'mysql',
    '#mysql50#lost+found'
  ];
  $full_dbs = $model->inc->dbc->fullDbs($model->data['host_id']);
  $res['data'] = array_map(
    function ($a) use (&$real_dbs) {
      $idx = array_search($a['name'], $real_dbs);
      $a['is_real'] = $idx !== false;
      if ($a['is_real']) {
        array_splice($real_dbs, $idx, 1);
      }
      $a['is_virtual'] = true;
      return $a;
    },
    $full_dbs
  );
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
    'last_check' => null
  ];
  if ($model->hasData('engine', true)) {
    $default['engine'] = $model->data['engine'];
  }

  foreach ( $real_dbs as $i => $db ){
    if (!in_array($db, $db_excluded)) {
      $res['data'][] = [
        'name' => $db,
        'text' => $db,
        'is_real' => true
      ];
    }
  }
  foreach ($res['data'] as $i => $r) {
    $res['data'][$i] = array_merge($default, $r);
  }
  X::sortBy($res['data'], 'name');
}
$res['total'] = \count($res['data']);
//$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;