<?php
$res = [
  'data' => []
];
if ($model->has_data(['host', 'db', 'engine'], true)) {
  $tables = [];
  if ( ($host_id = $model->inc->dbc->host_id($model->data['host'], $model->data['engine'])) &&
    ($db_id = $model->inc->dbc->db_id($model->data['db'], $host_id))
  ){
    $tables = $model->inc->dbc->full_tables($db_id);
    foreach ( $tables as $t ){
      $res['data'][] = \bbn\x::merge_arrays($t, [
        'is_real' => false,
        'is_virtual' => true
      ]);
    }
  }
  try {
    $dbconn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
  }
  if ($dbconn
      && ($real_tables = $dbconn->get_tables($model->data['db']))
  ){
    foreach ( $real_tables as $i => $t ){
      $idx = \bbn\x::find($res['data'], ["name" => $t]);
      $num_rcolumns = \count($dbconn->get_columns($dbconn->tfn($t, $model->data['db'])));
      $keys = $dbconn->get_keys($dbconn->tfn($t, $model->data['db']));
      $num_rkeys = $keys && $keys['keys'] ? \count($keys['keys']) : 0;
      if ( $idx !== null ){
        $res['data'][$idx]['num_real_columns'] = $num_rcolumns;
        $res['data'][$idx]['num_real_keys'] = $num_rkeys;
        $res['data'][$idx]['is_real'] = true;
      }
      else{
        $res['data'][] = [
          'id' => null,
          'text' => $t,
          'name' => $t,
          'num_columns' => 0,
          'num_keys' => 0,
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