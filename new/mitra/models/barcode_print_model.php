<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barcode_print_model extends MY_Model {
	
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
	public function get_rows($kd_supplier,$search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search =  "(lower(no_do) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
		$this->db->select('no_do,tanggal, tanggal_terima,no_bukti_supplier,a.kd_supplier,nama_supplier');
        $this->db->where('a.kd_supplier',$kd_supplier);
        $this->db->where('a.konsinyasi','0');
		$this->db->join('mst.t_supplier b','a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_receive_order a');
				
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		
		$this->db->select('count(*) as total');
		$sql_search = "";
		if($search != ""){
			$sql_search =  "(lower(no_do) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
        $this->db->where('a.kd_supplier',$kd_supplier);
        $this->db->where('a.konsinyasi','0');
		$this->db->join('mst.t_supplier b','a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_receive_order a');
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
		return $results;
		
	}
	
	
	public function get_rows_detail($search = ""){
		$sql_search = "";
		$sql_search =  "  AND (lower(a.no_do) = '" . strtolower($search) . "') ";	

		$sql1 = "SELECT  
					b.no_do, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty_beli, b.qty_terima, 
					b.keterangan,d.nm_satuan,
					coalesce(sum(e.qty_oh), 0,sum(e.qty_oh)) jml_stok
				FROM 
					purchase.t_receive_order a				
				JOIN 
					purchase.t_dtl_receive_order b ON b.no_do=a.no_do			
				JOIN 
					mst.t_produk c ON c.kd_produk=b.kd_produk
				JOIN 
					mst.t_satuan d ON d.kd_satuan=c.kd_satuan
				LEFT JOIN 
					inv.t_brg_inventory e ON e.kd_produk = b.kd_produk
				WHERE 1=1 ".$sql_search." 
				GROUP BY 
					b.no_do, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty_beli, b.qty_terima, 
					b.keterangan,d.nm_satuan";
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
						
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function search_receive_order($no_do = "", $search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(a.no_do) LIKE '%" . $search . "%') OR (a.no_do LIKE '%" . $search . "%'))", NULL);
		}
		
		$this->db->join("mst.t_supplier b","b.kd_supplier = a.kd_supplier");
		$this->db->join("purchase.t_dtl_receive_order c","c.no_do = a.no_do");
		$this->db->order_by("a.no_do");
		$query = $this->db->get("purchase.t_receive_order a", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
        if($search != ""){
			$this->db->where("((lower(a.no_do) LIKE '%" . $search . "%') OR (a.no_do LIKE '%" . $search . "%'))", NULL);
		}

		$this->db->select("count(*) AS total");
		$query = $this->db->get("purchase.t_receive_order a");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function search_produk_by_no_do($no_do = "", $search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(a.kd_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->join("mst.t_produk b","b.kd_produk = a.kd_produk");
		$this->db->join("mst.t_kategori1 c","c.kd_kategori1 = b.kd_kategori1");
		$this->db->join("mst.t_kategori2 d","d.kd_kategori2 = b.kd_kategori2");
		$this->db->join("mst.t_kategori3 e","e.kd_kategori3 = b.kd_kategori3");
		$this->db->join("mst.t_kategori4 f","f.kd_kategori4 = b.kd_kategori4");
		$this->db->join("mst.t_ukuran g","g.kd_ukuran = b.kd_ukuran","left");
		$this->db->where("no_do",$no_do);
		$this->db->where("d.kd_kategori1 = b.kd_kategori1");
		$this->db->where("e.kd_kategori1 = b.kd_kategori1");
		$this->db->where("e.kd_kategori2 = b.kd_kategori2");
		$this->db->where("f.kd_kategori1 = b.kd_kategori1");
		$this->db->where("f.kd_kategori2 = b.kd_kategori2");
		$this->db->where("f.kd_kategori3 = b.kd_kategori3");
		$this->db->order_by("no_do");
		$query = $this->db->get("purchase.t_dtl_receive_order a", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		// print_r($this->db->last_query());exit;
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
		if($search != ""){
			$this->db->where("((lower(a.kd_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->join("mst.t_produk b","b.kd_produk = a.kd_produk");
		$this->db->join("mst.t_kategori1 c","c.kd_kategori1 = b.kd_kategori1");
		$this->db->join("mst.t_kategori2 d","d.kd_kategori2 = b.kd_kategori2");
		$this->db->join("mst.t_kategori3 e","e.kd_kategori3 = b.kd_kategori3");
		$this->db->join("mst.t_kategori4 f","f.kd_kategori4 = b.kd_kategori4");
		$this->db->join("mst.t_ukuran g","g.kd_ukuran = b.kd_ukuran","left");
		$this->db->where("no_do",$no_do);
		$this->db->where("d.kd_kategori1 = b.kd_kategori1");
		$this->db->where("e.kd_kategori1 = b.kd_kategori1");
		$this->db->where("e.kd_kategori2 = b.kd_kategori2");
		$this->db->where("f.kd_kategori1 = b.kd_kategori1");
		$this->db->where("f.kd_kategori2 = b.kd_kategori2");
		$this->db->where("f.kd_kategori3 = b.kd_kategori3");
		$query = $this->db->get("purchase.t_dtl_receive_order a", $length, $offset);
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function get_data_print($no_do = '', $title = ''){
		/*
		$sql = "select 'RECEIVE ORDER' title, a.kd_supplier, b.nama_supplier, a.no_bukti_supplier, a.no_do, a.tanggal,
				a.tanggal_terima, a.created_by
				from purchase.t_receive_order a, mst.t_supplier b
				where a.no_do = '$no_do'
				and a.kd_supplier = b.kd_supplier";

		$query = $this->db->query($sql);
		
		// print_r($this->db->last_query());exit;
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		
		$sql_detail = "select a.no_po, a.kd_produk, b.kd_produk_supp, b.nama_produk, a.qty_terima,
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
		*/
	}
	
	public function update_sisa($no_do = '', $kd_produk = '', $jumlah = ''){
	
		$sql = "UPDATE purchase.t_dtl_receive_order SET jumlah_barcode = jumlah_barcode - " . $jumlah . " 
					WHERE no_do = '" . $no_do . "'
					AND kd_produk = '" . $kd_produk . "'";
		
		return $this->db->query($sql);
		// print_r($this->db->last_query());exit;
	}
}