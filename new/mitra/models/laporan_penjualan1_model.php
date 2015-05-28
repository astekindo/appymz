<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_penjualan1_model extends MY_Model {
	
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
	public function get_data_penjualan1_print($dari_tgl = '',$sampai_tgl = '',$kd_member = '',$kd_user = '',$kd_shift = ''){

        $where = '';
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
            $sql = " select * from report.v_lap_penjualan1 where 1=1 ".$where." limit 2";
					
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		// print_r($this->db->last_query());
		return $data;
	}
        
       
}
