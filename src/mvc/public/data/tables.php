<?php
if ( $ctrl->hasArguments(3)) {
	$ctrl->obj = $ctrl->getObjectModel([
    'engine' => $ctrl->arguments[0],
    'host_id' => $ctrl->arguments[1],
    'db' => $ctrl->arguments[2],
    'limit' => $ctrl->post['limit'],
    'start' => $ctrl->post['start']
  ]);
}