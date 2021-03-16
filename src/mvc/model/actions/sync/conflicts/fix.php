<?php
use bbn\Appui\Dbsync;
if (!dbsync::isInit()) {
  throw new Exception(_('Dbsync is not initialized'));
}
if (!$model->hasData('ids', true)) {
  throw new Exception(_('The "ids" property is mandatory'));
}
if (!$model->hasData('table', true)) {
  throw new Exception(_('The "table" property is mandatory'));
}
if (!$model->hasData('source', true)) {
  throw new Exception(_('The "source" property is mandatory'));
}
if (!$model->hasData('filename', true)) {
  throw new Exception(_('The "filename" property is mandatory'));
}
if (!($dbs = array_values($model->inc->options->getCodes('sync', 'database', 'appui')))) {
  throw new Exception(_('The databases to be synchronized have not been set'));
}
$file = $model->dataPath('appui-database') . 'sync/conflicts/' . $model->data['filename'];
if (!is_file($file)) {
  throw new Exception(sprintf(_('File not found: %s'), $file));
}
if (!($fileData = yaml_parse_file($file))) {
  throw new Exception(sprintf(_('The file is empty: %s'), $file));
}
if ($model->data['ids'] === 'all'){
  $model->data['ids'] = array_map(function($i){
    return $i['id'];
  }, $fileData);
}
$fileIsChanged = false;
$succ = false;
if (($dbIdx = array_search($model->data['source'], $dbs, true)) !== false) {
  unset($dbs[$dbIdx]);
}
if (!empty($dbs)) {
  $succ = true;
  $currentDb = $model->db->getCurrent();
  if ($currentDb !== $model->data['source']){
    $model->db->change($model->data['source']);
  }
  foreach ($model->data['ids'] as $id) {
    if ($model->data['source'] !== $model->db->getCurrent()){
      $model->db->change($model->data['source']);
    }
    if ($model->data['source'] === $model->db->getCurrent()) {
      if (($idxFile = \bbn\X::find($fileData, ['id' => $id])) !== null) {
        $data = $model->db->rselect($model->data['table'], [], $id);
        $isDelete = empty($data);
        $tmpSucc = true;
        foreach ($dbs as $db) {
          $model->db->change($db);
          $dbsyncIsEnabled = Dbsync::isEnabled();
          Dbsync::disable();
          
          /** @todo disattivare controllo chiavi esterne */
  
          if ($db === $model->db->getCurrent()) {
            $tmp = $model->db->rselect($model->data['table'], [], $id);
            if ($isDelete
              && !empty($tmp)
              && !$model->db->delete($model->data['table'], $id)
            ) {
              $succ = false;
              $tmpSucc = false;
            }
            if (!$isDelete
              && empty($tmp)
              && !$model->db->insert($model->data['table'], $data)
            ) {
              $succ = false;
              $tmpSucc = false;
            }
            if (!$isDelete
              && !empty($tmp)
              && ($data != $tmp)
            ) {
              if (($toUpd = array_filter($data, function($v, $f) use($tmp){
                  return is_array($v) || is_array($tmp[$f]) ? $v != $tmp[$f] : $v !== $tmp[$f];
                }, ARRAY_FILTER_USE_BOTH))
                && !$model->db->update($model->data['table'], $toUpd, $id)
              ) {
                $succ = false;
                $tmpSucc = false;
              }
            }
          }
          if ($dbsyncIsEnabled) {
            Dbsync::enable();
          }
  
          /** @todo riattivare controllo chiavi esterne */
  
        }
        if ($tmpSucc) {
          unset($fileData[$idxFile]);
          $fileIsChanged = true;
        }
      }
    }
  }
  if ($currentDb !== $model->db->getCurrent()) {
    $model->db->change($currentDb);
  }
}
if ($fileIsChanged) {
  yaml_emit_file($file, $fileData);
}
return ['success' => $succ];