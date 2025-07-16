<?php

/** @var bbn\Mvc\Model $model */
if ($model->hasData(['host_id', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
  && ($hostId = $model->inc->dbc->hostId($model->data['host_id'], $engine))
) {
	if (is_string($model->data['table'])) {
		$model->data['table'] = [$model->data['table']];
  }

  if (is_array($model->data['table'])) {
    $r = [];
    foreach ($model->data['table'] as $table) {
      $r[$table] = $model->inc->dbc->infoTable($table, $model->data['db'], $hostId, $engine);
    }

    return [
      'success' => true,
      'data' => $r
    ];
  }
}

return $model->data['res'];
