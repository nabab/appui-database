<?php
/** @var \bbn\mvc\model $model */

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new \bbn\appui\databases($model->db);
$hosts                = $dbc->full_hosts();
$res                  = [
  'total' => count($hosts),
  'data' => []
];

if ($res['total']) {
  $res['data'] = array_splice($hosts, $model->data['start'], $model->data['limit']);
}

return $res;
