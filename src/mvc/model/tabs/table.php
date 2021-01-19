<?php
/** @var $model \bbn\mvc\model */
$res['success'] = false;
if (
  $model->has_data(['host', 'db', 'engine'], true) &&
  ($host_id = $model->inc->dbc->host_id($model->data['host'], $model->data['engine']))
){
  if ($db_id = $model->inc->dbc->db_id($model->data['db'], $host_id)) {
    $table_id = $model->inc->dbc->db_id($model->data['table'], $db_id);
  }

  $structure = $model->inc->dbc->modelize($model->data['table'], $model->data['db'], $host_id);
  $constraints = [];
  $externals = [];
  foreach ( $structure['keys'] as $k => $a ){
    if (
      $a['unique'] &&
      (count($a['columns']) === 1) &&
      ($tmp = $model->db->get_foreign_keys($a['columns'][0], $model->data['table'], $model->data['db']))
    ){
      $externals[$a['columns'][0]] = $tmp;
    }
    if ( !empty($a['ref_column']) ){
      $constraints[$a['columns'][0]] = [
        'column' => $a['ref_column'],
        'table' => $a['ref_table'],
        'db' => $a['ref_db']
      ];
    }
  }
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
  }
  $res = [
    'success' => true,
    'host' => $model->data['host'],
    'db' => $model->data['db'],
    'db_id' => $db_id ?: null,
    'table' => $model->data['table'],
    'table_id' => $table_id ?? null,
    'is_real' => \in_array($model->data['table'], array_keys($conn->get_tables($model->data['db']))),
    'is_virtual' => isset($table_id) ? true : false,
		'info' => isset($table_id) ? $model->inc->options->option($table_id) : null,
    'structure' => $structure,
    'externals' => $externals,
    'constraints' => $constraints,
    'history' => false
  ];
  if (($model->data['db'] === $model->db->get_current())
      && class_exists('bbn\\appui\\history')
      && \bbn\appui\history::has_history($model->db)
      && ($tmp = \bbn\appui\history::get_table_cfg($model->data['db'].'.'.$model->data['table']))
  ) {
    $res['history'] = $tmp;
  }
 
  if ($res['is_real']) {
    $res['size'] = \bbn\str::say_size($conn->table_size($model->data['table']));
    $res['count'] = $conn->count($model->data['table']);
  }
}
return $res;