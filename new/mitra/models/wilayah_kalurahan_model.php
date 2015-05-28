<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_kalurahan_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function get_rows($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = "AND (lower(a.nama_kalurahan) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "select a.kd_kalurahan, a.nama_kalurahan, b.kd_kecamatan, b.nama_kecamatan, c.nama_kota, 
					c.kd_kota, d.nama_propinsi, d.kd_propinsi
					from mst.t_kalurahan a, mst.t_kecamatan b, mst.t_kota c , mst.t_propinsi d 
					where a.kd_kecamatan = b.kd_kecamatan and b.kd_kota = c.kd_kota and c.kd_propinsi = d.kd_propinsi
					".$sql_search."
					order by kd_kalurahan
					LIMIT ".$length." OFFSET ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select a.kd_kalurahan, a.nama_kalurahan, b.kd_kecamatan, b.nama_kecamatan, c.nama_kota, 
					c.kd_kota, d.nama_propinsi, d.kd_propinsi
					from mst.t_kalurahan a, mst.t_kecamatan b, mst.t_kota c , mst.t_propinsi d 
					where a.kd_kecamatan = b.kd_kecamatan and b.kd_kota = c.kd_kota and c.kd_propinsi = d.kd_propinsi
					".$sql_search.") as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL){
		$sql = "select a.kd_kalurahan, a.nama_kalurahan, b.kd_kecamatan, b.nama_kecamatan, c.nama_kota, 
					c.kd_kota, d.nama_propinsi, d.kd_propinsi
					from mst.t_kalurahan a, mst.t_kecamatan b, mst.t_kota c , mst.t_propinsi d 
					where 
					c.kd_propinsi = '$id1' and b.kd_kota = '$id2' and a.kd_kecamatan = '$id3' AND a.kd_kalurahan = '$id4'
					AND a.kd_kecamatan = b.kd_kecamatan and b.kd_kota = c.kd_kota and c.kd_propinsi = d.kd_propinsi
					";
        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_kalurahan', $data);
	}
	
	public function update_row($id1 = NULL, $id2 = NULL, $data = NULL){
		$this->db->where('kd_kecamatan', $id1);
		$this->db->where('kd_kalurahan', $id2);
		return $this->db->update('mst.t_kalurahan', $data);
	}
	
	public function delete_row($id1 = NULL, $id2 = NULL){
		$this->db->where('kd_kecamatan', $id1);
		$this->db->where('kd_kalurahan', $id2);
		return $this->db->delete('mst.t_kalurahan');
	}

	public function get_kecamatan($id1 = NULL, $id2= NULL){
		$sql = "SELECT a.kd_kecamatan,a.nama_kecamatan
					FROM mst.t_kecamatan a, mst.t_kota b, mst.t_propinsi c
					WHERE a.kd_kota=b.kd_kota 
					AND b.kd_propinsi=c.kd_propinsi 
					AND b.kd_propinsi='$id1' AND b.kd_kota='$id2' 
					ORDER BY a.nama_kecamatan ASC";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}
}