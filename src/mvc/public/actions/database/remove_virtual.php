<?php
$success = false;
if ( !empty($ctrl->post['row']['id']) ){
  if ( $full = $ctrl->inc->options->full_tree($ctrl->post['row']['id']) ){
    
    function remove_history($arr, $db){
      if (!empty($arr['id']) ){
        \bbn\appui\history::delete($arr['id']);
        //have to manually remove the row from bbn_history because it's not automatic basing on the configuration of the table bbn_histort_uids
        $db->delete('bbn_history', ['uid' => $arr['id']], true);
      }
      if ( !empty($arr['items']) ){
        foreach($arr['items'] as $item ){
          remove_history($item, $db);            
        }
      }
      return true;
    };

    \bbn\appui\history::disable();
    if ( empty($ctrl->post['admin']) ){
      $success = $ctrl->inc->options->remove($ctrl->post['row']['id']);
    }
    else if ( !empty($ctrl->post['admin']) && $ctrl->inc->user->is_admin() ){
      $success = remove_history($full, $ctrl->db);
    }
   \bbn\appui\history::enable();
  }
  
  $ctrl->inc->options->delete_cache($ctrl->post['row']['id']);
  $ctrl->obj->success = $success;
}