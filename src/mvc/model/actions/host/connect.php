<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model */
if ($model->has_data(['engine', 'host', 'username', 'name', 'password'], true)) {
  $model->data['res'];
  try {
    $db = new \bbn\db([
      'engine' => $model->data['engine'],
      'host' => $model->data['host'],
      'user' => $model->data['username'],
      'pass' => $model->data['password']
    ]);
  }
  catch (\Exception $e) {
    $model->data['res']['error'] = $e->getMessage();
  }

  if ($db) {
    $model->data['res']['success'] = true;
  }

}

return $model->data['res'];
