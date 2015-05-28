<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konsinyasi_approve_surat_pesanan_model extends MY_Model {
	
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
			$sql_search =  "(lower(no_sp) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search);
		}
                $this->db->join('mst.t_supplier b','b.kd_supplier = a.kd_suplier');
                $this->db->where('approval_sp','0');
                $this->db->where('konsinyasi','1');
                $this->db->order_by('tgl_sp','DESC');
                $query = $this->db->get('purchase.t_surat_pesanan a',$length,$offset);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from purchase.t_surat_pesanan where approval_sp = '0' AND konsinyasi ='1' limit 1";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
	
	
	public function get_rows_detail($search = "", $offset, $length){
		
		$sql1 = "SELECT b.kd_produk,c.nama_produk, b.no_sp, b.qty_sp
					FROM  purchase.t_surat_pesanan_detail b, mst.t_produk c
					WHERE b.no_sp = '$search' and 
					c.kd_produk=b.kd_produk ";
					// limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total
					FROM  purchase.t_surat_pesanan_detail b, mst.t_produk c
					WHERE b.no_sp = '$search' and 
					c.kd_produk=b.kd_produk
					";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $rows;
	}
	
	
	public function update_row($kd1 = NULL, $data = NULL){
		$this->db->where("no_sp",$kd1);
		$this->db->where("konsinyasi",'1');
		return $this->db->update('purchase.t_surat_pesanan', $data);
	}
}