<?php
/*
 * Describe what it does!
 *
 **/

/** @var $this \bbn\mvc\controller */
$ctrl->obj->url = 'result/'.time();

$ctrl->data = $ctrl->post;
//$ctrl->combo(_("Query result"));
$ctrl->combo("Query result", true);