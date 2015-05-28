<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_request_model extends MY_Model {

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

	public function validate_pr_on_po($kd_produk = '',$kd_peruntukan = ''){
		$sql = "SELECT b.no_po,qty_po
					FROM purchase.t_purchase_detail a
					JOIN purchase.t_purchase b
					ON a.no_po = b.no_po
					WHERE kd_produk = '$kd_produk'
					AND b.approval_po = '0'
                    AND b.kd_peruntukan = '$kd_peruntukan'";
		$query = $this->db->query($sql);
		// print_r($this->db->last_query());

		$result['po'] = 0;
		if($query->num_rows > 0)
			$result['po'] = $query->row();

		$this->db->flush_cache();
		$field = '';
		if($kd_peruntukan  == '0'){
			$field = 'rp_jual_supermarket as harga_jual ';
			$where = " AND kd_peruntukkan = '0'";
		}else if($kd_peruntukan == '1'){
			$field = 'rp_jual_distribusi as harga_jual ';
			$where = " AND kd_peruntukkan = '1'";
		}else{
			$field = 'rp_jual_supermarket as harga_jual ';
			$where = ' AND kd_peruntukkan is NULL';
		}
		$sql = "SELECT ".$field."
				FROM mst.t_produk
				WHERE kd_produk = '".$kd_produk."'
				".$where."";

		$query = $this->db->query($sql);

		$result['peruntukan'] = 0;
		if($query->num_rows > 0)
			$result['peruntukan'] = $query->row();
		// print_r($this->db->last_query());


		return $result;
	}

	public function validate_pr_by_kd_produk($kd_produk = '',$kd_peruntukan = ''){
		$sql = "select sum(qty) from purchase.t_purchase_request x, purchase.t_dtl_purchase_request a
				left join purchase.t_purchase_detail b on a.no_ro = b.no_ro
				where a.kd_produk = '$kd_produk'
				and x.no_ro = a.no_ro
				and ( x.status not in ('2', '9'))
				and b.no_po is null
                                and x.kd_peruntukan = '$kd_peruntukan'";
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());exit;
		$result['pr'] = 0;
		if($query->num_rows > 0)
			$result['pr'] = $query->row();

		$this->db->flush_cache();
//		$field = '';
//		if($kd_peruntukan  == '0'){
//			$field = 'rp_jual_supermarket as harga_jual ';
//			$where = " AND kd_peruntukkan = '0'";
//		}else if($kd_peruntukan == '1'){
//			$field = 'rp_jual_distribusi as harga_jual ';
//			$where = " AND kd_peruntukkan = '1'";
//		}else{
//			$field = 'rp_jual_supermarket as harga_jual ';
//			$where = ' ';
//		}
//		$sql = "SELECT ".$field."
//				FROM mst.t_produk
//				WHERE kd_produk = '".$kd_produk."'
//				".$where."";
//
//		$query = $this->db->query($sql);
//
		// print_r($this->db->last_query());exit;
//
//		$result['peruntukan'] = 0;
//		if($query->num_rows > 0)
//			$result['peruntukan'] = $query->row();



		return $result;
	}




	public function search_produk_by_supplier($kd_supplier = '', $sender = "", $search = "", $offset, $length){
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
					s.waktu_top, a.nama_produk, a.kd_produk, a.kd_produk_supp, a.kd_produk_lama, a.min_stok, a.max_stok, a.min_order, case when a.is_kelipatan_order = 1 then 'YA' else 'TIDAK' end is_kelipatan_order, b.nm_satuan, coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok
  				FROM
					mst.t_supp_per_brg s
				JOIN
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND a.kd_kategori1 <> '".KD_KATEGORI1_ASSET."'
					AND is_konsinyasi = '0' ".$sql_search."
  				JOIN
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
				WHERE
					a.aktif=1 and s.aktif is true
					AND
					s.kd_supplier = '$kd_supplier'
					AND a.aktif_purchase = 1
				GROUP BY
					s.waktu_top,a.nama_produk, a.kd_produk, a.kd_produk_supp, a.kd_produk_lama, a.min_stok, a.max_stok, a.min_order,is_kelipatan_order, b.nm_satuan
				ORDER BY
					a.nama_produk ASC";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			if($sender != ''){
				$rows = $query->row();
			}else{
				$rows = $query->result();
			}
		}
		 //print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
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
