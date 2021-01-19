<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 17/07/2017
 * Time: 14:57
 */
$queries = new \appui\queries($model->db);
return [
  'tables' => $queries->tables_with_uid(),
  'query' => $queries->build(1)
];