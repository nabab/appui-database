<?php
use bbn\Mvc\Model;
use bbn\Str;

/** @var Model $model */
if ($model->hasData(['host_id', 'engine', 'name'], true)) {
  if (!Str::checkName($model->data['name'])) {
    $model->data['res']['error'] = _("This name is not authorized");
  }
  else {
    try {
      $conn = $model->inc->dbc->connection($model->data['host_id'], $model->data['engine']);
    }
    catch (\Exception $e) {
      $model->data['res']['error'] = $e->getMessage();
    }

    if ($conn->check()) {
      if ($conn->createDatabase(
        $model->data['name'],
        $model->data['charset'] ?? null,
        $model->data['collation'] ?? null
      )) {
        $model->data['res']['success'] = true;
        if ($model->hasData('options', true)) {
          $model->inc->dbc->addDatabase(
            $model->data['name'],
            $model->data['host_id'],
            $model->data['engine']
          );
        }
      }
    }

  }
}

return $model->data['res'];