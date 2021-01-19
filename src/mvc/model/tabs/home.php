<?php
/** @var \bbn\mvc\model $model */
$dashboard = new \bbn\appui\dashboard($model->plugin_name());
return [
  'dashboard' => $dashboard->get_widgets_code($model->plugin_url().'/widgets/'),
  'current_db' => BBN_DATABASE
];
