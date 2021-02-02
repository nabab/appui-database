<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */

/** @var \bbn\Mvc\Controller $ctrl The current controller */

$ctrl->setIcon('nf nf-mdi-view_dashboard')
  ->setObj(['notext' => true])
  ->combo(_('Home'), true);