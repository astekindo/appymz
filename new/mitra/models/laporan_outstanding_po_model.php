<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_outstanding_po_model extends MY_Model {
	
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
	public function get_data_print(){
		$sql = "select a.tanggal, a.created_date, a.no_do, a.no_bukti_supplier, a.kd_supplier, c.nama_supplier, b.no_po, b.kd_produk,
					d.nama_produk, b.qty_beli, (b.qty_beli - b.qty_terima) qty_sisa, b.qty_terima, e.price_supp_po, 
					case when disk_persen_supp1_po = 0 then disk_amt_supp1_po || ' (Rp)' else disk_persen_supp1_po || ' (%)' end disk1,
					case when disk_persen_supp2_po = 0 then disk_amt_supp2_po || ' (Rp)' else disk_persen_supp2_po || ' (%)' end disk2,
					case when disk_persen_supp3_po = 0 then disk_amt_supp3_po || ' (Rp)' else disk_persen_supp3_po || ' (%)' end disk3,
					case when disk_persen_supp4_po = 0 then disk_amt_supp4_po || ' (Rp)' else disk_persen_supp4_po || ' (%)' end disk4,
					disk_amt_supp5_po || ' (Rp)' disk5, 
					e.net_price_po, e.dpp_po, e.dpp_po * 0.1 rp_ppn, e.rp_total_po , (e.dpp_po * 0.1 ) * b.qty_terima rp_total_ppn,
					e.rp_total_po + ((e.dpp_po * 0.1 ) * b.qty_terima) rp_total, b.berat_ekspedisi || ' ' || g.nm_satuan berat_ekspedisi,
					b.kd_ekspedisi, h.nama_ekspedisi
					from purchase.t_receive_order a, purchase.t_dtl_receive_order b, 
					mst.t_supplier c, mst.t_produk d, purchase.t_purchase_detail e, purchase.t_purchase f, 
					mst.t_satuan g, mst.t_ekpedisi h
					where a.no_do = b.no_do
					and a.kd_supplier = c.kd_supplier
					and b.kd_produk = d.kd_produk
					and b.no_po = e.no_po
					and b.kd_produk = e.kd_produk
					and b.no_po = f.no_po
					and b.kd_satuan_ekspedisi = g.kd_satuan
					and b.kd_ekspedisi = h.kd_ekspedisi
					order by a.no_do";
					
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->result();
		// print_r($this->db->last_query());
		return $data;
	}
        public function get_data_outpo_print(){
		$sql = "select a.*, b.nama_supplier ,b.kd_supplier
                        from purchase.t_purchase a, mst.t_supplier b
                        where a.kd_suplier_po = b.kd_supplier limit 20
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select a.*, b.nama_supplier,b.kd_supplier 
                        from purchase.t_purchase a, mst.t_supplier b
                        where a.kd_suplier_po = b.kd_supplier limit 20
                        ";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}
