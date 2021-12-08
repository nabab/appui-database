<?php

/** @var $model \bbn\Mvc\Model*/

$threads = $model->db->getRow("SHOW STATUS LIKE 'Threads_created'")['Value'];
$connections = $model->db->getRow("SHOW STATUS like 'Connections'")['Value'];
$ratio = $model->db->getOne("select 100 - (( $threads / $connections ) * 100)");
die(var_dump($connections, $threads, $ratio));
# 99.2424