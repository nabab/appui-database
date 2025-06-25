<?php
use bbn\Appui\Database;

set_time_limit(600);
if ($model->hasData(['host_id', 'db'])
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $model->data['db']);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }

  if ($conn->check()
    && $model->inc->dbc->importDb($model->data['db'], $model->data['host_id'], true)
  ) {
    return ['success' => true];
  }
}

return ['success' => false];
