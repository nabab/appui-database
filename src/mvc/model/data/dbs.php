<?php
/*
 * Describe what it does or you're a pussy
 *
 **/
use bbn\x;

/** @var $model \bbn\mvc\model */

$res = [
  'data' => []
];

if ($model->has_data('host_id', true)) {
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id']);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }
  $real_dbs = $conn->get_databases();
  $db_excluded = [
    'sys',
    'test',
    'lost+found',
    'performance_schema',
    'mysql',
    '#mysql50#lost+found'
  ];
  $full_dbs = $model->inc->dbc->full_dbs($model->data['host_id']);
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
  x::sort_by($res['data'], 'name');
}
$res['total'] = \count($res['data']);
//$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;