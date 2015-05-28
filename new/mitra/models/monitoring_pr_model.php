<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_pr_model extends MY_Model {
	
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
	 
	public function get_rows($kdSupplier = "", $tglAwal = "", $tglAkhir = "", $status = "", $close_ro = "", $konsinyasi = "",$peruntukan_sup = "",$peruntukan_dist="", $search = "", $offset, $length){
		$sql_search = "";
		$where = "";
		$left = " left ";
		if($kdSupplier != ""){
			$where .=  " AND a.kd_supplier = '$kdSupplier' ";
			$left = " ";
		}
		
		if($status != "" && $status != "A"){
			$where .=  " AND a.status = '$status' ";
			$left = " ";
		}
		
		if($close_ro != "" && $close_ro != "A"){
			$where .=  " AND a.close_ro = '$close_ro' ";
			$left = " ";
		}
		
		if($konsinyasi != "" && $konsinyasi != "A"){
			$where .=  " AND a.konsinyasi = '$konsinyasi' ";
			$left = " ";
		}
       
		if($tglAwal != "" && $tglAkhir != ""){
			$where .=  " AND a.tgl_ro between '$tglAwal' AND '$tglAkhir' ";
		}
                if($peruntukan_sup == "0"){
			$where .=  " AND a.kd_peruntukan = '$peruntukan_sup' ";
                }
                if($peruntukan_dist == "1"){
			$where .=  " AND a.kd_peruntukan = '$peruntukan_dist' ";
                }
		if($search != ""){
			$sql_search =  " AND ((lower(a.no_ro) LIKE '%" . strtolower($search) . "%') OR (a.subject LIKE '%" . strtolower($search) . "%') OR (a.subject LIKE '%" . $search . "%'))";
			$this->db->where($sql_search);
		}
        // $this->db->where('status','0');
		$sql = "select a.no_ro, a.subject, a.tgl_ro, d.no_po, e.tanggal_po,
					case a.status 
                                            when '0' then 'Belum Approve' 
                                            when '1' then 'Approve Ass Manager' 
                                            when '2' then 'Approve Manager' 
                                            when '9' then
                                                case a.keterangan2 
                                                    when 'X' then 'Reject Manager' 
                                                    else 'Reject Ass Manager' 
                                                end    
                                        end status_ro, 
					case a.close_ro when '2' then 'Reject' when '0' then 'Open' when '1' then 'Close' else '-' end is_close_ro,
					a.kd_supplier, c.nama_supplier, case a.konsinyasi when '0' then 'NORMAL' when '1' then 'KONSINYASI' end type_purchase,
                                        CASE WHEN a.kd_peruntukan ='1' THEN 'Distribusi' ELSE 'Supermarket' END peruntukan
					from purchase.t_purchase_request a
					join purchase.t_dtl_purchase_request f on a.no_ro = f.no_ro
					left join mst.t_supplier c on a.kd_supplier = c.kd_supplier
					left join purchase.t_purchase_detail d on d.no_ro = a.no_ro
					left join purchase.t_purchase e on e.no_po = d.no_po
					WHERE 1=1
					".$sql_search."
					".$where."
					order by a.tgl_ro desc
					limit ".$length." offset ".$offset."";
        $query = $this->db->query($sql);
	//print_r($this->db->last_query());exit;	
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select a.no_ro, a.subject, a.tgl_ro, d.no_po, e.tanggal_po,
					case a.status when '0' then 'Belum Approve' when '1' then 'Approve Ass Manager' when '2' then 'Approve Manager' end status_ro, 
					case a.close_ro when '0' then 'Open' when '1' then 'Close' else '-' end is_close_ro,
					a.kd_supplier, c.nama_supplier, case a.konsinyasi when '0' then 'NORMAL' when '1' then 'KONSINYASI' end type_purchase
					from purchase.t_purchase_request a
					join purchase.t_dtl_purchase_request f on a.no_ro = f.no_ro
					left join mst.t_supplier c on a.kd_supplier = c.kd_supplier
					left join purchase.t_purchase_detail d on d.no_ro = a.no_ro
					left join purchase.t_purchase e on e.no_po = d.no_po
					WHERE 1=1
					".$sql_search."
					".$where."
					order by a.tgl_ro desc) as tabel limit 1";
        
        $query = $this->db->query($sql2);
		// print_r($this->db->last_query());exit;
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
	
	public function get_data_html($no_ro = ''){
		$this->db->select('a.*,b.nama_supplier,b.alamat,b.pic');
		$this->db->where("a.no_ro", $no_ro);
		$this->db->join("mst.t_supplier b", "b.kd_supplier = a.kd_supplier");
		$query = $this->db->get("purchase.t_purchase_request a");
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$this->db->select('a.*,b.nama_produk,c.nm_satuan');
		$this->db->where("a.no_ro", $no_ro);
		$this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
		$this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
		$query_detail = $this->db->get("purchase.t_dtl_purchase_request a");
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
	
	

}
