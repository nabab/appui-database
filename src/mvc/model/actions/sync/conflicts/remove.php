<?php
if (!$model->hasData('id', true)) {
  throw new Exception(_('The "id" property is mandatory'));
}
if (!$model->hasData('filename', true)) {
  throw new Exception(_('The "filename" property is mandatory'));
}
$file = $model->dataPath('appui-database') . 'sync/conflicts/' . $model->data['filename'];
if (!is_file($file)) {
  throw new Exception(sprintf(_('File not found: %s'), $file));
}
if (!($data = yaml_parse_file($file))) {
  throw new Exception(sprintf(_('The file is empty: %s'), $file));
}
if (($idx = \bbn\X::search($data, ['id' => $model->data['id']])) !== null) {
  unset($data[$idx]);
  if (yaml_emit_file($file, $data)) {
    return ['success' => true];
  }
}
return ['success' => false];