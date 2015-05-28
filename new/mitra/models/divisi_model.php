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
class Divisi_model extends MY_Model{
    //put your code here
    public function get_rows($search = "", $offset, $length){
		$this->db->select("*,CASE WHEN aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif",FALSE);
		if($search != ""){
			$sql_search = "(lower(nama_divisi) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$this->db->where('aktif','true');
		$this->db->order_by("kd_divisi", "desc");
		$query = $this->db->get("secman.t_divisi", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(lower(nama_divisi) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$query = $this->db->get("secman.t_divisi");
		
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
		$this->db->select("*,CASE WHEN aktif IS true THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_divisi", $id);
        $query = $this->db->get('secman.t_divisi');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
        
        public function insert_row($data = NULL){
		return $this->db->insert('secman.t_divisi', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_divisi', $id);
		return $this->db->update('secman.t_divisi', $data);
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
		$this->db->where('kd_divisi', $id);
		return $this->db->update('secman.t_divisi', $data);
	}
}

?>
