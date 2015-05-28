<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_kwitansi_model extends MY_Model { 
	 
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
	public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_no_po($no_po = ''){
		if ($no_po != ''){
			$this->db->where("no_po",$no_po);
		}
		$this->db->select("no_po, kd_suplier_po, nama_supplier, rp_total_po");
		$this->db->join("mst.t_supplier b", "a.kd_suplier_po = b.kd_supplier");
		$this->db->order_by("no_po", 'asc');
		$this->db->where("konsinyasi", '0');
		$query = $this->db->get("purchase.t_purchase a");
		
		$rows = array();
		if($no_po == ''){
			if($query->num_rows() > 0){
				$rows = $query->result();
			}
		}else {
			if($query->num_rows() > 0){
				$rows = $query->row();
			}
		}
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}

}
