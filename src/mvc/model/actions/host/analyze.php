<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/

$threads = $model->db->get_row("SHOW STATUS LIKE 'Threads_created'")['Value'];
$connections = $model->db->get_row("SHOW STATUS like 'Connections'")['Value'];
$ratio = $model->db->get_one("select 100 - (( $threads / $connections ) * 100)");
die(var_dump($connections, $threads, $ratio));
# 99.2424