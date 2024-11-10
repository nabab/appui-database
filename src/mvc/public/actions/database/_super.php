<?php

/** @var bbn\Mvc\Controller $ctrl */
if ($ctrl->hasArguments(3)) {
  $ctrl->setData(
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
    if (!empty($ctrl->post['db'])) {
      $conn->change($ctrl->post['db']);
    }

    $ctrl->addInc('conn', $conn);
    return true;
  }
}
return false;
