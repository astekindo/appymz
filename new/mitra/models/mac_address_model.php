<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of divisi_model
 *
 * @author faroq
 */
class Mac_address_model extends MY_Model{
    //put your code here
    public function select_temp($mac_address = ""){
		$where = array(
					'mac_address' => $mac_address
					
				);
		$this->db->where($where);
		
		$query = $this->db->get("secman.t_mac_address");
		
                return $query->result();
	}
    public function get_rows($search = "", $offset, $length){
		$this->db->select("*,CASE WHEN status = 1 THEN 'Ya' ELSE 'Tidak' END status",FALSE);
		if($search != ""){
			$sql_search = "(lower(nama) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		//$this->db->where('status','1');
		$this->db->order_by("mac_address", "desc");
		$query = $this->db->get("secman.t_mac_address", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(lower(nama) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$query = $this->db->get("secman.t_mac_address");
		
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
		$this->db->select("*,CASE WHEN status = 1 THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("mac_address", $id);
        $query = $this->db->get('secman.t_mac_address');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
        
        public function insert_row($data = NULL){
		return $this->db->insert('secman.t_mac_address', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('mac_address', $id);
		return $this->db->update('secman.t_mac_address', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$data = array(
			'status' => '0'
		);
		$this->db->where('mac_address', $id);
		return $this->db->update('secman.t_mac_address', $data);
	}
}

?>
