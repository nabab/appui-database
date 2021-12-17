<?php
/** @var \bbn\Mvc\Model $model */

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new \bbn\Appui\Database($model->db);
$hosts                = $dbc->fullHosts();
$res                  = [
  'total' => count($hosts),
  'data' => []
];

if ($res['total']) {
  $url = $model->pluginUrl('appui-database');
  $res['data'] = array_map(function($o) use ($url) {
    return [
      'text' => $o['code'],
      'url'  => $url . '/tabs/' . $o['code'] . '/home'
    ];
  }, array_splice($hosts, $model->data['start'], $model->data['limit']));
}

return $res;
