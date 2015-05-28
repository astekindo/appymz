<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_order_approve_model extends MY_Model {
	
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
			$sql_search = " WHERE (lower(subject) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT a.id_po, a.no_po, a.no_pr, b.subject, a.jumlah, a.ppn, a.grand_total, a.masa_berlaku, 
							a.created_by, to_char(a.created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
							case when a.approval = '0' then 'Pending' when a.approval = '1' then 'Approved' when a.approval = '2' then 'Create RO' 
							when a.approval = '3' then 'Approved Buyer' when a.approval = '4' then 'Not Approved' end status, 
							a.approval sts
						FROM tt_purchase_order a 
						JOIN tt_purchase_request b 
						ON (b.no_pr = a.no_pr)
					".$sql_search." 
					ORDER BY a.no_po
					LIMIT ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (SELECT a.id_po, a.no_po, a.no_pr, b.subject, a.jumlah, a.ppn, a.grand_total, a.masa_berlaku, 
						a.created_by, to_char(a.created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
						case when a.approval = '0' then 'Pending' when a.approval = '1' then 'Approved' when a.approval = '2' then 'Create RO' 
						when a.approval = '3' then 'Approved Buyer' when a.approval = '4' then 'Not Approved' end status, 
						a.approval sts
					FROM tt_purchase_order a 
					JOIN tt_purchase_request b 
					ON (b.no_pr = a.no_pr)
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
        $this->db->where("id_po", $id);
        $query = $this->db->get('tt_purchase_order');
        
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
		return $this->db->insert('tt_purchase_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('id_po', $id);
		return $this->db->update('tt_purchase_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$this->db->where('id_po', $id);
		return $this->db->delete('tt_purchase_order');
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("id_po", 'asc');
		$query = $this->db->get("tt_purchase_order");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}