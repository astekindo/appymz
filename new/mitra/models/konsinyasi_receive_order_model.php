<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_receive_order_model extends MY_Model {
	
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
	public function query_update($sql = ""){
		return $this->db->query($sql);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_po($kd_supplier = "", $search = ""){
		if($search != ''){
			$this->db->where("((lower(no_sp) LIKE '%" . $search . "%') OR (no_sp LIKE '%" . $search . "%'))", NULL);
		}

		$this->db->select("no_sp as  no_po");
		$this->db->where("close_sp", 0);
		$this->db->where("konsinyasi", "1");
		$this->db->where("is_bonus", "0");
		$this->db->where("approval_sp", "1");
		$this->db->where("kd_suplier", $kd_supplier);
		$this->db->order_by("no_sp", 'asc');
		$query = $this->db->get("purchase.t_surat_pesanan");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		// print_r($this->db->last_query());exit;
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_po_detail($no_po = '', $search = '', $sender = ''){
		if($search != ''){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%') OR (kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->select(" a.kd_produk, a.qty_sp as qty_po,a.qty_retur,
							COALESCE(a.qty_sp - sum(f.qty_terima), a.qty_sp) AS qty_do, 
							COALESCE(a.qty_sp - sum(f.qty_terima), a.qty_sp) AS jumlah_barcode, 
							COALESCE(sum(f.qty_terima), 0) AS qty_terima, 
							b.nama_produk,b.kd_produk_supp,b.kd_produk_lama,c.nm_satuan,e.kd_supplier,e.nama_supplier", FALSE);
                $this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
                $this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
                $this->db->join("purchase.t_surat_pesanan d", "d.no_sp = a.no_sp");
                $this->db->join("mst.t_supplier e", "e.kd_supplier = d.kd_suplier");
                $this->db->join("purchase.t_dtl_receive_order f", "f.no_sp = a.no_sp and f.kd_produk = a.kd_produk", "left");
                $this->db->where("a.no_sp", $no_po);
                $this->db->group_by(array("a.kd_produk","a.qty_retur", "a.no_sp", "a.qty_sp", " b.nama_produk", " b.kd_produk_supp", " b.kd_produk_lama", " c.nm_satuan", " e.kd_supplier", " e.nama_supplier"));
                $query = $this->db->get("purchase.t_surat_pesanan_detail a");
		
		$rows = array();
		if($query->num_rows() > 0){
			if($sender !=''){
				$rows = $query->row();
			}else{
				$rows = $query->result();
			}
		}        
		// print_r($this->db->last_query());exit;
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_print($no_do = '', $title = ''){
		// $this->db->select('a.*,b.nama_supplier,b.alamat,b.pic');
		// $this->db->where("a.no_ro", $no_ro);
		// $this->db->join("mst.t_supplier b", "b.kd_supplier = a.kd_supplier");
		// $query = $this->db->get("purchase.t_purchase_request a");
		
		$sql = "select 'RECEIVE ORDER FORM (KONSINYASI)' title, a.kd_supplier, b.nama_supplier, a.no_bukti_supplier, a.no_do, a.tanggal,
				a.tanggal_terima, a.created_by
				from purchase.t_receive_order a, mst.t_supplier b
				where a.no_do = '$no_do'
				and a.kd_supplier = b.kd_supplier";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		// $this->db->select('a.*,b.nama_produk,c.nm_satuan');
		// $this->db->where("a.no_ro", $no_ro);
		// $this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
		// $this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
		// $query_detail = $this->db->get("purchase.t_dtl_purchase_request a");
		$sql_detail = "select a.no_po, a.kd_produk, b.kd_produk_lama, b.kd_produk_supp, b.nama_produk, a.qty_terima,
						c.nm_satuan, d.nama_lokasi || ' - ' || e.nama_blok || ' - ' || f.nama_sub_blok gudang,
						nama_ekspedisi, 
						(SELECT h.nm_satuan as nm_satuan_ekspedisi 
							FROM mst.t_satuan h 
							WHERE h.kd_satuan = a.kd_satuan_ekspedisi
						),
						berat_ekspedisi
						from purchase.t_dtl_receive_order a
						JOIN mst.t_produk b
							ON a.kd_produk = b.kd_produk
						JOIN mst.t_satuan c
							ON b.kd_satuan = c.kd_satuan
						JOIN mst.t_lokasi d
							ON a.kd_lokasi = d.kd_lokasi
						JOIN mst.t_blok e 
							ON a.kd_blok = e.kd_blok
						JOIN mst.t_sub_blok f 
							ON a.kd_sub_blok = f.kd_sub_blok
						LEFT JOIN mst.t_ekpedisi g
							ON  g.kd_ekspedisi = a.kd_ekspedisi
						where a.no_do = '$no_do'
						and d.kd_lokasi = e.kd_lokasi
						and d.kd_lokasi = f.kd_lokasi 
						and e.kd_blok = f.kd_blok ";
		
		$query_detail = $this->db->query($sql_detail);
		$data['detail'] = $query_detail->result();
		
		// print_r($this->db->last_query());exit;
		return $data;
	}
	
	public function get_hpp_by_kd_produk($kd_produk = '',$no_po = ''){
		$this->db->select("f.kd_peruntukan, e.dpp_po, a.pct_margin, a.rp_cogs, a.rp_ongkos_kirim,(SELECT COALESCE(sum(qty_oh),0,sum(qty_oh)) qty_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk),pkp",FALSE);
		$this->db->join("mst.t_supp_per_brg c","c.kd_produk = a.kd_produk");
		$this->db->join("mst.t_supplier d","d.kd_supplier = c.kd_supplier");
		$this->db->join("purchase.t_purchase_detail e","e.kd_produk = a.kd_produk");
		$this->db->join("purchase.t_purchase f","f.no_po = e.no_po");
		$this->db->where("a.kd_produk",$kd_produk);
		$this->db->where("e.no_po",$no_po);
		$query = $this->db->get("mst.t_produk a");
		$row = array();
		if($query->num_rows() != 0){
			$row = $query->row();
		}
		
		return $row;
	}
	
	public function select_cogs($kd_produk = ''){
		$this->db->select("rp_cogs,(SELECT COALESCE(sum(qty_oh),0,sum(qty_oh)) qty_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk)",FALSE);
		$this->db->where('a.kd_produk',$kd_produk);
		$query = $this->db->get('inv.t_hpp_inventory a');
		
		$row = array();
		if($query->num_rows() != 0){
			$row = $query->row();
		}
		
		return $row;
	}
	
	public function get_rows_lokasi($kd_produk = '', $search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
		}

		$sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, d.nama_lokasi || '-' || c.nama_blok || '-' || b.nama_sub_blok nama_sub,
					a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_sub_blok,  c.nama_blok, d.nama_lokasi, b.kapasitas,
					CASE WHEN d.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
					FROM mst.t_produk_lokasi a
					JOIN mst.t_sub_blok b
						ON b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					JOIN mst.t_blok c
						ON c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
					JOIN mst.t_lokasi d
						ON d.kd_lokasi = a.kd_lokasi
					WHERE a.kd_produk = '$kd_produk' 
					".$sql_search."
					LIMIT ".$length." OFFSET ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					".$sql_search."";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_stok_inventory($kd_produk = ''){
		$this->db->where("kd_produk",$kd_produk);
		$query = $this->db->get("inv.t_brg_inventory");
		$result = FALSE;
		
		if($query->num_rows() > 0){
			$result = TRUE;
		}
		
		return $result;
		
	}
	public function get_hpp_inventory($kd_produk = ''){
		$this->db->where("kd_produk",$kd_produk);
		$query = $this->db->get("inv.t_hpp_inventory");
		$result = FALSE;
		
		if($query->num_rows() > 0){
			$result = TRUE;
		}
		
		return $result;
		
	}
	
	public function get_sum_qty_terima($no_po = '', $kd_produk = ''){
		$sql = "SELECT SUM(qty_terima) 
				FROM purchase.t_dtl_receive_order 
				WHERE no_po = '$no_po'
				AND kd_produk = '$kd_produk'";
		$query = $this->db->query($sql);
		$result = FALSE;
		$row = '';
		if($query->num_rows() > 0){
			$row = $query->row();
		}
		
		return $row;
		
	}
	
	public function update_row_hpp($kd_peruntukan = '', $kd_produk = '',$datau = ''){
		$this->db->where('kd_peruntukan',$kd_peruntukan);
		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('inv.t_hpp_inventory',$datau);
	}
	
	public function update_row_produk($kd_peruntukan = '', $kd_produk = '',$hpp_cogs ='', $hpp_het = ''){
		if($kd_peruntukan == 0){
			$datau = array(
				'rp_het_cogs' => $hpp_het,
				'rp_cogs' => $hpp_cogs
			);
		}else{
			$datau = array(
				'rp_het_cogs_dist' => $hpp_het,
				'rp_cogs_dist' => $hpp_cogs				
			);
		}
		$this->db->where('kd_produk',$kd_produk);
		return $this->db->update('mst.t_produk',$datau);
	}
}