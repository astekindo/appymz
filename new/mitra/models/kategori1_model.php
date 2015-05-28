<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori1_model extends MY_Model {

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
    public function get_rows($search = "", $offset, $length, $fields = "") {
        $this->db->select("*, CASE WHEN aktif is true THEN 'Ya' ELSE 'Tidak' end aktif", FALSE);
        $this->db->from("mst.t_kategori1");
        // $this->db->where('aktif', 'TRUE');
        $this->db->limit($length, $offset);
        if ($search != "" && $fields == "") {
            $sql_search = "(lower(nama_kategori1) LIKE '%" . strtolower($search) . "%' or lower(kd_kategori1) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }

        if ($fields != "") {
            $sql_multi = "(";
            foreach ($fields as $field) {
                $sql_multi .= "(lower(" . $field . ") LIKE '%" . strtolower($search) . "%') OR ";
            }
            $sql_multi = substr($sql_multi, 0, -4);
            $sql_multi .= ")";
            $this->db->where($sql_multi, NULL);
        }
        $this->db->order_by("nama_kategori1", "asc");

        $query = $this->db->get();
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "" && $fields == "") {
            $sql_search = "(lower(nama_kategori1) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($fields != "") {
            $sql_multi = "(";
            foreach ($fields as $field) {
                $sql_multi .= "(lower(" . $field . ") LIKE '%" . strtolower($search) . "%') OR ";
            }
            $sql_multi = substr($sql_multi, 0, -4);
            $sql_multi .= ")";
            $this->db->where($sql_multi, NULL);
        }
        $query = $this->db->get("mst.t_kategori1");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row($id = NULL) {
        $sql = "SELECT kd_kategori1, nama_kategori1, created_by, created_date, updated_by, 
				updated_date, CASE WHEN aktif is true THEN 1 ELSE 0 end aktif
				FROM mst.t_kategori1 WHERE kd_kategori1 = '$id'";

        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_kategori1', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_kategori1', $id);
        return $this->db->update('mst.t_kategori1', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL, $data = NULL) {
        $this->db->where('kd_kategori1', $id);
        return $this->db->update('mst.t_kategori1', $data);
    }

}
