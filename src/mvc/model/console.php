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
if ($model->hasData('code', true)) {
  if ($model->hasData(['engine', 'action'])) {
    if ($model->data['action'] === 'save') {
      $idQueries = $model->inc->options->fromCode('queries', $model->data['engine'], 'engines', 'database', 'appui');
      $prefs = $model->inc->pref->getAll($idQueries);
      $res = $model->inc->pref->addToGroup($idQueries, ['query' => $model->data['code']]);
      return ['success' => true, 'prefs' => $prefs, 'idQueries' => $idQueries, 'res' => $res];
    }
  }
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

  $tabToStr = function(array $tab, $time) use (&$getTabDelim, &$getTabKeyRow, &$getDataRowsString): string
  {
    $res = "\n";
    if (empty($tab)) {
      return $res . "Empty set\n";
    }

    $keys = array_keys($tab[0]);
    $res .= $getTabDelim($tab);
    $res .= $getTabKeyRow($tab) . "\n";
    $res .= $getTabDelim($tab);
    $res .= $getDataRowsString($tab);
    $res .= $getTabDelim($tab) . "\n\n";
    $res .= X::_('Retrieved %d rows, query executed in %s sec', count($tab), $time);
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
      return X::_('Query OK %d rows affected (%s sec)', $nbr, $time);
    }

    return X::_('Query OK %d row affected (%d sec)', $nbr, $time);
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
  $start = microtime(true);
  $connection->disableTrigger();
  $res = ['parsed' => $cfg, 'query' => $model->data['code']];
  if (!empty($cfg['SELECT']) || !empty($cfg['SHOW'])) {
    if (empty($cfg['LIMIT'])) {
      $res['query'] .= ' LIMIT 0, 100';
    }

    try {
      $res['data'] = $connection->getRows($res['query']);
      $res['str_tab'] = $tabToStr($res['data'], microtime(true) - $start);
    }
    catch (Exception $e) {
      $error = $e->getMessage();
    }
  }
  else {
    try {
      $nbr = $connection->query($res['query']);
      $res['str_tab'] = $rowAffected($nbr, microtime(true) - $start);
    }
    catch (Exception $e) {
      $error = $e->getMessage();
    }
  }
  $connection->enableTrigger();
  // return cfg
  return array_merge($res, [
    'limit' => isset($cfg['LIMIT']) ? $cfg['LIMIT']['rowcount'] : 0,
    'error' => $error
  ]);
}

return [
  'model' => $model->data,
  'database' => $model->db->getCurrent(),
  'databases' => $model->db->getDatabases(),
  'engine' => $model->db->getEngine()
];

