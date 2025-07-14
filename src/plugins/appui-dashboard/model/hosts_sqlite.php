<?php
/** @var bbn\Mvc\Model $model */

use bbn\Appui\Database;

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new Database($model->db);
$hosts                = $dbc->fullHosts('sqlite') ?: [];
$res                  = [
  'total' => count($hosts),
  'data' => []
];

if ($res['total']) {
  $url = $model->pluginUrl('appui-database');
  $res['data'] = array_map(
    fn($a) => [
      'text' => $a['text'],
      'url'  => $url.'/tabs/sqlite/'.$a['id']
    ],
    array_splice($hosts, $model->data['start'], $model->data['limit'])
  );
}

return $res;
