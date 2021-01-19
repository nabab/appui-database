<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */



/** @var \bbn\mvc\controller $ctrl The current controller */
if ($ctrl->has_arguments(3)) {
  $ctrl->set_data(
    [
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2]
    ]
  )
   // ->set_url($ctrl->get_path().'/'.$ctrl->data['host'].'/'.$ctrl->data['db'])
    //->set_icon('nf nf-fa-database')
    ->set_icon('nf nf-fa-list')
    ->combo($ctrl->data['db'], true);
}