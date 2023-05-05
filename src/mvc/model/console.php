<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/

if ($model->hasData('code')) {
  $parser = new SQLParserEx();
  $cfg = $parser->parse($model->data['code']);
  //$cfg['limit'] = 10;
  return [
    'limit' => $cfg['limit'],
    'data' => $model->db->rselectAll($cfg)
  ];
}

return [
  'model' => $model->data
];