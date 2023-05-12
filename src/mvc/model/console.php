<?php
/**
 * Show the result of sql queries
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/


if ($model->hasData('code')) {
  function getCellLength(array $tab, string $key)
  {
    $res = strlen($key);

    foreach ($tab as $row) {
      if ($res < strlen($row[$key])) {
        $res = strlen($row[$key]);
      }
    }
    return $res;
  }

  function getTabDelim(array $tab): string
  {
    $res = "+";

    $keys = array_keys($tab[0]);
    foreach ($keys as $key) {
      foreach (range(0, getCellLength($tab, $key) + 2) as $i) {
        $res .= '-';
      }
      $res .= '+';
    }
    return $res . "\n";
  }

  function getTabKeyRow(array $tab): string
  {
    $res = '|';

    $keys = array_keys($tab[0]);
    foreach ($keys as $key) {
      $res .= ' ' . $key;
      $spaces = getCellLength($tab, $key) - strlen($key);
      foreach (range(0, $spaces) as $i) {
        $res .= ' ';
      }
      $res .= ' |';
    }
    return $res;
  }

  function getDataRowString(array $row, array $tab): string
  {
    $res = '|';

    foreach ($row as $key => $cell) {
      $res .= ' ' . $cell;
      $spaces = getCellLength($tab, $key) - strlen($cell);
      foreach (range(0, $spaces) as $i) {
        $res .= ' ';
      }
      $res .= ' |';
    }
    return $res;
  }

  function getDataRowsString(array $tab): string
  {
    $res = '';

    foreach ($tab as $row) {
      $res .= getDataRowString($row, $tab);
      $res .= "\n";
    }
    return $res;
  }

  function tabToStr(array $tab): string
  {
    $res = "\n";

    $keys = array_keys($tab[0]);
    $res .= getTabDelim($tab);
    $res .= getTabKeyRow($tab) . "\n";
    $res .= getTabDelim($tab);
    $res .= getDataRowsString($tab);
    $res .= getTabDelim($tab);
    return $res;
  }

  enum Actions
  {
    case Insert;
    case Select;
    case Update;
    case Delete;
  }

  function getQueryAction(string $query): Actions
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
  }

  // change database if required
  if ($model->hasData('database') && ($model->data['database'] !== '')) {
    $model->db->change($model->data['database']);
  }
  // parse query into cfg
  $parser = new SQLParser($model->db);
  $cfg = $parser->parse($model->data['code']);
  $action = getQueryAction($model->data['code']);

  if ($action === Actions::Select) {
    $data = $model->db->rselectAll($cfg);
  }
  // return cfg
  return [
    'limit' => $cfg['limit'],
    'data' => $data,
    'str_tab' => tabToStr($data)
  ];
} else
{
  return [
  	'model' => $model->data,
		'databases' => $model->db->getDatabases()
  ];
}

