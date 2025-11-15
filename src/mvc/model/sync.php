<?php
/** @var bbn\Mvc\Model $model */
use bbn\Str;
use \bbn\Appui\Dbsync;
$resConflictsFiles = [];
$conflictsFiles = \bbn\File\Dir::getFiles($model->dataPath('appui-database') . 'sync/conflicts/');
foreach ($conflictsFiles as $file){
  preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.j{1}s{1}o{1}n{1}$/', basename($file), $f);
  if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
    $resConflictsFiles[] = [
      'text' => $f[1],
      'value' => $f[1],
      'file' => basename($file),
      'date' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s')
    ];
  }
}
$resStructuresFiles = [];
$structuresFiles = \bbn\File\Dir::getFiles($model->dataPath('appui-database') . 'sync/conflicts/');
foreach ($structuresFiles as $file){
  preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.j{1}s{1}o{1}n{1}$/', basename($file), $f);
  if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
    $resStructuresFiles[] = [
      'text' => $f[1],
      'value' => $f[1],
      'file' => basename($file),
      'date' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s')
    ];
  }
}
return [
  'dbs' => array_map(function($a){
    return $a['code'];
  }, $model->inc->options->fullOptions('sync', 'database', 'appui')),
  'tables' => array_map(function($t) use($model){
    $t = Str::sub($t, Str::pos($t, '.') + 1);
    return [
      'text' => $t,
      'value' => $t,
      'columns' => array_keys($model->db->getColumns($t))
    ];
  }, Dbsync::$tables),
  'conflictsFiles' => $resConflictsFiles,
  'conflictsFilesHash' => md5(json_encode($resConflictsFiles)),
  'structuresFiles' => $resStructuresFiles,
  'structuresFilesHash' => md5(json_encode($resStructuresFiles)),
  'database' => BBN_DATABASE
];