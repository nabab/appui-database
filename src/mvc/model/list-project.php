<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
use bbn\Appui\Project;
/** @var bbn\Mvc\Model $model */

$project = new Project($model->db, null);

return [
  'project' => $project->getProjectInfo()
];
