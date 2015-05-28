<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_mutasi_stok_lengkap_model extends MY_Model {
	
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
        public function search_gudang($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nama_lokasi) LIKE '%" . strtolower($search) . "%' )";
        }

      $sql1 = "select kd_lokasi,nama_lokasi from mst.t_lokasi order by kd_lokasi desc
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_lokasi";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
      
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_mutasi_stok_lengkap_print($dari_tgl = '',$sampai_tgl = '',$kd_member = '',$kd_user = '',$kd_shift = ''){
           
            if($dari_tgl != "" && $sampai_tgl != ""){
                        $dari_tgl = date('Y-m-d', strtotime($dari_tgl));
                        $sampai_tgl = date('Y-m-d', strtotime($sampai_tgl));
			$where .=  " AND tgl between '$dari_tgl' AND '$sampai_tgl' ";
		}
                
            if($kd_user != ""){
			$where .=  " AND kd_user = '$kd_user' ";
			
                        
            }
            if($kd_shift != ""){
			$where .=  " AND kd_shift = '$kd_shift' ";
			
                        
            }
            if($kd_member != ""){
			$where .=  " AND kd_member = '$kd_member' ";
			
                        
            }
            $sql = " select * from report.v_lap_penjualan1 limit 2";
					
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		// print_r($this->db->last_query());
		return $data;
	}
        
       
}
