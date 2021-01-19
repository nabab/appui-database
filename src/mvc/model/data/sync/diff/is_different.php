<?php
if ($model->has_data(['id', 'dbs'])
  && ($m = $model->get_model(APPUI_DATABASES_ROOT.'data/sync/diff/dbs', [
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
      $a = array_map(function($t){
        return \bbn\str::is_json($t) ? json_decode($t, true) : $t;
      }, $a);
      $b = array_map(function($t){
        return \bbn\str::is_json($t) ? json_decode($t, true) : $t;
      }, $b);
      return $a !== $b;
    })
  ];
}
return ['success' => false];