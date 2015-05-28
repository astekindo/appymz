<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_order_model extends MY_Model {
	
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
	public function get_rows($kdSupplier = "", $tglAwal = "", $tglAkhir = "", $search = "", $offset, $length){
		$sql_search = "";
		$where = "";
		if($kdSupplier != ""){
			$where .=  " AND b.kd_supplier = '$kdSupplier' ";
		}
       
		if($tglAwal != "" && $tglAkhir != ""){
			$where .=  " AND b.tanggal_po between '$tglAwal' AND '$tglAkhir') ";
		}
       
		if($search != ""){
			$sql_search =  " AND (lower(a.no_ro) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
        // $this->db->where('status','0');
		$sql = "select a.no_ro, a.subject, a.tgl_ro, a.status status_ro, a.close_ro is_close_ro,
					b.no_po, b.kd_suplier_po kd_supplier, c.nama_supplier, b.tanggal_po, b.approval_po status_po, b.close_po is_close_po,
					d.no_do, d.tanggal tanggal_do  
					from purchase.t_purchase_request a
					left join purchase.t_purchase b on a.no_ro = b.no_ro
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_receive_order d on d.no_po = b.no_po
					".$sql_search."
					where a.konsinyasi = '0' 
					".$where." 
					order by a.tgl_ro desc
					limit ".$length." offset ".$offset."";
        $query = $this->db->query($sql);

		// print_r($this->db->last_query());exit;

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select a.no_ro, a.subject, a.tgl_ro, a.status status_ro, a.close_ro is_close_ro,
					b.no_po, b.kd_suplier_po kd_supplier, c.nama_supplier, b.tanggal_po, b.approval_po status_po, b.close_po is_close_po,
					d.no_do, d.tanggal tanggal_do  
					from purchase.t_purchase_request a
					left join purchase.t_purchase b on a.no_ro = b.no_ro
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_receive_order d on d.no_po = b.no_po
					".$sql_search."
					where a.konsinyasi = '0'
					order by a.tgl_ro desc) as tabel limit 1";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}

}
