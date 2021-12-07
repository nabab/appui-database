<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */

/** @var \bbn\Mvc\Controller $ctrl The current controller */

if ($ctrl->hasArguments(4)) {
  $ctrl->addData(
    [
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2],
      'table' => $ctrl->arguments[3]
    ]
  )
    ->setUrl($ctrl->data['root'].'/tabs/'.$ctrl->data['engine'].'/'.$ctrl->data['host'].'/'.$ctrl->data['db'].'/'.$ctrl->data['table'])
    ->setIcon('nf nf-fa-table')
    ->combo($ctrl->data['table'], true);
}
