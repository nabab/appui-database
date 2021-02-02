<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */


/** @var \bbn\Mvc\Model $model */

if ($model->hasData(['name', 'username', 'password', 'host', 'engine'])
    && ($id_host = $model->inc->dbc->importHost($model->data['username'].'@'.$model->data['host'],
                                                 $model->data['engine'],
                                                 ['password' => $model->data['password']]
                                                ))
) {
  $id_user = $model->db->lastId();
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



