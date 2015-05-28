<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simulasi_harga_jual_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insert_row($table = '', $data = NULL){
		return $this->db->insert($table, $data);
	}

	public function search_produk($kd_supplier = '', $search = "", $offset, $length){
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
		$sql = "SELECT b.kd_produk, b.is_konsinyasi,b.kd_produk_lama,b.nama_produk,b.rp_cogs p_rp_cogs, b.rp_het_cogs p_rp_het_cogs, a.*,b.rp_ongkos_kirim, b.pct_margin, b.rp_het_harga_beli, h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.koreksi_ke koreksi_diskon, b.koreksi_ke koreksi_produk, nm_satuan,
				(SELECT nama_supplier FROM mst.t_supplier z WHERE z.kd_supplier = h.kd_supplier) nama_supplier
					FROM mst.t_produk b
					JOIN mst.t_supp_per_brg h
						ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales a
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					JOIN mst.t_kategori1 d
						ON b.kd_kategori1 = d.kd_kategori1
					JOIN mst.t_kategori2 e
						ON b.kd_kategori2 = e.kd_kategori2
						AND b.kd_kategori1 = e.kd_kategori1
					JOIN mst.t_kategori3 f
						ON b.kd_kategori3 = f.kd_kategori3
						AND b.kd_kategori2 = f.kd_kategori2
						AND b.kd_kategori1 = f.kd_kategori1
					JOIN mst.t_kategori4 g
						ON b.kd_kategori4 = g.kd_kategori4
						AND b.kd_kategori3 = g.kd_kategori3
						AND b.kd_kategori2 = g.kd_kategori2
						AND b.kd_kategori1 = g.kd_kategori1
                                        LEFT JOIN mst.t_ukuran u
						ON b.kd_ukuran = u.kd_ukuran
					WHERE h.kd_supplier = '$kd_supplier'
                                        ORDER BY b.nama_produk";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;

		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_produk b
					JOIN mst.t_supp_per_brg h
						ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales a
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					JOIN mst.t_kategori1 d
						ON b.kd_kategori1 = d.kd_kategori1
					JOIN mst.t_kategori2 e
						ON b.kd_kategori2 = e.kd_kategori2
						AND b.kd_kategori1 = e.kd_kategori1
					JOIN mst.t_kategori3 f
						ON b.kd_kategori3 = f.kd_kategori3
						AND b.kd_kategori2 = f.kd_kategori2
						AND b.kd_kategori1 = f.kd_kategori1
					JOIN mst.t_kategori4 g
						ON b.kd_kategori4 = g.kd_kategori4
						AND b.kd_kategori3 = g.kd_kategori3
						AND b.kd_kategori2 = g.kd_kategori2
						AND b.kd_kategori1 = g.kd_kategori1
                                        LEFT JOIN mst.t_ukuran u
						ON b.kd_ukuran = u.kd_ukuran
					WHERE h.kd_supplier = '$kd_supplier'";

        $query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$result['total'] = $total;
        return $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_print($no_ro = ''){
		$this->db->select('a.*,b.nama_supplier,b.alamat,b.pic');
		$this->db->where("a.no_ro", $no_ro);
		$this->db->join("mst.t_supplier b", "b.kd_supplier = a.kd_supplier");
		$query = $this->db->get("purchase.t_purchase_request a");

		if($query->num_rows() == 0) return FALSE;

		$data['header'] = $query->row();

		$this->db->flush_cache();
		$this->db->select('a.*,b.nama_produk, b.kd_produk_supp,c.nm_satuan');
		$this->db->where("a.no_ro", $no_ro);
		$this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
		$this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
		$query_detail = $this->db->get("purchase.t_dtl_purchase_request a");

		$data['detail'] = $query_detail->result();

		return $data;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function setCetakKe($no_ro = ''){
		$this->db->query('UPDATE purchase.t_purchase_request set cetak_ke = cetak_ke + 1 where no_ro = ?', array($no_ro));
	}
}
