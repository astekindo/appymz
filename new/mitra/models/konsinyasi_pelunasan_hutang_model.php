<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_pelunasan_hutang_model extends MY_Model {
	 
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
	public function get_no_po($no_po = ''){
		if ($no_po != ''){
			$this->db->where("no_po",$no_po);
		}
		$this->db->select("no_po, kd_suplier_po, nama_supplier, rp_total_po");
		$this->db->join("mst.t_supplier b", "a.kd_suplier_po = b.kd_supplier");
		$this->db->order_by("no_po", 'asc');
		$this->db->where("konsinyasi", '1');
		$query = $this->db->get("purchase.t_purchase a");
		
		$rows = array();
		if($no_po == ''){
			if($query->num_rows() > 0){
				$rows = $query->result();
			}
		}else {
			if($query->num_rows() > 0){
				$rows = $query->row();
			}
		}
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}
        
        public function get_all_invoice($kd_supplier = "",$kd_peruntukan ="", $search = ""){
            if ($search != '') {
            $where = " AND ((lower(a.no_invoice) LIKE '%" . $search . "%') OR (a.no_invoice LIKE '%" . $search . "%'))";
            }
            if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $where = " AND a.kd_peruntukan = '$kd_peruntukan'";
              }
            $sql = "select *, (a.rp_total - a.rp_pelunasan_hutang) as sisa_invoice , b.rp_pelunasan_hutang as total_bayar
                    from purchase.t_invoice a, purchase.t_invoice b 
                    where a.status = '0' AND a.konsinyasi = '1' and a.no_invoice = b.no_invoice
                    AND a.no_invoice like '" . GET_INVOICE_KONSINYASI_REQUEST . "%' 
                    AND a.kd_supplier = '" . $kd_supplier . "' " . $where . "";

        $query = $this->db->query($sql);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
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
}
