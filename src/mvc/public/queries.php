<?php

use bbn\X;
use bbn\Str;
/** @var $ctrl \bbn\Mvc\Controller */

if ($ctrl->hasArguments()) {
  $ctrl->addData(['engine' => $ctrl->arguments[0]])
    ->combo(X::_("Saved queries' list"), true);
}