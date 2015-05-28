<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_po_model extends MY_Model {
	
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

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_ro($keyword = ""){
		$this->db->select("no_ro");
		$this->db->where("close_ro", 0);
		$this->db->where("status", "2");
		$this->db->where("konsinyasi", "0");
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
				WHERE a.no_ro = '".$no_ro."' AND a.konsinyasi='0' AND b.status = '2'";
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
	public function get_ro_detail_by_supplier($kd_supplier = '', $wkt_top = '', $kd_peruntukan = ''){
		$sql = "SELECT a.*, b.*, d.nama_produk, d.kd_peruntukkan, e.nm_satuan, f.nama_supplier, f.pic, f.alamat, g.waktu_top
				FROM purchase.t_purchase_request a
				JOIN purchase.t_dtl_purchase_request b ON a.no_ro = b.no_ro				
				JOIN mst.t_produk d ON b.kd_produk = d.kd_produk
				JOIN mst.t_satuan e ON e.kd_satuan = d.kd_satuan
				JOIN mst.t_supplier f On f.kd_supplier = a.kd_supplier
				JOIN mst.t_supp_per_brg g ON g.kd_supplier = a.kd_supplier AND g.kd_produk = b.kd_produk
				WHERE a.close_ro = '0' AND a.kd_peruntukan = '".$kd_peruntukan."' AND a.kd_supplier = '".$kd_supplier."' AND a.konsinyasi='0' AND b.status = '2' AND g.waktu_top =".$wkt_top;
		$query = $this->db->query($sql);
                
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		// print_r($this->db->last_query());exit;
		//print_r($rows);exit;
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
				and a.konsinyasi = '0'
				and a.kd_supplier = '" . $kd_supplier . "'";
				
		$query = $this->db->query($sql);
		
		 //print_r($this->db->last_query());exit;
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
		// print_r($this->db->last_query());exit;
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
		// print_r($this->db->last_query());exit;
        return $row;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_print($no_po = ''){
            $sql = "select b.pkp, CASE WHEN a.konsinyasi='1' THEN 'PURCHASE ORDER (KONSINYASI)' ELSE 'PURCHASE ORDER' END title, a.no_po, a.tanggal_po,a.kd_peruntukan, a.order_by_po, a.top,b.nama_supplier, a.alamat_kirim_po, a.remark,
                    a.rp_jumlah_po, a.rp_diskon_po, a.ppn_percent_po, a.rp_ppn_po, a.rp_total_po, b.nama_supplier, b.fax, b.pic, b.telpon,
                    b.npwp, b.email,a.is_bonus,a.no_po_induk,
                    a.tgl_berlaku_po, a.rp_dp, a.kirim_po, a.cetak_ke, a.cetak_ke_non_harga, a.approval_by
                    from purchase.t_purchase a, mst.t_supplier b
                    where a.no_po = '$no_po'
                    and a.kd_suplier_po = b.kd_supplier";

		$query = $this->db->query($sql);
		// print_r($this->db->last_query());exit;
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
$sql_detail = "select a.*, b.kd_produk_lama, b.nama_produk, c.nm_satuan,b.kd_produk_supp
from purchase.t_purchase_detail a, mst.t_produk b, mst.t_satuan c
where a.no_po = '$no_po'
and a.kd_produk = b.kd_produk
and b.kd_satuan = c.kd_satuan";
		
		$query_detail = $this->db->query($sql_detail);
		$data['detail'] = $query_detail->result();
		
		return $data;
	}


	public function validate_po_by_kd_produk($kd_produk = '',$kd_peruntukkan = ''){
		$sql = "select sum(qty_po) from purchase.t_purchase x, purchase.t_purchase_detail a
				where a.kd_produk = '$kd_produk'
				and x.no_po = a.no_po
				and x.approval_po = '0'
                                and x.kd_peruntukan = '$kd_peruntukkan'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			$result = $query->row();
		
		return $result;
	}

	public function setCetakKe($no_po = ''){
		$this->db->query('UPDATE purchase.t_purchase set cetak_ke = cetak_ke + 1 where no_po = ?', array($no_po)); 
	}
	
	public function setCetakKeNonHarga($no_po = ''){
		$this->db->query('UPDATE purchase.t_purchase set cetak_ke_non_harga = cetak_ke_non_harga + 1 where no_po = ?', array($no_po)); 
	}
        
       public function getCustomers($limit, $offset, $search = "") {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND ((lower(mst.t_pelanggan_dist.nama_pelanggan) LIKE '%" . strtolower($search) . "%') OR (mst.t_pelanggan_dist.kd_pelanggan LIKE '%" . strtolower($search) . "%'))";
        }

        $getDataQuery = "SELECT a.*,b.nama_propinsi,c.nama_kota,d.nama_kecamatan,e.nama_kalurahan,f.nama_area,g.nama_cabang,
                         CASE WHEN a.aktif = 1 THEN 'aktif' 
                         ELSE 'tidak aktif' 
                         END status,
                         CASE 
                         WHEN a.tipe = 0 THEN 'TOKO' 
                         WHEN a.tipe = 1 THEN 'AGEN'
                         WHEN a.tipe = 2 THEN 'MODERN MARKET'
                         ELSE 'UNKNOWN' 
                         END nama_tipe,
                         CASE
                         WHEN a.is_pkp = 0 THEN 'tidak' 
                         ELSE 'ya' 
                         END pkp
                         FROM mst.t_pelanggan_dist a 
                         LEFT JOIN mst.t_propinsi b on a.kd_propinsi=b.kd_propinsi
                         LEFT JOIN mst.t_kota c on a.kd_kota=c.kd_kota
                         LEFT JOIN mst.t_kecamatan d on a.kd_kecamatan=d.kd_kecamatan
                         LEFT JOIN mst.t_kalurahan e on a.kd_kalurahan=e.kd_kalurahan
                         LEFT JOIN mst.t_area f on a.kd_area=f.kd_area
                         LEFT JOIN mst.t_cabang g on a.kd_cabang=g.kd_cabang
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT COUNT(*) AS total FROM 
                         (SELECT a.*,b.nama_propinsi,c.nama_kota,d.nama_kecamatan,e.nama_kalurahan,f.nama_area,g.nama_cabang,
                         CASE WHEN a.aktif = 1 THEN 'aktif' 
                         ELSE 'tidak aktif' 
                         END status
                         FROM mst.t_pelanggan_dist a 
                         LEFT JOIN mst.t_propinsi b on a.kd_propinsi=b.kd_propinsi
                         LEFT JOIN mst.t_kota c on a.kd_kota=c.kd_kota
                         LEFT JOIN mst.t_kecamatan d on a.kd_kecamatan=d.kd_kecamatan
                         LEFT JOIN mst.t_kalurahan e on a.kd_kalurahan=e.kd_kalurahan
                         LEFT JOIN mst.t_area f on a.kd_area=f.kd_area
                         LEFT JOIN mst.t_cabang g on a.kd_cabang=g.kd_cabang
                         )a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            $queryResults = $queryGetData->result();
            $total = $queryGetTotal->row()->total;
        }


        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }


}
