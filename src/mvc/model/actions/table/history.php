<?php
use bbn\Appui\History;

if ($model->hasData(['host', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $host = $model->data['host'];
  $db = $model->data['db'];
  $table = $model->data['table'];
  try {
    if (($conn = $model->inc->dbc->connection($host, $engine, $db))
      && ($conn->getHash() === $model->db->getHash())
    ) {
      $succ = $model->inc->dbc->integrateHistoryIntoTable(
        $table,
        $db,
        $host,
        $engine,
        $model->data['user'] ?? null,
        $model->data['date'] ?? null
      );

      // aggiornare struttura opzioni della tabella, creare opzioni per db se necessario
    
      // se la tabella non è vuota creare i records di history basandosi sull'utente e la data inviata

    }
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }

  
}