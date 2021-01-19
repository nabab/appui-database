<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 17/07/2017
 * Time: 12:40
 */

namespace appui;
use bbn;


class queries extends bbn\models\cls\db
{
  protected $dbc;

  public function tables_with_uid($db = '', $host = ''){
    if ( !$db ){
      $db = $this->db->get_current();
    }
    if ( $tables = $this->dbc->tables($db, $host) ){
      foreach ( $tables as $t ){
        $keys = $this->dbc->full_keys($t['id']);
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
      $query->columns = $this->db->select_all('bbn_queries_columns', [], ['id_query' => $query->id], 'order');
      $query->keys = $this->db->select_all('bbn_queries_links', [], ['id_query' => $query->id]);
      $query->conditions = $this->db->select_all('bbn_queries_conditions', [], ['id_query' => $query->id], 'order');
      $query->linked_tables = $this->linked_tables($query->id_table);
    }
    return $query;
  }

  public function __construct(\bbn\db $db){
    parent::__construct($db);
    $this->dbc = new \appui\databases($db);
  }

  public function build($id){
    return $this->get($id);
  }

  public function linked_tables($id_table){
    if (
      $id_table &&
      ($primary = $this->db->get_unique_primary($id_table))
    ){
      return array_unique(array_map(function($a){
        return explode('.', $a->column)[1];
      }, $this->db->select_all('bbn_keys', [], [
        'ref_column' => $id_table.'.'.$primary
      ])));
      }
  }
}