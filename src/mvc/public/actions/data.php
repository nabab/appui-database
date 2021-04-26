<?php

/** @var $ctrl \bbn\Mvc\Controller */
if (isset($ctrl->post['action'])) {
  $ctrl->getModel($ctrl->post);
  if (isset($ctrl->inc->conn) && $ctrl->inc->conn->check()) {
    switch ($ctrl->post['action']) {
      case 'update':
        $ctrl->addToObj('./data/update', $ctrl->post, true);
        break;
      case 'delete':
        $ctrl->addToObj('./data/delete', $ctrl->post, true);
        break;
    }
  }
}
