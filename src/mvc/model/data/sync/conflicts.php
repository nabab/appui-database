<?php
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
  $data = json_decode(file_get_contents($path.$model->data['data']['file']), true);
  $total = count($data);
  $res['data'] = array_splice($data, $model->data['start'], $model->data['limit']);
  $res['total'] = $total;
  $res['success'] = true;
}
return $res;