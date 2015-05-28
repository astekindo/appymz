<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_create_po_non_request_model extends MY_Model {

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
	public function search_produk_by_supplier($kd_supplier = '', $sender = "", $waktu_top = '', $kd_peruntukkan = 0, $search = '', $offset, $length){
		$sql_search = '';
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
					s.*, a.nama_produk, a.kd_produk, a.kd_produk_supp, a.kd_produk_lama, a.kd_peruntukkan, b.nm_satuan, a.min_stok, a.max_stok,  coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok,a.min_order, case when a.is_kelipatan_order = 1 then 'YA' else 'TIDAK' end is_kelipatan_order
  				FROM
					mst.t_supp_per_brg s
				JOIN
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND a.kd_kategori1 <> '".KD_KATEGORI1_ASSET."'
					AND is_konsinyasi <> '0'
  				JOIN
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN
					(select kd_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory group by kd_produk) c ON c.kd_produk = a.kd_produk
  				WHERE
					a.aktif = 1
					AND
					s.kd_supplier = '$kd_supplier'
					AND
					s.waktu_top = '$waktu_top'
					".$sql_search."
				GROUP BY
					s.kd_supplier,
					s.waktu_top,
					s.kd_produk,
					s.disk_persen_supp1,
					s.disk_persen_supp2,
					s.disk_persen_supp3,
					s.disk_persen_supp4,
					s.disk_amt_supp1,
					s.disk_amt_supp2,
					s.disk_amt_supp3,
					s.disk_amt_supp4,
					s.hrg_supplier,
					s.dpp,
					s.created_by,
					s.created_date,
					s.updated_by,
					s.updated_date,
					s.aktif,
					s.disk_amt_supp5,
					s.konsinyasi,
					s.hrg_supplier_dist,
					s.net_hrg_supplier_dist,
					s.net_hrg_supplier_sup,
					s.disk_persen_dist1,
					s.disk_persen_dist2,
					s.disk_persen_dist3,
					s.disk_persen_dist4,
					s.disk_amt_dist1,
					s.disk_amt_dist2,
					s.disk_amt_dist3,
					s.disk_amt_dist4,
					s.disk_amt_dist5,
					s.net_hrg_supplier_sup_inc,
					s.net_hrg_supplier_dist_inc,
					s.no_bukti,
					s.keterangan, a.nama_produk, a.kd_produk, a.kd_produk_supp, a.kd_produk_lama, a.kd_peruntukkan, b.nm_satuan, a.min_stok, a.max_stok, c.qty_oh, a.min_order, a.is_kelipatan_order
				ORDER BY
					a.nama_produk ASC
				limit ".$length." offset ".$offset;

		$query = $this->db->query($sql);
		//print_r($this->db->last_query($query));
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as TOTAL
				FROM
					mst.t_supp_per_brg s
				JOIN
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND a.kd_kategori1 <> '".KD_KATEGORI1_ASSET."'
					AND is_konsinyasi <> '0'
  				JOIN
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
  				WHERE
					a.aktif = 1
					AND
					s.kd_supplier = '$kd_supplier'
					AND
					s.waktu_top = '$waktu_top'
					".$sql_search."";

        $query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$rows[] = $total;

		// print_r($this->db->last_query());

		return $rows;
	}
	public function get_data_print($no_po = ''){
		$sql = "select 'PURCHASE ORDER NON REQUEST (KONSINYASI)' title, a.no_po, a.tanggal_po, a.order_by_po, a.top,b.nama_supplier, a.alamat_kirim_po, a.remark,
					a.rp_jumlah_po, a.rp_diskon_po, a.ppn_percent_po, a.rp_ppn_po, a.rp_total_po, b.nama_supplier, b.fax, b.pic
					from purchase.t_purchase a, mst.t_supplier b
					where a.no_po = '$no_po'
					and a.kd_suplier_po = b.kd_supplier";

		$query = $this->db->query($sql);

		if($query->num_rows() == 0) return FALSE;

		$data['header'] = $query->row();

		$this->db->flush_cache();
		$sql_detail = "select a.no_po, a.kd_produk, b.nama_produk, a.qty_po, c.nm_satuan, a.net_price_po, a.rp_total_po
						from purchase.t_purchase_detail a, mst.t_produk b, mst.t_satuan c
						where a.no_po = '$no_po'
						and a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan";

		$query_detail = $this->db->query($sql_detail);
		$data['detail'] = $query_detail->result();

		return $data;
	}

	public function get_term_of_payment_by_supplier($kd_supplier = ''){
		$sql = "select distinct c.waktu_top
				from mst.t_supp_per_brg c
				where c.kd_supplier = '" . $kd_supplier . "'";

		$query = $this->db->query($sql);

		// print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
}
