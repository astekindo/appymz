<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blok_lokasi_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get_rows($search = "", $offset, $length){
		if($search != ""){
			$sql_search = "(lower(a.nama_blok) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$this->db->select("a.kd_lokasi || a.kd_blok blok,  b.nama_lokasi || ' - ' || a.nama_blok nm_blok, b.nama_lokasi2 || ' - ' || a.nama_blok2 nm_blok2, a.kd_blok, a.kd_lokasi, b.nama_lokasi, a.nama_blok", FALSE);
		$this->db->join("mst.t_lokasi b", "b.kd_lokasi = a.kd_lokasi");
		$this->db->where('a.aktif', 'TRUE');
		$this->db->order_by("a.kd_blok", "desc");

		$query = $this->db->get("mst.t_blok a", $length, $offset);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(lower(nama_blok) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$this->db->join("mst.t_lokasi b", "b.kd_lokasi = a.kd_lokasi");
		$query = $this->db->get("mst.t_blok a");

		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_row($id = NULL,$id1 = NULL){
		$this->db->select("*, CASE WHEN aktif IS true THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_lokasi", $id1);
        $this->db->where("kd_blok", $id);
        $query = $this->db->get('mst.t_blok');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}

	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_blok', $data);
	}

	public function update_row($id = NULL, $id1 = NULL, $data = NULL){
		$this->db->where('kd_lokasi', $id1);
		$this->db->where('kd_blok', $id);
		return $this->db->update('mst.t_blok', $data);
	}

	public function delete_row($id = NULL, $id1 = NULL){
		$this->db->where('kd_blok', $id);
		$this->db->where('kd_lokasi', $id1);
		$data = array(
			'aktif' => '0'
		);
		return $this->db->update('mst.t_blok', $data);
	}

	public function get_all($peruntukan){
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
		if($peruntukan !== 2) {
            $this->db->where('kd_peruntukan',"$peruntukan");
        }
        $this->db->where('aktif', 'true');
        $this->db->stop_cache();

        $results['total'] = $this->db->count_all_results('mst.t_lokasi');

		$this->db->order_by('kd_lokasi', 'asc');
		$query = $this->db->get('mst.t_lokasi');
        $results['lq'] = $this->db->last_query();
		if($results['total'] > 0){
	        $results['data'] = $query->result();
		}

        $this->db->flush_cache();
        return $results;
	}
        public function get_lokasi($peruntukan = ""){
		if($peruntukan == '1' || $peruntukan == '0'){
                    $this->db->where("kd_peruntukan","$peruntukan");
                }
                $this->db->where("aktif", 'true');
		$this->db->order_by("kd_lokasi", 'asc');
		$query = $this->db->get("mst.t_lokasi");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		echo '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';
        }
//         public function get_row($id = NULL) {
//        $this->db->select("*", FALSE);
//        $this->db->where("kd_pelanggan", $id);
//        $query = $this->db->get('mst.t_pelanggan_dist');
//
//        if ($query->num_rows() != 0) {
//            $row = $query->row();
//
//            echo '{"success":true,"data":' . json_encode($row) . '}';
//        }
//    }

}
