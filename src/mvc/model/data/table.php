<?php
/** @var bbn\Mvc\Model $model */
use bbn\X;
use bbn\Str;

$res['success'] = false;
if (
  $model->hasData(['host', 'db', 'engine'], true) &&
  ($host_id = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
){
  if ($db_id = $model->inc->dbc->dbId($model->data['db'], $host_id)) {
    $table_id = $model->inc->dbc->tableId($model->data['table'], $db_id);
  }

  $structure = $model->inc->dbc->modelize($model->data['table'], $model->data['db'], $host_id);
  $constraints = [];
  $externals = [];
  try {
    $conn = $model->inc->dbc->connection($host_id, $model->data['engine'], $model->data['db']);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
  }
  foreach ($structure['fields'] as $k => &$f) {
    if ($f['type'] === 'longtext') {
      $example = $conn->selectOne($model->data['table'], $k, [[$k, 'isnotnull']]);
      if (Str::isJson($example)) {
        $f['type'] = 'json';
      }
    }
  }
  unset($f);
  foreach ( $structure['keys'] as $k => $a ){
    if (
      $a['unique'] &&
      (count($a['columns']) === 1) &&
      ($tmp = $conn->getForeignKeys($a['columns'][0], $model->data['table'], $model->data['db']))
    ){
      $externals[$a['columns'][0]] = $tmp;
    }
    if ( !empty($a['ref_column']) ){
      $constraints[$a['columns'][0]] = [
        'column' => $a['ref_column'],
        'table' => $a['ref_table'],
        'db' => $a['ref_db'],
        'num' => $conn->count($conn->tfn($a['ref_table'], $a['ref_db']))
      ];
    }
  }
  $cfg = $model->inc->dbc->getGridConfig($model->data['table'], $model->data['db'], $model->data['host'], $model->data['engine']);
  $res = [
    'success' => true,
    'data' => [
      'host' => $model->data['host'],
      'engine' => $model->data['engine'],
      'db' => $model->data['db'],
      'db_id' => $db_id ?: null,
      'table' => $model->data['table'],
      'comment' => $conn->getTableComment($model->data['db'].'.'.$model->data['table']),
      'table_id' => $table_id ?? null,
      'ocolumns' => $table_id ? $model->inc->options->fullOptions('columns', $table_id) : [],
      'is_real' => \in_array($model->data['table'], array_keys($conn->getTables($model->data['db']))),
      'is_virtual' => isset($table_id) ? true : false,
      'option' => isset($table_id) ? $model->inc->options->option($table_id) : null,
      'col_info' => isset($table_id) ? $model->inc->dbc->fullColumns($table_id) : [],
      'structure' => $structure,
      'externals' => $externals,
      'constraints' => $constraints,
      'history' => false,
      'tableCfg' => $cfg['js']['columns'],
      'projectId' => BBN_PROJECT,
      'editColumnsData' => [],
    ]
  ];
  if (!isset($res['data']['option']['dcolumns'])) {
    $res['data']['option']['dcolumns'] = [];
  }
  if (($model->data['db'] === $model->db->getCurrent())
      && class_exists('bbn\\Appui\\History')
      && \bbn\Appui\History::hasHistory($model->db)
      && ($tmp = \bbn\Appui\History::getTableCfg($model->data['db'].'.'.$model->data['table']))
     ) {
    $res['data']['history'] = $tmp;
  }

  if ($res['data']['is_real']) {
    $res['data']['size'] = \bbn\Str::saySize($conn->tableSize($model->data['db'].'.'.$model->data['table']));
    $res['data']['count'] = $conn->count($model->data['db'].'.'.$model->data['table']);
  }

  $engines = ['mysql', 'sqlite', 'postgre'];
  foreach ($engines as $engine) {
    $res['data']['editColumnsData'][$engine] =   [
      'types' => bbn\Db\Languages\Sql::$types,
      'predefined' => $model->inc->options->fullOptions('pcolumns', $engine, 'database', 'appui'),
      'root' => APPUI_DATABASES_ROOT
    ];
  }
}
return $res;