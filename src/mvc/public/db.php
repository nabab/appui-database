<?php

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Controller $ctrl */

if ($ctrl->hasArguments(3)) {
  $ctrl->setData(
    [
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2]
    ]
  )
   // ->setUrl($ctrl->getPath().'/'.$ctrl->data['host'].'/'.$ctrl->data['db'])
    //->setIcon('nf nf-fa-database')
    ->setIcon('nf nf-fa-list')
    ->combo($ctrl->data['db'], $ctrl->data);
}