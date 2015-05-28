<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_kecamatan_model extends MY_Model {
	
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
			$sql_search = " AND (lower(a.nama_kecamatan) LIKE '%" . strtolower($search) . "%') ";
		}

		$sql1 = "select  a.kd_kecamatan, a.nama_kecamatan, b.kd_kota, b.nama_kota, c.nama_propinsi, c.kd_propinsi 
					 from mst.t_kecamatan a,mst.t_kota b,mst.t_propinsi c 
					 WHERE a.kd_kota = b.kd_kota 
					 and b.kd_propinsi = c.kd_propinsi 
					 ".$sql_search." 
					 order by kd_kecamatan
					 limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select  a.kd_kecamatan, a.nama_kecamatan, b.kd_kota, b.nama_kota, c.nama_propinsi, c.kd_propinsi 
					 from mst.t_kecamatan a,mst.t_kota b,mst.t_propinsi c 
					 Where a.kd_kota = b.kd_kota 
					 and b.kd_propinsi = c.kd_propinsi 
					 ".$sql_search." 
					 ) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_row($id1 = NULL, $id2 = NULL, $id3 = NULL){
		$sql = "select  a.kd_kecamatan, a.nama_kecamatan, b.kd_kota, b.nama_kota, c.nama_propinsi, c.kd_propinsi 
					 from mst.t_kecamatan a,mst.t_kota b,mst.t_propinsi c  
					 where b.kd_propinsi ='$id1' 
					 AND a.kd_kota ='$id2' 
					 AND a.kd_kecamatan ='$id3' 
					 and a.kd_kota = b.kd_kota 
					 and b.kd_propinsi = c.kd_propinsi"; 

        $query = $this->db->query($sql);
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_kecamatan', $data);
	}
	
	public function update_row($id1 = NULL, $id2 = NULL, $data = NULL){
		$this->db->where('kd_kota', $id1);
		$this->db->where('kd_kecamatan', $id2);
		return $this->db->update('mst.t_kecamatan', $data);
	}
	
	public function delete_row($id1 = NULL, $id2 = NULL){
		$this->db->where('kd_kota', $id1);
		$this->db->where('kd_kecamatan', $id2);
		return $this->db->delete('mst.t_kecamatan');
	}

	public function get_kota($kd_propinsi = NULL){
		$sql= "SELECT kd_kota, nama_kota FROM mst.t_kota WHERE kd_propinsi='$kd_propinsi'";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
}