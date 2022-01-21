<?php

/** @var $model \bbn\Mvc\Model */
$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new \bbn\Appui\Database($model->db);
$tabs                 = $dbc->fullTables();
$res                  = [
  'total' => count($tabs),
  'data' => []
];

if ($res['total']) {
  $url = $model->pluginUrl('appui-database');
  $res['data'] = array_map(
    function ($a) use ($url) {
      return [
        'text' => $a['text'],
        'url'  => $url.'/current-db/'.$a['name']
      ];
    },
    array_splice($tabs, $model->data['start'], $model->data['limit'])
  );
}

return $res;
