<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buku_model extends MY_Model {
	
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
            $sql_search = "(lower(kd_buku) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_buku", "desc");
        $query = $this->db->get("acc.t_buku_acc", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(kd_buku) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("acc.t_buku_acc");

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
        $this->db->where("kd_buku", $id);
        $query = $this->db->get('acc.t_buku_acc');
        
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
	public function insert_row($data = NULL){
		return $this->db->insert('acc.t_buku_acc"', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_buku', $id);
		return $this->db->update('acc.t_buku_acc"', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL, $data = NULL){		
		$this->db->where('kd_buku', $id);
		return $this->db->update('acc.t_buku_acc"', $data);
	}
	
}
