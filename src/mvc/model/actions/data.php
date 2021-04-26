<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\Appui\Database;

/** @var $model \bbn\Mvc\Model*/
if ($model->hasData(['engine', 'host', 'db', 'table', 'name', 'res'], true)) {
  $dbc = new Database($model->db);
  try {
    $conn = $dbc->connection(
      $model->data['host'],
      $model->data['engine'],
	  	$model->data['db']
    );
  }
  catch (\Exception $e) {
    return [
      'success' => false,
      'error' => $e->getMessage()
    ];
  }
  $model->addInc('dbc', new bbn\Appui\Database($model->db));
  $model->addInc('conn', $conn);
}
