<?php
use bbn\X;
use bbn\Db\Languages\Sqlite;
use bbn\Str;

$res = [
  'data' => []
];

if ($model->hasData(['host_id', 'engine'], true)) {
  $engine = Str::isUid($model->data['engine']) ?
    $model->inc->dbc->engineCode($model->data['engine']) :
    $model->data['engine'];
  $engineId = Str::isUid($model->data['engine']) ?
    $model->data['engine'] :
    $model->inc->dbc->engineId($engine);
  $hostId = $model->inc->dbc->hostId($model->data['host_id'], $engine);
  if (!empty($engine)
    && !empty($engineId)
    && !empty($hostId)
  ) {
    $isSqlite = $engine === 'sqlite';
    try {
      $conn = $model->inc->dbc->connection($hostId, $engine);
    }
    catch (\Exception $e) {
      if (!$isSqlite) {
        $res['error'] = $e->getMessage();
      }
    }

    $dbsExcluded = [
      'sys',
      'test',
      'lost+found',
      'performance_schema',
      'mysql',
      '#mysql50#lost+found'
    ];
    $dbs = [];
    if ($isSqlite
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
    else {
      $dbs = $conn ? $conn->getDatabases() : [];
    }

    $odbs = array_map(fn($d) => $d['name'], $model->inc->dbc->dbs($hostId, $engine));
    array_push($dbs, ...array_values(array_filter($odbs, fn($d) => !in_array($d, $dbs))));
    $res['data'] = array_map(
      function($a) use ($model, $hostId, $engine) {
        return $model->inc->dbc->analyzeDatabase($a, $hostId, $engine);
      },
      array_values(array_filter($dbs, fn($d) => !in_array($d, $dbsExcluded)))
    );

    X::sortBy($res['data'], 'name');
  }
}

$res['total'] = count($res['data']);
if (isset($model->data['start'], $model->data['limit'])) {
  $res['data'] = array_slice($res['data'], $model->data['start'], $model->data['limit']);
}

return $res;