<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_penjualan3_model extends MY_Model {
	
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
	public function get_data_penjualan3_print(){
		$sql = " SELECT a.tgl_so AS tgl, a.no_so AS no_bukti, NULL::unknown AS status, 
                            NULL::unknown AS jam, b.kd_produk, c.nama_produk, c.kd_kategori1, 
                            c.kd_kategori2, c.kd_kategori3, c.kd_kategori4, b.qty, 
                            ( SELECT y.nm_satuan
                                   FROM mst.t_satuan y
                                  WHERE y.kd_satuan::text = c.kd_satuan::text) AS satuan, 
                            b.rp_harga, b.disk_persen_kons1 AS diskon1, b.disk_persen_kons2 AS diskon2, 
                            b.disk_persen_kons3 AS diskon3, b.disk_persen_kons4 AS diskon4, 
                            b.disk_amt_kons1 + b.disk_amt_kons2 + b.disk_amt_kons3 + b.disk_amt_kons4 + b.disk_amt_kons5 AS disk_nilai, 
                            b.rp_total, b.rp_ekstra_diskon, a.rp_ongkos_kirim, a.rp_ongkos_pasang, 
                            a.rp_bank_charge, NULL::unknown AS g_total, c.kd_ukuran, 
                            '-'::character varying(6) AS kd_supplier
                           FROM sales.t_sales_order a, sales.t_sales_order_detail b, mst.t_produk c
                          WHERE a.no_so::text = b.no_so::text AND b.kd_produk::text = c.kd_produk::text
                          ORDER BY a.no_so limit 6 ";
					
		$query = $this->db->query($sql);
		//print_r($query);
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		// print_r($this->db->last_query());
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
