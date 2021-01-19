<?php
/*
 * Describe what it does!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ($ctrl->has_arguments(3)) {
  $ctrl->set_data(
    [
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2]
    ]
  );
  return true;
}
elseif (isset($ctrl->post['host_id'])) {
  try {
    $conn = $ctrl->inc->dbc->connection($ctrl->post['host_id']);
  }
  catch (\Exception $e) {
    return false;
  }
  if ($conn->check()) {
    $ctrl->add_inc('conn', $conn);
    return true;
  }
}
return false;
