<?php
/** @var \bbn\Mvc\Model $model */
$dashboard = new \bbn\Appui\Dashboard($model->pluginName());
return [
  'dashboard' => $dashboard->getWidgetsCode($model->pluginUrl().'/widgets/'),
  'current_db' => BBN_DATABASE
];
