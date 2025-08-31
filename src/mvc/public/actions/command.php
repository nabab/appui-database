<?php

/** @var bbn\Mvc\Controller $ctrl */

//send data to tabs/result
$ctrl->reroute('databases/tabs/result', $ctrl->getModel($ctrl->post));