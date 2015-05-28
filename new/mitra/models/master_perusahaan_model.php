<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class master_perusahaan_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function getRows($limit, $offset, $search = "", $kdPerusahaan) {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        if (!empty($kdPerusahaan)) {
            $getDataQuery = $this->db->query("SELECT * FROM mst.t_perusahaan WHERE kd_perusahaan = '$kdPerusahaan'");
            $getTotalQuery = $this->db->query("SELECT COUNT(*) AS total FROM mst.t_perusahaan WHERE kd_perusahaan = '$kdPerusahaan'");
        } else {
            $sqlSearch = "";
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(mst.t_perusahaan.kd_perusahaan) LIKE '%" . strtolower($search) . "%') OR (mst.t_perusahaan.nama_perusahaan LIKE '%" . strtolower($search) . "%') OR (mst.t_perusahaan.npwp LIKE '%" . strtolower($search) . "%') OR (mst.t_perusahaan.no_telp LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "SELECT * from mst.t_perusahaan $sqlSearch LIMIT $limit OFFSET $offset";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM mst.t_perusahaan $sqlSearch";
        }
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);


        if ($queryGetData->num_rows() > 0) {
            $queryResults = $queryGetData->result();
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function insert($data) {
        $this->db->insert('mst.t_perusahaan', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function update($data, $kdPerusahaan) {
        $this->db->where('kd_perusahaan', $kdPerusahaan);
        $this->db->update('mst.t_perusahaan', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function delete() {
        $this->db->where('kd_perusahaan', $kdPerusahaan);
        $this->db->delete('mst.t_perusahaan');
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    //unused
    public function isDataExist($kdPelanggan) {
        $query = $this->db->query("SELECT * FROM mst.t_perusahaan WHERE mst.t_perusahaan.kd_perusahaan = '$kdPelanggan'");
        $result = $query->num_rows();
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

}
