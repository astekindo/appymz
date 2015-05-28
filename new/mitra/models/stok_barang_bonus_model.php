<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_barang_bonus_model extends MY_Model {
	
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
			$sql_search = " AND (lower(nama_lokasi) LIKE '%" . strtolower($search) . "%')";
		}

		// $sql1 = "SELECT a.id_sub_blok, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas
		//             FROM tm_sub_blok a
		// 			join tm_blok b on b.kd_blok = a.kd_blok and b.kd_lokasi = a.kd_lokasi
		// 			join tm_lokasi c on c.kd_lokasi = b.kd_lokasi
		// 			WHERE a.aktif is true
		// 			".$sql_search."
		// 			LIMIT ".$length." offset ".$offset;
        
		$sql1 = "select d.nama_lokasi || ' - ' || e.nama_blok  || ' - ' || f.nama_sub_blok lokasi, a.kd_produk, b.nama_produk, a.qty_oh, c.nm_satuan 
						from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
						where a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan
						and a.kd_lokasi = d.kd_lokasi
						and a.kd_blok = e.kd_blok
						and e.kd_lokasi = d.kd_lokasi
						and f.kd_lokasi = d.kd_lokasi
						and f.kd_blok = e.kd_blok
						and a.kd_sub_blok = f.kd_sub_blok
                                                and a.kd_lokasi = '".KD_LOKASI."'
                                                and a.kd_blok = '".KD_BLOK."'
                                                and a.kd_sub_blok = '".KD_SUB_BLOK."'
						".$sql_search." order by nama_lokasi LIMIT ".$length." offset ".$offset;

        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) AS total FROM (select d.nama_lokasi || ' - ' || e.nama_blok  || ' - ' || f.nama_sub_blok lokasi, a.kd_produk, b.nama_produk, a.qty_oh, c.nm_satuan 
						from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
						where a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan
						and a.kd_lokasi = d.kd_lokasi
						and a.kd_blok = e.kd_blok
						and e.kd_lokasi = d.kd_lokasi
						and f.kd_lokasi = d.kd_lokasi
						and f.kd_blok = e.kd_blok
						and a.kd_sub_blok = f.kd_sub_blok
                                                and a.kd_lokasi = '".KD_LOKASI."'
                                                and a.kd_blok = '".KD_BLOK."'
                                                and a.kd_sub_blok = '".KD_SUB_BLOK."'
						".$sql_search.") as tabel";
        
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
        $sql = "select d.nama_lokasi || ' - ' || e.nama_blok  || ' - ' || f.nama_sub_blok lokasi, a.kd_produk, b.nama_produk, a.qty_oh, c.nm_satuan 
						from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
						where a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan
						and a.kd_lokasi = d.kd_lokasi
						and a.kd_blok = e.kd_blok
						and a.kd_sub_blok = f.kd_sub_blok
						and a.is_bonus = 1
						and lokasi = $id
					";
		print_r($this->db->last_query());
        $query = $this->db->query($sql);

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