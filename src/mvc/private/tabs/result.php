<?php

/** @var $this \bbn\Mvc\Controller */
$ctrl->obj->url = 'result/'.time();

$ctrl->data = $ctrl->post;
//$ctrl->combo(_("Query result"));
$ctrl->combo("Query result", true);