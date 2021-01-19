<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 21/02/2017
 * Time: 13:25
 */


/*
if ( !isset($ctrl->post['id']) ){
  return false;
}
$ctrl->data = $ctrl->post;
$bits = explode('.', $ctrl->post['id']);
$i = 0;
if ( \is_int($bits[0]) ){
  $ctrl->data['host'] = $bits[0];
  $i = 1;
}
else{
  $ctrl->data['host'] = $ctrl->db->host;
}
while ( isset($bits[$i]) ){
  if ( !isset($ctrl->data['database']) ){
    $ctrl->data['database'] = $bits[$i];
  }
  else if ( !isset($ctrl->data['table']) ){
    $ctrl->data['table'] = $bits[$i];
  }
  else if ( !isset($ctrl->data['column']) ){
    $ctrl->data['column'] = $bits[$i];
  }
  else if ( !isset($ctrl->data['key']) ){
    $ctrl->data['key'] = $bits[$i];
  }
  $i++;
}
$ctrl->data['mapper'] = new \appui\databases($ctrl->db);
*/