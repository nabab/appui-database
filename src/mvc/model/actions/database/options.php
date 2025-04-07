<?php

set_time_limit(600);
/** @var bbn\Mvc\Model $model */
return [
  'success' => !empty($model->inc->conn)
    && $model->hasData(['host_id', 'db'], true)
    && ($dbc = new bbn\Appui\Database($model->db))
    && $dbc->importDb($model->data['db'], $model->data['host_id'], true)
];