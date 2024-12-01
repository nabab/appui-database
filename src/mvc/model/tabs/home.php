<?php
use bbn\X;
use bbn\Appui\Dashboard;
/** @var \bbn\Mvc\Model $model */
if ($model->inc->user->isDev()) {
  $res = [
    'dashboard' => [],
    'current_db' => BBN_DATABASE
  ];
  if (($dashboard = new Dashboard($model->pluginName()))) {
    //X::ddump($model->pluginName(), $dashboard->getUserWidgetsCode($model->pluginUrl('appui-database')), $dashboard->getUserWidgets($model->pluginUrl('appui-database').'/data/'));
    $widgets = $dashboard->getUserWidgetsCode($model->pluginUrl('appui-dashboard').'/data/');
    $res['dashboard'] = [
      'widgets' => $widgets,
      'order' => $dashboard->getOrder($widgets)
    ];
  }

  return $res;
}
