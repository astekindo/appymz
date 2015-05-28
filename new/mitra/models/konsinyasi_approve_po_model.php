<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_approve_po_model extends MY_Model {
	
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
			$sql_search =  "(lower(no_po) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
        $this->db->join('mst.t_supplier b','b.kd_supplier = a.kd_suplier_po');
        $this->db->where('approval_po','0');
        $this->db->where('konsinyasi','1');
        $this->db->order_by('tanggal_po','DESC');
        $query = $this->db->get('purchase.t_purchase a',$length,$offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from purchase.t_purchase where approval_po = '0' AND konsinyasi ='1' limit 1";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
	
	
	public function get_rows_detail($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search =  "  (lower(a.no_po) = '" . strtolower($search) . "') AND ";
		}

		$sql1 = "SELECT b.kd_produk,c.nama_produk, a.no_po, b.qty_po, b.disk_persen_supp1_po, b.disk_persen_supp2_po, 
					b.disk_persen_supp3_po, b.disk_persen_supp4_po, b.disk_amt_supp1_po, b.disk_amt_supp2_po, 
					b.disk_amt_supp3_po, b.disk_amt_supp4_po, COALESCE(b.disk_amt_supp5_po, 0, b.disk_amt_supp5_po) as disk_amt_supp5_po,
					b.price_supp_po, 
					b.net_price_po, b.dpp_po, b.rp_disk_po, b.rp_jumlah_po, b.rp_total_po, 
					b.approval_po
					FROM purchase.t_purchase a, purchase.t_purchase_detail b, mst.t_produk c
					WHERE ".$sql_search." a.approval_po='0' AND a.konsinyasi ='1' AND
					b.no_po=a.no_po AND c.kd_produk=b.kd_produk ";
					// limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total
					FROM purchase.t_purchase a, purchase.t_purchase_detail b, mst.t_produk c
					WHERE ".$sql_search." a.approval_po='0' AND
					b.no_po=a.no_po AND c.kd_produk=b.kd_produk 
					";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $rows;
	}
	
	
	public function update_row($kd1 = NULL, $data = NULL){
		$this->db->where("no_po",$kd1);
		$this->db->where("konsinyasi",'1');
		return $this->db->update('purchase.t_purchase', $data);
	}
}