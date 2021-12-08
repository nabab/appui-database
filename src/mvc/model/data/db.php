<?php
/*
 * Describe what it does
 *
 **/

/** @var $model \bbn\Mvc\Model */

$res = [
  'data' => []
];

if (!$model->hasData(['host_id', 'db', true])) {
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id'], null, $model->data['db']);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }
  $tables = $conn->getTables();
  $num_real_tables = \count($tables);
  if ( $idx !== null ){
    $res['data'][$idx]['num_real_tables'] = $num_real_tables;
    $res['data'][$idx]['is_real'] = true;
  }
  else{
    $res['data'][] = [
      'id' => null,
      'name' => $db,
      'text' => $db,
      'num_tables' => 0,
      'is_real' => true,
      'is_virtual' => false,
      'num_real_tables' => $num_real_tables
    ];
  }
}
$res['total'] = \count($res['data']);
$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;