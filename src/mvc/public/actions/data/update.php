<?php

if ( !empty($ctrl->post['host']) && !empty($ctrl->post['db']) && !empty($ctrl->post['table']) ){
  $ctrl->action();
}