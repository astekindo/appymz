<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_setting_stock_model extends MY_Model {
	
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
	public function get_rows_detail(){
		$sql1 = "SELECT a.kd_produk,a.approval_ops, a.approval_buyer,b.nama_produk,a.pct_alert,round(a.stok_min+(a.pct_alert/100 * a.stok_min)) 
			limit_stok, a.stok_min,a.stok_max,a.max_order, coalesce(c.qty_oh, 0) qty_oh, coalesce(c.qty_oh, 0) qty_oh_so, case when a.is_kelipatan = 1 then 'YA' else 'TIDAK' end is_kelipatan_order
			FROM inv.t_stok_setting a, mst.t_produk b 
			left join inv.t_brg_inventory c on b.kd_produk = c.kd_produk
			where a.kd_produk = b.kd_produk and a.approval_ops = '0'";
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		// print_r($this->db->last_query());exit;
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT a.kd_produk,a.approval_ops, a.approval_buyer,b.nama_produk,b.pct_alert,a.stok_min,a.stok_max,a.max_order
				    FROM inv.t_stok_setting a, mst.t_produk b where a.kd_produk = b.kd_produk and a.approval_ops = '0') as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function update_row_detail($kd = NULL,$data = NULL){
		$this->db->where("kd_produk",$kd);
		return $this->db->update('inv.t_stok_setting', $data);
	}
}
