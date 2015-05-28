<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori2_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nama_kategori2) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select a.kd_kategori2, a.kd_kategori1, b.nama_kategori1, a.nama_kategori2, 
					a.kd_kategori1 || a.kd_kategori2 kd_kategori,
					b.nama_kategori1 || ' - ' || a.nama_kategori2 nama_kategori ,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif 
					from mst.t_kategori2 a
					join mst.t_kategori1 b on a.kd_kategori1 = b.kd_kategori1					 
					 " . $sql_search . " 
					order by nama_kategori1
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_kategori2 a
			join mst.t_kategori1 b on a.kd_kategori1 = b.kd_kategori1
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

    public function get_nama_kategori2($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nama_kategori2) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select distinct(a.nama_kategori2)
					from mst.t_kategori2 a
					 " . $sql_search . " 
					order by nama_kategori2
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_row($id = NULL, $id1 = NULL) {
        $sql = "SELECT a.nama_kategori1,b.*,CASE WHEN b.aktif IS true THEN 1 ELSE 0 END aktif FROM mst.t_kategori1 a, mst.t_kategori2 b WHERE b.kd_kategori1='" . $id1 . "' AND a.kd_kategori1 = b.kd_kategori1 AND b.kd_kategori2 ='" . $id . "'";
        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_kategori2', $data);
    }

    public function update_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

    public function delete_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

    public function get_kategori1() {
        $sql = "SELECT kd_kategori1, nama_kategori1 FROM mst.t_kategori1 WHERE aktif=true ORDER BY nama_kategori1";
        $query = $this->db->query($sql);
        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

}
