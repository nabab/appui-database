<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:49
 */
 
 
/** @var \bbn\mvc\controller $ctrl The current controller */
if ($ctrl->has_arguments(2)) {
  $ctrl->set_data(
    [
	    'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1]
    ]
  )
   // ->set_url($ctrl->get_path().'/'.$ctrl->data['host'])
    //->set_icon('nf nf-fa-server')
    ->set_icon('nf nf-fa-th_list')
    ->combo($ctrl->data['host'], true);
}