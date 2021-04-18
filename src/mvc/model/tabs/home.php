<?php
/** @var \bbn\Mvc\Model $model */
if ($model->inc->user->isDev()) {
  $dashboard = new \bbn\Appui\Dashboard();
  if ($dashboard->exists($model->pluginName())) {
    return [
      'dashboard' => $dashboard->getUserWidgetsCode($model->pluginUrl().'/widgets/'),
      'current_db' => BBN_DATABASE
    ];
  }

  return [
    'dashboard' => [],
    'current_db' => BBN_DATABASE
  ];
}
