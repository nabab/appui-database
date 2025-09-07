<?php
/**
 * Show the result of sql queries
 *
 **/

use bbn\X;
use bbn\Db;
use PHPSQLParser\PHPSQLParser;
/** @var bbn\Mvc\Model $model */

set_time_limit (0);
if ($model->hasData('code')) {
  $getCellLength = function(array $tab, string $key)
  {
    $res = strlen($key);

    foreach ($tab as $row) {
      if ($res < strlen($row[$key])) {
        $res = strlen($row[$key]);
        //TODO: add limit
      }
    }
    return $res;
  };

  $getTabDelim = function(array $tab) use ($getCellLength): string
  {
    $res = "+";

    $keys = array_keys($tab[0]);
    foreach ($keys as $key) {
      foreach (range(0, $getCellLength($tab, $key) + 2) as $i) {
        $res .= '-';
      }
      $res .= '+';
    }
    return $res . "\n";
  };

  $getTabKeyRow = function(array $tab) use ($getCellLength): string
  {
    $res = '|';

    $keys = array_keys($tab[0]);
    foreach ($keys as $key) {
      $res .= ' ' . $key;
      $spaces = $getCellLength($tab, $key) - strlen($key);
      foreach (range(0, $spaces) as $i) {
        $res .= ' ';
      }
      $res .= ' |';
    }
    return $res;
  };

  $getDataRowString = function(array $row, array $tab) use ($getCellLength): string
  {
    $res = '|';

    foreach ($row as $key => $cell) {
      $res .= ' ' . $cell;
      $spaces = $getCellLength($tab, $key) - strlen($cell);
      foreach (range(0, $spaces) as $i) {
        $res .= ' ';
      }
      $res .= ' |';
    }
    return $res;
  };

  $getDataRowsString = function(array $tab) use (&$getDataRowString): string
  {
    $res = '';

    foreach ($tab as $row) {
      $res .= $getDataRowString($row, $tab);
      $res .= "\n";
    }
    return $res;
  };

  $tabToStr = function(array $tab) use (&$getTabDelim, &$getTabKeyRow, &$getDataRowsString): string
  {
    $res = "\n";

    $keys = array_keys($tab[0]);
    $res .= $getTabDelim($tab);
    $res .= $getTabKeyRow($tab) . "\n";
    $res .= $getTabDelim($tab);
    $res .= $getDataRowsString($tab);
    $res .= $getTabDelim($tab);
    return $res;
  };

  enum Actions
  {
    case Insert;
    case Select;
    case Update;
    case Delete;
  }

  $getQueryAction = function(string $query): string
  {
    $exploded_query = explode(" ", $query);
    return strtoupper($exploded_query[0]);
  };

  $createConnection = function(string $host, string $engine, string $db) use (&$model): ?Db
  {
    $res = null;

    $hostId = $model->inc->dbc->hostId($host, $engine);
    try {
			$res = $model->inc->dbc->connection($hostId, $engine, $db);
    } catch (\Exception $e) {
      $res['error'] = $e->getMessage();
    }
    return $res;
  };

  $rowAffected = function(int $nbr, float $time)
  {
    if ($nbr > 1) {
      return 'Query OK, ' . $nbr . 'row affected (' . $time . 'sec)';
    }
    return 'Query OK, ' . $nbr . 'rows affected (' . $time . 'sec)';
  };

  // change database if required
  if ($model->hasData('database') && ($model->data['database'] !== '')) {
    $model->db->change($model->data['database']);
  }
  // parse query into cfg
  $connection = $model->db;
  $cfg = $model->db->parseQuery($model->data['code']);
  $action = $getQueryAction(array_keys((array)$cfg)[0]);

  $error = false;
  $data = false;
  $connection->disableTrigger();
  if ($action === 'SELECT') {
    try {
      $data = $connection->getRows($model->data['code']);
    }
    catch (Exception $e) {
      $error = $e->getMessage();
    }
  }
  $connection->enableTrigger();
  // return cfg
  return [
    'limit' => isset($cfg['LIMIT']) ? $cfg['LIMIT']['rowcount'] : 0,
    'data' => $data,
    'error' => $error,
    'str_tab' => $tabToStr($data)
  ];
}

return [
  'model' => $model->data,
  'database' => $model->db->getCurrent(),
  'databases' => $model->db->getDatabases()
];

