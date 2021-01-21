<?php
use \bbn\appui\dbsync;
use \bbn\x;
if (dbsync::is_init()
  && ($dbs = array_map(function($a){
    return $a['code'];
  }, $model->inc->options->full_options('sync', 'database', 'appui')))
) {
  if (!empty($model->data['filters'])
    && !empty($model->data['filters']['conditions'])
    && (($idx = x::find($model->data['filters']['conditions'], ['field' => 'diff'])) !== null)
  ) {
    $diff_cond = $model->data['filters']['conditions'][$idx]['value'];
    $limit = $model->data['limit'];
    $model->data['limit'] = 0;
    unset($model->data['filters']['conditions'][$idx]);
  }
  $grid = new \bbn\appui\grid(dbsync::$dbs, $model->data, [
    'table'=> dbsync::$dbs_table,
    'fields' => [],
    'filters' => [
      'conditions' => [[
        'field' => 'state',
        'value' => 5
      ]]
    ]
  ]);
  if ($grid->check()) {
    $data = $grid->get_datatable();
    foreach ($data['data'] as $i => $d) {
      $data['data'][$i]['rows'] = x::json_base64_decode($d['rows']);
      $data['data'][$i]['vals'] = x::json_base64_decode($d['vals']);
      if (($m = $model->get_model(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
          'id' => $d['id'],
          'dbs' => $dbs
        ]))
        && !empty($m['success'])
        && isset($m['diff'])
      ) {
        $data['data'][$i]['diff'] = $m['diff'];
      }
    }
    if (isset($diff_cond)) {
      $data['data'] = array_values(array_filter($data['data'], function($d) use($diff_cond){
        return $d['diff'] === $diff_cond;
      }));
      $data['total'] = count($data['data']);
      $data['data'] = array_slice($data['data'], $model->data['start'], $limit);
    }
    return $data;
}
}
return ['success' => false];