<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_pembelian_model extends MY_Model {

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
	public function update_row($kd_supplier = '', $kd_produk = '', $waktu_top = '', $datau = NULL){
		$this->db->where('kd_supplier',$kd_supplier);
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('waktu_top',$waktu_top);
		return $this->db->update('mst.t_supp_per_brg',$datau);
		// print_r($this->db->last_query());

	}

	public function update_net_produk($kd_produk = '', $rp_het_harga_beli = '', $hrg_beli_sup = '', $hrg_beli_dist = ''){
		$datau = array(
				   'rp_het_harga_beli' => $rp_het_harga_beli,
				   'hrg_beli_sup' => $hrg_beli_sup,
				   'hrg_beli_dist' => $hrg_beli_dist
				);

		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
		// print_r($this->db->last_query());

	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_history($no_hp = '',$kd_supplier = '', $kd_produk = '', $waktu_top = ''){
		$sql = "INSERT INTO mst.t_supp_per_brg_history (no_bukti, kd_supplier, waktu_top, kd_produk, disk_persen_supp1,
					   disk_persen_supp2, disk_persen_supp3, disk_persen_supp4, disk_amt_supp1,
					   disk_amt_supp2, disk_amt_supp3, disk_amt_supp4, hrg_supplier,
					   dpp, created_by, created_date, updated_by, updated_date, aktif,
					   disk_amt_supp5, konsinyasi, hrg_supplier_dist, net_hrg_supplier_dist,
					   net_hrg_supplier_sup, disk_persen_dist1, disk_persen_dist2, disk_persen_dist3,
					   disk_persen_dist4, disk_amt_dist1, disk_amt_dist2, disk_amt_dist3,
					   disk_amt_dist4, disk_amt_dist5, net_hrg_supplier_sup_inc, net_hrg_supplier_dist_inc,
					   keterangan)
				SELECT no_bukti, kd_supplier, waktu_top, kd_produk, disk_persen_supp1, disk_persen_supp2,
					   disk_persen_supp3, disk_persen_supp4, disk_amt_supp1, disk_amt_supp2,
					   disk_amt_supp3, disk_amt_supp4, hrg_supplier, dpp, created_by,
					   created_date, updated_by, updated_date, aktif, disk_amt_supp5,
					   konsinyasi, hrg_supplier_dist, net_hrg_supplier_dist, net_hrg_supplier_sup,
					   disk_persen_dist1, disk_persen_dist2, disk_persen_dist3, disk_persen_dist4,
					   disk_amt_dist1, disk_amt_dist2, disk_amt_dist3, disk_amt_dist4,
					   disk_amt_dist5, net_hrg_supplier_sup_inc, net_hrg_supplier_dist_inc,
					   keterangan
				FROM mst.t_supp_per_brg
				WHERE kd_supplier = '".$kd_supplier."'
				AND kd_produk = '".$kd_produk."'
				AND waktu_top = '".$waktu_top."'";

		return $this->db->query($sql);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_supplier($kd_supplier = ""){
		$this->db->select("no_po");
		$this->db->where("close_po", 0);
		$this->db->where("konsinyasi", "0");
		$this->db->where("is_bonus", "1");
		$this->db->where("kd_suplier_po", $kd_supplier);
		$this->db->order_by("no_po", 'asc');
		$query = $this->db->get("purchase.t_purchase");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_hp_detail($no_po = '', $search = ''){
		if($search != ''){
			$this->db->where("(lower(nama_produk) LIKE '%" . strtolower($search) . "%')", NULL);
		}
		$this->db->select("a.*,a.qty_po AS qty_do,b.nama_produk,c.nm_satuan,e.kd_supplier,e.nama_supplier");
		$this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
		$this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
		$this->db->join("purchase.t_purchase d", "d.no_po = a.no_po");
		$this->db->join("mst.t_supplier e", "e.kd_supplier = d.kd_suplier_po");
		$this->db->where("a.no_po", $no_po);
		$query = $this->db->get("purchase.t_purchase_detail a");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_nobukti($search = ""){
		$sql_search = "";
		if($search != ""){
			$sql_search =  " WHERE(lower(no_bukti) = '" . strtolower($search) . "') ";
		}
		$sql1 = "SELECT DISTINCT no_bukti,kd_supplier FROM mst.t_supp_per_brg_history
					".$sql_search;

        $query = $this->db->query($sql1);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_nobukti_all($no_bukti = NULL){
		$sql1 = "SELECT case when a.disk_persen_supp1 > 0 then a.disk_persen_supp1 || ' %' else 'Rp. ' ||a.disk_amt_supp1 end disk_supp1,
						case when a.disk_persen_supp2 > 0 then a.disk_persen_supp2 || ' %' else 'Rp. ' ||a.disk_amt_supp2 end disk_supp2,
						case when a.disk_persen_supp3 > 0 then a.disk_persen_supp3 || ' %' else 'Rp. ' ||a.disk_amt_supp3 end disk_supp3,
						case when a.disk_persen_supp4 > 0 then a.disk_persen_supp4 || ' %' else 'Rp. ' ||a.disk_amt_supp4 end disk_supp4,
						case when a.disk_persen_dist1 > 0 then a.disk_persen_dist1 || ' %' else 'Rp. ' ||a.disk_persen_dist1 end disk_dist1,
						case when a.disk_persen_dist2 > 0 then a.disk_persen_dist2 || ' %' else 'Rp. ' ||a.disk_persen_dist2 end disk_dist2,
						case when a.disk_persen_dist3 > 0 then a.disk_persen_dist3 || ' %' else 'Rp. ' ||a.disk_persen_dist3 end disk_dist3,
						case when a.disk_persen_dist4 > 0 then a.disk_persen_dist4 || ' %' else 'Rp. ' ||a.disk_persen_dist4 end disk_dist4,
								a.*,b.nama_produk,c.nm_satuan,d.kd_supplier,d.nama_supplier,a.net_hrg_supplier_dist, a.net_hrg_supplier_sup
								FROM mst.t_supp_per_brg_history a,mst.t_produk b,mst.t_satuan c,mst.t_supplier d
								where
								a.no_bukti = '$no_bukti'
								and a.kd_produk = b.kd_produk
								and c.kd_satuan = b.kd_satuan
								and a.kd_supplier = d.kd_supplier";

        $query = $this->db->query($sql1);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function search_produk_by_supplier($kd_supplier = "", $no_bukti = "", $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $kd_ukuran ="",$kd_satuan ="", $list = "", $search = '', $offset, $length){
		$where = " WHERE is_konsinyasi = '0'";

//		if ($kd_supplier != ''){
			$where .= " AND a.kd_supplier = '$kd_supplier'";
                        $where .= "  AND b.is_konsinyasi = '0'";
//		}
		if ($no_bukti != ''){
			$from = "mst.t_supp_per_brg_temp";
			$where .= " AND a.no_bukti = '$no_bukti'";
		}else{
			$from = "mst.t_supp_per_brg";
                        $where .= " AND a.aktif = TRUE";
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
			$where .= " AND b.kd_kategori1 = '$kd_kategori1' ";
		}

		if ($kd_kategori2 != ''){
			$where .= " AND b.kd_kategori2 = '$kd_kategori2' ";
		}

		if ($kd_kategori3 != ''){
			$where .= " AND b.kd_kategori3 = '$kd_kategori3' ";
		}

		if ($kd_kategori4 != ''){
			$where .= " AND b.kd_kategori4 = '$kd_kategori4' ";
		}

                if ($kd_ukuran != ''){
			$where .= " AND b.kd_ukuran = '$kd_ukuran' ";
		}
		if ($kd_satuan != ''){
			$where .= " AND b.kd_satuan = '$kd_satuan' ";
		}
		$sql = "SELECT a.*, b.is_konsinyasi,b.kd_produk_lama, b.rp_het_harga_beli, b.pct_margin, b.rp_ongkos_kirim, nama_produk, nm_satuan ,
					(SELECT nama_supplier FROM mst.t_supplier h WHERE h.kd_supplier = a.kd_supplier) as nama_supplier
					FROM " . $from ." a
					JOIN mst.t_produk b
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
                                       LEFT JOIN mst.t_ukuran i
						ON b.kd_ukuran = i.kd_ukuran
						  ".$where." and a.aktif_diskon = 1 and tgl_start_diskon <= current_date and coalesce(tgl_end_diskon, current_date) >= current_date
						ORDER BY nama_produk";
						// limit ".$length." offset ".$offset;
		$query = $this->db->query($sql);

		 //print_r($this->db->last_query());

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;

		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_supp_per_brg a
					JOIN mst.t_produk b
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
                                       LEFT JOIN mst.t_ukuran i
						ON b.kd_ukuran = i.kd_ukuran
						  ".$where ." and a.aktif_diskon = 1 and tgl_start_diskon <= current_date and coalesce(tgl_end_diskon, current_date) >= current_date ";

        $query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$result['total'] = $total;
        return $result;
	}

	public function search_produk_history($no_bukti = '', $kd_produk = ''){
		$where = " AND is_konsinyasi = '0'";
		if($no_bukti != ''){
			$where .= " AND a.no_bukti = '$no_bukti' ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
		}
		$sql = "SELECT COALESCE(a.updated_date, a.created_date, a.updated_date) as tanggal, a.*,b.pct_margin, b.rp_ongkos_kirim, b.rp_het_harga_beli,b.rp_het_harga_beli_dist,nama_produk, nm_satuan
					FROM mst.t_supp_per_brg_history a
					JOIN mst.t_produk b
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					WHERE 1=1 AND kd_peruntukan ='0' ".$where."
					ORDER BY b.nama_produk";
		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

        return $rows;
	}

        public function search_produk_history_dist($no_bukti = '', $kd_produk = ''){
		$where = '';
		if($no_bukti != ''){
			$where .= " AND a.no_bukti = '$no_bukti' ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
		}
		$sql = "SELECT COALESCE(a.updated_date, a.created_date, a.updated_date) as tanggal, a.*,b.pct_margin, b.rp_ongkos_kirim, b.rp_het_harga_beli,b.rp_het_harga_beli_dist,nama_produk, nm_satuan
					FROM mst.t_supp_per_brg_history a
					JOIN mst.t_produk b
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					WHERE kd_peruntukan ='1' ".$where."
					ORDER BY b.nama_produk";
		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

        return $rows;
	}

	public function search_no_bukti($search = "", $offset, $length){
			$this->db->select("a.no_bukti");
			$this->db->select("nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("a.no_bukti");
			if($search != ""){
				$this->db->where("a.no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
                        $this->db->where("a.kd_peruntukan","0");
			$this->db->order_by("a.no_bukti");
			$query = $this->db->get("mst.t_supp_per_brg_history a", $length, $offset);

			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}
			// print_r($this->db->last_query());
			$this->db->flush_cache();
			$this->db->select("count(DISTINCT no_bukti) AS total");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->where("aktif is TRUE", NULL);
			$query = $this->db->get("mst.t_supp_per_brg_history");

			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}

			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
		}
	public function search_no_bukti_dist($search = "", $offset, $length){
			$this->db->select("a.no_bukti");
			$this->db->select("nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("a.no_bukti");
			if($search != ""){
				$this->db->where("a.no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
			$this->db->where("kd_peruntukan", "1");
                        $this->db->order_by("a.no_bukti");

			$query = $this->db->get("mst.t_supp_per_brg_history a", $length, $offset);

			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}
			// print_r($this->db->last_query());
			$this->db->flush_cache();
			$this->db->select("count(DISTINCT no_bukti) AS total");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->where("aktif is TRUE", NULL);
			$query = $this->db->get("mst.t_supp_per_brg_history");

			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}

			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
		}
	public function get_produk($keyword = ''){
		$this->db->select("kd_produk,nama_produk");
		if($keyword != ""){
			$this->db->where("(lower(nama_produk) LIKE '%" . strtolower($keyword) . "%') OR (kd_produk LIKE '" . $keyword . "%')", NULL);
			$this->db->order_by("kd_produk", 'asc');
		}else{
			$this->db->order_by("nama_produk", 'asc');
		}
		$this->db->where("aktif",1);
		$query = $this->db->get("mst.t_produk");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_data_print($no_bukti = '', $kd_produk = ''){
		$where = '';
		$title = 'HISTORY HARGA PEMBELIAN';
		if($no_bukti != '' && $no_bukti != '0'){
			$where .= " AND a.no_bukti = '$no_bukti' ";
			$title .= " - No Bukti : $no_bukti ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
			$title .= " - Kd Produk : $kd_produk ";
		}
		$sql = "SELECT '". $title ."' title, d.nama_supplier,
				COALESCE(a.updated_date, a.created_date, a.updated_date) as tanggal, a.*, b.pct_margin, b.rp_ongkos_kirim,
				CASE WHEN a.status_approve=1 THEN 'Approve' ELSE CASE WHEN a.status_approve=9 THEN 'REJECT' END  END status_approve,
				b.rp_het_harga_beli,b.rp_het_harga_beli_dist,nama_produk, nm_satuan
					FROM mst.t_supp_per_brg_history a
					JOIN mst.t_produk b
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					JOIN mst.t_supplier d
						ON d.kd_supplier = a.kd_supplier
					WHERE 1=1 ".$where."
					ORDER BY b.nama_produk";
		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}

	public function select_temp($kd_supplier = "",$kd_produk = "",$status = ""){
		$where = array(
					'kd_supplier' => $kd_supplier,
					'kd_produk' => $kd_produk,
					'status' => $status,
				);
		$this->db->where($where);

		$query = $this->db->get("mst.t_supp_per_brg_temp");
		$result = FALSE;

		if($query->num_rows > 0){
			$result = TRUE;
		}

		return $result;
	}

	public function get_temp($kd_supplier = "",$kd_produk = "",$status = ""){
		$where = array(
					'kd_supplier' => $kd_supplier,
					'kd_produk' => $kd_produk,
					'status' => $status,
				);
		$this->db->where($where);

		$query = $this->db->get("mst.t_supp_per_brg_temp");

		if($query->num_rows > 0){
			$result = $query->row;
		}

		return $result;
	}

	public function insert_temp($data = NULL){
		return $this->db->insert('mst.t_supp_per_brg_temp', $data);
	}

	public function get_no_bukti_filter($search = "", $offset, $length){
			$this->db->select("no_bukti AS no_bukti_filter",FALSE);
			$this->db->select("nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("a.no_bukti");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
			$this->db->where('a.status','0');
                        $this->db->where('a.kd_peruntukan','0');
			$this->db->order_by("no_bukti");
			$query = $this->db->get("mst.t_supp_per_brg_temp a", $length, $offset);

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
			$query = $this->db->get("mst.t_supp_per_brg_temp");

			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}

			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
		}
	public function get_no_bukti_filter_dist($search = "", $offset, $length){
			$this->db->select("no_bukti AS no_bukti_filter",FALSE);
			$this->db->select("nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("a.no_bukti");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
			$this->db->where('a.status','0');
                        $this->db->where('a.kd_peruntukan','1');
			$this->db->order_by("no_bukti");
			$query = $this->db->get("mst.t_supp_per_brg_temp a", $length, $offset);

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
			$query = $this->db->get("mst.t_supp_per_brg_temp");

			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}

			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
		}
	public function update_temp($kd_supplier = '', $kd_produk = '', $waktu_top = '', $no_bukti = '', $datau = NULL){
		$this->db->where('kd_supplier',$kd_supplier);
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('waktu_top',$waktu_top);
		$this->db->where('no_bukti',$no_bukti);
		return $this->db->update('mst.t_supp_per_brg_temp',$datau);
		// print_r($this->db->last_query());
	}
        public function select_data_temp($kd_produk = "", $tgl_start_diskon =""){
		
                $sql = "select * from mst.t_supp_per_brg_temp
                        where kd_produk ='$kd_produk' 
                        AND tgl_start_diskon ='$tgl_start_diskon'
                        AND konsinyasi ='0'";
                $query = $this->db->query($sql);
                return $query->num_rows();
	}
        public function update_temp_by_date($kd_produk = '', $tgl_start_diskon = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('tgl_start_diskon',$tgl_start_diskon);
                $this->db->where('konsinyasi','0');
               	return $this->db->update('mst.t_supp_per_brg_temp',$datau);
        }
}
