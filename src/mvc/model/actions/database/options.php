<?php
return [
  'success' => !empty($model->inc->conn)
    && ($dbc = new bbn\Appui\Database($model->inc->conn))
    && $model->hasData(['host_id', 'db'], true)
    && $dbc->importDb($model->data['db'], $model->data['host_id'], true)
];