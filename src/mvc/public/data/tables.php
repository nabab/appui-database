<?php
if ( $ctrl->has_arguments(3)) {
	$ctrl->obj = $ctrl->get_object_model([
    'engine' => $ctrl->arguments[0],
    'host' => $ctrl->arguments[1],
    'db' => $ctrl->arguments[2],
    'limit' => $ctrl->post['limit'],
    'start' => $ctrl->post['start']
  ]);
}