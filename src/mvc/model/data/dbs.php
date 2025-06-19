<?php
use bbn\X;
use bbn\Db\Languages\Sqlite;

$res = [
  'data' => []
];

if ($model->hasData('host_id', true)) {
  $engine = $model->data['engine'] ?? '';
  $hostId = $model->inc->dbc->hostId($model->data['host_id'], $engine);
  try {
    $conn = $model->inc->dbc->connection($hostId, $engine);
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
  }

  $dbsExcluded = [
    'sys',
    'test',
    'lost+found',
    'performance_schema',
    'mysql',
    '#mysql50#lost+found'
  ];
  $default = [
    'name' => null,
    'text' => null,
    'id_host' => $hostId,
    'is_real' => false,
    'is_virtual' => false,
    'num_connections' => null,
    'num_procedures' => null,
    'num_tables' => null,
    'size' => null,
    'is_bbn' => null,
    'has_history' => null,
    'last_check' => null,
    'engine' => $conn ? $conn->getEngine() : ($engine ?: null),
  ];
  $isSqlite = $default['engine'] === 'sqlite';
  $dbs = $conn ? $conn->getDatabases() : [];
  if (!$conn
    && $isSqlite
    && ($path = $model->inc->options->code($hostId))
  ) {
    $pbits = X::split($path, '/');
    foreach ($pbits as &$bit) {
      if (str_starts_with($bit, 'BBN_') && defined($bit)) {
        $bit = constant($bit);
        if (str_ends_with($bit, '/')) {
          $bit = rtrim($bit, '/');
        }
      }
    }

    $path = X::join($pbits, '/');
    if (is_dir($path)) {
      $dbs = Sqlite::getHostDatabases($path);
    }
  }

  $dbsOptions = $model->inc->dbc->fullDbs($hostId, $engine);
  $res['data'] = array_map(
    function ($a) use (&$dbs, $default) {
      $idx = array_search($a['name'], $dbs);
      $a['is_real'] = $idx !== false;
      if ($a['is_real']) {
        array_splice($dbs, $idx, 1);
      }

      $a['is_virtual'] = true;
      return array_merge($default, $a);
    },
    $dbsOptions
  );

  foreach ($dbs as $db) {
    if (!in_array($db, $dbsExcluded)) {
      $res['data'][] = array_merge($default, [
        'name' => $db,
        'text' => $db,
        'is_real' => true
      ]);
    }
  }

  foreach ($res['data'] as $i => $d) {
    if (!empty($d['is_real'])) {
      $res['data'][$i]['size'] = $conn ?
        $conn->dbSize($d['name']) :
        ($isSqlite && !empty($path) ? filesize($path.'/'.$d['name']) : 0);
      $qCharset = match ($engine) {
        'mysql' => "
          SELECT default_character_set_name AS charset
          FROM information_schema.SCHEMATA
          WHERE schema_name = ?
        ",
        'pgsql' => "
          SELECT pg_encoding_to_char(encoding) AS charset
          FROM pg_database
          WHERE datname = ?
        ",
        default => false
      };
      $qCollation = match ($engine) {
        'mysql' => "
          SELECT default_collation_name AS collation
          FROM information_schema.SCHEMATA
          WHERE schema_name = ?
        ",
        'pgsql' => "
          SELECT datcollate AS collation
          FROM pg_database
          WHERE datname = ?
        ",
        default => false
      };
      if (!empty($qCharset)
        && ($v = $conn->getRow($qCharset, $d['name']))
      ) {
        $res['data'][$i]['charset'] = $v['charset'];
      }

      if (!empty($qCollation)
        && ($v = $conn->getRow($qCollation, $d['name']))
      ) {
        $res['data'][$i]['collation'] = $v['collation'];
      }

      if ($isSqlite) {
        $res['data'][$i]['charset'] = '';
      }
    }
  }

  X::sortBy($res['data'], 'name');
}

$res['total'] = \count($res['data']);
//$res['data'] = \array_slice($res['data'], $model->data['start'], $model->data['limit']);
return $res;