<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_penjualan_model extends MY_Model {

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
	public function update_rows_produk($kd_produk = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_rows_produk_history($kd_produk = '', $koreksi_ke = ''){
		$sql = "INSERT INTO mst.t_produk_history
				SELECT * FROM mst.t_produk
				WHERE kd_produk = '".$kd_produk."'
				AND koreksi_ke = '".$koreksi_ke."'";

		return $this->db->query($sql);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_rows_diskon($datau = NULL){
		return $this->db->insert('mst.t_diskon_sales',$datau);
		// print_r($this->db->last_query());

	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_rows_diskon( $kd_produk = '', $kd_diskon_sales = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('kd_diskon_sales',$kd_diskon_sales);
		return $this->db->update('mst.t_diskon_sales',$datau);
		// print_r($this->db->last_query());

	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_rows_diskon_history($kd_produk = '', $kd_diskon_sales = '', $koreksi_ke = ''){
		$sql = "INSERT INTO mst.t_diskon_sales_history
				SELECT * FROM mst.t_diskon_sales
				WHERE kd_produk = '".$kd_produk."'
				AND kd_diskon_sales = '".$kd_diskon_sales."'
				AND koreksi_ke = '".$koreksi_ke."'";

		return $this->db->query($sql);
	}

	public function search_produk_by_kategori($kd_supplier = "", $no_bukti = "", $konsinyasi = "", $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "",$kd_ukuran = "",$kd_satuan = "", $list = "", $search = '', $offset, $length){
		$where = '';

		//if ($kd_supplier != ''){
			$where .= " AND h.kd_supplier = '$kd_supplier'";
		//}
		if ($no_bukti != ''){
			$select = " i.*, ";
			$from = "JOIN mst.t_diskon_sales_temp i ON b.kd_produk = i.kd_produk  ";
			$where .= " AND i.no_bukti = '$no_bukti'";
		}else{
			$select = " a.*,b.rp_ongkos_kirim, b.pct_margin, b.rp_het_harga_beli,";
			$from = '';
		}
		if ($search != ''){
			$where .= " AND (
							(lower(nama_produk) LIKE '%" . strtolower($search) . "%')
							OR
							(lower(a.kd_produk) LIKE '%" . strtolower($search) . "%')
							OR
							(lower(b.kd_produk_lama) LIKE '%" . strtolower($search) . "%')
						)";
		}
		if ($list != ''){
			$where .= " AND (
							a.kd_produk in(".$list.")
							OR
							b.kd_produk_lama in(".$list.")
						)";
		}
		if ($kd_kategori1 != ''){
			$where .= "AND b.kd_kategori1 = '$kd_kategori1' ";
		}

		if ($kd_kategori2 != ''){
			$where .= "AND b.kd_kategori2 = '$kd_kategori2' ";
		}

		if ($kd_kategori3 != ''){
			$where .= "AND b.kd_kategori3 = '$kd_kategori3' ";
		}

		if ($kd_kategori4 != ''){
			$where .= "AND b.kd_kategori4 = '$kd_kategori4' ";
		}
		if ($konsinyasi != ''){
			$where .= "AND b.is_konsinyasi = '$konsinyasi' ";
		}
                if ($kd_ukuran != ''){
			$where .= "AND b.kd_ukuran = '$kd_ukuran' ";
		}
                if ($kd_satuan != ''){
			$where .= "AND b.kd_satuan = '$kd_satuan' ";
		}

		$sql = "SELECT b.kd_produk, b.is_konsinyasi,b.kd_produk_lama,b.nama_produk,b.rp_cogs p_rp_cogs, b.rp_het_cogs p_rp_het_cogs, " . $select . " h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.koreksi_ke koreksi_diskon, b.koreksi_ke koreksi_produk, nm_satuan,
				(SELECT nama_supplier FROM mst.t_supplier z WHERE z.kd_supplier = h.kd_supplier) nama_supplier
					FROM mst.t_produk b
					 " . $from . "
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
					WHERE h.hrg_supplier > 0 ".$where." and a.diskon_aktif = 1 and a.tgl_start_diskon <= current_date and coalesce(a.tgl_end_diskon, current_date) >= current_date
						ORDER BY b.nama_produk";
					// limit ".$length." offset ".$offset;
		$query = $this->db->query($sql);

		// print_r($this->db->last_query());echo "{success: false, errMsg: 'a'}";exit;

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;

		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_produk b
					 " . $from . "
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
					WHERE h.hrg_supplier > 0 ".$where." and a.diskon_aktif = 1 and a.tgl_start_diskon <= current_date and coalesce(a.tgl_end_diskon, current_date) >= current_date";

        $query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$result['total'] = $total;
        return $result;
	}

	public function search_produk_history($no_bukti = '',$kd_produk = ''){
		$where = '';
		if($no_bukti != ''){
			$where .= " AND a.no_bukti = '$no_bukti' ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
		}
		$sql = <<<EOT
SELECT
  a.kd_produk,
  a.kd_diskon_sales,
  a.tanggal,
  a.disk_persen_kons1,
  a.disk_persen_kons2,
  a.disk_persen_kons3,
  a.disk_persen_kons4,
  a.disk_amt_kons1,
  a.disk_amt_kons2,
  a.disk_amt_kons3,
  a.disk_amt_kons4,
  a.disk_amt_kons5,
  a.disk_persen_member1,
  a.disk_persen_member2,
  a.disk_persen_member3,
  a.disk_persen_member4,
  a.disk_amt_member1,
  a.disk_amt_member2,
  a.disk_amt_member3,
  a.disk_amt_member4,
  a.disk_amt_member5,
  a.created_by,
  a.created_date,
  a.updated_by,
  a.updated_date,
  a.koreksi_ke,
  a.is_bonus,
  a.qty_beli_bonus,
  a.kd_produk_bonus,
  a.qty_bonus,
  a.is_bonus_kelipatan,
  a.qty_beli_member,
  a.kd_produk_member,
  a.qty_member,
  a.is_member_kelipatan,
  a.kd_kategori1_bonus,
  a.kd_kategori2_bonus,
  a.kd_kategori3_bonus,
  a.kd_kategori4_bonus,
  a.kd_kategori1_member,
  a.kd_kategori2_member,
  a.kd_kategori3_member,
  a.kd_kategori4_member,
  a.keterangan,
  a.no_bukti,
  a.tgl_approve,
  a.approve_by,
  a.hrg_beli_sup,
  a.rp_ongkos_kirim,
  a.pct_margin,
  a.rp_margin,
  a.rp_jual_supermarket,
  a.rp_jual_distribusi,
  a.rp_het_harga_beli,
  a.rp_cogs,
  a.rp_het_cogs,
  a.status_approve,
  a.tgl_start_diskon,
  a.tgl_end_diskon,
  a.tgl_start_bonus,
  a.tgl_end_bonus,
  b.kd_kategori1,
  b.kd_kategori2,
  b.kd_kategori3,
  b.kd_kategori4,
  b.thn_reg,
  b.no_urut_produk,
  b.nama_produk,
  b.kd_produk,
  b.kd_produk_lama,
  b.kd_produk_supp,
  b.kd_peruntukkan,
  b.hrg_beli_sup,
  b.hrg_beli_dist,
  b.created_by,
  b.created_date,
  b.updated_by,
  b.updated_date,
  b.min_stok,
  b.max_stok,
  b.min_order,
  b.kd_satuan,
  b.is_konsinyasi,
  b.rp_margin,
  b.rp_ongkos_kirim,
  b.pct_margin,
  b.rp_het_harga_beli,
  b.tanggal,
  b.koreksi_ke,
  b.aktif_purchase,
  b.pct_alert,
  b.rp_het_cogs,
  b.rp_cogs,
  b.aktif,
  b.pct_margin_dist,
  b.rp_margin_dist,
  b.rp_ongkos_kirim_dist,
  b.rp_cogs_dist,
  b.rp_het_cogs_dist,
  b.rp_het_harga_beli_dist,
  b.is_harga_lepas,
  b.is_barang_paket,
  b.kd_ukuran,
  b.tgl_awal_promo,
  b.tgl_akhir_promo,
  b.ket_perubahan,
  b.no_urut,
  b.is_kelipatan_order,
  b.pkp_update,
  b.tonality,
  d.nama_supplier, h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.tanggal, a.koreksi_ke koreksi_diskon, nm_satuan
					FROM mst.t_produk b
					JOIN mst.t_supp_per_brg h ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales_history a ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan
					JOIN mst.t_supplier d ON d.kd_supplier = h.kd_supplier
					WHERE 1=1 $where ORDER BY a.tanggal DESC
EOT;
		$query = $this->db->query($sql);
		 //print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
        return $rows;
	}

	public function get_data_print($no_bukti = '', $kd_produk = ''){
		$where = '';
		$title = 'HISTORY HARGA PENJUALAN';
		if($no_bukti != '' && $no_bukti != '0'){
			$where .= " AND a.no_bukti = '$no_bukti' ";
			$title .= " - No Bukti : $no_bukti ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
			$title .= " - Kd Produk : $kd_produk ";
		}
		$sql_detail = "SELECT '$title' title, a.*,b.*,
						CASE WHEN a.status_approve=1 THEN 'Approve' ELSE CASE WHEN a.status_approve=9 THEN 'REJECT' END END status_approve,
						d.nama_supplier, h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.tanggal, a.koreksi_ke koreksi_diskon,
						b.koreksi_ke koreksi_produk, nm_satuan
						FROM mst.t_produk b
						JOIN mst.t_supp_per_brg h
							ON h.kd_produk = b.kd_produk
						LEFT JOIN mst.t_diskon_sales_history a
							ON b.kd_produk = a.kd_produk
						JOIN mst.t_satuan c
							ON c.kd_satuan = b.kd_satuan
						JOIN mst.t_supplier d
							ON d.kd_supplier = h.kd_supplier
						WHERE 1=1
						".$where." ORDER BY a.tanggal DESC";

		$query_detail = $this->db->query($sql_detail);
		//print_r($this->db->last_query());
		$data['detail'] = $query_detail->result();

		return $data;
	}
	public function search_no_bukti($search = "", $offset, $length){
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->select("b.nama_supplier");
			$this->db->select("a.no_bukti");
			$this->db->distinct("a.no_bukti");
			if($search != ""){
				$this->db->where("a.no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
			$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');
			$this->db->order_by("a.no_bukti");
			$query = $this->db->get("mst.t_diskon_sales_history a", $length, $offset);

			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}

			$this->db->flush_cache();
			$this->db->select("count(DISTINCT no_bukti) AS total");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$query = $this->db->get("mst.t_diskon_sales_history");

			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}

			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
	}


	public function get_no_bukti_filter($search = "", $offset, $length){

		$this->db->select("a.no_bukti AS no_bukti_filter",FALSE);
		$this->db->select("a.keterangan");
		$this->db->select("a.created_by");
		$this->db->select("b.nama_supplier");
		$this->db->distinct("a.no_bukti");
		if($search != ""){
			$this->db->where("a.no_bukti LIKE '%" . $search . "%'", NULL);
		}
		$this->db->where('a.status','0');
		$this->db->order_by("a.no_bukti");
		$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
		$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');

		$query = $this->db->get("mst.t_diskon_sales_temp a", $length, $offset);
		//print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$this->db->flush_cache();
		$this->db->select("count(DISTINCT no_bukti) AS total");
		if($search != ""){
			$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
		}
		$this->db->where("status", '0');
		$query = $this->db->get("mst.t_diskon_sales_temp");

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

		return $results;
	}

	public function select_temp($kd_produk = "",$status = ""){
		$where = array(
					'kd_produk' => $kd_produk,
					'status' => $status,
				);
		$this->db->where($where);

		$query = $this->db->get("mst.t_diskon_sales_temp");
		//$result = FALSE;

		//if($query->num_rows > 0){
		//	$result = TRUE;
		//}

		//return $result;
                return $query->result();
	}
        public function select_data_jual_sama($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){

		$sql = "select * from mst.t_diskon_sales
                          where kd_produk ='$kd_produk'
                          and tgl_start_diskon = '$tgl_start_diskon' and tgl_end_diskon = '$tgl_end_diskon'
                          ";

                $query = $this->db->query($sql);
                //print_r($this->db->last_query());
                return $query->result();
	}
         public function select_data_jual($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){

		$sql = "select * from mst.t_diskon_sales
                          where kd_produk ='$kd_produk'
                          and tgl_start_diskon <= '$tgl_start_diskon' and tgl_end_diskon >= '$tgl_start_diskon'
                          ";

                $query = $this->db->query($sql);
                return $query->result();
	}
        public function select_data_jual_end($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){

		$sql = "select * from mst.t_diskon_sales
                          where kd_produk ='$kd_produk'
                          and tgl_start_diskon <= '$tgl_end_diskon' and tgl_end_diskon >= '$tgl_end_diskon'
                          ";

                $query = $this->db->query($sql);
                return $query->result();
	}
	public function get_temp($kd_produk = "",$status = ""){
		$where = array(
					'kd_produk' => $kd_produk,
					'status' => $status,
				);
		$this->db->where($where);

		$query = $this->db->get("mst.t_diskon_sales_temp");

		if($query->num_rows > 0){
			$result = $query->row;
		}

		return $result;
	}

	public function update_temp($kd_produk = '', $no_bukti = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('no_bukti',$no_bukti);
		return $this->db->update('mst.t_diskon_sales_temp',$datau);
		// print_r($this->db->last_query());
	}

	public function insert_temp($data = NULL){
		return $this->db->insert('mst.t_diskon_sales_temp', $data);
	}
         public function select_data_temp($kd_produk = "", $tgl_start_diskon =""){

                $sql = "select * from mst.t_diskon_sales_temp
                        where kd_produk ='$kd_produk'
                        AND tgl_start_diskon ='$tgl_start_diskon'";
                $query = $this->db->query($sql);
                return $query->num_rows();
	}
        public function update_temp_by_date($kd_produk = '', $tgl_start_diskon = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('tgl_start_diskon',$tgl_start_diskon);
               	return $this->db->update('mst.t_diskon_sales_temp',$datau);
        }
}
