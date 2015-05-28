<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of input_jumlah_pengunjung_model
 *
 * @author Yakub
 */
class input_jumlah_pengunjung_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getAll($limit, $offset, $tanggal = "", $namaCabang = "") {
        $sqlSearch = "";
        if (!empty($tanggal)) {
            $sqlSearch .= " AND a.tanggal = '$tanggal'";
        }
        if (!empty($namaCabang)) {
            $sqlSearch .= " AND lower(b.nama_cabang) LIKE '%" . strtolower($namaCabang) . "%'";
        }

        $getDataQuery = $this->db->query("SELECT a.*,b.nama_cabang FROM sales.t_jumlah_pengunjung a,mst.t_cabang b "
                . "WHERE a.kd_cabang=b.kd_cabang $sqlSearch limit $limit offset $offset");
        $getTotalQuery = $this->db->query("SELECT count(*) as total FROM sales.t_jumlah_pengunjung a,mst.t_cabang b "
                . "WHERE a.kd_cabang=b.kd_cabang $sqlSearch");
        $total = $getTotalQuery->row()->total;

        if ($getDataQuery->num_rows() > 0) {
            $queryResults = $getDataQuery->result();
        }

        $results = array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        );
        return json_encode($results);
    }

    public function getCabang() {
        $getDataQuery = $this->db->query("select * from mst.t_cabang");
        $getTotalQuery = $this->db->query("select count(*) as total from mst.t_cabang");
        $result = $getDataQuery->result();
        $total = $getTotalQuery->row()->total;
        $results = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );
        return json_encode($results);
    }

    public function update($data, $kdCabang, $tanggal) {
        $this->db->where('kd_cabang', $kdCabang);
        $this->db->where('tanggal', $tanggal);
        $this->db->update('sales.t_jumlah_pengunjung', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function insert($data) {
        $this->db->insert('sales.t_jumlah_pengunjung', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function delete($kdCabang, $tanggal) {
        $this->db->where('kd_cabang', $kdCabang);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete("sales.t_jumlah_pengunjung");
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }
    //unused
    public function isDataExist($kdCabang, $tanggal) {
        $query = $this->db->query("select * from sales.t_jumlah_pengunjung where kd_cabang = '$kdCabang' and tanggal='$tanggal'");
        $results = $query->result();
        if ($results != null) {
            return true;
        } else {
            return false;
        }
    }
}
