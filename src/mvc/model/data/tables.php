<?php
$res = [
  'data' => []
];
if ($model->hasData(['host', 'db', 'engine'], true)) {
  $tables = [];
  if ( ($host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine'])) &&
    ($db_id = $model->inc->dbc->dbId($model->data['db'], $host_id, $model->data['engine']))
  ){
    $tables = $model->inc->dbc->fullTables($db_id);
    foreach ( $tables as $t ){
      $res['data'][] = \bbn\X::mergeArrays($t, [
        'is_real' => false,
        'is_virtual' => true
      ]);
    }
  }
  try {
    $dbconn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
    return [
      'success' => false,
      'error' => $e->getMessage()
    ];
  }
  if ($dbconn->check() && ($real_tables = $dbconn->getTables($model->data['db']))) {
    foreach ( $real_tables as $i => $t ){
      $idx = \bbn\X::find($res['data'], ["name" => $t]);
      $num_rcolumns = \count($dbconn->getColumns($model->data['db'].'.'.$t));
      $keys = $dbconn->getKeys($model->data['db'].'.'.$t);
      $num_rkeys = $keys && $keys['keys'] ? \count($keys['keys']) : 0;
      $num_vcolumns = \bbn\X::getField($tables, ['name' => $t], 'num_columns') ?: 0;
      $num_vkeys = \bbn\X::getField($tables, ['name' => $t], 'num_keys') ?: 0;
      if ( $idx !== null ){
        $res['data'][$idx]['num_real_columns'] = $num_rcolumns;
        $res['data'][$idx]['num_real_keys'] = $num_rkeys;
        $res['data'][$idx]['is_real'] = true;
        $res['data'][$idx]['num_columns'] = $num_vcolumns;
        $res['data'][$idx]['num_keys'] = $num_vkeys;
      }
      else{
        $res['data'][] = [
          'id' => null,
          'text' => $t,
          'name' => $t,
          'num_columns' => $num_vcolumns,
          'num_keys' => $num_vkeys,
          'is_real' => true,
          'is_virtual' => false,
          'num_real_columns' => $num_rcolumns,
          'num_real_keys' => $num_rkeys
        ];
      }
    }
  }
}
$res['total'] = \count($res['data']);
$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;