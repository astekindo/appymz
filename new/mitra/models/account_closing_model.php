<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_closing_model
 *
 * @author miyzan
 */
class account_closing_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    //put your code here
    public function get_rows_exist($thbl = "", $kdcabang="" ) {
        $sql1 = "SELECT count(*) total from acc.t_master_closing where thbl=$thbl and kd_cabang='$kdcabang'";
        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {   
            $row=$query->row();
            $total = $row->total;
        }
        $results=false;
        if($total>0){
            $results=true;
        }
        return $results;
    }
    public function get_rows_exists_aktif($kdcabang="" ) {
        $sql1 = "SELECT count(*) total from acc.t_master_closing where kd_cabang='$kdcabang' and status=1";
        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {   
            $row=$query->row();
            $total = $row->total;
        }
        $results=false;
        if($total>0){
            $results=true;
        }
        return $results;
    }
    
    public function get_rows($search = "", $offset, $length) {
        $this->db->select("a.thbl,
            (case when a.status=1 then 'aktif' else case when a.status=2 then 'close' else null end end) as status,
            a.aktif_date,
            a.aktif_by,
            a.close_date,
            a.close_by,
            a.kd_cabang,
            b.nama_cabang
        ");
        $this->db->join("mst.t_cabang b","a.kd_cabang = b.kd_cabang");
        $this->db->order_by("a.thbl", "desc");
        $query = $this->db->get("acc.t_master_closing a",$length, $offset);
        
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql1 = "SELECT count(*) total from acc.t_master_closing";
        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }
    
    public function insert_row($db,$data = NULL) {
        return $this->db->insert($db, $data);
		// print_r($this->db->last_query());
    }
    
    public function update_row($dbname, $data, $where) {  
                 
        return $this->db->update($dbname, $data, $where);		
    }

}

?>
