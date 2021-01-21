<?php 

if ( ($host = $model->data['host']) && ($db = $model->data['db']) && ($table = $model->data['table']) && ( $original= $model->data['original'] )){
  $success =false;  
  $tmp = [];
  //instantiate the class appui/databases to $model->db
  $t = new \appui\database($model->db);
  //connect using the class database to the given host and given db
  $db_alt = $t->connection($host, $db);

  if (isset($model->data['original'])){
    unset($model->data['original']);
  }
  if(isset($original['original'])){
    unset($original['original']);
  }
  //creates an array containing the columns and values to be updated
  foreach ( array_keys($original) as $o ){
    //if the original value is different from the new value pushes value and key in the array $new
    if ( $original[$o] !== $model->data[$o] ){
      $new[$o] = $model->data[$o];
    }
  }
  
 
 
  if ( !empty($new) ){
    if ( $old = $db_alt->rselect($table, [], $original)){
      $success = $db_alt->update($table, $new, $old);
      //the data of the new row 
      $tmp = array_merge($old, $new); 
      if ( !empty($success) ){
        //if success clears the cache of the db
        $db_alt->clear_all_cache();
      }
    }
  }
  return [
    'success' => $success,
    'data' => $db_alt->rselect($table, [], $tmp) ?: []
  ];
}
