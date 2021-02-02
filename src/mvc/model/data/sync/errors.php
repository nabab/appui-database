<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
if (dbsync::isInit()
  && ($dbs = array_map(function($a){
    return $a['code'];
  }, $model->inc->options->fullOptions('sync', 'database', 'appui')))
) {
  if (!empty($model->data['filters'])
    && !empty($model->data['filters']['conditions'])
    && (($idx = X::find($model->data['filters']['conditions'], ['field' => 'diff'])) !== null)
  ) {
    $diff_cond = $model->data['filters']['conditions'][$idx]['value'];
    $limit = $model->data['limit'];
    $model->data['limit'] = 0;
    unset($model->data['filters']['conditions'][$idx]);
  }
  $grid = new \bbn\Appui\Grid(dbsync::$dbs, $model->data, [
    'table'=> Dbsync::$dbs_table,
    'fields' => [],
    'filters' => [
      'conditions' => [[
        'field' => 'state',
        'value' => 5
      ]]
    ]
  ]);
  if ($grid->check()) {
    $data = $grid->getDatatable();
    foreach ($data['data'] as $i => $d) {
      $data['data'][$i]['rows'] = X::jsonBase64Decode($d['rows']);
      $data['data'][$i]['vals'] = X::jsonBase64Decode($d['vals']);
      if (($m = $model->getModel(APPUI_DATABASES_ROOT.'data/sync/diff/is_different', [
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