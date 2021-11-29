<?php

/** @var $ctrl \bbn\Mvc\Controller */

use bbn\X;
use bbn\Str;

$model = $ctrl->db->modelize("bbn_users_options");

$query = $ctrl->db->getCreate("mk_test_".Str::genpwd(5), $model);

X::hdump($ctrl->db->query($query), $query, $model);