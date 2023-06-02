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
  $mod = $conn->modelize($model->data['table']);
  $model->data['data']['alter_type'] = 'modify';


  if($model->data['data']['key'] === 'PRI') {
    $keys =  [
      'PRIMARY' => [
        'columns' => [$model->data['name']]
      ]
    ];
    $cols = [$model->data['name'] => ['PRIMARY']];
  }
  if (X::hasProp($model->data['data'], 'oldname', true) && ($model->data['oldname'] !== $model->data['name'])) {
    $model->data['data']['new_name'] = $model->data['data']['name'];
    $model->data['data']['col_name'] = $model->data['name'];
  }
  if (X::hasProp($model->data['data'], 'oldtype', true) && ($model->data['data']['oldtype'] !== $model->data['data']['type'])) {

    $model->data['data']['new_type'] = $model->data['data']['type'];
    $model->data['data']['col_type'] = $model->data['data']['oldtype'];
  }
  if (X::hasProp($model->data['data'], 'oldnull', true) && ($model->data['data']['oldnull'] !== $model->data['data']['null'])) {
    $model->data['data']['new_null'] = $model->data['data']['null'];
    $model->data['data']['col_null'] = $model->data['data']['oldnull'];
  }
  if (X::hasProp($model->data['data'], 'oldkey', true) && ($model->data['data']['oldkey'] !== $model->data['data']['key'])) {
    $model->data['data']['new_key'] = $model->data['data']['key'];
    $model->data['data']['col_key'] = $model->data['data']['oldkey'];
  }
  if (X::hasProp($model->data['data'], 'oldsigned', true) && ($model->data['data']['oldsigned'] !== $model->data['data']['signed'])) {
    $model->data['data']['new_signed'] = $model->data['data']['signed'];
    $model->data['data']['col_signed'] = $model->data['data']['oldsigned'];
  }
  if (X::hasProp($model->data['data'], 'oldmaxlength', true) && ($model->data['data']['oldmaxlength'] !== $model->data['data']['maxlength'])) {
    $model->data['data']['new_maxlength'] = $model->data['data']['maxlength'];
    $model->data['data']['col_maxlength'] = $model->data['data']['oldmaxlength'];
  }
  if (str_contains($model->data['data']['type'], 'text') === true) {
    $model->data['data']['default'] = null;
  }
  if (X::hasProp($model->data['data'], 'olddefaultExpression', true) && ($model->data['data']['olddefaultExpression'] !== $model->data['data']['defaultExpression'])) {
    $model->data['data']['new_defaultExpression'] = $model->data['data']['defaultExpression'];
    $model->data['data']['col_defaultExpression'] = $model->data['data']['olddefaultExpression'];
  }
  if (X::hasProp($model->data['data'], 'olddecimal', true) && ($model->data['data']['olddecimal'] !== $model->data['data']['decimal'])) {
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
    'keys' => $keys,
    'cols' => $cols,
    'fields' => [
      $model->data['name'] => $model->data['data'],
    ],
  ];
  //X::ddump($res, $model->db->getAlterTable("edition_test", $update), $update, $model->data, $mod);
  try {
    if ($conn->alter("edition_test", $update)) {
      $res = [
        'success' => true
      ];
    }
  }
  catch (\Exception $e) {
    $res = [
      'error' => $e->getMessage()
    ];
    }
}

return $res;