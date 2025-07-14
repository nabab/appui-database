<?php
/** @var bbn\Mvc\Model $model */

use bbn\X;

$res['success'] = false;
if ($model->hasData(['host', 'db', 'engine'], true)
  && ($hostId = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
) {
  $isReal = in_array($model->data['db'], $model->db->getDatabases());
  $dbId = $model->inc->dbc->dbId($model->data['db'], $hostId);
  try {
    $conn = $model->inc->dbc->connection($hostId, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
    return [
      'error' => $e->getMessage(),
      'success' => false
    ];
  }

  $constraints = [];
  $fmodel = $conn->modelize();
  foreach ($fmodel as $tablename => $table) {
    if (!empty($table['keys']['PRIMARY']) && count($table['keys']['PRIMARY']['columns']) === 1) {
      $colname = $table['keys']['PRIMARY']['columns'][0];
      $column = $table['fields'][$colname];
      $column['value'] = $column['text'] = $conn->cfn($colname, $tablename);
      $constraints[] = $column;
    }
  }

  $res = X::mergeArrays(
    $model->inc->dbc->infoDb(
      $model->data['db'],
      $hostId,
      $model->data['engine']
    ), [
      'success' => true,
      'constraints' => $constraints,
      'pcolumns' => $model->inc->dbc->enginePcolumns($model->data['engine']),
      'data_types' => $model->inc->dbc->engineDataTypes($model->data['engine']),
      'charsets' => $conn->charsets(),
      'collations' => $conn->collations(),
    ]
  );
}
return $res;
