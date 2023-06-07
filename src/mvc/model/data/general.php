<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$engines = ['mysql', 'sqlite', 'postgre'];
$res = [];

foreach ($engines as $engine) {
  $res[$engine] =   [
    'types' => bbn\Db\Languages\Sql::$types,
  	'predefined' => $model->inc->options->fullOptions('pcolumns', $engine, 'database', 'appui')
  ];
}

return $res;