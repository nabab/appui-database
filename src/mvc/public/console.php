<?php

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Controller $ctrl */
die(var_dump('aaaa'));
if (empty($ctrl->post)) {
  $ctrl->combo(_("console"), true);
}
else {
  $ctrl->action();
}
