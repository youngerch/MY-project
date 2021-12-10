<?php
/**
 * Created by cs.kim@ablex.co.kr on 2020-08-18
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Member_memo_model extends MY_Model
{
    public $_table = __TABLE_PREFIX__ . "member_memo";
    public $_admin_table = __TABLE_PREFIX__ . "admin";
    public $primary_key = 'seq';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-18
     * DESC : 관리자 메모 목록
     * @param $from_record
     * @param $rows
     * @param array $whereData
     * @return mixed
     */
    public function get_list_all($from_record, $rows, $whereData = array() ){

        $whereSql = "WHERE (1) ";

        foreach($whereData as $key => $value):
            if($value != "") {
                if ($key == "search_str") {
                    $whereSql .= " and (A.name like '%" . $value . "%' or A.email like '%" . $value . "%')";
                } else {
                    $whereSql .= " and {$key} = '{$value}'";
                }
            }
        endforeach;

        $sql = "
			SELECT
			  	A.*,
			  	B.name as admin_name
			FROM
				{$this->_table} AS A
			LEFT JOIN {$this->_admin_table} AS B ON A.reg_admin_seq = B.seq					
			".$whereSql."
			ORDER BY A.seq DESC
			limit ? , ?
		";
        //echo $sql;
        $query = $this->db->query($sql, array($from_record, $rows));
        $result = $query->result();

        return $result;
    }

    /**
     * Created by cs.kim
     * USER : ablex
     * DATE : 2020-08-14
     * TIME : 오후 1:58
     * DESC : 관리자 메모 수
     * @param array $whereData
     * @return mixed
     */
    public function get_count_all($whereData = array()){

        $whereSql = "WHERE (1) ";

        foreach($whereData as $key => $value):
            if($value != "") {
                if ($key == "search_str") {
                    $whereSql .= " and (A.name like '%" . $value . "%' or A.email like '%" . $value . "%')";
                } else {
                    $whereSql .= " and {$key} = '{$value}'";
                }
            }
        endforeach;

        $sql = "
			SELECT
			  	count(*) AS cnt
			FROM
				{$this->_table} A	
			".$whereSql."
		";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cnt;
    }
}