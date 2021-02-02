<?php
/** @var $model \bbn\Mvc\Model*/
use \bbn\Appui\Dbsync;
$res_files = [];
$files = \bbn\File\Dir::getFiles($model->dataPath('appui-database') . 'sync/conflicts/');
foreach ($files as $file){
  preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.j{1}s{1}o{1}n{1}$/', basename($file), $f);
  if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
    $res_files[] = [
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
    $t = substr($t, Strpos($t, '.') + 1);
    return [
      'text' => $t,
      'value' => $t,
      'columns' => array_keys($model->db->getColumns($t))
    ];
  }, Dbsync::$tables),
  'files' => $res_files,
  'filesHash' => md5(json_encode($res_files)),
  'database' => BBN_DATABASE
];