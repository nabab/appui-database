<?php
$path = $model->dataPath('appui-database') . 'sync/conflicts/';
return [[
  'id' => 'appui-database-0',
  'frequency' => 60,
  'function' => function(array $data) use($path){
    $res = [
      'success' => true,
      'data' => []
    ];
    $res_files = [];
    if ($files = \bbn\File\Dir::getFiles($path)) {
      foreach ($files as $file){
        preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.yml$/', basename($file), $f);
        if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
          $res_files[] = [
            'text' => $f[1],
            'value' => $f[1],
            'file' => basename($file),
            'date' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s')
          ];
        }
      }
    }
    $hash = md5(json_encode($res_files));
    if (isset($data['data']['sync']['conflictsHash']) && ($hash !== $data['data']['sync']['conflictsHash'])) {
      if (!isset($res['data']['sync'])) {
        $res['data']['sync'] = [];
      }
      $res['data']['sync']['conflictsHash'] = $hash;
      $res['data']['sync']['conflictsFiles'] = $res_files;
      if (!isset($res['data']['serviceWorkers'])) {
        $res['data']['serviceWorkers'] = [];
      }
      $res['data']['serviceWorkers']['sync'] = ['conflictsHash' => $hash];
    }
    return $res;
  }
]];