<?php
use bbn\X;

$res = ['success' => false];
if ($model->hasData(['host', 'engine'], true)
  && ($hostId = $model->inc->dbc->hostId($model->data['host'], $model->data['engine']))
) {
  if ($o = $model->inc->options->option($hostId)) {
    $res = X::mergeArrays($res, [
      'name' => $o['text'],
      'idServer' => $o['id_alias'],
    ]);
    if (!empty($o['code']) && (strpos($o['code'], '@') !== false)) {
      $res['user'] = $o['code'];
    }

    $tmp = gethostbyname($o['text']);
    if (\bbn\Str::isIp($tmp)) {
      $res['ip'] = $tmp;
    }
  }

  try {
    /** @var bbn\Db $conn */
    $conn = $model->inc->dbc->connection($hostId, $model->data['engine']);
  }
  catch (Exception $e) {
    //$res['error'] = $e->getMessage();
  }

  $dbs = $model->getModel($model->pluginUrl('appui-database').'/data/dbs', [
    'host_id' => $hostId,
    'engine' => $model->data['engine']
  ]) ?: [];

  if (isset($dbs['data'])) {
    $dbs = $dbs['data'];
  }

  $size = 0;
  foreach ($dbs as $i => $d) {
    if (!empty($d['size']) && is_numeric($d['size'])) {
      $size += $d['size'];
    }
  }

  $serverInfo = [
    'version' => null,
    'charset' => null,
    'collation' => null,
  ];
  if (!empty($conn)) {
    switch ($model->data['engine']) {
      case 'mysql':
        if ($v = $conn->getRow('SHOW VARIABLES LIKE "character_set_database"')) {
          $serverInfo['charset'] = $v['Value'];
        }

        if ($v = $conn->getRow('SHOW VARIABLES LIKE "collation_database"')) {
          $serverInfo['collation'] = $v['Value'];
        }

        if ($v = $conn->getRow('SELECT VERSION() AS version')) {
          $serverInfo['version'] = $v['version'];
        }

        break;
      case 'pgsql':
        break;
      case 'sqlite':
        break;
    }
  }

  $c = 'bbn\\Db\\Languages\\'.ucfirst($model->data['engine']);
  return X::mergeArrays($res, [
    'success' => true,
    'size' => $size,
    'dataTypes' => $c::$types,
    'predefined' => $model->inc->options->fullOptions('pcolumns', $model->data['engine'], 'engines', 'database', 'appui') ?: [],
    'dbs' => $dbs,
    'id' => $hostId,
    'engine' => $model->data['engine']
  ], $serverInfo);
}

return $res;