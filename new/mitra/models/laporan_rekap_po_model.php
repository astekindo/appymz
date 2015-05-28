<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_rekap_po_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	
	
	public function get_data_print(){
            
		$sql = "select * from report.v_lap_rekap_po limit 5  ";
                       
              
		//$sql = "select * from mst.t_supplier order by kd_supplier "	;		
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		// print_r($this->db->last_query());
		return $data;
	}
}
