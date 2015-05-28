<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_penjualan_per_kategori1_model extends MY_Model {
	
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
	public function get_penjualan_perkategori1_print($kd_user = '',$kd_shift = '',$dari_tgl = '',$sampai_tgl = ''){
		$sql = "select * from report.v_lap_penj_per_kategori1 limit 3";
					
		$query = $this->db->query($sql);
		//print_r($query);
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		//print_r($this->db->last_query());
		return $data;
	}
        
       
}
