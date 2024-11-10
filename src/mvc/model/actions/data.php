<?php
/**
 * What is my purpose?
 *
 **/

/** @var bbn\Mvc\Model $model */

use bbn\Appui\Database;

/** @var bbn\Mvc\Model $model */
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
