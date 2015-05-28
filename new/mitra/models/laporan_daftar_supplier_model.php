<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_daftar_supplier_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	public function get_data_print($kd_supplier=''){
           $sql = "select * from mst.t_supplier where kd_supplier = $kd_supplier";
          
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select * from mst.t_supplier";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
         public function get_data_po_print($kd_supplier=''){
		$sql = "select * from mst.t_supplier where kd_supplier = '$kd_supplier'
                        ";
 //print_r($sql);
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select * from mst.t_supplier where kd_supplier = '$kd_supplier'
                        ";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}
