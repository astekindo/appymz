<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_retur_order_model extends MY_Model {
	
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
		return $this->db->insert($table, $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_supplier($kd_supplier = '', $search = ''){
		$sql_search = '';
		if($search != ''){
			$sql_search = " AND ((lower(a.nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
		}
		$sql = 'SELECT 
					s.*, a.nama_produk, a.kd_produk, b.nm_satuan
  				FROM
					mst.t_supp_per_brg s
				JOIN 
					mst.t_produk a ON s.kd_produk = a.kd_produk
  				JOIN 
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				WHERE 
					a.aktif is true
					AND
					s.konsinyasi = \'1\'
					AND 
					s.kd_supplier = \''. $kd_supplier . '\''
					.$sql_search.'
				ORDER BY 
					a.nama_produk ASC';

		$query = $this->db->query($sql);
		// print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		return $rows;
	}
}