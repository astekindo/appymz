<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_create_po_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	public function get_jth_tempo_po(){
		$this->db->select('nilai_parameter');
		$this->db->where('kd_parameter',JTH_TEMPO_PO);
		$query = $this->db->get('mst.t_parameter');
		
		$nilai_parameter = '';
        if ($query->num_rows() != 0) {
            $nilai_parameter = $query->row()
						 ->nilai_parameter;
        }
        return $nilai_parameter;
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
	public function update_detail_request_order($no_ro = '', $kd_produk = '', $qty = NULL){
		$sql = "UPDATE purchase.t_dtl_purchase_request 
				SET qty_po = qty_po + ".$qty."
				WHERE kd_produk = '$kd_produk'
				AND no_ro = '$no_ro'";
		// $data['qty_po'] = $qty;	
		// $this->db->where("kd_produk", $kd_produk);
		// $this->db->where("no_ro", $no_ro);
		// return $this->db->update("purchase.t_dtl_purchase_request", $data);
		return $this->db->query($sql);
	}
        public function update_gen_order_konsinyasi($no_bukti ='', $kd_produk = ''){
		$sql = "UPDATE purchase.t_gen_order_konsinyasi 
				SET status = 1
				WHERE kd_produk = '$kd_produk'
				AND no_bukti = '$no_bukti' AND status = 0";
		return $this->db->query($sql);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_ro($keyword = ""){
		$this->db->select("no_ro");
		$this->db->where("close_ro", 0);
		$this->db->where("status", "2");
		$this->db->where("konsinyasi", "1");
		if($keyword != ""){
			$this->db->where("no_ro LIKE '%" . $keyword . "%'", NULL);
		}
		$this->db->order_by("no_ro", 'asc');
		$query = $this->db->get("purchase.t_purchase_request");
		
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
	public function get_ro_detail($no_ro = ''){
		$sql = "SELECT a.*, b.*, d.nama_produk, e.nm_satuan, f.nama_supplier, f.pic, f.alamat, g.waktu_top
				FROM purchase.t_purchase_request a
				JOIN purchase.t_dtl_purchase_request b ON a.no_ro = b.no_ro				
				JOIN mst.t_produk d ON b.kd_produk = d.kd_produk
				JOIN mst.t_satuan e ON e.kd_satuan = d.kd_satuan
				JOIN mst.t_supplier f On f.kd_supplier = a.kd_supplier
				JOIN mst.t_supp_per_brg g ON g.kd_supplier = a.kd_supplier AND g.kd_produk = b.kd_produk
				WHERE a.no_ro = '".$no_ro."' AND a.konsinyasi='1' AND b.status = '2'";
		$query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
				
        return $rows;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_ro_detail_by_supplier($kd_supplier = '', $wkt_top = ''){
		$sql = "SELECT a.*, b.*, d.nama_produk, d.kd_peruntukkan, e.nm_satuan, f.nama_supplier, f.pic, f.alamat, g.waktu_top
				FROM purchase.t_purchase_request a
				JOIN purchase.t_dtl_purchase_request b ON a.no_ro = b.no_ro				
				JOIN mst.t_produk d ON b.kd_produk = d.kd_produk
				JOIN mst.t_satuan e ON e.kd_satuan = d.kd_satuan
				JOIN mst.t_supplier f On f.kd_supplier = a.kd_supplier
				JOIN mst.t_supp_per_brg g ON g.kd_supplier = a.kd_supplier AND g.kd_produk = b.kd_produk
				WHERE a.kd_supplier = '".$kd_supplier."' AND a.konsinyasi='1' AND b.status = '2' AND g.waktu_top =".$wkt_top;
		$query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		//print_r($this->db->last_query());
        return $rows;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_supplier_by_barang($kd_produk = ""){
		$this->db->select("a.kd_produk,a.kd_supplier,b.nama_supplier");		
		$this->db->where("a.kd_produk", $kd_produk);
		$this->db->where("a.aktif",1);
		$this->db->where("b.aktif is TRUE", NULL);
		$this->db->order_by("b.nama_supplier", 'asc');
		$this->db->join("mst.t_supplier b", "b.kd_supplier = a.kd_supplier");
		$query = $this->db->get("mst.t_supp_per_brg a");
		
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
	public function get_row_harga_supplier($search_by = "", $id = NULL, $kd_produk = ""){
		$this->db->select("a.*,b.nama_supplier,b.alamat,b.pic");
		if($search_by == "nama"){
			$this->db->where("(lower(b.nama_supplier) = '" . strtolower($id) . "')", NULL);
		}else{
			$this->db->where("a.kd_supplier", $id);
		}
        $this->db->where("a.kd_produk", $kd_produk);
		$this->db->where("a.aktif is true", NULL);
		$this->db->limit(1);
		$this->db->join("mst.t_supplier b", "a.kd_supplier = b.kd_supplier");	
        $query = $this->db->get('mst.t_supp_per_brg a');
        
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
		
        return $row;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_term_of_payment_by_supplier($kd_supplier = ''){
		$sql = "select distinct c.waktu_top
				from purchase.t_purchase_request a, purchase.t_dtl_purchase_request b, mst.t_supp_per_brg c
				where a.no_ro = b.no_ro
				and b.kd_produk = c.kd_produk
				and a.kd_supplier = c.kd_supplier
				and b.status = '2'
				and qty_adj-qty_po != 0
				and a.konsinyasi = '1'
				and a.kd_supplier = '" . $kd_supplier . "'";
				
		$query = $this->db->query($sql);
		
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
	public function get_term_of_payment($no_ro = ''){
		$sql = "select distinct c.waktu_top
				from purchase.t_purchase_request a, purchase.t_dtl_purchase_request b, mst.t_supp_per_brg c
				where a.no_ro = b.no_ro
				and b.kd_produk = c.kd_produk
				and a.kd_supplier = c.kd_supplier
				and b.status = '2'
				and qty_adj-qty_po != 0
				and a.no_ro = '" . $no_ro . "'";
				
		$query = $this->db->query($sql);
		
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
	public function get_row_harga_produk_per_supplier($kd_produk = "", $kd_supplier = ""){
		$this->db->where("kd_supplier", $kd_supplier);		
        $this->db->where("kd_produk", $kd_produk);
        $query = $this->db->get('mst.t_supp_per_brg');
        
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
		
        return $row;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_print($no_po = ''){	
		$sql = "select 'PURCHASE ORDER' title, a.no_po, a.tanggal_po, a.order_by_po, a.top,b.nama_supplier, a.alamat_kirim_po, a.remark,
					a.rp_jumlah_po, a.rp_diskon_po, a.ppn_percent_po, a.rp_ppn_po, a.rp_total_po, b.nama_supplier, b.fax, b.pic, b.telpon,
					a.tgl_berlaku_po, a.rp_dp, a.kirim_po, a.cetak_ke, a.cetak_ke_non_harga, a.approval_by
					from purchase.t_purchase a, mst.t_supplier b
					where a.no_po = '$no_po'
					and a.kd_suplier_po = b.kd_supplier";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select a.no_po, a.kd_produk, b.nama_produk, a.qty_po, c.nm_satuan, a.net_price_po, a.rp_total_po, b.kd_produk_supp, a.rp_disk_po,
				a.disk_persen_supp1_po, a.disk_persen_supp2_po, a.disk_persen_supp3_po, a.disk_persen_supp4_po, a.disk_amt_supp5_po, a.price_supp_po,
				a.dpp_po
						from purchase.t_purchase_detail a, mst.t_produk b, mst.t_satuan c
						where a.no_po = '$no_po'
						and a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan";
		
		$query_detail = $this->db->query($sql_detail);
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
        public function search_generate_sales($kd_supplier ='',$search='', $start='', $limit=''){
		$sql = "select distinct no_bukti,blth 
                        from purchase.t_gen_order_konsinyasi 
                        where kd_supplier ='$kd_supplier'";
				
		$query = $this->db->query($sql);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
      public function get_data_generate_detail($no_bukti = ''){
		$sql = "select a.*,b.nama_produk,c.nm_satuan,d.pkp from purchase.t_gen_order_konsinyasi a
                        join mst.t_produk b on a.kd_produk = b.kd_produk
                        join mst.t_satuan c on c.kd_satuan = b.kd_satuan 
                        join mst.t_supplier d on d.kd_supplier = a.kd_supplier
                        where a.no_bukti ='$no_bukti' and a.status = 0
                        limit 50";
		$query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		//print_r($this->db->last_query());
        return $rows;
	}
}
