<?php

/** @var bbn\Mvc\Controller $ctrl */
$ctrl->obj->url = 'result/'.time();

$ctrl->data = $ctrl->post;
//$ctrl->combo(_("Query result"));
$ctrl->combo("Query result", true);