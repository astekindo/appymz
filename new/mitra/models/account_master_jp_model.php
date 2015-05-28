<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_master_jp_model
 *
 * @author faroq
 */
class account_master_jp_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif", FALSE);
        if ($search != "") {
            $sql_search = "(lower(kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(nama_transaksi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_transaksi", "desc");
        $query = $this->db->get("acc.t_mjurnalpenutup", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(nama_transaksi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("acc.t_mjurnalpenutup");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_akun($search = "") {
        
        $this->db->select("td.kd_transaksi, 
                td.kd_akun, 
                ta.nama, 
                upper(ta.dk) as dk_akun, 
                td.dk_transaksi", FALSE);
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
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
		$this->db->select("*",FALSE);
        $this->db->where("kd_transaksi", $id);
        $query = $this->db->get('acc.t_mjurnalpenutup');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($dbname='',$id = NULL, $data = NULL){
		$this->db->where('kd_transaksi', $id);
		return $this->db->update($dbname, $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL, $data = NULL){		
		$this->db->where('kd_transaksi', $id);
		return $this->db->update('acc.t_mjurnalpenutup', $data);
	}
        
        public function delete_rowAll($id = NULL){				
                $sql="delete from acc.t_mjurnalpenutup_detail where kd_transaksi='$id'";
                return $this->db->query($sql);  		
	}
        
         public function cek_exists_rowakun($id = NULL){
            $sql="select * from acc.t_mjurnalpenutup_detail 
                where kd_transaksi='$id'";            
            $query = $this->db->query($sql);            
            return $query->num_rows();
        }
}

?>
