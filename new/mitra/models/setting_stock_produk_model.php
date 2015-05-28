<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_stock_produk_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('inv.t_stok_setting', $data);
	}
	
	public function update_row($kd_produk = NULL, $data = NULL){
		$this->db->where('kd_produk', $kd_produk);
		return $this->db->update('inv.t_stok_setting', $data); 
	}
	
	public function select_inventory($where = ""){
		$this->db->where($where);
		$query = $this->db->get('inv.t_stok_setting');
		
		$results = FALSE;
		
		if($query->num_rows() > 0 ){
			$results = TRUE;
		}
		
		return $results;
		
	}
	
	public function get_produk($search = "", $offset, $length){
		if($search != ""){
			$sql_search = "WHERE (lower(nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR kd_produk LIKE '%".$search."%'";
		}
		$query = $this->db->query("SELECT kd_produk,kd_produk_lama,nama_produk
									FROM mst.t_produk
									".$sql_search." 
									ORDER BY nama_produk ASC LIMIT ".$length." OFFSET ".$offset);
		$rows = $query->result();
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total
				FROM mst.t_produk
				".$sql_search;
        
        $query = $this->db->query($sql2);
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
		return $results;
		
	}
	
	public function get_row_kode_produk($kd_produk = ""){
		$sql = "SELECT *
				FROM  inv.t_stok_setting 
				WHERE kd_produk = '".$kd_produk."'";
		
        $query = $this->db->query($sql);
        
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
		
        return $row;
	}
}
