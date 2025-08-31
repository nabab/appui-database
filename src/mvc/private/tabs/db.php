<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */



/** @var bbn\Mvc\Controller $ctrl The current controller */
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
    ->setIcon('nf nf-fa-database')
    ->combo($ctrl->data['db'], true);
}