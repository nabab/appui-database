<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
use bbn\Appui\Project;
/** @var $model \bbn\Mvc\Model*/

$project = new Project($model->db, null);

return [
  'project' => $project->getProjectInfo()
];
