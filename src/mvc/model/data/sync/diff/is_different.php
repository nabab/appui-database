<?php
if ($model->hasData(['id', 'dbs'])
  && ($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/dbs', [
    'id' => $model->data['id'],
    'dbs' => $model->data['dbs']
  ]))
) {
  return [
    'success' => true,
    'diff' => !!array_filter($m['data'], function($d) use($m){
      $a = $d['data'];
      $b = $m['from']['data'];
      if (json_encode($a) === json_encode($b)) {
        return false;
      }
      $a = is_array($a) ? array_map(function($t){
        return \bbn\Str::isJson($t) ? json_decode($t, true) : $t;
      }, $a) : $a;
      $b = is_array($b) ? array_map(function($t){
        return \bbn\Str::isJson($t) ? json_decode($t, true) : $t;
      }, $b) : $b;
      return is_array($a) || is_array($b) ? $a != $b : $a !== $b;
    })
  ];
}
return ['success' => false];