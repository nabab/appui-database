<?php
/** @var $model \bbn\Mvc\Model */

use bbn\X;

$res['success'] = false;
if (
  $model->hasData(['host', 'db', 'engine'], true) &&
  ($host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
){
  $isReal = \in_array($model->data['db'], $model->db->getDatabases());
  $db_id = $model->inc->dbc->dbId($model->data['db'], $host_id);
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
  }

  $tables = [];
  $fmodel = $conn->modelize();
  foreach ($fmodel as $tablename => $table) {
    if (!empty($table['keys']['PRIMARY']) && count($table['keys']['PRIMARY']['columns']) === 1) {
      $colname = $table['keys']['PRIMARY']['columns'][0];
      $column = $table['fields'][$colname];
      $column['value'] = $column['text'] = $conn->cfn($colname, $tablename);
      $tables[] = $column;
    }
  }

  $res = [
    'success' => true,
    'host' => $model->data['host'],
    'db' => $model->data['db'],
    'db_id' => $db_id,
    'is_real' => $isReal,
    'is_virtual' => $db_id ? true : false,
    'info' => $model->inc->options->option($db_id),
    'engine' => $model->data['engine'],
    'size' => $isReal ? bbn\Str::saySize($model->db->dbSize($model->data['db'])) : 0,
    'types' => bbn\Db\Languages\Sql::$types,
    'predefined' => $model->inc->options->fullOptions('pcolumns', $model->data['engine'], 'database', 'appui'),
    'tables' => $tables
  ];
}
return $res;