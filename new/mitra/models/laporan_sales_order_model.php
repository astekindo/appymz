<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_sales_order_model extends MY_Model {
	
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
	public function get_sales_order_print($dari_tgl = '',$sampai_tgl = ''){
		$sql = "select * from report.v_lap_sales_order_1 limit 3";
					
		$query = $this->db->query($sql);
		//print_r($query);
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		//print_r($this->db->last_query());
		return $data;
	}
       
}
