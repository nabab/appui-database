<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */


/** @var \bbn\mvc\model $model */

if ($model->has_data(['name', 'username', 'password', 'host', 'engine'])
    && ($id_host = $model->inc->dbc->import_host($model->data['username'].'@'.$model->data['host'],
                                                 $model->data['engine'],
                                                 ['password' => $model->data['password']]
                                                ))
) {
  $id_user = $model->db->last_id();
  $res = [
    'success' => true,
    'data' => [
      'id' => $id_host,
      'name' => $model->data['name'],
      'text' => $model->data['name'],
      'admin' => [
        'id' => $id_user,
        'password' => $model->data['password'],
        'username' => $model->data['username']
      ]
    ]
  ];
}
return $res;



