<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cetak_faktur_pajak_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
       public function search_uang_muka($kd_pelanggan = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select * from sales.t_uang_muka 
                        where is_pajak = '1' and kd_pelanggan = '$kd_pelanggan'";
                 
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        
        public function search_faktur_jual($kd_pelanggan = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select a.* from sales.t_faktur_jual a,mst.t_pelanggan_dist b
                        where a.kd_pelanggan = b.kd_pelanggan and a.kd_pelanggan = '$kd_pelanggan' 
                        and a.is_pajak = '1'";
                  
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
      public function get_rows($no_faktur = "", $no_bayar_uang_muka = "",$kd_pelanggan = "", $search = "", $offset, $length) {
        $sql_search = "";
        if ($no_faktur != "") {
            $where .= " AND a.no_faktur = '$no_faktur' ";
        }
        if ($kd_pelanggan != "") {
            $where .= " AND a.kd_pelanggan = '$kd_pelanggan' ";
        }
        if ($no_bayar_uang_muka != "") {
            $where .= " AND a.no_bayar_uang_muka = '$no_bayar_uang_muka' ";
        }
        if ($search != "") {
            
            $sql_search = " AND (lower(no_faktur_pajak) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select a.*,b.nama_pelanggan from sales.t_faktur_pajak a,mst.t_pelanggan_dist b
                where a.kd_pelanggan = b.kd_pelanggan
                " . $sql_search . "
		" . $where . "
		limit " . $length . " offset " . $offset . "";
        
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
         $this->db->flush_cache();
         $sql2 = "select count(*) as total from (select a.*,b.nama_pelanggan from sales.t_faktur_pajak a,mst.t_pelanggan_dist b
                where a.kd_pelanggan = b.kd_pelanggan
                " . $sql_search . "
		" . $where . "
                    ) as tabel limit 1";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }
}
