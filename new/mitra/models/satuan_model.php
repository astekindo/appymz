<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Satuan_model extends MY_Model {
	
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
		$this->db->select("*,CASE WHEN aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif",FALSE);
		if($search != ""){
			$sql_search = "(lower(nm_satuan) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$this->db->where('aktif','true');
		$this->db->order_by("kd_satuan", "desc");
		$query = $this->db->get("mst.t_satuan", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(lower(nm_satuan) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$query = $this->db->get("mst.t_satuan");
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_nm_satuan($search = "", $offset, $length){
		$this->db->order_by("nm_satuan", "ASC");
		
		if($search != ""){
			$sql_search = "(lower(nm_satuan) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		
		$query = $this->db->get("mst.t_satuan", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$results = '{success:true,data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
		$this->db->select("*,CASE WHEN aktif IS true THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_satuan", $id);
        $query = $this->db->get('mst.t_satuan');
        
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
		return $this->db->insert('mst.t_satuan', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_satuan', $id);
		return $this->db->update('mst.t_satuan', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$data = array(
			'aktif' => '0'
		);
		$this->db->where('kd_satuan', $id);
		return $this->db->update('mst.t_satuan', $data);
	}	
}