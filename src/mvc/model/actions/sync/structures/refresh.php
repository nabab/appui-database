<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
use \bbn\File\Dir;
use \bbn\Str;
if (!Dbsync::isInit()) {
  throw new Exception(_('Dbsync is not initialized'));
}
if (!$model->hasData('table', true)) {
  throw new Exception(_('The "table" property is mandatory'));
}
$currentDb = $model->db->getCurrent();
$fileCreated = false;
$numConflicts = 0;
if (($dbs = array_keys($model->inc->options->codeIds('sync', 'database', 'appui')))
  && ($path = $model->dataPath('appui-database') . 'sync/structures/')
) {
  $table = $model->data['table'];
  $res = [];
  $tableStructures = [];
  foreach ($dbs as $db) {
    $model->db->change($db);
    if ($db === $model->db->getCurrent()) {
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Getting structure of the table %s'), $model->db->cfn($table, $db));
      $tableStructures[$db] = $model->db->modelize($table, true);
    }
  }
  $model->db->change($currentDb);
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scanning structure of the tables %s'), $db . '.' .$table) . PHP_EOL;
  $tmp = [
    $currentDb => $tableStructures[$currentDb]
  ];
  $toadd = false;
  foreach ($dbs as $d) {
    if ($d !== $currentDb) {
      $diff = $tableStructures[$currentDb] != $tableStructures[$d];
      if ($diff) {
        $toadd = true;
      }
      $tmp[$d] = $diff ? $tableStructures[$d] : true;
    }
  }
  if ($toadd) {
    $res[] = $tmp;
  }
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Scan of the tables %s completed, found %d conflicts'), $table, count($res)) . PHP_EOL;
  if (Dir::createPath($path)) {
    $file = $path.$table.'_'.date('Ymd_His').'.yml';
    echo date('d/m/Y H:i:s') . ' - ' . _('Checking an old data file') . PHP_EOL;
    if ($files = Dir::getFiles($path)) {
      foreach ($files as $f){
        preg_match('/^(.*)(_\d{4}\d{2}\d{2}_\d{6}\.yml)$/', basename($f), $ff);
        if (!empty($ff) && ($table === $ff[1])) {
          Dir::delete($f);
          echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Removed %s'), $f) . PHP_EOL;
        }
      }
    }
    if (!empty($res)) {
      $numConflicts = count($res);
      yaml_emit_file($file, $res);
      $fileCreated = true;
      echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %s'), $file) . PHP_EOL;
    }
  }
  if ($currentDb !== $model->db->getCurrent()) {
    $model->db->change($currentDb);
  }
  return [
    'success' => true,
    'fileCreated' => $fileCreated,
    'numConflicts' => $numConflicts
  ];
}
return [
  'success' => false
];
