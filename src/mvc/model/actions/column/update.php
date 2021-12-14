<?php
/**
 * What is my purpose?
 *
 **/

/**@var $model \bbn\Mvc\Model*/

use bbn\X;

$res = ['success' => false];

$data =  [
  'name' => 'data',
  'oldname' => 'datz',
  'type' => 'binary',
  'oldtype' => 'binary',
  'null' => 0,
  'key' => 'PRI',
  'maxlength' => 16,
  'defaultExpression' =>  0,
  'position' => 1,
  'signed' => 1,
  'generation' => '',
  'extra' => ''
];

if ($model->hasData(['engine', 'db', 'host', 'table', 'name'])) {
  $database = new bbn\Appui\Database($model->db);
  $conn = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $data = $conn->getColumnValues($model->data['table'], $model->data['name']);
  $mod = $model->db->modelize("Didier_Drogba");
  $model->data['data']['alter_type'] = 'modify';


  if (X::hasProp($model->data['data'], 'oldname', true) && ($model->data['oldname'] !== $model->data['name'])) {
    $model->data['data']['new_name'] = $model->data['data']['name'];
    $model->data['data']['col_name'] = $model->data['name'];
  }
  if (X::hasProp($model->data['data'], 'oldtype', true) && ($model->data['oldtype'] !== $model->data['type'])) {
    $model->data['data']['new_type'] = $model->data['data']['type'];
    $model->data['data']['col_type'] = $model->data['data']['oldtype'];
  }
  if (X::hasProp($model->data['data'], 'oldnull', true) && ($model->data['oldnull'] !== $model->data['null'])) {
    $model->data['data']['new_null'] = $model->data['data']['null'];
    $model->data['data']['col_null'] = $model->data['data']['oldnull'];
  }
  if (X::hasProp($model->data['data'], 'oldkey', true) && ($model->data['oldkey'] !== $model->data['key'])) {
    $model->data['data']['new_key'] = $model->data['data']['key'];
    $model->data['data']['col_key'] = $model->data['data']['oldkey'];
  }
  if (X::hasProp($model->data['data'], 'oldsigned', true) && ($model->data['oldsigned'] !== $model->data['signed'])) {
    $model->data['data']['new_signed'] = $model->data['data']['signed'];
    $model->data['data']['col_signed'] = $model->data['data']['oldsigned'];
  }
  if (X::hasProp($model->data['data'], 'oldmaxlength', true) && ($model->data['oldmaxlength'] !== $model->data['maxlength'])) {
    $model->data['data']['new_maxlength'] = $model->data['data']['maxlength'];
    $model->data['data']['col_maxlength'] = $model->data['data']['oldmaxlength'];
  }
  if (str_contains($model->data['data']['type'], 'text') === true) {
    $model->data['data']['default'] = null;
  }
  if (X::hasProp($model->data['data'], 'olddefaultExpression', true) && ($model->data['olddefaultExpression'] !== $model->data['defaultExpression'])) {
    $model->data['data']['new_defaultExpression'] = $model->data['data']['defaultExpression'];
    $model->data['data']['col_defaultExpression'] = $model->data['data']['olddefaultExpression'];
  }
  if (X::hasProp($model->data['data'], 'olddecimal', true) && ($model->data['olddecimal'] !== $model->data['decimal'])) {
    $model->data['data']['new_decimal'] = $model->data['data']['default'];
    $model->data['data']['col_decimal'] = $model->data['data']['olddecimal'];
  }
  unset($model->data['data']['oldname']);
  unset($model->data['data']['oldtype']);
  unset($model->data['data']['oldnull']);
  unset($model->data['data']['oldkey']);
  unset($model->data['data']['oldsigned']);
  unset($model->data['data']['oldmaxlength']);
  unset($model->data['data']['olddefault']);
  unset($model->data['data']['olddefaultExpression']);
  unset($model->data['data']['olddecimal']);
  $update = [
    'fields' => [
      $model->data['name'] => $model->data['data'],
    ],
    'cols' => [
      // $model->data['data']['key'] = 'PRI' | à changer par 'PRIMARY' mais ce n'est pas passé dans la source.
      $model->data['name'] => $model->data['data']['key'],
    ],
  ];
  $res = [
    'success' => true
  ];
  //X::ddump($res, $model->db->getAlterTable("Didier_Drogba", $update), $update, $model->data, $mod);
  $conn->alter("Didier_Drogba", $update);
}

return $res;