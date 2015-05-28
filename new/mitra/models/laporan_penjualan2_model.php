<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_penjualan2_model extends MY_Model {
	
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
        public function search_user($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.username) LIKE '%" . strtolower($search) . "%' )";
        }

      $sql1 = "select kd_user,username from secman.t_user order by kd_user desc
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from secman.t_user";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
      
    public function search_shift($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.username) LIKE '%" . strtolower($search) . "%' )";
        }

      $sql1 = "select no_open_saldo,username from sales.t_open_kasir order by no_open_saldo desc
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from secman.t_user";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function search_member($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nmmember) LIKE '%" . strtolower($search) . "%' )";
        }

      $sql1 = "select kd_member,nmmember from mst.t_member order by kd_member desc
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_member";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
	public function search_produk_by_supplier($kd_supplier = '', $search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = " AND ((lower(a.nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
		}
		$sql = "SELECT 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan, coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok
  				FROM
					mst.t_supp_per_brg s
				JOIN 
					mst.t_produk a ON s.kd_produk = a.kd_produk
					AND is_konsinyasi = '0' ".$sql_search." 
  				JOIN 
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN 
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
				WHERE 
					a.aktif=1
					AND 
					s.kd_supplier = '$kd_supplier'
					AND a.aktif_purchase = 1
				GROUP BY 
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan
				ORDER BY 
					a.nama_produk ASC";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_data_penjualan2_print($kd_kategori1 = '',$kd_kategori2 = ''){
            
            
		$sql = " select * from report.v_lap_penjualan2 limit 6 ";
			
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());
		if($query->num_rows() == 0) return FALSE;
		
		$data['detail'] = $query->result();
		
		return $data;
	}
        
       
}
