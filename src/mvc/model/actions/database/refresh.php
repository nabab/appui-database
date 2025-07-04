<?php

/** @var bbn\Mvc\Model $model */
if ($model->hasData(['host_id', 'db'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
  && ($hostId = $model->inc->dbc->hostId($model->data['host_id'], $engine))
) {
	if (is_string($model->data['db'])) {
		$model->data['db'] = [$model->data['db']];
  }

  if (is_array($model->data['db'])) {
    $r = [];
    foreach ($model->data['db'] as $db) {
      $r[$db] = $model->inc->dbc->infoDb($db, $hostId, $engine);
    }

    return [
      'success' => true,
      'data' => $r
    ];
  }
}

return $model->data['res'];
