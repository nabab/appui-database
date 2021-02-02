<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\Mvc\Model*/
$res['success'] = false;
if ($model->hasData(['host', 'db', 'engine'], true) &&
    ($host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
) {
  $db_id = $model->inc->dbc->dbId($model->data['db'], $host_id);
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
    return $res;
  }
  if ($conn->check()) {
    try {
      $size = $conn->dbSize($model->data['db']);
    }
    catch (\Exception $e) {
      $res['error'] = $e->getMessage();
      return $res;
    }
    if ($db_id && ($info = $model->inc->options->option($db_id))) {
      if (isset($info['size']) && ($info['size'] !== $size)) {
        $info['size'] = $size;
        $model->inc->options->set($db_id, $info);
      }
    }
    $res = [
      'success' => true,
      'size' => $size ? \bbn\Str::saySize($size) : 0,
      'info' => $info ?? null
    ];
  }
}
return $res;
