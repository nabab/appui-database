<?php
/** @var \bbn\Mvc\Model $model */

use bbn\X;

$d =& $model->data;
if ($model->hasData('limit', true) && X::hasProps($d['data'], ['db', 'host', 'table', 'engine'], true)) {
  try {
    $conn = $model->inc->dbc->connection($d['data']['host'], $d['data']['engine'], $d['data']['db']);
  }
  catch (\Exception $e) {
    throw new \Exception($e->getMessage());
  }

  if (!$conn->check()) {
    throw new \Exception(_("Impossible to connect to db").' '.$d['data']['db']);
  }

  $cfg = $model->inc->dbc->getGridConfig($d['data']['table'], $d['data']['db'], $d['data']['host'], $d['data']['engine']);
  $grid = new \bbn\Appui\Grid($conn, $d, $cfg['php']);
  if (!$grid->check()) {
    throw new \Exception(_("Impossible to make a grid with table").' '.$d['data']['table']);
  }

  if ($table = $grid->getDatatable()) {
    foreach ($table['data'] as $idx => $t) {
      //die(var_dump($t));
      $table['data'][$idx] = array_map(
        function($a) {
          //removes the html tag different from <br> and cuts the string
          if ( is_string($a) && (strlen($a) > 100) ){
            $a = \bbn\Str::cut(strip_tags($a, '<br>'), 100);
          }

          return $a;
        },
        $t
      );
    }

    return $table;
  }
  else {
    throw new \Exception(_("Impossible to get the data from table").' '.$d['data']['table']);
  }
}
