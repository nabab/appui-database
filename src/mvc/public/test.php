<?php

/** @var bbn\Mvc\Controller $ctrl */

use bbn\X;
use bbn\Str;

$model = $ctrl->db->modelize("bbn_options");

$query = $ctrl->db->getCreate("Compare_bbn_options", $model);

X::hdump($ctrl->db->query($query), $query, $model);