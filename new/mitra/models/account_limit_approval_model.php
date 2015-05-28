<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_limit_approval_model
 *
 * @author miyzan
 */
class Account_limit_approval_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    public function get_rows() {        
        $query = $this->db->get("acc.t_limit_approval");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        return $rows;
//        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
//
//        return $results;
    }
    
    public function get_rows_data() {        
        $query = $this->db->get("acc.t_limit_approval");

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
    public function update_row($data = NULL) {        
        return $this->db->update('acc.t_limit_approval', $data);
    }
     public function insert_row($data = NULL) {        
        return $this->db->insert('acc.t_limit_approval', $data);
    }
    
    
    public function get_row_exists() {        
        $query = $this->db->get("acc.t_limit_approval");
        $retval=FALSE;
        if ($query->num_rows() > 0) {
            $retval=TRUE;            
        }
        return $retval;
    }
}

?>
