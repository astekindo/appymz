<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_po_model extends MY_Model { 
	 
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
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_invoice($no_invoice, $rp_sisa_invoice, $rp_bayar){
		if($rp_sisa_invoice <= 0){
			$sql = "UPDATE purchase.t_invoice SET status=2, rp_pelunasan_hutang=rp_pelunasan_hutang+" . $rp_bayar . " WHERE no_invoice='" . $no_invoice . "'";
		}else{
			$sql = "UPDATE purchase.t_invoice SET rp_pelunasan_hutang=rp_pelunasan_hutang+" . $rp_bayar . " WHERE no_invoice='" . $no_invoice . "'";;
		}
		$this->db->flush_cache();
	
		return $this->db->query($sql);
	}
	
	

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_invoice($kd_supplier = ""){
		$this->db->select("t_invoice.*, (rp_total - rp_pelunasan_hutang) as sisa_invoice");
		$this->db->where("status", 0);
		$this->db->where("konsinyasi", '0');
		$this->db->where("kd_supplier", $kd_supplier);
		$this->db->order_by("no_invoice", 'asc');
		$query = $this->db->get("purchase.t_invoice");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        
        public function get_no_po_bysupplier($kd_supplier = ""){
            
                $sql = "select no_po, tanggal_po, rp_jumlah_po, rp_ppn_po, rp_diskon_po, rp_total_po, coalesce(rp_dp, 0) rp_pembayaran_po,kd_supplier
                        from purchase.t_purchase , mst.t_supplier
                        where approval_po = '1'
                        and kd_suplier_po = kd_supplier
                        and kd_supplier = '$kd_supplier'";
	
		 $query = $this->db->query($sql);
               //  print_r($query);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

}
