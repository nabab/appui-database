<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$res = [];

if (!empty($model->data['data']) && X::hasProps($model->data['data'], ['host', 'db', 'engine', 'table'], true)) {
  $host_id = $model->inc->dbc->hostId($model->data['data']['host'], $model->data['data']['engine']);
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['data']['engine'], $model->data['data']['db']);
  }
  catch (\Exception $e) {
  }

  $structure = $conn->modelize($model->data['data']['table']);
  foreach ($structure['fields'] as $colname => $col) {
    if ($col['key'] && (X::indexOf($col['type'], "text") === -1) && (X::indexOf($col['type'], "blob") === -1))
      $col['text'] = $col['value'] = $colname;
      $res[] = $col;
    }
  }

  return ['data' => $res];
}
