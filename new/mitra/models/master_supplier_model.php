<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_supplier_model extends MY_Model {
	
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
			$sql_search = " AND (lower(nama_supplier) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT kd_supplier, nama_supplier, alias_supplier, pic, alamat, telpon, fax, email, npwp,top,created_date,
							CASE WHEN status ='1' THEN 'Aktif' ELSE 'Tidak Aktif' END status, 
							CASE WHEN aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif,
							CASE WHEN pkp='1' THEN 'Ya' ELSE 'Tidak' END pkp,
                                                        CASE WHEN flag_hj_konsinyasi='1' THEN 'Ya' ELSE 'Tidak' END flag_hj_konsinyasi
					FROM mst.t_supplier 
					WHERE aktif = true
					".$sql_search." ORDER BY nama_supplier
					LIMIT ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (SELECT kd_supplier, nama_supplier, alias_supplier, pic, alamat, telpon, fax, email,top, npwp, aktif,
							CASE WHEN status ='1' THEN 'Aktif' ELSE 'Tidak Aktif' END status, 
							CASE WHEN pkp='1' THEN 'Ya' ELSE 'Tidak' END pkp 
					FROM mst.t_supplier 
					WHERE aktif = true
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
		$this->db->select("*,CASE WHEN pkp='1' THEN '1' ELSE '0' END pkp ,CASE WHEN status='1' THEN '1' ELSE '0' END status ,CASE WHEN aktif IS true THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_supplier", $id);
        $query = $this->db->get('mst.t_supplier');
        
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
		return $this->db->insert('mst.t_supplier', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_supplier', $id);
		return $this->db->update('mst.t_supplier', $data);
	}

/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_supplier_per_brg($id = NULL, $data = NULL){
		$this->db->where('kd_supplier', $id);
		return $this->db->update('mst.t_supp_per_brg', $data);
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
		$this->db->where('kd_supplier', $id);
		return $this->db->update('mst.t_supplier', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_last_records(){
		$query = $this->db->query("SELECT to_number(kd_supplier,'99') kd_supplier FROM mst.t_supplier 
									WHERE kd_supplier = (SELECT MAX(kd_supplier) FROM mst.t_supplier)");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_supplier;
                }
        return $return_value;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		//$this->db->where("aktif is true", NULL);
		$this->db->order_by("kd_supplier", 'asc');
		$query = $this->db->get("mst.t_supplier");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}
