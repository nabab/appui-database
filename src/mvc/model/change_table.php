<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 13:42
 */
 
/** @var \bbn\mvc\model $model The model */

$res = [
  'success' => false
];
if ( isset($model->data['table']) ){
  $q = new \bbn\appui\queriesOld($model->db);
  $t = new \appui\database($model->db);
  $res['import'] = $t->import($model->data['table']);
  $res['cols'] = $t->full_columns($model->data['table']);
  if ( \is_array($res['tables'] = $q->linked_tables($model->data['table'])) ){
    $res['success'] = true;
  }
}
return $res;