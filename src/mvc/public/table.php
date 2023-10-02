<?php


/** @var \bbn\Mvc\Controller $ctrl The current controller */
if ($ctrl->hasArguments(4)) {
  $ctrl->addData(
    [
      'root' => $ctrl->pluginUrl('appui-database') . '/',
      'engine' => $ctrl->arguments[0],
      'host' => $ctrl->arguments[1],
      'db' => $ctrl->arguments[2],
      'table' => $ctrl->arguments[3]
    ]
  )
    //setUrl($ctrl->data['root'].'/'.$ctrl->data['engine'].'/'.$ctrl->data['host'].'/'.$ctrl->data['db'].'/'.$ctrl->data['table'])
    ->setIcon('nf nf-fa-table')
    ->setUrl($ctrl->pluginUrl('appui-database') . '/table/' . $ctrl->data['engine'] . '/' . $ctrl->data['host'] . '/' . $ctrl->data['db'] . '/' . $ctrl->data['table'])
    ->combo($ctrl->data['table'], $ctrl->data);
}
