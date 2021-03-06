<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_harga_penjualan_distribusi_model extends MY_Model {
	
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
	public function insert_history($data){
		return $this->db->insert('mst.t_supp_per_brg_history', $data);		
	}
	
	public function search_produk_by_no_bukti($no_bukti = "", $search = '', $offset, $length){
		$where = '';
		if ($no_bukti != ''){
			$where .= " AND a.kd_diskon_sales = '$no_bukti'";
		}else{
			$from = "mst.t_supp_per_brg";
		}
		if ($search != ''){
			$where .= " AND ((lower(nama_produk) LIKE '%" . $search . "%') OR (nama_produk LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
		}
		
		$sql = "SELECT  a.*, b.kd_produk_lama, b.nama_produk, nm_satuan, 'Approve' as status
					FROM mst.t_diskon_sales_dist_temp a 
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
						".$where."
						ORDER BY b.nama_produk";
		$query = $this->db->query($sql);
                //print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_diskon_sales_dist_temp a 
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
		$this->db->where("kd_diskon_sales",$no_bukti);
		$query = $this->db->get("mst.t_diskon_sales_dist_temp");
		// print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result_array();
		}
		$result = $rows;
		
		return $result;
	}
	
	public function get_no_bukti_filter($search = "", $offset, $length){
                $this->db->select("a.kd_diskon_sales AS no_bukti_filter",FALSE);
		$this->db->select("a.keterangan");
		$this->db->select("a.created_by");
		$this->db->select("b.nama_supplier");
		$this->db->distinct("a.kd_diskon_sales");
		if($search != ""){
			$this->db->where("a.kd_diskon_sales LIKE '%" . $search . "%'", NULL);
		}
		$this->db->where('a.status','0');
		$this->db->order_by("a.kd_diskon_sales");
		$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
		$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');

		$query = $this->db->get("mst.t_diskon_sales_dist_temp a", $length, $offset);
		//print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        

		$this->db->flush_cache();
		$this->db->select("count(DISTINCT kd_diskon_sales) AS total");
		if($search != ""){
			$this->db->where("kd_diskon_sales LIKE '%" . $search . "%'", NULL);
		}
		$this->db->where("status", '0');
		$query = $this->db->get("mst.t_diskon_sales_dist_temp");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}	
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

		return $results;
		}
	
	public function update_temp($no_bukti = '', $kd_produk = '', $status = '',$approve_by = '',$approve_date = ''){
		$this->db->where('kd_diskon_sales',$no_bukti);
		$this->db->where('kd_produk',$kd_produk);
		$datau = array(
					'status' => $status,
                                        'tgl_approve' => $approve_date,
                                        'approve_by' => $approve_by,
				);
		$query =  $this->db->update('mst.t_diskon_sales_dist_temp',$datau);
                //print_r($this->db->last_query());exit;
		return $query;
	}
		
	public function update_rows_produk($kd_produk = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
		
	}
        public function update_temp_dist($kd_produk = '', $no_bukti = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('kd_diskon_sales',$no_bukti);
		return $this->db->update('mst.t_diskon_sales_dist_temp',$datau);
		// print_r($this->db->last_query());	
	}
	
	public function insert_rows_produk_history($kd_produk = '', $koreksi_ke = ''){
		$sql = "INSERT INTO mst.t_produk_history
				SELECT * FROM mst.t_produk
				WHERE kd_produk = '".$kd_produk."' 
				AND koreksi_ke = '".$koreksi_ke."'";
				
		return $this->db->query($sql);
	}
	
	public function insert_rows_diskon($datau = NULL){
		return $this->db->insert('mst.t_diskon_sales_dist',$datau);
		//print_r($this->db->last_query());exit;
		
	}
	
	
	public function update_rows_diskon( $kd_produk = '', $tgl_start_diskon = "",$tgl_end_diskon = "", $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
//		$this->db->where('tgl_start_diskon',$tgl_start_diskon);
//                $this->db->where('tgl_end_diskon',$tgl_end_diskon);
		return $this->db->update('mst.t_diskon_sales_dist',$datau);
		// print_r($this->db->last_query());
		
	}
	
	public function insert_rows_diskon_history($kd_produk = '', $kd_diskon_sales = '', $koreksi_ke = '', $no_bukti = '', $tgl_approve = '', $approve_by = '', $status_approve = ''){
		$sql = "INSERT INTO mst.t_diskon_sales_dist_history(
					kd_produk, tanggal, disk_persen1, disk_persen2, 
					disk_persen3, disk_persen4, disk_amt1, disk_amt2, 
					disk_amt3, disk_amt4, disk_amt5, disk_persen_agen1, 
					disk_persen_agen2, disk_persen_agen3, disk_persen_agen4, 
					disk_amt_agen1, disk_amt_agen2, disk_amt_agen3, disk_amt_agen4, 
					disk_amt_agen5, created_by, created_date, updated_by, updated_date, 
					koreksi_ke, is_bonus, qty_beli_bonus, kd_produk_bonus, qty_bonus, 
					is_bonus_kelipatan, qty_beli_agen, kd_produk_agen, qty_agen, 
					is_agen_kelipatan, kd_kategori1_bonus, kd_kategori2_bonus, 
					kd_kategori3_bonus, kd_kategori4_bonus, kd_kategori1_agen, 
					kd_kategori2_agen, kd_kategori3_agen, kd_kategori4_agen, 
					keterangan, no_bukti, tgl_approve, approve_by,kd_diskon_sales,  
					 rp_jual_toko,rp_jual_agen,rp_jual_toko_net,rp_jual_agen_net, 
					 tgl_start_diskon,tgl_end_diskon,status) 
				SELECT kd_produk, tanggal, disk_persen1, disk_persen2, 
					disk_persen3, disk_persen4, disk_amt1, disk_amt2, 
					disk_amt3, disk_amt4, disk_amt5, disk_persen_agen1, 
					disk_persen_agen2, disk_persen_agen3, disk_persen_agen4, 
					disk_amt_agen1, disk_amt_agen2, disk_amt_agen3, disk_amt_agen4, 
					disk_amt_agen5, created_by, created_date, updated_by, updated_date, 
					koreksi_ke, is_bonus, qty_beli_bonus, kd_produk_bonus, qty_bonus, 
					is_bonus_kelipatan, qty_beli_agen, kd_produk_agen, qty_agen, 
					is_agen_kelipatan, kd_kategori1_bonus, kd_kategori2_bonus, 
					kd_kategori3_bonus, kd_kategori4_bonus, kd_kategori1_agen, 
					kd_kategori2_agen, kd_kategori3_agen, kd_kategori4_agen, 
					keterangan, '$no_bukti', '$tgl_approve', '$approve_by', '$kd_diskon_sales',
					rp_jual_toko, 
					rp_jual_agen, rp_jual_toko_net,rp_jual_agen_net,
                                        tgl_start_diskon,tgl_end_diskon,'$status_approve'
				FROM mst.t_diskon_sales_dist_temp b
				WHERE b.kd_produk = '".$kd_produk."' 
				AND b.no_bukti = '".$no_bukti."'
				AND b.status = '".$status_approve."'";
		
		return $this->db->query($sql);
	}
	public function select_data_dist($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){
		
		$sql = "select * from mst.t_diskon_sales_dist
                          where kd_produk ='$kd_produk' 
                         ";
		
                $query = $this->db->query($sql);
                //print_r($this->db->last_query());
                return $query->result();
	}
}
