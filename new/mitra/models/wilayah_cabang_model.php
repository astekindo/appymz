<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_cabang_model extends MY_Model {
	
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
	public function get_rows($search = "", $offset, $length){
//        $this->db->where('status', 1);
		if($search != ""){
			$sql_search = " where (lower(kd_cabang) LIKE '%" . strtolower($search) . "%' or lower(nama_cabang) LIKE '%" . strtolower($search) . "%') ";
		}

        $sql = "select kd_cabang,nama_cabang, status
from mst.t_cabang $sql_search order by kd_cabang desc limit $length offset $offset";
        $query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(kd_cabang) as total from mst.t_cabang $sql_search ";
		$query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
		$sql = "SELECT kd_cabang, nama_cabang, status
				FROM mst.t_cabang WHERE kd_cabang = '$id'";

        $query = $this->db->query($sql);
        
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
		return $this->db->insert('mst.t_cabang', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_cabang', $id);
		return $this->db->update('mst.t_cabang', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){
		$this->db->where('kd_cabang',$id);
		return $this->db->update('mst.t_cabang',array('status' => 0));
	}
	
		
}