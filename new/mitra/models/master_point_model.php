<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_point_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function get_rows($search = "", $offset, $length){
	$sql_search = "";
		if($search != ""){
			$sql_search = "AND (lower(a.nama_kategori1) LIKE '%" . strtolower($search) . "%' )";
		}

		$sql1 = "SELECT a.kd_kategori1 || a.kd_kategori2 || a.kd_kategori3 || a.kd_kategori4 kd_kategori,
						COALESCE (b.nama_kategori1,'',b.nama_kategori1) || ' - ' || COALESCE (c.nama_kategori2, '', c.nama_kategori2) || ' - ' || COALESCE (d.nama_kategori3, '', d.nama_kategori3) || ' - ' || COALESCE (e.nama_kategori4, '',e.nama_kategori4) nama_kategori ,
						a.*,
						CASE WHEN a.aktif = '1' THEN 'Ya' ELSE 'Tidak' END aktif 
						FROM mst.t_point_setting a
						JOIN mst.t_kategori1 b
						ON a.kd_kategori1 = b.kd_kategori1
						LEFT JOIN mst.t_kategori2 c
						ON a.kd_kategori2 = c.kd_kategori2
						AND a.kd_kategori1 = c.kd_kategori1
						LEFT JOIN mst.t_kategori3 d
						ON a.kd_kategori3 = d.kd_kategori3
						AND a.kd_kategori2 = d.kd_kategori2
						AND a.kd_kategori1 = d.kd_kategori1
						LEFT JOIN mst.t_kategori4 e
						ON a.kd_kategori4 = e.kd_kategori4
						AND a.kd_kategori3 = e.kd_kategori3
						AND a.kd_kategori2 = e.kd_kategori2
						AND a.kd_kategori1 = e.kd_kategori1
					".$sql_search."
					order by b.nama_kategori1
					LIMIT ".$length." OFFSET ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c , mst.t_kategori1 d 
					where a.kd_kategori3 = b.kd_kategori3 and a.kd_kategori2 = c.kd_kategori2 and a.kd_kategori1 = d.kd_kategori1
					and b.kd_kategori2 = c.kd_kategori2  and b.kd_kategori1 = d.kd_kategori1
					and c.kd_kategori1 = d.kd_kategori1
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
	
	public function get_row($id = NULL){
		$this->db->select("*,CASE WHEN aktif = '1' THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_kategori1", $id);
        $query = $this->db->get('mst.t_point_setting');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_point_setting', $data);
	}
	
	public function update_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL, $data = NULL){
		$this->db->where('kd_kategori1', $id1);
		$this->db->where('kd_kategori2', $id2);
		$this->db->where('kd_kategori3', $id3);
		$this->db->where('kd_kategori4', $id4);
		return $this->db->update('mst.t_point_setting', $data);
	}
	
	public function delete_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL, $data = NULL){

		$this->db->where('kd_kategori1', $id1);
		$this->db->where('kd_kategori2', $id2);
		$this->db->where('kd_kategori3', $id3);
		$this->db->where('kd_kategori4', $id4);
		return $this->db->update('mst.t_point_setting', $data);
	}

	public function get_kategori3($id1 = NULL, $id2= NULL){
		$sql = "SELECT a.kd_kategori3,a.nama_kategori3
									FROM mst.t_kategori3 a, mst.t_kategori2 b, mst.t_kategori1 c
									WHERE a.kd_kategori1=b.kd_kategori1  AND a.kd_kategori2=b.kd_kategori2 
									AND a.kd_kategori1=c.kd_kategori1 AND b.kd_kategori1=c.kd_kategori1
									AND a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.aktif = true
									ORDER BY a.nama_kategori3 ASC";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}
    
	public function get_kategori4($id1 = NULL, $id2 = NULL, $id3 = NULL)
	{
		$query = $this->db->query("SELECT a.kd_kategori4,a.nama_kategori4
									FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d
									WHERE a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.kd_kategori3='$id3'
									AND b.kd_kategori3=a.kd_kategori3 AND b.kd_kategori2=a.kd_kategori2 AND b.kd_kategori1=a.kd_kategori1 
									AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
									AND d.kd_kategori1=c.kd_kategori1 
									AND a.aktif = true
									ORDER BY a.nama_kategori4 ASC");
		$rows = $query->result();

			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;
		
	}
}
