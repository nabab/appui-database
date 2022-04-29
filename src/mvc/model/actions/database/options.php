<?php
return [
  'success' => !empty($model->inc->conn)
    && $model->hasData(['host_id', 'db'], true)
    && ($dbc = new bbn\Appui\Database($model->db))
    && $dbc->importDb($model->data['db'], $model->data['host_id'], true)
];