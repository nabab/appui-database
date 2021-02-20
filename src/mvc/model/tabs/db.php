<?php
/** @var $model \bbn\Mvc\Model */
$res['success'] = false;
if (
  $model->hasData(['host', 'db', 'engine'], true) &&
  ($host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
){
  $db_id = $model->inc->dbc->dbId($model->data['db'], $host_id);
  $res = [
    'success' => true,
    'host' => $model->data['host'],
    'db' => $model->data['db'],
    'db_id' => $db_id,
    'is_real' => \in_array($model->data['db'], $model->db->getDatabases()),
    'is_virtual' => $db_id ? true : false,
		'info' => $model->inc->options->option($db_id),
    'engine' => $model->data['engine']
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
    'is_real' => \in_array($model->data['db'], $model->db->getDatabases()),
    'is_virtual' => $db_id ? true : false,
		'info' => $model->inc->options->option($db_id),
    'engine' => $model->data['engine']
  ];
  $res['size'] = $res['is_real'] ? \bbn\Str::saySize($model->db->dbSize($model->data['db'])) : 0;
}
return $res;