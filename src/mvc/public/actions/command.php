<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */

//send data to tabs/result
$ctrl->reroute('databases/tabs/result', $ctrl->get_model($ctrl->post));