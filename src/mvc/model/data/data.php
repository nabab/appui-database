<?php
use bbn\X;
use bbn\Str;
use bbn\Appui\Grid;

$d = $model->data['data'] ?? [];
if ($model->hasData('limit', true)
  && X::hasProps($d, ['db', 'host', 'table', 'engine'], true)
) {
  $host = $d['host'];
  $table = $d['table'];
  $db = $d['db'];
  $engine = $d['engine'];
  try {
    $conn = $model->inc->dbc->connection($host, $engine, $db);
  }
  catch (\Exception $e) {
    throw new Exception($e->getMessage());
  }

  if (!$conn->check()) {
    throw new Exception(X::_("Impossible to connect to db %s", $db));
  }

  if (($cfg = $model->inc->dbc->getGridConfig($table, $db, $host, $engine))
    && !empty($cfg['php'])
  ) {
    $grid = new Grid($conn, $model->data, $cfg['php']);
    if (!$grid->check()) {
      throw new Exception(X::_("Impossible to make a grid with table %s", $table));
    }

    if ($data = $grid->getDatatable()) {
      foreach ($data['data'] as $idx => $t) {
        //removes the html tag different from <br> and cuts the string
        $data['data'][$idx] = array_map(
          fn($a) => is_string($a) && (strlen($a) > 100) ?
            Str::cut(strip_tags(Str::sanitizeHtml($a), '<br>'), 100) :
            $a,
          $t
        );
      }

      $data['config'] = $cfg;
      return $data;
    }
    else {
      throw new \Exception(X::_("Impossible to get the data from table %s", $table));
    }
  }
  else {
    throw new \Exception(X::_("Impossible to get the grid cfg for the table %s", $table));
  }
}
