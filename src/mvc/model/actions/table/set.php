<?php
/**
 * What is my purpose?
 *
 **/

use bbn\Appui\Database;

/** @var $model \bbn\Mvc\Model*/
if ($model->hasData(['engine', 'host', 'db', 'table', 'type', 'value', 'res'], true)) {
  $dbc = new Database($model->db);
  $table_id = $dbc->tableId(
  	$model->data['table'],
  	$model->data['db'],
  	$model->data['host'],
  	$model->data['engine']
  );
  switch ($model->data['type']) {
    case 'title':
      if ($table_id && \is_string($model->data['value'])) {
        $model->data['res']['success'] = $model->inc->options->setText($table_id, $model->data['value'] ?: $demol->data['table']);
      }
      break;
    case 'dcolumns':
      if ($table_id && \is_array($model->data['value'])) {
        $model->data['res']['success'] = $model->inc->options->setProp($table_id, ['dcolumns' => $model->data['value']]);
      }
      break;
    case 'comment':
      $model->data['res']['error'] = "This feature is not ready yet";
      break;
  }
}

return $model->data['res'];
