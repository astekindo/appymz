<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_perbulan_per_model extends MY_Model {
	
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
	public function get_rows($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = " AND (lower(subject) LIKE '%" . strtolower($search) . "%')";
		}

		// $sql1 = "SELECT a.id_sub_blok, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas
		//             FROM tm_sub_blok a
		// 			join tm_blok b on b.kd_blok = a.kd_blok and b.kd_lokasi = a.kd_lokasi
		// 			join tm_lokasi c on c.kd_lokasi = b.kd_lokasi
		// 			WHERE a.aktif is true
		// 			".$sql_search."
		// 			LIMIT ".$length." offset ".$offset;
        
		$sql1 = "SELECT inv.t_rkp_inventory.kd_produk, inv.t_rkp_inventory.blth, mst.t_produk.nama_produk, 
					inv.t_rkp_inventory.qty_in, inv.t_rkp_inventory.qty_out, inv.t_rkp_inventory.qty_oh, 
					inv.t_rkp_inventory.qty_mutasi_in, inv.t_rkp_inventory.qty_mutasi_out, inv.t_rkp_inventory.qty_target, 
					inv.t_rkp_inventory.qty_adj_in, inv.t_rkp_inventory.qty_adj_out, mst.t_satuan.nm_satuan
				  FROM inv.t_rkp_inventory INNER JOIN mst.t_produk
					ON inv.t_rkp_inventory.kd_produk = mst.t_produk.kd_produk INNER JOIN mst.t_satuan 
					ON mst.t_produk.kd_satuan = mst.t_satuan.kd_satuan";

        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (SELECT inv.t_rkp_inventory.kd_produk, inv.t_rkp_inventory.blth, mst.t_produk.nama_produk, 
					inv.t_rkp_inventory.qty_in, inv.t_rkp_inventory.qty_out, inv.t_rkp_inventory.qty_oh, 
					inv.t_rkp_inventory.qty_mutasi_in, inv.t_rkp_inventory.qty_mutasi_out, inv.t_rkp_inventory.qty_target, 
					inv.t_rkp_inventory.qty_adj_in, inv.t_rkp_inventory.qty_adj_out, mst.t_satuan.nm_satuan
				  FROM inv.t_rkp_inventory INNER JOIN mst.t_produk
					ON inv.t_rkp_inventory.kd_produk = mst.t_produk.kd_produk INNER JOIN mst.t_satuan 
					ON mst.t_produk.kd_satuan = mst.t_satuan.kd_satuan) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
        $this->db->where("id_ro", $id);
        $query = $this->db->get('tt_receive_order');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_row($data = NULL){
		return $this->db->insert('tt_receive_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('id_ro', $id);
		return $this->db->update('tt_receive_order', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$this->db->where('id_ro', $id);
		return $this->db->delete('tt_receive_order');
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("id_ro", 'asc');
		$query = $this->db->get("tt_receive_order");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}