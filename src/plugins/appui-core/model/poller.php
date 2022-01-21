<?php
$path = $model->dataPath('appui-database');
return [[
  'id' => 'appui-database-0',
  'frequency' => 60,
  'function' => function(array $data) use($path){
    $res = [
      'success' => true,
      'data' => []
    ];
    $pathConflicts = $path . 'sync/conflicts/';
    $pathStructures = $path . 'sync/structures/';
    $resConflictsFiles = [];
    if ($conflictsFiles = \bbn\File\Dir::getFiles($pathConflicts)) {
      foreach ($conflictsFiles as $file){
        preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.yml$/', basename($file), $f);
        if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
          $resConflictsFiles[] = [
            'text' => $f[1],
            'value' => $f[1],
            'file' => basename($file),
            'date' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s')
          ];
        }
      }
    }
    if ($structuresFiles = \bbn\File\Dir::getFiles($pathStructures)) {
      foreach ($structuresFiles as $file){
        preg_match('/^(.*)_(\d{4}\d{2}\d{2}_\d{6})\.yml$/', basename($file), $f);
        if (!empty($f) && !empty($f[1]) && !empty($f[2])) {
          $resStructuresFiles[] = [
            'text' => $f[1],
            'value' => $f[1],
            'file' => basename($file),
            'date' => date_create_from_format('Ymd_His', $f[2])->format('Y-m-d H:i:s')
          ];
        }
      }
    }
    $conflictsHash = md5(json_encode($resConflictsFiles));
    $structuresHash = md5(json_encode($resStructuresFiles));
    if (isset($data['data']['sync']['conflictsHash'])
      && ($conflictsHash !== $data['data']['sync']['conflictsHash'])
    ) {
      if (!isset($res['data']['sync'])) {
        $res['data']['sync'] = [];
      }
      $res['data']['sync']['conflictsHash'] = $conflictsHash;
      $res['data']['sync']['conflictsFiles'] = $resConflictsFiles;
      if (!isset($res['data']['serviceWorkers'])) {
        $res['data']['serviceWorkers'] = [];
      }
      $res['data']['serviceWorkers']['sync'] = ['conflictsHash' => $conflictsHash];
    }
    if (isset($data['data']['sync']['structuresHash'])
      && ($structuresHash !== $data['data']['sync']['structuresHash'])
    ) {
      if (!isset($res['data']['sync'])) {
        $res['data']['sync'] = [];
      }
      $res['data']['sync']['structuresHash'] = $structuresHash;
      $res['data']['sync']['structuresFiles'] = $resStructuresFiles;
      if (!isset($res['data']['serviceWorkers'])) {
        $res['data']['serviceWorkers'] = [];
      }
      $res['data']['serviceWorkers']['sync'] = ['structuresHash' => $structuresHash];
    }
    return $res;
  }
]];