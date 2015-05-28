<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_harga_pembelian_model extends MY_Model {

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
	public function update_row($kd_produk = '',$tgl_start_diskon ='',  $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
                $this->db->where('tgl_start_diskon',$tgl_start_diskon);
                
		return $this->db->update('mst.t_supp_per_brg',$datau);

	}

	public function update_net_produk($kd_produk = '', $rp_het_harga_beli = '',  $hrg_beli_sup = ''){
		$datau = array(
				   'rp_het_harga_beli' => $rp_het_harga_beli,
				   // 'hrg_beli_sup' => $hrg_beli_sup,
				   );

		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
	}
	public function update_net_produk_dist($kd_produk = '', $rp_het_harga_beli_dist = '',$hrg_beli_dist = ''){
		$datau = array(
				   'rp_het_harga_beli_dist' => $rp_het_harga_beli_dist,

				);

		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
	}
	public function get_hrgJual_produk($kd_produk){
		$sql = "select * from mst.t_produk
					where kd_produk = '$kd_produk'
					and (rp_jual_supermarket = 0 or rp_jual_supermarket is null)
					and (rp_jual_distribusi = 0 or rp_jual_distribusi is null)";
		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		return $rows;

	}

	public function update_hrgJual_produk($kd_produk = '', $rp_het_harga_beli = '', $rp_het_harga_beli_dist = ''){
		$datau = array(
				   'rp_jual_supermarket' => $rp_het_harga_beli,
				   'rp_jual_distribusi' => $rp_het_harga_beli_dist,
				);

		$this->db->where('kd_produk',$kd_produk);

		return $this->db->update('mst.t_produk',$datau);
	}

        public function update_hrgJual_produk_dist($kd_produk = '', $rp_het_harga_beli_dist = ''){
		$datau = array(
				  'rp_jual_distribusi' => $rp_het_harga_beli_dist,
				);

		$this->db->where('kd_produk',$kd_produk);

		return $this->db->update('mst.t_produk',$datau);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_history($data){
		$data['tgl_end_diskon'] = $data['tgl_send_diskon'];
		unset($data['tgl_send_diskon']);
		//print_r($data);return;
		return $this->db->insert('mst.t_supp_per_brg_history', $data);
	}

	public function search_produk_by_no_bukti($no_bukti = "", $search = '', $offset, $length){
		$where = '';
		if ($no_bukti != ''){
			$where .= " AND a.no_bukti = '$no_bukti'";
		}else{
			$from = "mst.t_supp_per_brg";
		}
		if ($search != ''){
			$where .= " AND ((lower(nama_produk) LIKE '%" . $search . "%') OR (nama_produk LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
		}
		$sql = "SELECT a.*,b.kd_produk_lama,b.rp_het_harga_beli,b.is_konsinyasi, b.pct_margin, b.rp_ongkos_kirim, nama_produk, nm_satuan, 'Approve' as status,
					CASE WHEN pkp='1' THEN 'YA' ELSE 'TIDAK' end pkp
					FROM mst.t_supp_per_brg_temp a
					JOIN mst.t_produk b
						ON b.kd_produk = a.kd_produk
					JOIN mst.t_satuan c
						ON c.kd_satuan = b.kd_satuan
					JOIN mst.t_supplier h
						ON a.kd_supplier = h.kd_supplier
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
						".$where."
						ORDER BY b.nama_produk";
		$query = $this->db->query($sql);
		// print_r($this->db->last_query());
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
						".$where;

        $query = $this->db->query($sql2);

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$result['total'] = $total;
        return $result;
	}

	public function get_data_temp($no_bukti = ""){
		$this->db->where("no_bukti",$no_bukti);
		$query = $this->db->get("mst.t_supp_per_brg_temp");
		// print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result_array();
		}
		$result = $rows;

		return $result;
	}

	public function get_no_bukti_filter($search = "", $offset, $length){
			$this->db->select("a.no_bukti AS no_bukti_filter",FALSE);
			$this->db->select("b.nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("no_bukti");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
			$this->db->where('a.status','0');
                        $this->db->where('a.kd_peruntukan','0');
			$this->db->order_by("a.no_bukti");
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
			$this->db->select("a.no_bukti AS no_bukti_filter",FALSE);
			$this->db->select("b.nama_supplier");
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->distinct("no_bukti");
			if($search != ""){
				$this->db->where("no_bukti LIKE '%" . $search . "%'", NULL);
			}
			$this->db->join('mst.t_supplier b','b.kd_supplier=a.kd_supplier');
			$this->db->where('a.status','0');
                        $this->db->where('a.kd_peruntukan','1');
			$this->db->order_by("a.no_bukti");
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

	public function update_temp($no_bukti = '', $kd_produk = '', $status = ''){
		$this->db->where('no_bukti',$no_bukti);
		$this->db->where('kd_produk',$kd_produk);
		$datau = array(
					'status' => $status,
				);
		return $this->db->update('mst.t_supp_per_brg_temp',$datau);
		// print_r($this->db->last_query());
	}

	public function get_produk_margin($kd_produk){
		$this->db->select("pct_margin, rp_ongkos_kirim,pct_margin_dist, rp_ongkos_kirim_dist");
		$this->db->where("kd_produk", $kd_produk);
		$query = $this->db->get("mst.t_produk");
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		return $rows;

	}

        public function update_diskon_jual_konsinyasi($kd_produk = NULL, $data = NULL){
            $this->db->where('kd_produk',$kd_produk);
            $result = $this->db->update('mst.t_diskon_sales', $data);
            return  $result;
        }
        public function select_data_beli($kd_produk = "",$tgl_start_diskon=""){
		$sql = "select * from mst.t_supp_per_brg
                        where kd_produk ='$kd_produk'
                        and tgl_start_diskon ='$tgl_start_diskon'
                        and konsinyasi ='0'
                           ";

                $query = $this->db->query($sql);
                ///print_r($this->db->last_query());
                return $query->num_rows();
	}
        public function update_harga_beli_non_aktif($kd_produk = '',$tgl_start_diskon =''){
		$this->db->where('kd_produk',$kd_produk);
                $this->db->where('tgl_start_diskon >',$tgl_start_diskon);
                $data = array (
                    'aktif_diskon' => 0,
                );
		return $this->db->update('mst.t_supp_per_brg',$data);

	}
        public function update_harga_beli_tgl_end($kd_produk = '',$tgl_start_diskon =''){
		$tgl_end = strtotime(date("Y-m-d", strtotime($tgl_start_diskon)) . " -1 day");
                $tgl_end = date("Y-m-d",$tgl_end);
                $sql ="update mst.t_supp_per_brg set tgl_end_diskon = '$tgl_end'
                       where kd_produk = '$kd_produk' and tgl_start_diskon < '$tgl_start_diskon'
                       and (tgl_end_diskon is null or tgl_end_diskon >= '$tgl_start_diskon')";
                //$query = $this->db->query($sql);

		return $this->db->query($sql);

	}
}
