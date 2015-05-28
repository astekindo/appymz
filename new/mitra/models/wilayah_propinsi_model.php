<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_propinsi_model extends MY_Model {
	
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
	public function get_rows($search = "", $offset, $length, $fields = ""){
		if($search != "" && $fields == ""){
			$sql_search = "(lower(nama_propinsi) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		
		if($fields != ""){
			$sql_multi = "(";
			foreach($fields as $field){
				$sql_multi .= "(lower(". $field . ") LIKE '%" . strtolower($search) . "%') OR ";				
			}
			$sql_multi =  substr($sql_multi, 0, -4);
			$sql_multi .= ")";
			$this->db->where($sql_multi, NULL);
		}
		$this->db->order_by("kd_propinsi", "desc");
		
        $query = $this->db->get("mst.t_propinsi");
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != "" && $fields == ""){
			$sql_search = "(lower(nama_propinsi) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		if($fields != ""){
			$sql_multi = "(";
			foreach($fields as $field){
				$sql_multi .= "(lower(". $field . ") LIKE '%" . strtolower($search) . "%') OR ";				
			}
			$sql_multi =  substr($sql_multi, 0, -4);
			$sql_multi .= ")";
			$this->db->where($sql_multi, NULL);
		}
		$query = $this->db->get("mst.t_propinsi");
		
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
		$sql = "SELECT kd_propinsi, nama_propinsi
				FROM mst.t_propinsi WHERE kd_propinsi = '$id'";

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
		return $this->db->insert('mst.t_propinsi', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_propinsi', $id);
		return $this->db->update('mst.t_propinsi', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){
		$this->db->where('kd_propinsi',$id);
		return $this->db->delete('mst.t_propinsi');
	}
	
		
}