<?php
/**
 * User: BBN
 * Date: 18/07/2017
 * Time: 20:15
 */
use bbn;
use bbn\X;
/** @var \bbn\Mvc\Controller $ctrl The current controller */
//the base_url
$root = $ctrl->pluginUrl('appui-database').'/';

// Root tabnav
if (empty($ctrl->baseURL)) {
  $url = $root.'tabs';
  $combo = true;
  $title = _("Databases");
  $ctrl
    ->setColor('#666', '#EEE')
    ->setIcon('icon-database');
}
// Routing
else {
  $root .= 'tabs/';
  $url = '';
  $host = '';
  $db = '';
  $table = '';
  $engine = '';
  $combo = false;
  /*
  if ($ctrl->hasArguments(2) && ($ctrl->arguments[1] === 'export')){
    $url = APPUI_DATABASES_ROOT.'tabs/'.$ctrl->arguments[0].'/export';
    $ctrl->setUrl($url);
    $ctrl->addToObj('./tabs/export', [], true);
  }
  */
  if ($ctrl->hasArguments(2)) {
    if (!bbn\Db::isEngineSupported($ctrl->arguments[0])) {
      $ctrl->obj->error = _("Database engine not supported");
      return;
    }
    $engine = $ctrl->arguments[0];

    $host = $ctrl->arguments[1];
    if ($ctrl->hasArguments(3)) {
      //the db
      $db = $ctrl->arguments[2];
    }
    if ($ctrl->hasArguments(4)) {
      // the table
      $table = $ctrl->arguments[3];
    }
  }
  // 1st level containers
  if ($ctrl->baseURL === $root) {
    // Hosts list (home)
    if (!$engine) {
      //takes the controller in private tabs/home
      $ctrl->addToObj('./tabs/home', [], true);
      $url = $root.'home';
    }
    // Host details
    else {
      //host's nav
      $combo = true;
      $url = $root.$engine.'/'.$host;
      $title = $host;
      $ctrl->setIcon(bbn\Db::getEngineIcon($engine));
    }
  }
  // Lower level containers
  elseif (\bbn\X::indexOf($ctrl->baseURL, $root) === 0) {
    $bits = \bbn\X::split(substr($ctrl->baseURL, strlen($root)), '/');
    if (end($bits) === '') {
      array_pop($bits);
    }
    switch (count($bits)) {
        // Host
      case 2:
        if (!empty($db) && ($db !== 'home')) {
          //db's nav
          $combo = true;
          $title = $db;
          $url = $root.$engine.'/'.$host.'/'.$db;
          $ctrl->setIcon('nf nf-fa-database');
        }
        else {
          //host
          $ctrl->addToObj('./tabs/host/'.$engine.'/'.$host, [], true);
          $url = $root.$engine.'/'.$host.'/home';
        }
        break;
        // DB
      case 3:
        //the tab of selected table
        if (!empty($table) && ($table !== 'home')) {
          $ctrl->addToObj('./tabs/table/'.$engine.'/'.$host.'/'.$db.'/'.$table, [], true);
          $url = $root.$engine.'/'.$engine.'/'.$host.'/'.$db.'/'.$table;
          $title = $table;
        }
        // The database homepage
        else {
          $ctrl->addToObj('./tabs/db/'.$engine.'/'.$host.'/'.$db, [], true);
          $url = $root.$engine.'/'.$host.'/'.$db.'/';
        }
        break;
        // Table
      case 4:
        break;
    }
  }
}

if ($url) {
  if ( $combo ){
    $ctrl->combo($title, true);
  }

  $ctrl->setUrl($url);
}