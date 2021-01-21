<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new \bbn\appui\database($model->db);
$tabs                 = $dbc->full_tables();
$res                  = [
  'total' => count($tabs),
  'data' => []
];

if ($res['total']) {
  $url = $model->plugin_url('appui-database');
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
