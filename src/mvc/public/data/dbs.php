<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 20/07/2017
 * Time: 00:39
 */

/** @var \bbn\Mvc\Controller $ctrl The current controller */
if ($ctrl->hasArguments(2)) {
  $ctrl->obj = $ctrl->getObjectModel([
    'engine' => $ctrl->arguments[0],
    'host' => $ctrl->arguments[1],
    'limit' => $ctrl->post['limit'],
    'start' => $ctrl->post['start']
  ]);
}