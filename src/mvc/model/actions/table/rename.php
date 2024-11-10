<?php
/**
 * What is my purpose?
 *
 **/

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

  if ($conn->check()) {
    $tables =  $conn->getTables();
    $renamed = 0;
    $exists = in_array($model->data['name'], $tables);
    if (!$exists) {
      try {
        if ($conn->renameTable($model->data['table'], $model->data['name'])) {
          $renamed++;
        }
      }
      catch (\Exception $e) {
        return [
          'success' => false,
          'error' => $e->getMessage()
        ];
      }
    }

    if ($exists || $renamed) {
      $table_id = $dbc->tableId(
        $model->data['table'],
        $model->data['db'],
        $model->data['host'],
        $model->data['engine']
      );
      if ($table_id) {
        $opt = $model->inc->options->option($table_id);
        $change = [
          'code' => $model->data['name']
        ];

        if ($opt['text'] === $model->data['table ']) {
          $change['text'] = $model->data['name'];
        }

        if ($model->inc->options->setProp($table_id, $change)) {
          $renamed++;
        }
      }
      if ($renamed) {
        $model->data['res']['success'] = true;
      }
    }
  }
}

return $model->data['res'];
