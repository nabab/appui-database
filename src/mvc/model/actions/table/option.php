<?php

if ($model->hasData(["table", "db_id", "host"])) {
  $dbc = new bbn\Appui\Database($model->db);
  $import = $dbc->importTable($model->data["table"], $model->data["db_id"], $model->data["host"]);
  $table_id = $dbc->tableId($model->data["table"], $model->data["db_id"]);
  return [
    "success" => $import["columuns"] || $import["columns_removed"] || $import["keys"] || $import["keys_removed"],
    "data" => $import,
    "table_id" => $table_id,
    "option" => $model->inc->options->option($table_id)
  ];
}