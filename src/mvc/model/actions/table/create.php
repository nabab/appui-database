<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$res = ['success' => false];
if ($model->hasData(['host', 'db', 'engine', 'name', 'fields'], true)) {
  $table = $model->data['name'];
  $db = $model->data['db'];
  try {
    $dbconn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $db);
  }
  catch (Exception $e) {
    $res['error'] = Str::text2html($e->getMessage());
  }

  if ($dbconn->check()) {
    if ($dbconn->tableExists($table, $db)) {
      $res['error'] = X::_("The table %s already exists", $table);
    }

    $structure = ['cols'=> $model->data['cols'] ?? [], 'fields'=> $model->data['fields'], 'keys'=> $model->data['keys'] ?? []];
    $res['statement'] = $model->db->getCreate($table, $structure);
    $mode = $model->db->getErrorMode();
    $model->db->setErrorMode('continue');
    try {
      $model->db->query($res['statement']);
    }
    catch (\Exception $e) {
      $res['error'] = Str::text2html($e->getMessage());
    }
    $model->db->setErrorMode($mode);
    $res['success'] = $dbconn->tableExists($table, $db);
  }
}

return $res;


return [
  'query' => $statement,
  'success' => $res,
  'error' => $error ?? null
];