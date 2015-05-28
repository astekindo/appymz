<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receive_order_model extends MY_Model {
	
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
			$sql_search = " WHERE (lower(no_ro) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT *,to_char(created_date,'dd-mm-yyyy hh:mm:ss') complite_date							
						FROM tt_receive_order
					".$sql_search."
					LIMIT ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (SELECT *,to_char(created_date,'dd-mm-yyyy hh:mm:ss') complite_date							
						FROM tt_receive_order
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
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
        $this->db->where("id_ro", $id);
        $query = $this->db->get('tt_receive_order');
        
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
		return $this->db->insert('tt_receive_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('id_ro', $id);
		return $this->db->update('tt_receive_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$this->db->where('id_ro', $id);
		return $this->db->delete('tt_receive_order');
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("id_ro", 'asc');
		$query = $this->db->get("tt_receive_order");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}