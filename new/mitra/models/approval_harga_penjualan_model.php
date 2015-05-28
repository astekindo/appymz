<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_harga_penjualan_model extends MY_Model {
	
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
		
		$sql = "SELECT  a.*,b.is_konsinyasi, b.kd_produk_lama, b.nama_produk, nm_satuan, 'Approve' as status
					FROM mst.t_diskon_sales_temp a 
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
                //print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_diskon_sales_temp a 
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
		$query = $this->db->get("mst.t_diskon_sales_temp");
		//print_r($this->db->last_query());
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
			$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
			$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');
			$this->db->order_by("a.kd_diskon_sales desc");
			$query = $this->db->get("mst.t_diskon_sales_temp a");
			//print_r($this->db->last_query());exit;
			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}     
			$this->db->flush_cache();
			$this->db->select(" count( DISTINCT kd_diskon_sales) AS total");
			if($search != ""){
				$this->db->where("kd_diskon_sales LIKE '%" . $search . "%'", NULL);
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
	
	public function update_temp($no_bukti = '', $kd_produk = '', $status = ''){
		$this->db->where('kd_diskon_sales',$no_bukti);
		$this->db->where('kd_produk',$kd_produk);
		$datau = array(
					'status' => $status,
				);
		return $this->db->update('mst.t_diskon_sales_temp',$datau);
		//print_r($this->db->last_query());	
	}
		
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

	public function update_rows_diskon( $kd_produk = '', $tgl_start_diskon = "", $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('tgl_start_diskon',$tgl_start_diskon);
                //$this->db->where('tgl_end_diskon',$tgl_end_diskon);
		return $this->db->update('mst.t_diskon_sales',$datau);
		// print_r($this->db->last_query());
		
	}
        public function update_harga_jual_non_aktif($kd_produk = '',$tgl_start_diskon =''){
		$this->db->where('kd_produk',$kd_produk);
                $this->db->where('tgl_start_diskon >',$tgl_start_diskon);
                $data = array (
                    'diskon_aktif' => 0,
                );
		return $this->db->update('mst.t_diskon_sales',$data);
		
	}
        public function update_harga_jual_tgl_end($kd_produk = '',$tgl_start_diskon =''){
		$tgl_end = strtotime(date("Y-m-d", strtotime($tgl_start_diskon)) . " -1 day");
                $tgl_end = date("Y-m-d",$tgl_end);
                $sql ="update mst.t_diskon_sales set tgl_end_diskon = '$tgl_end'
                       where kd_produk = '$kd_produk' and tgl_start_diskon < '$tgl_start_diskon'
                       and (tgl_end_diskon is null or tgl_end_diskon >= '$tgl_start_diskon')";
                //$query = $this->db->query($sql);
                
		return $this->db->query($sql);
		
	}
        public function select_data_beli($kd_produk = "",$tgl_start_diskon=""){
		$sql = "select * from mst.t_diskon_sales
                        where kd_produk ='$kd_produk' 
                        and tgl_start_diskon ='$tgl_start_diskon'
                           ";
		
                $query = $this->db->query($sql);
                //print_r($this->db->last_query());
                return $query->num_rows()>0;
	}
	
	public function insert_rows_diskon_history($kd_produk = '', $kd_diskon_sales = '', $koreksi_ke = '', $no_bukti = '', $tgl_approve = '', $approve_by = '', $status_approve = ''){
		$sql = "INSERT INTO mst.t_diskon_sales_history(
					kd_produk, kd_diskon_sales, tanggal, disk_persen_kons1, disk_persen_kons2, 
					disk_persen_kons3, disk_persen_kons4, disk_amt_kons1, disk_amt_kons2, 
					disk_amt_kons3, disk_amt_kons4, disk_amt_kons5, disk_persen_member1, 
					disk_persen_member2, disk_persen_member3, disk_persen_member4, 
					disk_amt_member1, disk_amt_member2, disk_amt_member3, disk_amt_member4, 
					disk_amt_member5, created_by, created_date, updated_by, updated_date, 
					koreksi_ke, is_bonus, qty_beli_bonus, kd_produk_bonus, qty_bonus, 
					is_bonus_kelipatan, qty_beli_member, kd_produk_member, qty_member, 
					is_member_kelipatan, kd_kategori1_bonus, kd_kategori2_bonus, 
					kd_kategori3_bonus, kd_kategori4_bonus, kd_kategori1_member, 
					kd_kategori2_member, kd_kategori3_member, kd_kategori4_member, 
					keterangan, no_bukti, tgl_approve, approve_by, hrg_beli_sup, 
					rp_ongkos_kirim, pct_margin, rp_margin, rp_jual_supermarket, 
					rp_het_harga_beli, rp_cogs, rp_het_cogs,tgl_start_diskon,tgl_end_diskon, status_approve) 
				SELECT kd_produk, kd_diskon_sales, tanggal, disk_persen_kons1, disk_persen_kons2, 
					disk_persen_kons3, disk_persen_kons4, disk_amt_kons1, disk_amt_kons2, 
					disk_amt_kons3, disk_amt_kons4, disk_amt_kons5, disk_persen_member1, 
					disk_persen_member2, disk_persen_member3, disk_persen_member4, 
					disk_amt_member1, disk_amt_member2, disk_amt_member3, disk_amt_member4, 
					disk_amt_member5, created_by, created_date, updated_by, updated_date, 
					koreksi_ke, is_bonus, qty_beli_bonus, kd_produk_bonus, qty_bonus, 
					is_bonus_kelipatan, qty_beli_member, kd_produk_member, qty_member, 
					is_member_kelipatan, kd_kategori1_bonus, kd_kategori2_bonus, 
					kd_kategori3_bonus, kd_kategori4_bonus, kd_kategori1_member, 
					kd_kategori2_member, kd_kategori3_member, kd_kategori4_member, 
					keterangan, '$no_bukti', '$tgl_approve', '$approve_by', net_hrg_supplier_sup_inc, 
					rp_ongkos_kirim, pct_margin, rp_margin, rp_jual_supermarket, 
					rp_het_harga_beli, rp_cogs, rp_het_cogs,tgl_start_diskon,tgl_end_diskon, '$status_approve'
				FROM mst.t_diskon_sales_temp b
				WHERE b.kd_produk = '".$kd_produk."' 
				AND b.no_bukti = '".$no_bukti."'
				AND b.status = '".$status_approve."'";
		
		return $this->db->query($sql);
	}
	public function select_data_jual($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){
		
		$sql = "select * from mst.t_diskon_sales
                          where kd_produk ='$kd_produk' 
                          and tgl_start_diskon = '$tgl_start_diskon' and tgl_end_diskon = '$tgl_end_diskon'
                          ";
		
                $query = $this->db->query($sql);
                //print_r($this->db->last_query());
                return $query->result();
	}
}
