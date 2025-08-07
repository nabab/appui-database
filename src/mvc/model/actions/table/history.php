<?php
use bbn\Appui\History;

$succ = false;
if ($model->hasData(['host', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $host = $model->data['host'];
  $db = $model->data['db'];
  $table = $model->data['table'];
  try {
    if (($conn = $model->inc->dbc->connection($host, $engine, $db))
      && ($conn->getHash() === $model->db->getHash())
    ) {
      $succ = $model->inc->dbc->integrateHistoryIntoTable(
        $table,
        $db,
        $host,
        !empty($model->data['user']) ? $model->data['user'] : null,
        !empty($model->data['date']) ? $model->data['date'] : null,
        !empty($model->data['activeColumn']) ? $model->data['activeColumn'] : null
      );
    }
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }
}

return ['success' => $succ];
