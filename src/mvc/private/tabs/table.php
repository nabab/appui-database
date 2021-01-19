<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */
 
/** @var \bbn\mvc\controller $ctrl The current controller */

if ($ctrl->has_arguments(4)) {
  $ctrl->set_data(
    [
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2],
      'table' => $ctrl->arguments[3]
    ]
  )
    //->set_url($ctrl->get_path().'/'.$ctrl->data['host'].'/'.$ctrl->data['db'].'/'.$ctrl->data['table'])
    ->set_icon('nf nf-fa-table')
    ->combo($ctrl->data['table'], true);
}