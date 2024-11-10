<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
use Exception;
/** @var bbn\Mvc\Model $model */

$res = ['success' => false, 'num_deleted' => 0];
if ($model->hasData(['host', 'db', 'engine', 'table'], true)) {
  try {
    $dbconn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  }
  catch (Exception $e) {
    $res['error'] = Str::text2html($e->getMessage());
  }
  if ($dbconn->check()) {
    if (!is_array($model->data['table'])) {
      $model->data['table'] = [$model->data['table']];
    }

    foreach ($model->data['table'] as $t) {
	    try {
        $res['num_deleted'] += (int)$dbconn->dropTable($t, $model->data['db']);
      }
      catch (Exception $e) {
        $res['error'] = Str::text2html($e->getMessage());
        break;
      }
    }

    $res['success'] = $res['num_deleted'] === count($model->data['table']);
  }
}

return $res;
