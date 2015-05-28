<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_point_rupiah_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "AND (lower(rupiah) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "SELECT *  FROM mst.t_point_rupiah_setting 
			  " . $sql_search . "
                          order by kd_point_setting desc
			  LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $this->db->flush_cache();
        $sql2 = "select count(*) as total from mst.t_point_rupiah_setting 
					" . $sql_search;

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_row($id = NULL) {
        $this->db->select("*");
        $this->db->where("kd_point_setting", $id);
        $query = $this->db->get('mst.t_point_rupiah_setting');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_point_rupiah_setting', $data);
    }

    public function update_row($kd_point_setting = NULL, $data = NULL) {
        $this->db->where('kd_point_setting', $kd_point_setting);
        return $this->db->update('mst.t_point_rupiah_setting', $data);
    }
    public function select_data($rupiah = "",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting = Null){
            $where ="";
            if ($kd_point_setting != NULL){
                $where = " AND kd_point_setting not in ('$kd_point_setting')";
            }
            $sql = "select * from mst.t_point_rupiah_setting
                          where rupiah ='$rupiah' 
                          and tgl_awal <= '$tgl_awal' and tgl_akhir >= '$tgl_awal'
                          $where";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
    public function select_data_end($rupiah = "",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting = Null){
		$where ="";
                if ($kd_point_setting != NULL){
                    $where = " AND kd_point_setting not in ('$kd_point_setting')";
                }
		$sql = "select * from mst.t_point_rupiah_setting
                          where rupiah ='$rupiah' 
                          and tgl_awal <= '$tgl_akhir' and tgl_akhir >= '$tgl_akhir'
                          $where";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
    public function delete_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL, $data = NULL) {

        $this->db->where('kd_kategori1', $id1);
        $this->db->where('kd_kategori2', $id2);
        $this->db->where('kd_kategori3', $id3);
        $this->db->where('kd_kategori4', $id4);
        return $this->db->update('mst.t_point_setting', $data);
    }

    public function get_kategori3($id1 = NULL, $id2 = NULL) {
        $sql = "SELECT a.kd_kategori3,a.nama_kategori3
									FROM mst.t_kategori3 a, mst.t_kategori2 b, mst.t_kategori1 c
									WHERE a.kd_kategori1=b.kd_kategori1  AND a.kd_kategori2=b.kd_kategori2 
									AND a.kd_kategori1=c.kd_kategori1 AND b.kd_kategori1=c.kd_kategori1
									AND a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.aktif = true
									ORDER BY a.nama_kategori3 ASC";
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_kategori4($id1 = NULL, $id2 = NULL, $id3 = NULL) {
        $query = $this->db->query("SELECT a.kd_kategori4,a.nama_kategori4
									FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d
									WHERE a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.kd_kategori3='$id3'
									AND b.kd_kategori3=a.kd_kategori3 AND b.kd_kategori2=a.kd_kategori2 AND b.kd_kategori1=a.kd_kategori1 
									AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
									AND d.kd_kategori1=c.kd_kategori1 
									AND a.aktif = true
									ORDER BY a.nama_kategori4 ASC");
        $rows = $query->result();

        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

}
