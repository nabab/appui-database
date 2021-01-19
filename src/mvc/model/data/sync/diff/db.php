<?php
/** @var $model \bbn\mvc\model */
use bbn\appui\dbsync;
use bbn\x;
$current = $model->db->get_current();
if (dbsync::is_init()
    && $model->has_data(['db', 'id'])
    && ($sync = dbsync::$dbs->select(dbsync::$dbs_table, [], ['id' => $model->data['id']]))
) {
  $sync->vals = x::json_base64_decode($sync->vals);
  $sync->rows = x::json_base64_decode($sync->rows);
  if (!is_array($model->data['db'])) {
    $model->data['db'] = [$model->data['db']];
  }
  foreach ($model->data['db'] as $db) {
    // Changing to desired DB
    $model->db->change($db);
    if ($db === $model->db->get_current()) {
      $primaries = $model->db->get_primary($sync->tab);
      $all = array_merge($sync->rows, $sync->vals);
      if (count($primaries) && \bbn\x::has_props($all, $primaries)) {
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
        'data' => $model->db->rselect($sync->tab, [], $filters),
        'query' => $model->db->last(),
        'filters' => $filters
      ];
      $model->data['res']['success'] = true;
    }
  }
}
if ($current !== $model->db->get_current()) {
  $model->db->change($current);
}
return $model->data['res'];