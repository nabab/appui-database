<?php
/** @var $model \bbn\mvc\model */
if ( $host_id = $model->inc->dbc->host_id($model->data['host']) ){
  $o = $model->inc->options->option($host_id);
  $tmp = gethostbyname($o['text']);
  $id = '';
  if (\bbn\str::is_ip($tmp)) {
    $id = $tmp;
  }

  $dbs = $model->inc->dbc->full_dbs($host_id);

  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine']);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }
  if (empty($o['databases'])) {
    $dbs = $model->get_model($model->plugin_url('appui-database').'/data/dbs', [
      'host_id' => $host_id
    ]);
  }

  return [
    'dbs' => $dbs,
    'ip' => $id,
    'host' => $model->data['host'],
		'info' => $o
  ];
}
return false;