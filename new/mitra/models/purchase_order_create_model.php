<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_order_create_model extends MY_Model {
	
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
			$sql_search = " AND (lower(subject) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
						case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
						when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
						status sts
					FROM tt_purchase_request 
					WHERE status = '1'
					".$sql_search." 
					order by no_pr
					LIMIT ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (SELECT id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
						case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
						when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
						status sts
					FROM tt_purchase_request 
					WHERE status = '1'
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
        $this->db->where("id_pr", $id);
        $query = $this->db->get('tt_purchase_request');
        
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
		return $this->db->insert('tt_purchase_request', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('id_pr', $id);
		return $this->db->update('tt_purchase_request', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$this->db->where('id_pr', $id);
		return $this->db->delete('tt_purchase_request');
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("id_pr", 'asc');
		$query = $this->db->get("tt_purchase_request");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}