<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori3_model extends MY_Model {
	
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
			$sql_search = "AND (lower(a.nama_kategori3) LIKE '%" . strtolower($search) . "%' )";
		}

		$sql1 = "select  a.kd_kategori3, a.nama_kategori3, b.kd_kategori2, b.nama_kategori2, c.nama_kategori1, c.kd_kategori1,
					c.kd_kategori1 || b.kd_kategori2 || a.kd_kategori3 kd_kategori,
					c.nama_kategori1 || ' - ' || b.nama_kategori2 || ' - ' || a.nama_kategori3 nama_kategori,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif 
					from mst.t_kategori3 a,mst.t_kategori2 b,mst.t_kategori1 c 
					where a.kd_kategori2 = b.kd_kategori2 and a.kd_kategori1 = c.kd_kategori1
					and b.kd_kategori1 = c.kd_kategori1
					".$sql_search."
					order by c.nama_kategori1, b.nama_kategori2, a.nama_kategori3
					limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from mst.t_kategori3 a,mst.t_kategori2 b,mst.t_kategori1 c 
					where a.kd_kategori2 = b.kd_kategori2 and a.kd_kategori1 = c.kd_kategori1
					and b.kd_kategori1 = c.kd_kategori1
					".$sql_search;
					
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_nama_kategori3($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = "WHERE (lower(a.nama_kategori3) LIKE '%" . strtolower($search) . "%' )";
		}

		$sql1 = "select distinct(a.nama_kategori3)
					from mst.t_kategori3 a
					 ".$sql_search." 
					order by a.nama_kategori3
					limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
							
		$results = '{success:true,data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_row($id1 = NULL, $id2 = NULL, $id3 = NULL){
		$sql = "select  a.kd_kategori3, a.nama_kategori3, b.kd_kategori2, b.nama_kategori2, c.nama_kategori1, c.kd_kategori1,
					c.kd_kategori1 || b.kd_kategori2 || a.kd_kategori3 kd_kategori,
					c.nama_kategori1 || ' - ' || b.nama_kategori2 || ' - ' || a.nama_kategori3 nama_kategori ,
					CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif 
					from mst.t_kategori3 a,mst.t_kategori2 b,mst.t_kategori1 c 
					where a.kd_kategori1 ='$id1'
					AND a.kd_kategori2 ='$id2'
					AND a.kd_kategori3 ='$id3'
					and a.kd_kategori2 = b.kd_kategori2 and a.kd_kategori1 = c.kd_kategori1
					and b.kd_kategori1 = c.kd_kategori1";

        $query = $this->db->query($sql);		
		// print_r($this->db->last_query());exit;

        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_kategori3', $data);
	}
	
	public function update_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $data = NULL){
		$this->db->where('kd_kategori1', $id1);
		$this->db->where('kd_kategori2', $id2);
		$this->db->where('kd_kategori3', $id3);
		return $this->db->update('mst.t_kategori3', $data);
	}
	
	public function delete_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $data = NULL){

		$this->db->where('kd_kategori1', $id1);
		$this->db->where('kd_kategori2', $id2);
		$this->db->where('kd_kategori3', $id3);
		return $this->db->update('mst.t_kategori3', $data);
	}

	public function get_kategori2($kd_kategori1 = NULL){
            
                if($kd_kategori1 === NULL){
                    $sql= "SELECT kd_kategori2, nama_kategori2 FROM mst.t_kategori2 
				WHERE aktif=true ORDER BY nama_kategori2";
                }else{
                    $sql= "SELECT kd_kategori2, nama_kategori2 FROM mst.t_kategori2 
				WHERE kd_kategori1='$kd_kategori1' AND aktif=true ORDER BY nama_kategori2";
                }
		
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
}
