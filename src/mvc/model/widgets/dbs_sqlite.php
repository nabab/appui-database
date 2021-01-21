<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new \bbn\appui\database($model->db);
$dbs                  = $dbc->full_dbs('', 'sqlite');
$res                  = [
  'total' => count($dbs),
  'data' => []
];

if ($res['total']) {
  $url = $model->plugin_url('appui-database');
  $res['data'] = array_map(
    function ($a) use ($url) {
      return [
        'text' => $a['text'],
        'url'  => $url.'/tabs/sqlite-'.$a['code']
      ];
    },
    array_splice($dbs, $model->data['start'], $model->data['limit'])
  );
}

return $res;
