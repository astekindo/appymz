<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_uang_muka_po_model extends MY_Model { 
	
	public function __construct(){
		parent::__construct();
	}
	
	public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
	}
	
	public function update_invoice($no_invoice, $rp_sisa_invoice, $rp_bayar){
		if($rp_sisa_invoice <= 0){
			$sql = "UPDATE purchase.t_invoice SET status=2, rp_pelunasan_hutang=rp_pelunasan_hutang+" . $rp_bayar . " WHERE no_invoice='" . $no_invoice . "'";
		}else{
			$sql = "UPDATE purchase.t_invoice SET rp_pelunasan_hutang=rp_pelunasan_hutang+" . $rp_bayar . " WHERE no_invoice='" . $no_invoice . "'";;
		}
		$this->db->flush_cache();
	
		return $this->db->query($sql);
	}
	
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
            
                $sql = "select a.no_po, a.tanggal_po, a.rp_jumlah_po, a.rp_ppn_po, a.rp_diskon_po, a.rp_total_po, coalesce(a.rp_dp, 0) rp_pembayaran_uang_muka_po,b.kd_supplier,c.no_do
                        from purchase.t_purchase a  
                        join mst.t_supplier b on a.kd_suplier_po = b.kd_supplier
                        left join purchase.t_dtl_receive_order c on a.no_po = c.no_po
                        where a.approval_po = '1'
                        and b.kd_supplier = '$kd_supplier'
                        and c.no_do is null";
	
		 $query = $this->db->query($sql);
               //  print_r($query);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        public function query_update($sql = "") {
            return $this->db->query($sql);
    }
}
