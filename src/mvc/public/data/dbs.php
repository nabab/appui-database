<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 20/07/2017
 * Time: 00:39
 */

/** @var \bbn\mvc\controller $ctrl The current controller */
if ($ctrl->has_arguments(2)) {
  $ctrl->obj = $ctrl->get_object_model([
    'engine' => $ctrl->arguments[0],
    'host' => $ctrl->arguments[1],
    'limit' => $ctrl->post['limit'],
    'start' => $ctrl->post['start']
  ]);
}