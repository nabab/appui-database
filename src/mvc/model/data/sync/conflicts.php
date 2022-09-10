<?php
use bbn\X;
use   bbn\File\Dir;
$res = [
  'success' => false,
  'data' => [],
  'total' => 0
];
if ($model->hasData(['start', 'limit'])
  && !empty($model->data['data']['file'])
  && ($path = $model->dataPath('appui-database') . 'sync/conflicts/')
  && is_file($path.$model->data['data']['file'])
) {
  $data = yaml_parse_file($path.$model->data['data']['file']);
  $total = count($data);
  $res['data'] = X::getRows($data, $model->data['filters'] ?? [], $model->data['order'] ?? [], $model->data['limit'], $model->data['start']);
  $res['total'] = $total;
  $res['success'] = true;
}
return $res;