<?php
use   bbn\File\Dir;
$res = [
  'success' => false,
  'data' => [],
  'total' => 0
];
if (($path = $model->dataPath('appui-database') . 'sync/structures/')
  && ($files = Dir::getFiles($path))
) {
  $data = [];
  foreach ($files as $file) {
    preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.yml$/', basename($file), $f);
    if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
      $structures = yaml_parse_file($file);
      $tmp = [
        'file' => basename($file),
        'last' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s'),
        'table' => $f[1]
      ];
      $tmp = \bbn\X::mergeArrays($tmp, $structures[0]);
      $data[] = $tmp;
    }
  }
  $res['data'] = $data;
  $res['total'] = count($files);
  $res['success'] = true;
}
return $res;