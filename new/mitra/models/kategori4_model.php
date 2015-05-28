<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori4_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "AND (lower(a.nama_kategori4) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select d.kd_kategori1 || c.kd_kategori2 || b.kd_kategori3 || a.kd_kategori4 kd_kategori,
					d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori ,
					a.kd_kategori4, a.nama_kategori4, b.kd_kategori3, b.nama_kategori3, c.nama_kategori2, 
					c.kd_kategori2, d.nama_kategori1, d.kd_kategori1,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif 
					from mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c , mst.t_kategori1 d 
					where 
					 a.kd_kategori3 = b.kd_kategori3 and a.kd_kategori2 = c.kd_kategori2 and a.kd_kategori1 = d.kd_kategori1
					and b.kd_kategori2 = c.kd_kategori2  and b.kd_kategori1 = d.kd_kategori1
					and c.kd_kategori1 = d.kd_kategori1
					" . $sql_search . "
					order by d.nama_kategori1,  c.nama_kategori2, b.nama_kategori3, a.nama_kategori4
					LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        $this->db->flush_cache();
        $sql2 = "select count(*) as total from mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c , mst.t_kategori1 d 
					where a.kd_kategori3 = b.kd_kategori3 and a.kd_kategori2 = c.kd_kategori2 and a.kd_kategori1 = d.kd_kategori1
					and b.kd_kategori2 = c.kd_kategori2  and b.kd_kategori1 = d.kd_kategori1
					and c.kd_kategori1 = d.kd_kategori1
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

    public function get_nama_kategori4($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "WHERE (lower(a.nama_kategori4) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select distinct (a.nama_kategori4)
					from mst.t_kategori4 a
					" . $sql_search . "
					order by a.nama_kategori4
					LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL) {
        $sql = "select d.kd_kategori1 || c.kd_kategori2 || b.kd_kategori3 || a.kd_kategori4 kd_kategori,
					d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori ,
					a.kd_kategori4, a.nama_kategori4, b.kd_kategori3, b.nama_kategori3, c.nama_kategori2, 
					c.kd_kategori2, d.nama_kategori1, d.kd_kategori1,
					CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif 
					from mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c , mst.t_kategori1 d 
					where 
					a.kd_kategori1 = '$id1' and a.kd_kategori2 = '$id2' and a.kd_kategori3 = '$id3' AND a.kd_kategori4 = '$id4'
					AND a.kd_kategori3 = b.kd_kategori3 and a.kd_kategori2 = c.kd_kategori2 and a.kd_kategori1 = d.kd_kategori1
					and b.kd_kategori2 = c.kd_kategori2  and b.kd_kategori1 = d.kd_kategori1
					and c.kd_kategori1 = d.kd_kategori1
					";
        // print_r($this->db->last_query());
        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_kategori4', $data);
    }

    public function update_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL, $data = NULL) {
        $this->db->where('kd_kategori1', $id1);
        $this->db->where('kd_kategori2', $id2);
        $this->db->where('kd_kategori3', $id3);
        $this->db->where('kd_kategori4', $id4);
        return $this->db->update('mst.t_kategori4', $data);
    }

    public function delete_row($id1 = NULL, $id2 = NULL, $id3 = NULL, $id4 = NULL, $data = NULL) {

        $this->db->where('kd_kategori1', $id1);
        $this->db->where('kd_kategori2', $id2);
        $this->db->where('kd_kategori3', $id3);
        $this->db->where('kd_kategori4', $id4);
        return $this->db->update('mst.t_kategori4', $data);
    }

    public function get_kategori3($id1 = NULL, $id2 = NULL) {
        $sql = "SELECT a.kd_kategori3,a.nama_kategori3
                FROM mst.t_kategori3 a, mst.t_kategori2 b, mst.t_kategori1 c
                WHERE a.kd_kategori1=b.kd_kategori1  AND a.kd_kategori2=b.kd_kategori2 
                AND a.kd_kategori1=c.kd_kategori1 AND b.kd_kategori1=c.kd_kategori1
                AND a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.aktif = true
                ORDER BY a.nama_kategori3 ASC";
        
        if($id1 === NULL and $id2 === NULL){
            $sql = "SELECT a.kd_kategori3,a.nama_kategori3 FROM mst.t_kategori3 a";
        }
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_kategori4($id1 = NULL, $id2 = NULL, $id3 = NULL) {
        $sql = "SELECT d.kd_kategori4,d.nama_kategori4
                FROM mst.t_kategori3 a, mst.t_kategori2 b, mst.t_kategori1 c, mst.t_kategori4 d
                WHERE d.kd_kategori3=a.kd_kategori3
                AND d.kd_kategori2=a.kd_kategori2 
                AND d.kd_kategori1=a.kd_kategori1  	
                AND d.kd_kategori1=c.kd_kategori1	
                AND d.kd_kategori2=b.kd_kategori2
                AND d.kd_kategori1=c.kd_kategori1
                AND a.kd_kategori1=b.kd_kategori1  
                AND a.kd_kategori2=b.kd_kategori2 
                AND a.kd_kategori1=c.kd_kategori1 
                AND b.kd_kategori1=c.kd_kategori1
                AND d.kd_kategori1='$id1' 
                AND d.kd_kategori2='$id2' 
                AND d.kd_kategori3='$id3' 
                AND d.aktif = true
                ORDER BY d.nama_kategori4 ASC";
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

}
