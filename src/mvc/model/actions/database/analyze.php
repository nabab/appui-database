<?php
use bbn\Appui\Database;

/** @var bbn\Mvc\Model $model */
if ($model->hasData(['host_id', 'db'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
	if (is_string($model->data['db'])) {
		$model->data['db'] = [$model->data['db']];
  }

  if (is_array($model->data['db'])) {
    $d = [];
    foreach ($model->data['db'] as $db) {
      if (($conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $db))
        && $conn->check()
      ) {
        $tables = $conn->getTables();
        $numRealTables = count($tables);
        $d[] = [
          'id' => null,
          'name' => $db,
          'text' => $db,
          'is_real' => true,
          'is_virtual' => false,
          'num_tables' => $numRealTables
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
