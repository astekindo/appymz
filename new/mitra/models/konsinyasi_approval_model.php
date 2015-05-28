<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_approval_model extends MY_Model {
	
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
	public function get_rows($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search =  "(lower(no_ro) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
		$this->db->select('no_ro,tgl_ro,subject,nama_supplier');
        $this->db->where('a.status','0');
        $this->db->where('konsinyasi','1');
        $this->db->order_by('tgl_ro','DESC');
		$this->db->join('mst.t_supplier b','a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_purchase_request a',$length,$offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT no_ro
					FROM purchase.t_purchase_request) as tabel limit 1";
        
        $query = $this->db->query($sql2);
		
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
		if($search != ""){
			$sql_search =  "  (lower(a.no_ro) = '" . strtolower($search) . "') AND ";
		}

		$sql1 = "SELECT  b.no_ro, b.kd_produk, nama_produk, c.min_stok, c.max_stok, b.qty, b.qty_po,
					b.qty_adj, b.keterangan, d.nm_satuan, 
					coalesce(sum(e.qty_oh), 0,sum(e.qty_oh)) jml_stok,c.min_order, 
					case when c.is_kelipatan_order = 1 then 'YA' else 'TIDAK' end is_kelipatan_order 
					FROM purchase.t_purchase_request a
					JOIN purchase.t_dtl_purchase_request b
						ON b.no_ro=a.no_ro 
					JOIN mst.t_produk c 
						ON c.kd_produk=b.kd_produk
					JOIN mst.t_satuan d
						ON d.kd_satuan=c.kd_satuan
					LEFT JOIN 
						inv.t_brg_inventory e ON e.kd_produk = b.kd_produk
					WHERE ".$sql_search." 
						a.status='0' 
						AND a.konsinyasi = '1'
					GROUP BY 
						b.no_ro, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty, b.qty_po, 
						b.qty_adj, b.keterangan, d.nm_satuan, c.min_order, is_kelipatan_order";
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT  nama_produk, b.qty, b.qty_po, d.nm_satuan
					FROM purchase.t_purchase_request a, purchase.t_dtl_purchase_request b, mst.t_produk c, mst.t_satuan d
					WHERE ".$sql_search."
					b.no_ro=a.no_ro AND c.kd_produk=b.kd_produk AND d.kd_satuan=c.kd_satuan) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}

	public function update_row($kd1 = NULL, $data = NULL){
		$this->db->where("no_ro",$kd1);
		$this->db->where("konsinyasi",'1');
		return $this->db->update('purchase.t_purchase_request', $data);
		print_r($this->db->last_query());
		// $this->db->flush_cache();
		
		// $this->db->where("no_ro",$kd1);
		// $this->db->where("status",'0');
		// return $this->db->update('purchase.t_dtl_purchase_request', $data);
	}
	
	public function update_row_detail($kd1 = NULL, $kd2 = NULL, $data = NULL){
		$this->db->where("no_ro",$kd1);
		$this->db->where("kd_produk",$kd2);
		$where = "status <> '9'";
		$this->db->where($where);
		return $this->db->update('purchase.t_dtl_purchase_request', $data);
	}
}