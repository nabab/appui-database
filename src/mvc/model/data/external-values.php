<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Model $model */

$res = [
  'success' => false,
];
if ($model->hasData('data')) {
  $data = $model->data['data'];
}
if (X::hasProps($data ?? [], ['host', 'engine', 'db', 'table', 'column'], true)) {
  // data
  $host = $data['host'];
  $engine = $data['engine'];
  $db = $data['db'];
  $table = $data['table'];
  $column = $data['column'];

  // make connection object
  $hostId = $model->inc->dbc->hostId($host, $engine);
  $connection = $model->inc->dbc->connection($hostId, $engine, $db);

  // get table
  $tableColumns = $connection->getColumns($table);

  // get 'text' field or 'name' field or first varchar field
  $displayColumnName = '';

  $nameColumn = '';
  $textColumn = '';
  $varcharColumn = '';

  // get displayColumnName
  foreach ($tableColumns as $name => $columnData) {
    if ($name === 'name') {
      $nameColumn = $name;
      break;
    }
    if ($name === 'text') {
      $textColumn = $name;
    }
    if ($varcharColumn === '' && $columnData['type'] === 'varchar') {
      $varcharColumn = $name;
    }
  }
  if ($nameColumn !== '') {
    $displayColumnName = $nameColumn;
  } else if ($textColumn !== '') {
    $displayColumnName = $textColumn;
  } else if ($varcharColumn !== '') {
    $displayColumnName = $varcharColumn;
  }

  // pull data
  $data = $connection->rselectAll($table, [$column, $displayColumnName]);
  foreach ($data as $entrie) {
    $res['data'][] = [
      'value' => $entrie[$column],
      'text' => $entrie[$displayColumnName]
    ];
  }
  $res['success'] = true;
} else {
  $res['error'] = _('missing parameters');
}

return $res;
