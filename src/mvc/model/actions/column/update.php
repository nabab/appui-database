<?php
/**
 * What is my purpose?
 *
 **/

/**@var $model \bbn\Mvc\Model*/

use bbn\X;

$res = ['success' => false];

$data =  [
  'name' => 'data',
  'oldname' => 'datz',
  'type' => 'binary',
  'oldtype' => 'binary',
  'null' => 0,
  'key' => 'PRI',
  'maxlength' => 16,
  'defaultExpression' =>  0,
  'position' => 1,
  'signed' => 1,
  'generation' => '',
  'extra' => ''
];

if ($model->hasData(['engine', 'db', 'host', 'table', 'name'])) {
  $database = new bbn\Appui\Database($model->db);
  $conn = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $mod = $conn->modelize($model->data['table']);
  $cfg = [];
  $colEndName = $model->data['data']['name'];

  if ($model->data['name'] === '') {
    $cfg['fields'][$colEndName] = $model->data['data'];
  } else {
    $cfg['fields'][$model->data['name']] = $model->data['data'];
    $cfg['fields'][$model->data['name']]['alter_type'] = 'modify';
  }

  try {
    if ($conn->alter($model->data['table'], $cfg)) {
      $conn->clearCache($model->data['table'], 'columns');
      // Also update options here
      $dbId = $database->dbId($model->data['db'], $model->data['host'], $model->data['engine']);
      $hostId = $database->hostId($model->data['host'], $model->data['engine']);
      $database->importTable($model->data['table'], $dbId, $model->data['host']);
      $res = [
        'success' => true
      ];
    }
  }
  catch (\Exception $e) {
    $res = [
      'error' => $e->getMessage(),
      'data' => $model->data,
      'mod' => $mod
    ];
  }
}

return $res;
