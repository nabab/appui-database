<?php
use   bbn\file\dir;
$res = [
  'success' => false,
  'data' => [],
  'total' => 0
];
if ($model->has_data(['start', 'limit'])
  && !empty($model->data['data']['file'])
  && ($path = $model->data_path('appui-databases') . 'sync/conflicts/')
  && is_file($path.$model->data['data']['file'])
) {
  $data = json_decode(file_get_contents($path.$model->data['data']['file']), true);
  $total = count($data);
  $res['data'] = array_splice($data, $model->data['start'], $model->data['limit']);
  $res['total'] = $total;
  $res['success'] = true;
}
return $res;