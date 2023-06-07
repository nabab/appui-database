<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/

$res = [
  'success' => false
];

if ($model->hasData('host', 'engine', 'db', 'table', 'column')) {
  $database = new bbn\Appui\Database($model->db);
  $connection = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $cfg = [];
  $colName = $model->data['column']['name'];

  $cfg['fields'][$colName] = $model->data['column'];
  $cfg['fields'][$colName]['alter_type'] = 'drop';

  try {
    if ($connection->alter($model->data['table'], $cfg)) {
      $res['success'] = true;
		  $connection->clearCache($model->data['table'], 'columns');
      //also update options here
      $dbId = $database->dbId($model->data['db'], $model->data['host'], $model->data['engine']);
      $hostId = $database->hostId($model->data['host'], $model->data['engine']);
      $database->importTable($model->data['table'], $dbId, $model->data['host']);
    }
  } catch (\Exception $e) {
    $res['error'] = $e;
  }
}

return $res;