<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$fields = [];
$cols = [];
$keys = [];

foreach ($model->data['columns'] as $col) {
  if (!empty($col['index'])) {
    if ($col['index'] === "PRIMARY") {
    	$keys[$col['index']] = $col['index']; // je ne sais pas comment mais je voudrais mettre "PRIMARY" qui est dans $col['index'] dans $keys
    }
    $cols[/*?*/] = $col['index'];
    $cols[[/*?*/] = $col['name'];
    unset($col['index']);
  }
  $fields[$col['name']] = $col;
}

$opt = $model->db->modelize("bbn_options");

X::ddump($model->db->getCreateTable($model->data['name'], ['keys' => $keys, 'cols' => $cols ,'fields' => $fields]), $fields, $model->db->getCreateTable("titi", $opt), $opt);
$res = ['success' => false];
return $res;