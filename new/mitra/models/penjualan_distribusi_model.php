<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_distribusi_model extends MY_Model {
	
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
        
        public function search_sales($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(kd_sales) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select * from mst.t_sales  " . $sql_search . "  order by kd_sales desc
		limit " . $length . " offset " . $offset;
                 

        $query = $this->db->query($sql1);
        //print_r($query);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_sales";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_data_print($no_so){
		
		$sql = "select 'SALES ORDER (DISTRIBUSI) FORM' title, a.*, b.nama_pelanggan,b.npwp,b.is_pkp,b.nama_pic, c.nama_sales from sales.t_sales_order_dist a, mst.t_pelanggan_dist b ,mst.t_sales c
			where a.no_so = '$no_so'
			 and a.kd_member = b.kd_pelanggan
                           and a.kd_sales = c.kd_sales";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		
		$sql = "SELECT a.*,b.*,nama_produk,nm_satuan
				FROM sales.t_sales_order_dist a
				JOIN sales.t_sales_order_dist_detail b
					ON a.no_so = b.no_so
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
                                JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan       
				WHERE a.no_so = '$no_so'";
				
		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}
	
}