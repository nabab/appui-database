<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 17/07/2017
 * Time: 12:40
 */

namespace Appui;

use bbn\Db;
use bbn\Appui\Database;
use bbn\Models\Cls\Db as DbCls;

class queries extends DbCls
{
  protected $dbc;

  public function tables_with_uid($db = '', $host = ''){
    if ( !$db ){
      $db = $this->db->getCurrent();
    }
    if ( $tables = $this->dbc->tables($db, $host) ){
      foreach ( $tables as $t ){
        $keys = $this->dbc->fullKeys($t['id']);
        foreach ( $keys as $k ){
          if ( $k['unique'] && (\count($k['columns']) === 1) ){
            $res[] = $t;
            break;
          }
        }
      }
    }
    return $res;
  }

  public function get($id){
    if ( $query = $this->db->select('bbn_queries', [], ['id' => $id]) ){
      $query->columns = $this->db->selectAll('bbn_queries_columns', [], ['id_query' => $query->id], 'order');
      $query->keys = $this->db->selectAll('bbn_queries_links', [], ['id_query' => $query->id]);
      $query->conditions = $this->db->selectAll('bbn_queries_conditions', [], ['id_query' => $query->id], 'order');
      $query->linked_tables = $this->linked_tables($query->id_table);
    }
    return $query;
  }

  public function __construct(Db $db){
    parent::__construct($db);
    $this->dbc = new Database($db);
  }

  public function build($id){
    return $this->get($id);
  }

  public function linked_tables($id_table){
    if (
      $id_table &&
      ($primary = $this->db->getUniquePrimary($id_table))
    ){
      return array_unique(array_map(function($a){
        return explode('.', $a->column)[1];
      }, $this->db->selectAll('bbn_keys', [], [
        'ref_column' => $id_table.'.'.$primary
      ])));
      }
  }
}