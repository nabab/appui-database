<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */

/** @var \bbn\mvc\controller $ctrl The current controller */

$ctrl->set_icon('nf nf-mdi-view_dashboard')
  ->set_obj(['notext' => true])
  ->combo(_('Home'), true);