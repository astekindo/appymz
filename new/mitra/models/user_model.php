<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends MY_Model {
    public function get_rows($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = "AND (lower(a.username) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT 
                        a.username, 
                        a.passwd, 
                        a.passwd2, 
                        a.email, 
                        a.aktif, 
                        a.kd_jabatan, 
                        a.kd_kategori1, 
                        a.kd_kategori2, 
                        a.kd_kategori3, 
                        a.kd_kategori4, 
                        a.kd_peruntukan,
                        a.kd_cabang,
                        CASE
                            WHEN a.kd_peruntukan=0 THEN 'Supermarket'
                            WHEN a.kd_peruntukan=1 THEN 'Distribusi'
                            WHEN a.kd_peruntukan=2 THEN 'All'
                        END as kd_peruntukan_alias,
                        CASE
                            WHEN a.aktif IS true THEN 'Aktif'
                            WHEN a.aktif IS false THEN 'Non Aktif'
                        END as aktif_alias,
                        b.nama_lengkap,
                        b.gelar_akademis, 
                        b.jns_kelamin, 
                        b.tmp_lahir, 
                        b.tgl_lahir, 
                        b.no_ktp, 
                        b.alamat, 
                        b.foto, 
                        b.agama, 
                        b.no_npwp, 
                        b.no_telp, 
                        b.no_hp
                      FROM 
                        secman.t_user a, 
                        secman.t_user_info b
                      WHERE
                        a.username = b.username ".$sql_search."
                      order by a.username LIMIT ".$length." OFFSET ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT 
                        a.username, 
                        a.passwd, 
                        a.passwd2, 
                        a.email, 
                        a.aktif, 
                        a.kd_jabatan, 
                        a.kd_kategori1, 
                        a.kd_kategori2, 
                        a.kd_kategori3, 
                        a.kd_kategori4, 
                        a.kd_peruntukan,
                        a.kd_cabang,
                        b.nama_lengkap,
                        b.gelar_akademis, 
                        b.jns_kelamin, 
                        b.tmp_lahir, 
                        b.tgl_lahir, 
                        b.no_ktp, 
                        b.alamat, 
                        b.foto, 
                        b.agama, 
                        b.no_npwp, 
                        b.no_telp, 
                        b.no_hp
                      FROM 
                        secman.t_user a, 
                        secman.t_user_info b
                      WHERE 
                        a.username = b.username ".$sql_search."
                      order by a.username) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
        
        public function get_row($id = NULL) {
        $sql_search = "AND a.username = '" . $id . "'";
        $sql1 = "SELECT 
                        a.username, 
                        a.passwd, 
                        a.passwd2, 
                        a.email, 
                        a.aktif, 
                        a.kd_jabatan, 
                        a.kd_kategori1, 
                        a.kd_kategori2, 
                        a.kd_kategori3, 
                        a.kd_kategori4,  
                        a.kd_group,
                        a.kd_peruntukan,
                        a.kd_cabang,
                        a.is_bazar,
                        CASE
                            WHEN a.kd_peruntukan=0 THEN 'Supermarket'
                            WHEN a.kd_peruntukan=1 THEN 'Distribusi'
                            WHEN a.kd_peruntukan=2 THEN 'All'
                        END as kd_peruntukan_alias,
                        b.nama_lengkap,
                        b.gelar_akademis, 
                        b.jns_kelamin, 
                        b.tmp_lahir, 
                        b.tgl_lahir, 
                        b.no_ktp, 
                        b.alamat, 
                        b.foto, 
                        b.agama, 
                        b.no_npwp, 
                        b.no_telp, 
                        b.no_hp
                      FROM 
                        secman.t_user a, 
                        secman.t_user_info b
                      WHERE 
                        a.username = b.username and a.aktif IS true ".$sql_search;
        $query = $this->db->query($sql1);       

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }
	
	public function get_group(){
		$sql = "SELECT * FROM secman.t_group;";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}

    
    public function insert_row($data = NULL) {
        return $this->db->insert('secman.t_user', $data);
    }
    public function insert_row_info($data = NULL) {
        return $this->db->insert('secman.t_user_info', $data);
    }
    
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('username', $id);
        return $this->db->update('secman.t_user', $data);
    }
    public function update_row_info($id = NULL, $data = NULL) {
        $this->db->where('username', $id);
        return $this->db->update('secman.t_user_info', $data);
    }
    
    public function delete_row($id = NULL) {
        $data = array(
            'aktif' => '0'
        );
        $this->db->where('username', $id);
        return $this->db->update('secman.t_user', $data);
    }
    public function search_user($username = "") {
        $sql = "select * from secman.t_user where username = '$username'";
       $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }
}
?>
