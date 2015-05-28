<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approve_po_model extends MY_Model {
	
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
	public function get_rows($kd_peruntukan = "", $search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search =  "((lower(no_po) LIKE '%" . strtolower($search) . "%') OR (lower(nama_supplier) LIKE '%" . strtolower($search) . "%'))";
			$this->db->where($sql_search);
		}
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $this->db->where('a.kd_peruntukan', $kd_peruntukan);
        }      
        $this->db->join('mst.t_supplier b','b.kd_supplier = a.kd_suplier_po');
        $this->db->where('approval_po','0');
        $this->db->where('konsinyasi','0');
        $this->db->order_by('tanggal_po','DESC');
        $query = $this->db->get('purchase.t_purchase a',$length,$offset);
	//print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select("count(*) as total");
		if($search != ""){
			$sql_search =  "(lower(no_po) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
        $this->db->where('approval_po','0');
        $this->db->where('konsinyasi','0');
        $query = $this->db->get('purchase.t_purchase');
		
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

		$sql1 = "SELECT b.kd_produk,nama_produk, a.no_po, b.qty_po, b.disk_persen_supp1_po, b.disk_persen_supp2_po, 
					b.disk_persen_supp3_po, b.disk_persen_supp4_po, b.disk_amt_supp1_po, b.disk_amt_supp2_po, 
					b.disk_amt_supp3_po, b.disk_amt_supp4_po, COALESCE(b.disk_amt_supp5_po, 0, b.disk_amt_supp5_po) as disk_amt_supp5_po,
					b.price_supp_po, 
					b.net_price_po, b.dpp_po, b.rp_disk_po, b.rp_jumlah_po, b.rp_total_po, 
					b.approval_po
					FROM purchase.t_purchase a, purchase.t_purchase_detail b, mst.t_produk c
					WHERE ".$sql_search." a.approval_po='0' AND a.konsinyasi ='0' AND
					b.no_po=a.no_po AND c.kd_produk=b.kd_produk
					ORDER BY nama_produk"; 
					// limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		// print_r($this->db->last_query());
		// exit;
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
	
	public function get_jumlah_po($no_po){
		$sql1 = "SELECT b.qty_po, b.no_ro, b.kd_produk
					FROM purchase.t_purchase a, purchase.t_purchase_detail b, mst.t_produk c
					WHERE a.no_po = '$no_po'
					AND a.approval_po='0' AND a.konsinyasi ='0' AND
					b.no_po=a.no_po AND c.kd_produk=b.kd_produk";
        
        $query = $this->db->query($sql1);
		// print_r($this->db->last_query());
		// exit;
		$row = array();
		if($query->num_rows() > 0){
			$row = $query->result();
		}
        
        return $row;
	}
	
	
	public function update_qty_po($qty = NULL, $no_pr = NULL, $kd_produk = NULL){
		$sqlUpdate = "UPDATE purchase.t_dtl_purchase_request 
						SET qty_po = qty_po - ".$qty." 
						WHERE no_ro = '$no_pr' and kd_produk ='$kd_produk'";
		return $this->db->query($sqlUpdate);
		// print_r($this->db->last_query());
	}
	
	public function update_row($kd1 = NULL, $data = NULL){
		$this->db->where("no_po",$kd1);
		$this->db->where("konsinyasi",'0');
		return $this->db->update('purchase.t_purchase', $data);
	}
}