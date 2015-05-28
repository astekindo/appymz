<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_entry_voucher_model
 *
 * @author faroq
 */
class account_entry_voucher_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function get_cabang() {
        $this->db->select("kd_cabang,nama_cabang");                       
        $query = $this->db->get("mst.t_cabang");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
     public function get_search_akun($search = "", $offset, $length) {
        $sql_search="" ;
        if ($search != ""){
            $sql_search="(kd_akun like '%".  strtolower($search)."%' or lower(nama) like '%".  strtolower($search)."%')" ;
        }
        $this->db->select("kd_akun,nama,dk");
        $this->db->where("aktif","1");
        $this->db->where("header_status is false");
        if ($sql_search!=""){
            $this->db->where($sql_search);       
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
    
    public function get_dk_akun($search = "") {        
        $this->db->select("dk");
        $this->db->where("aktif","1");
        $this->db->where("kd_akun",$search);
        $query = $this->db->get("acc.t_akun");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        $results = $rows[0]->dk;

        return $results;
    }
    public function get_header_transaksi($search) {
        $sql="select *,CASE WHEN (approval1=1 or approval2=1) THEN 1 ELSE 0 END as approval12,CASE WHEN type_transaksi='Cash Out' THEN 1 ELSE 0 END as type_transaksi1
            from acc.t_transaksi
            where aktif=1 and kd_transaksi='".$search."'";          
        $query = $this->db->query($sql);

        $rows = array();
        $total=0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();        }

        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_rows_akun($search = "") {
        
        $this->db->select("td.kd_transaksi, 
                td.kd_akun, 
                ta.nama, 
                upper(ta.dk) as dk_akun, 
                td.dk_transaksi, 0 as debet,0 as kredit,
                td.kd_costcenter as costcenter,
                tc.nama_costcenter,NULL as keterangan_detail,
                NULL as ref_detail"
                , FALSE);
        $this->db->join('acc.t_transaksi tt', 'tt.kd_transaksi=td.kd_transaksi');
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');        
        $this->db->join('acc.t_costcenter tc', 'tc.kd_costcenter=td.kd_costcenter','left'); 
        $this->db->where("td.kd_transaksi",$search);        
        $this->db->order_by("td.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_transaksi_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_transaksi",$search);      
        $query = $this->db->get("acc.t_transaksi_detail");

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
	
}

?>
