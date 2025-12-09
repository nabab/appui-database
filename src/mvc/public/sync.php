<?php
/** @var bbn\Mvc\Controller $ctrl */
$ctrl
  ->setIcon('nf nf-fa-nf nf-fa-exchange')
  ->setUrl(APPUI_DATABASES_ROOT . 'sync')
  ->combo(_("Synchronization"), true);