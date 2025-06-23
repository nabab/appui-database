<?php
use bbn\Mvc\Model;
use bbn\Db\Languages\Sqlite;
use bbn\X;

/** @var Model $model */

if ($model->hasData(['host_id', 'db'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $isSqlite = $engine === 'sqlite';
  if (!is_array($model->data['db'])) {
    $model->data['db'] = [$model->data['db']];
  }

  try {
    $model->data['res']['undeleted'] = [];
    foreach ($model->data['db'] as $db) {
      if (!$isSqlite) {
        $conn = $model->inc->dbc->connection($model->data['host_id'], $engine);
      }

      if ($isSqlite || $conn->check()) {
        foreach ($model->data['db'] as $db) {
          if (($isSqlite && Sqlite::dropDatabaseOnHost($db, $model->data['host_id']))
            || (!$isSqlite && $conn->dropDatabase($db))
          ) {
            if ($model->hasData('options', true)
              && ($dbId = $model->inc->dbc->dbId($db, $model->data['host_id'], $engine))
            ) {
              $model->inc->dbc->removeDatabase($dbId);
            }

            $model->data['res']['success'] = true;
          }
          else {
            $model->data['res']['undeleted'][] = $db;
          }
        }
      }
    }
  }
  catch (\Exception $e) {
    $model->data['res']['error'] = $e->getMessage();
  }

  if (!empty($model->data['res']['undeleted'])) {
    $model->data['res']['error'] = X::_("Impossible to delete the following databases: %s", X::join($model->data['res']['undeleted'], ', '));
  }
}

return $model->data['res'];