<?php
/**
 * Show the result of sql queries
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/

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
    case "update":
      return Actions::Update;
      break;
    case "delete":
      return Actions::Delete;
      break;
		default:
      return Actions::Select;
      break;
  }
  return Actions::Select;
}

if ($model->hasData('code')) {
  // change database if required
  if ($model->hasData('database') && ($model->data['database'] !== '')) {
    $model->db->change($model->data['database']);
  }
  // parse query into cfg
  $parser = new SQLParser($model->db);
  $cfg = $parser->parse($model->data['code']);
  $action = getQueryAction($model->data['code']);

  // execute query
	$data = [];
  if ($action === Actions::Select) {
    $data = $model->db->rselectAll($cfg)
	} else if ($action === Actions::Delete) {
    $data = $model->db->rselectAll($cfg);
    $model->db->delete($cfg);
  } else if ($action === Actions::Update) {
    $data = $model->db->rselectAll($cfg);
    $model->db->update($cfg);
  }
  // return cfg
  return [
    'limit' => $cfg['limit'],
    'data' => $data
  ];
}

return [
  'model' => $model->data
];
