<?php

/** @var $ctrl \bbn\Mvc\Controller */

use bbn\X;

$model = $ctrl->db->modelize("bbn_users_options");

$query = $ctrl->db->getCreate("mk_test2", $model);

$ctrl->db->query($query);