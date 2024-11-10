<?php

/** @var bbn\Mvc\Model $model */
if ($model->inc->conn->check()) {
	if (is_string($model->data['db'])) {
		$model->data['db'] = [$model->data['db']];
  }

  if (is_array($model->data['db'])) {
    $d = [];
    foreach ($model->data['db'] as $db) {
      if ($model->inc->conn->change($db)) {
        $tables = $model->inc->conn->getTables();
        $num_real_tables = \count($tables);
        $d[] = [
          'id' => null,
          'name' => $db,
          'text' => $db,
          'is_real' => true,
          'is_virtual' => false,
          'num_tables' => $num_real_tables
        ];
      }
    }
    if (count($model->data['db']) === 1) {
      $d = $d[0];
    }

    $model->data['res']['data'] = $d;
  }
}
return $model->data['res'];
