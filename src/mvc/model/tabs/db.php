<?php
/** @var $model \bbn\mvc\model */
$res['success'] = false;
if (
  $model->has_data(['host', 'db', 'engine'], true) &&
  ($host_id = $model->inc->dbc->host_id($model->data['host'], $model->data['engine']))
){
  $db_id = $model->inc->dbc->db_id($model->data['db'], $host_id);
  $res = [
    'success' => true,
    'host' => $model->data['host'],
    'db' => $model->data['db'],
    'db_id' => $db_id,
    'is_real' => \in_array($model->data['db'], $model->db->get_databases()),
    'is_virtual' => $db_id ? true : false,
		'info' => $model->inc->options->option($db_id)
  ];
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
  }
  if ($db_id)
  $res = [
    'success' => true,
    'host' => $model->data['host'],
    'db' => $model->data['db'],
    'db_id' => $db_id,
    'is_real' => \in_array($model->data['db'], $model->db->get_databases()),
    'is_virtual' => $db_id ? true : false,
		'info' => $model->inc->options->option($db_id)
  ];
  $res['size'] = $res['is_real'] ? \bbn\str::say_size($model->db->db_size($model->data['db'])) : 0;
}
return $res;