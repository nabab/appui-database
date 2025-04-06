<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */
use bbn\Db;

/** @var \bbn\Mvc\Controller $ctrl The current controller */
if ($ctrl->hasArguments(2)) {
  $ctrl->setData(
    [
	    'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1]
    ]
  )
   // ->setUrl($ctrl->getPath().'/'.$ctrl->data['host'])
    //->setIcon('nf nf-fa-server')
    ->setIcon(Db::getEngineIcon($ctrl->data['engine'])    )
    ->combo($ctrl->data['host'], true);
}