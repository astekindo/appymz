<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_request_asset_model extends MY_Model {
	
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
	public function search_produk_by_supplier($kd_supplier = '', $search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
				$sql_search = " AND ((lower(a.nama_produk) LIKE '%" . $search . "%') 
					OR (a.nama_produk LIKE '%" . $search . "%') 
					OR (lower(a.kd_produk) LIKE '%" . $search . "%') 
					OR (a.kd_produk LIKE '%" . $search . "%') 
					OR (lower(a.kd_produk_supp) LIKE '%" . $search . "%') 
					OR (a.kd_produk_supp LIKE '%" . $search . "%') 
					OR (lower(a.kd_produk_lama) LIKE '%" . $search . "%') 
					OR (a.kd_produk_lama LIKE '%" . $search . "%'))";
		}
		$sql = "SELECT 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan, coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok
  				FROM
					mst.t_supp_per_brg s
				JOIN 
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND is_konsinyasi = '0' ".$sql_search." AND a.kd_kategori1 = '".KD_KATEGORI1_ASSET."'
  				JOIN 
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN 
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
				WHERE 
					a.aktif = 1
					AND 
					s.kd_supplier = '$kd_supplier'
					AND a.aktif_purchase = 1
				GROUP BY 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan
				ORDER BY 
					a.nama_produk ASC";

		$query = $this->db->query($sql);
                //print_r($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
}
