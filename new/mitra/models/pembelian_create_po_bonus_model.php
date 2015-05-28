<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_po_bonus_model extends MY_Model {

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
	public function search_produk_by_supplier($kd_supplier = '', $waktu_top = '', $search = ''){
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
					s.*, a.nama_produk, a.kd_produk, b.nm_satuan
  				FROM
					mst.t_supp_per_brg s
				JOIN
					mst.t_produk a ON s.kd_produk = a.kd_produk
  				JOIN
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				WHERE
					a.aktif = 1
					AND
					s.konsinyasi = '0'
					AND
					s.kd_supplier = '$kd_supplier'
					AND
					s.waktu_top = $waktu_top
					".$sql_search."
				ORDER BY
					a.nama_produk ASC";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		return $rows;
	}

	public function get_data_print($no_po = ''){
		$sql = "select 'PURCHASE ORDER BONUS' title, a.no_po, a.tanggal_po, a.order_by_po, a.top,b.nama_supplier, a.alamat_kirim_po, a.remark,
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
       public function search_po_induk($kd_supplier ="",$search = "", $offset, $length){
		if(!empty($search)){
			$this->db->where("(upper(no_po) LIKE '%" . strtoupper($search) . "%')", NULL);
        }
		$this->db->where("(no_po LIKE 'PN%' OR no_po LIKE 'PO%')", NULL);
        $this->db->where("kd_suplier_po",$kd_supplier);
        $this->db->order_by("no_po");
		$query = $this->db->get("purchase.t_purchase", $length, $offset);
		//print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
                if($search != ""){
			$this->db->where("((lower(no_po) LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("no_po LIKE 'PN%' OR no_po LIKE 'PO%'", NULL);
                $this->db->where("kd_suplier_po",$kd_supplier);
		$query = $this->db->get("purchase.t_purchase");

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
}
