<?php
/**
 * Show the result of sql queries
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/

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

  $getQueryAction = function(string $query): Actions
  {
    $exploded_query = explode(" ", $query);
    switch (strtolower($exploded_query[0])) {
      case "insert":
        return Actions::Insert;
      case "select":
        return Actions::Select;
        break;
      case "Update":
        return Actions::Update;
        break;
      case "Delete":
        return Actions::Delete;
        break;
      default:
        return Actions::Select;
        break;
    }
    return Actions::Select;
  };

  $createConnection = function(string $host, string $engine, string $db) use (&$model)
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
  $connection = $createConnection('dev_clovis@db.m3l.co', 'mysql', $model->data['database']);
  $parser = new SQLParser($connection);
  $cfg = $parser->parse($model->data['code']);
  $action = $getQueryAction($model->data['code']);

  if ($action === Actions::Select) {
    $data = $connection->rselectAll($cfg);
  }
  // return cfg
  return [
    'limit' => $cfg['limit'],
    'data' => $data,
    'str_tab' => $tabToStr($data)
  ];
}

return [
  'model' => $model->data,
  'database' => $model->db->getCurrent(),
  'databases' => $model->db->getDatabases()
];

