<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_kota_model extends MY_Model {
	
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
		$sql_search = "";
		if($search != ""){
			$sql_search = "AND (lower(nama_kota) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "select b.*, a.nama_propinsi
					from mst.t_propinsi a
					join mst.t_kota b on a.kd_propinsi = b.kd_propinsi
					 ".$sql_search." 
					order by kd_propinsi ASC, kd_kota ASC
					limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from mst.t_kota";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_row($id = NULL, $id1 = NULL){
        $sql = "SELECT b.*,a.nama_propinsi
				FROM mst.t_propinsi a, mst.t_kota b 
				WHERE b.kd_propinsi='".$id1."' 
				AND a.kd_propinsi = b.kd_propinsi 
				AND b.kd_kota ='".$id."'";
				
        $query = $this->db->query($sql);
		
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_kota', $data);
	}
	
	public function update_row($kd2 = NULL, $kd1 = NULL, $data = NULL){
		$this->db->where("kd_kota",$kd2);
		$this->db->where("kd_propinsi",$kd1);
		return $this->db->update('mst.t_kota', $data);
	}
	
	public function delete_row($kd2 = NULL, $kd1 = NULL){
		$this->db->where("kd_kota",$kd2);
		$this->db->where("kd_propinsi",$kd1);
		return $this->db->delete('mst.t_kota');
	}

	public function get_propinsi(){
		$sql= "SELECT * FROM mst.t_propinsi";
		$query = $this->db->query($sql);
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}
}