<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_blok_lokasi_model extends MY_Model {
	
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
			$sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
		}

		$sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub, c.nama_lokasi2 || '-' || b.nama_blok2 || '-' || a.nama_sub_blok2 nama_sub2, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas,a.nama_sub_blok2,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
		            FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					".$sql_search."
					LIMIT ".$length." OFFSET ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					".$sql_search."";
        
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
	public function get_row($id = NULL, $id1 = NULL, $id2 = NULL){
        $sql = "SELECT a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas, a.nama_sub_blok2,
					CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif
		            FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					WHERE  a.kd_lokasi ='$id' AND a.kd_blok ='$id1' AND kd_sub_blok ='$id2'";

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
		return $this->db->insert('mst.t_sub_blok', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $id1 = NULL, $id2 = NULL, $data = NULL){
		$this->db->where('kd_lokasi', $id);
		$this->db->where('kd_blok', $id1);
		$this->db->where('kd_sub_blok', $id2);
		return $this->db->update('mst.t_sub_blok', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL, $id1 = NULL, $id2 = NULL){
		$data = array(
			'aktif' => '0'
		);
		$this->db->where('kd_lokasi', $id);
		$this->db->where('kd_blok', $id1);
		$this->db->where('kd_sub_blok', $id2);
		return $this->db->update('mst.t_sub_blok', $data);
	}
	
	public function get_blok($kd_lokasi){
		$query = $this->db->query("SELECT a.kd_blok,a.nama_blok
				FROM mst.t_blok a, mst.t_lokasi b  
				WHERE a.kd_lokasi = '$kd_lokasi'
				AND a.kd_lokasi = b.kd_lokasi AND a.aktif is true
				ORDER BY a.nama_blok ASC");

		$rows = $query->result();
		
		if($query->num_rows() > 0)
		{
			$results = '{success:true,data:'.json_encode($rows).'}';
		}else{
			$results = '{success:false,data:'.$kd_lokasi.'}';
		}
		return $results;
	}
        
        public function get_sub_blok($kd_lokasi,$kb_blok){
		$query = $this->db->query("SELECT c.kd_sub_blok, c.nama_sub_blok
                                        FROM mst.t_blok a, mst.t_lokasi b  , mst.t_sub_blok c
                                        WHERE a.kd_lokasi = '$kd_lokasi' 
                                        AND a.kd_blok='$kb_blok'
                                        AND a.kd_lokasi = b.kd_lokasi 
                                        AND c.kd_lokasi=a.kd_lokasi
                                        AND c.kd_lokasi=b.kd_lokasi
                                        AND c.kd_blok=a.kd_blok
                                        AND a.aktif is true AND c.aktif=true
                                        ORDER BY c.nama_sub_blok ASC");

		$rows = $query->result();
		
		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}
}