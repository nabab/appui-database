<?php
use bbn\X;
use bbn\Appui\History;

$res['success'] = false;
if ($model->hasData(['host', 'db', 'engine', 'table'], true)
  && ($hostId = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
) {
  $table = $model->data['table'];
  $db = $model->data['db'];
  $host = $model->data['host'];
  $engine = $model->data['engine'];
  try {
    $infoTable = $model->inc->dbc->infoTable($table, $db, $hostId, $engine);
    $conn = $model->inc->dbc->connection($hostId, $engine, $db);
    $structure = $model->inc->dbc->modelize($table, $db, $hostId, $engine);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
  }

  $constraints = [];
  $constraintTables = [];
  $externals = [];
  foreach ($structure['keys'] as $k => $a) {
    if ($a['unique']
      && (count($a['columns']) === 1)
      && ($tmp = $conn->getForeignKeys($a['columns'][0], $table, $db))
    ) {
      $externals[$a['columns'][0]] = $tmp;
    }

    if (($k !== 'PRIMARY') && !empty($a['ref_column'])) {
      if (!isset($constraintTables[$a['ref_table']])) {
        $constraintTables[$a['ref_table']] = $conn->getColumns($a['ref_table']);
      }

      $constraints[$a['columns'][0]] = [
        'column' => $a['ref_column'],
        'table' => $a['ref_table'],
        'db' => $a['ref_db']
      ];
    }
  }

  $cfg = $model->inc->dbc->getGridConfig($table, $db, $host, $engine);
  $engines = $model->inc->dbc->engines();
  $res =  X::mergeArrays($infoTable, [
    'success' => true,
    'root' => $model->data['root'],
    'comment' => !empty($conn) ? $conn->getTableComment($table) : '',
    'ocolumns' => !empty($infoTable['id']) ? $model->inc->options->fullOptions('columns', $infoTable['id']) : [],
    'option' => !empty($infoTable['id']) ? $model->inc->options->option($infoTable['id']) : null,
    'col_info' => !empty($infoTable['id']) ? $model->inc->dbc->fullColumns($infoTable['id']) : [],
    'structure' => $structure,
    'externals' => $externals,
    'constraints' => $constraints,
    'constraint_tables' => $constraintTables,
    'primary' => $structure['keys']['PRIMARY'] ? $structure['keys']['PRIMARY']['columns'] : [],
    'history' => false,
    'tableCfg' => $cfg['js']['columns'],
    'editColumnsData' => array_combine(
      $engines,
      array_map(
        fn($eng) => [
          'types' => $model->inc->dbc->engineDataTypes($eng),
          'predefined' => $model->inc->options->fullOptions('pcolumns', $eng, 'engines', 'database', 'appui'),
          'root' => $model->pluginUrl('appui-database')
        ],
        $engines
      )
    ),
    'count' => !empty($infoTable['is_real']) && !empty($conn) ? $conn->count($table) : 0,
  ]);
  if (!isset($res['option']['dcolumns'])) {
    $res['option']['dcolumns'] = [];
  }

  if (!empty($infoTable['is_real'])
    && !empty($conn)
    && ($conn->getHash() === $model->db->getHash())
    && class_exists('bbn\\Appui\\History')
    && History::hasHistory($conn)
    && ($tmp = History::getTableCfg($db.'.'.$table, true))
  ) {
    $res['history'] = $tmp;
  }

  if ($conn) {
    $conn->close();
  }
}

return $res;