<?php

$res = ['success' => false];
if ($model->hasData(['engine', 'db', 'host', 'table'], true)) {
  try {
    $conn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
    return $res;
  }

  $data = [];
  if ($model->hasData('name', true)) {
    try {
	    $data = $conn->getColumnValues($model->data['table'], $model->data['name']);
    }
    catch (\Exception $e) {
      $res['error'] = $e->getMessage();
      return $res;
    }
  }

  $res = [
    'success' => true,
    'num' => count($data)
  ];
}


return $res;
