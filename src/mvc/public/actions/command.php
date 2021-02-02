<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\Mvc\Controller */

//send data to tabs/result
$ctrl->reroute('databases/tabs/result', $ctrl->getModel($ctrl->post));