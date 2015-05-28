<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_receive_order_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	public function get_ro($no_ro){
		$this->db->where("a.no_do", $no_ro);
		$this->db->join("mst.t_supplier b", "b.kd_supplier = a.kd_supplier");
		$query = $this->db->get("purchase.t_receive_order a");
		
		$row = array();
		if($query->num_rows() > 0){
			$row = $query->row();
			$result = '{success:true,data:'.json_encode($row).'}';
		}else{
			$result = '{"success":false,"errMsg":"Data tidak ditemukan"}';
		}        
		
        return $result;
	}
	
	public function get_ro_detail($no_ro){
		$this->db->select("a.*,e.nama_produk,f.nm_satuan,g.nama_ekspedisi,h.nm_satuan as nm_satuan_eksp,
		a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, d.nama_lokasi || '-' || c.nama_blok || '-' || b.nama_sub_blok nama_sub,
		");
		$this->db->where("no_do", $no_ro);
		$this->db->join("mst.t_sub_blok b", "b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_blok c", "c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_lokasi d", "d.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_produk e", "e.kd_produk = a.kd_produk");
		$this->db->join("mst.t_satuan f", "f.kd_satuan = e.kd_satuan", "left");
		$this->db->join("mst.t_ekpedisi g", "g.kd_ekspedisi = a.kd_ekspedisi", "left");
		$this->db->join("mst.t_satuan h", "e.kd_satuan = a.kd_satuan_ekspedisi", "left");
		$query = $this->db->get("purchase.t_dtl_receive_order a");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
}