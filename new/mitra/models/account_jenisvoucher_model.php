<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_jenisvoucher_model
 *
 * @author miyzan
 */
class account_jenisvoucher_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    public function get_rows( $offset, $length) {        
        $query = $this->db->get("acc.t_jenis_voucher",$length, $offset);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
//        return $rows;
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_akun($search = "") {
        
        $this->db->select("td.kd_jenis_voucher, 
                td.kd_akun, 
                ta.nama", FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');
        $this->db->where("td.kd_jenis_voucher",$search);        
        $this->db->order_by("td.kd_akun", "asc");
        $query = $this->db->get("acc.t_jenis_voucher_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_jenis_voucher",$search);      
        $query = $this->db->get("acc.t_jenis_voucher_detail");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_akun_select($search = "") {
        
        $this->db->select("td.kd_akun", FALSE);        
        $this->db->where("td.kd_jenis_voucher",$search);        
        $this->db->order_by("td.kd_akun", "asc");
        $query = $this->db->get("acc.t_jenis_voucher_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }        
        
        $results = $rows;
        return $results;
    }
    
    public function get_all_akun(){
        
        $this->db->select("ta.kd_akun,ta.nama", FALSE);        
        $this->db->where("ta.header_status is FALSE");        
        $this->db->order_by("ta.kd_akun", "asc");
        $query = $this->db->get("acc.t_akun ta");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }        
        
        $results = $rows;
        return $results;
    }
    
    public function check_exists($id,$value){       
        $this->db->where($id,$value);               
        $query = $this->db->get("acc.t_jenis_voucher");
        $retval=FALSE;
        if ($query->num_rows() > 0) {
            $retval=TRUE;
        }
        return $retval;
    }
    
    public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
    }
    
    public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_jenis_voucher', $id);
		return $this->db->update('acc.t_jenis_voucher', $data);
	}
//    $tables = array('table1', 'table2', 'table3');
//    $this->db->where('id', '5');
//    $this->db->delete($tables);    
    public function delete_row($dbname=null,$id = NULL){		
		$this->db->where('kd_jenis_voucher', $id);
		return $this->db->delete($dbname);
	}
    public function delete_row_akun($data = NULL){				
		return $this->db->delete('acc.t_jenis_voucher_detail',$data);
	}
}

?>
