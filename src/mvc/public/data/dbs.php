<?php
/** @var bbn\Mvc\Controller $ctrl The current controller */

if (!empty($ctrl->post['data'])) {
  $ctrl->addData($ctrl->post['data']);
}

$ctrl->action();
