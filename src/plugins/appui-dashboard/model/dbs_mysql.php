<?php

use bbn\Appui\Database;

/** @var bbn\Mvc\Model $model */
$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$dbc                  = $model->inc->dbc ?? new Database($model->db);
$dbs                  = $dbc->fullDbs('', 'mysql');
$res                  = [
  'total' => count($dbs),
  'data' => []
];

if ($res['total']) {
  $url = $model->pluginUrl('appui-database');
  $res['data'] = array_map(
    function ($a) use ($url) {
      return [
        'text' => $a['text'],
        'url'  => $url.'/tabs/mysql-'.$a['name']
      ];
    },
    array_splice($dbs, $model->data['start'], $model->data['limit'])
  );
}

return $res;
