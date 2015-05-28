<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_master_closing_model
 *
 * @author miyzan
 */
class account_master_closing_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    public function get_row_exists($sqlwhere=""){
        if($sqlwhere){
            $sqlwhere=' where '.$sqlwhere;
        }
        $sql1 = "SELECT count(*) as total from acc.t_closing_akun a
          left join acc.t_akun ta
          on a.akun_posting=ta.kd_akun $sqlwhere ";
        
        $query = $this->db->query($sql1);
        $total = 0;
        $retval=false;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        if($total>0){
            $retval=true;
        }
        return $retval;
    }
    
     public function get_row($kdcm='') {
         $this->db->select("a.kd_cm,a.thbl_type as thbltype, 
            (CASE WHEN a.thbl_type=1 then 'Bulan' else 'Tahun' end) as thbl_name,
            a.jenis,c.jenis as nmjenis,
            a.akun_jenis as kd_akun_jenis, tb.nama as nmakunjenis,a.akun_posting as kd_akun_posting,ta.nama  as nmakunposting");
        $this->db->where("a.kd_cm",$kdcm);
//        $this->db->where("a.jenis",$jenis);        
        $this->db->join("acc.t_akun ta","a.akun_posting=ta.kd_akun","left");
        $this->db->join("acc.t_akun tb","a.akun_jenis=tb.kd_akun","left");
        $this->db->join("acc.t_jenis_closing c ","a.jenis=c.kode");
        
        $query = $this->db->get("acc.t_closing_akun a");
        
        $rows = array();        
        $total = 0;
        if ($query->num_rows() > 0) {            
            $rows = $query->row();
            $total = $query->num_rows();  
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
//        echo '{"success":true,"data":' . json_encode($row) . '}';
        return $results;
     }
     
    public function get_rows($search = "", $offset, $length) {
        if ($search != "") {
            $sql_search = "where lower(c.jenis) LIKE '%" . strtolower($search) . "%'";
        }
        $sql1 = "SELECT 
            a.kd_cm,a.thbl_type, 
            case when a.thbl_type=1 then 'Bulan' else 'Tahun' end as thbl_name,
            a.jenis as kd_jenis,c.jenis,
            a.akun_jenis,a.akun_posting,ta.nama  as nama_akun
          FROM acc.t_closing_akun a
          left join acc.t_akun ta
          on a.akun_posting=ta.kd_akun 
          inner join acc.t_jenis_closing c 
          on a.jenis=c.kode $sql_search 
          ORDER BY a.thbl_type,a.jenis
          LIMIT $length OFFSET $offset";
        $query = $this->db->query($sql1);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql1 = "SELECT count(*) total from acc.t_closing_akun a
          left join acc.t_akun ta
          on a.akun_posting=ta.kd_akun $sql_search ";
        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }
    

    public function insert_row($data = NULL) {
        return $this->db->insert('acc.t_closing_akun', $data);
		// print_r($this->db->last_query());
    }
    
    public function update_row($data = NULL) {
        return $this->db->insert('acc.t_closing_akun', $data);
		// print_r($this->db->last_query());
    }
    
    public function update_row_set($dbname, $data = NULL, $where) {  
            $this->db->where('kd_cm', $where);        
        return $this->db->update($dbname, $data);		
    }
    
    public function delete_row($id = NULL) {       
        $this->db->where('kd_cm', $id);
        return $this->db->delete('acc.t_closing_akun');
    }

    //put your code here
}

?>
