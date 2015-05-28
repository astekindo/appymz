<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_penjualan4_model extends MY_Model {
	
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
	public function search_produk_by_supplier($kd_supplier = '', $search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = " AND ((lower(a.nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
		}
		$sql = "SELECT 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan, coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok
  				FROM
					mst.t_supp_per_brg s
				JOIN 
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND is_konsinyasi = '0' ".$sql_search." 
  				JOIN 
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN 
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
				WHERE 
					a.aktif=1
					AND 
					s.kd_supplier = '$kd_supplier'
					AND a.aktif_purchase = 1
				GROUP BY 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan
				ORDER BY 
					a.nama_produk ASC";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_penjualan4_print(){
		$sql = "select * from report.v_lap_penjualan4 limit 6";
					
		$query = $this->db->query($sql);
		//print_r($query);
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		//print_r($this->db->last_query());
		return $data;
	}
        
        public function get_data_po_print(){
		$sql = "select a.*, b.nama_supplier 
                        from purchase.t_purchase a, mst.t_supplier b
                        where a.kd_suplier_po = b.kd_supplier limit 20
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select a.*, b.nama_supplier 
                        from purchase.t_purchase a, mst.t_supplier b
                        where a.kd_suplier_po = b.kd_supplier limit 20
                        ";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}
