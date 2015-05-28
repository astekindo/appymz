<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_pelunasan_hutang_model extends MY_Model { 
	 
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
	public function get_all_invoice($kd_supplier = "",$kd_peruntukan ="", $search = ""){
            if ($search != '') {
            $search = " AND ((lower(a.no_invoice) LIKE '%" . $search . "%') OR (a.no_invoice LIKE '%" . $search . "%'))";
            }
            if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $where = " AND a.kd_peruntukan = '$kd_peruntukan'";
              }
            $sql = "select *, (a.rp_total - a.rp_pelunasan_hutang) as sisa_invoice , b.rp_pelunasan_hutang as total_bayar
                    from purchase.t_invoice a, purchase.t_invoice b 
                    where a.status = '0' AND a.konsinyasi = '0' and a.no_invoice = b.no_invoice
                    AND a.no_invoice like '" . GET_INVOICE_REQUEST . "%' 
                    AND a.kd_supplier = '$kd_supplier' " . $where . " ".$search."";

        $query = $this->db->query($sql);
        //print_r($this->db->last_query());exit;
		/*$this->db->select("t_invoice.*, (rp_total - rp_pelunasan_hutang) as sisa_invoice");
		$this->db->where("status", 0);
		$this->db->where("konsinyasi", '0');
		$this->db->where("kd_supplier", $kd_supplier);
		$this->db->order_by("no_invoice", 'asc');
		$query = $this->db->get("purchase.t_invoice");*/
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

}
