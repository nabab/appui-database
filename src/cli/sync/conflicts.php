<?php
use \bbn\Appui\Dbsync;
use \bbn\X;
use \bbn\File\Dir;
use \bbn\Str;
$currentDb = $ctrl->db->getCurrent();
if (Dbsync::isInit()
  && ($tables = array_map(function($t){
    return substr($t, Strpos($t, '.') + 1);
  }, Dbsync::$tables))
  && ($dbs = array_keys($ctrl->inc->options->codeIds('sync', 'database', 'appui')))
) {
  $filesCreated = 0;
  $numConflicts = 0;
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Number of databases: %d'), count($dbs)) . PHP_EOL;
  echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Number of tables: %d'), count($tables)) . PHP_EOL;
  foreach ($tables as $table) {
    $m = $ctrl->getModel($ctrl->pluginUrl('appui-database') . '/actions/sync/conflicts/refresh', [
      'table' => $table
    ]);
    if (!empty($m['success'])){
      if (!empty($m['fileCreated'])) {
        $filesCreated++;
      }
      if (!empty($m['numConflicts'])) {

      }
      $numConflicts += $m['numConflicts'];
    }
  }
}
if ($currentDb !== $ctrl->db->getCurrent()) {
  $ctrl->db->change($currentDb);
}
echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Created %d files'), $filesCreated) . PHP_EOL;
echo date('d/m/Y H:i:s') . ' - ' . sprintf(_('Completed, found %s in total'), $numConflicts) . PHP_EOL;