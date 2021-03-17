<?php
/** @var $model \bbn\Mvc\Model */
use bbn\Appui\Dbsync;
use bbn\X;
$current = $model->db->getCurrent();
if (dbsync::isInit()
&& $model->hasData(['db', 'id'])
&& ($sync = Dbsync::$dbs->select(dbsync::$dbs_table, [], ['id' => $model->data['id']]))
) {
  $custom = $model->getPluginModel('conflicts');
  $sync->vals = X::jsonBase64Decode($sync->vals);
  $sync->rows = X::jsonBase64Decode($sync->rows);
  if (!is_array($model->data['db'])) {
    $model->data['db'] = [$model->data['db']];
  }
  foreach ($model->data['db'] as $db) {
    // Changing to desired DB
    $model->db->change($db);
    if ($db === $model->db->getCurrent()) {
      $primaries = $model->db->getPrimary($sync->tab);
      if ($structure = $model->db->modelize($sync->tab)) {
        $fields = array_keys($structure['fields']);
        if (!empty($custom)
          && !empty($custom['excluded'])
          && !empty($custom['excluded'][$sync->tab])
        ) {
          $excluded = $custom['excluded'][$sync->tab];
          $fields = array_values(array_filter($fields, function($field) use($excluded){
            return !in_array($field, $excluded);
          }));
        }
      }
      else {
        $fields = [];
      }
      $all = array_merge($sync->rows, $sync->vals);
      if (count($primaries) && \bbn\X::hasProps($all, $primaries)) {
        $filters = [];
        foreach ($primaries as $prim) {
          $filters[$prim] = $all[$prim];
        }
      }
      else {
        $filters = $all;
      }
      if (!isset($model->data['res']['data'])) {
        $model->data['res']['data'] = [];
      }
      $model->data['res']['data'][] = [
        'db' => $db,
        'data' => $model->db->rselect($sync->tab, $fields, $filters),
        'query' => $model->db->last(),
        'filters' => $filters
      ];
      $model->data['res']['success'] = true;
    }
  }
}
if ($current !== $model->db->getCurrent()) {
  $model->db->change($current);
}
return $model->data['res'];