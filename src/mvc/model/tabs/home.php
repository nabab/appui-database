<?php
/** @var \bbn\Mvc\Model $model */
if ($model->inc->user->isDev()) {
  $res = [
    'dashboard' => [],
    'current_db' => BBN_DATABASE
  ];
  if (($dashboard = new \bbn\Appui\Dashboard($model->pluginName()))) {
    $widgets = $dashboard->getUserWidgetsCode($model->pluginUrl('appui-dashboard').'/data/');
    $res['dashboard'] = [
      'widgets' => $widgets,
      'order' => $dashboard->getOrder($widgets)
    ];
  }

  return $res;
}
