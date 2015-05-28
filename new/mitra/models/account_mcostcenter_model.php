<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_mcostcenter
 *
 * @author faroq
 */
class account_mcostcenter_model extends MY_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function get_rows($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN aktif ='1' THEN 1 ELSE 0 END aktif", FALSE);
        if ($search != "") {
            $sql_search = "(lower(nama_costcenter) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_costcenter", "desc");
        $query = $this->db->get("acc.t_costcenter", $length, $offset);
        
        $total = 0;
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total=$query->num_rows();
        }

        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    } 
    
    public function get_rows_twin($search = "", $offset, $length) {
        $this->db->select("tc.kd_costcenter,tc.nama_costcenter");
        if ($search != "") {
            $sql_search = "((lower(tc.nama_costcenter) LIKE '%" . strtolower($search) . "%') or (tca.kd_akun LIKE '%" . strtolower($search) . "%'))";
            $this->db->where($sql_search, NULL);
        }        
        $this->db->join("acc.t_costcenter_akun tca","tc.kd_costcenter=tca.kd_costcenter",'left');
        $this->db->where('tc.aktif', '1');
        $this->db->order_by("tc.kd_costcenter", "desc");
        $this->db->distinct();
        $query = $this->db->get("acc.t_costcenter tc", $length, $offset);
        
        $total = 0;
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total=$query->num_rows();
        }

        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    } 
    
    public function get_rows_all() {
        $this->db->select("*,CASE WHEN aktif ='1' THEN 1 ELSE 0 END aktif", FALSE);        
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_costcenter", "desc");
        $query = $this->db->get("acc.t_costcenter");
        
        $total = 0;
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total=$query->num_rows();
        }

        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    } 
    
    public function get_row($id = NULL) {
        $this->db->select("kd_costcenter,nama_costcenter", FALSE);
        $this->db->where("kd_costcenter", $id);
        $query = $this->db->get('acc.t_costcenter');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }
    
    public function get_rows_akun($search = "",$offset, $length) {
        
        $this->db->select("td.kd_costcenter, 
                td.kd_akun, 
                ta.nama", FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');
        $this->db->where("td.kd_costcenter",$search);        
        $this->db->order_by("td.kd_akun", "asc");
        $query = $this->db->get("acc.t_costcenter_akun td", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_costcenter",$search);      
        $query = $this->db->get("acc.t_costcenter_akun");

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
        $this->db->where("td.kd_costcenter",$search);        
        $this->db->order_by("td.kd_akun", "asc");
        $query = $this->db->get("acc.t_costcenter_akun td");

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
    
    public function insert_row($data = NULL) {
        return $this->db->insert('acc.t_costcenter', $data);
		// print_r($this->db->last_query());
    }
    public function insert_row_table($table='',$data = NULL) {
        return $this->db->insert($table, $data);
		// print_r($this->db->last_query());
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_costcenter', $id);
        return $this->db->update('acc.t_costcenter', $data);
		// print_r($this->db->last_query());
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $data = array(
            'aktif' => '0'
        );
        $this->db->where('kd_costcenter', $id);
        return $this->db->update('acc.t_costcenter', $data);
    }
    
    public function delete_rows($table=null,$where = array()) {                
        return $this->db->delete($table ,$where); 
    }
}

?>
