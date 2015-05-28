<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_posting_jp_model
 *
 * @author faroq
 */
class account_posting_jp_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
     public function get_search_akun($search = "", $offset, $length) {
        $sql_search="" ;
        if ($search != ""){
            $sql_search="(kd_akun like '%$search%' or lower(nama) like '%$search%')" ;
        }
        $this->db->select("kd_akun,nama,dk");
        $this->db->where("aktif","1");
        $this->db->where("header_status is false",null);
        if ($sql_search!=""){
            $this->db->where($sql_search,null);       
            }
        
        $query = $this->db->get("acc.t_akun", $length, $offset);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_akun($search = "") {
        
        $this->db->select("td.kd_transaksi, 
                td.kd_akun, 
                ta.nama, 
                upper(ta.dk) as dk_akun, 
                td.dk_transaksi, 0 as debet,0 as kredit", FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');
        $this->db->where("td.kd_transaksi",$search);        
        $this->db->order_by("td.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_mjurnalpenutup_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_transaksi",$search);      
        $query = $this->db->get("acc.t_mjurnalpenutup_detail");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}
	
    //put your code here
}

?>
