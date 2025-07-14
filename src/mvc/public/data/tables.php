<?php
use bbn\X;
if (!empty($ctrl->post['data'])
  && X::hasProps($ctrl->post['data'], ['engine', 'host_id', 'db'], true)
) {
  $ctrl->obj = $ctrl->getObjectModel($ctrl->post['data']);
}
else if ($ctrl->hasArguments(3) ){
	$ctrl->obj = $ctrl->getObjectModel([
    'engine' => $ctrl->arguments[0],
    'host_id' => $ctrl->arguments[1],
    'db' => $ctrl->arguments[2],
    'limit' => $ctrl->post['limit'],
    'start' => $ctrl->post['start']
  ]);
}