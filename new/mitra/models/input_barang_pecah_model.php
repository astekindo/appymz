<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of input_barang_pecah_model
 *
 * @author Yakub
 */
class input_barang_pecah_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataProduk($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "WHERE ((lower(a.kd_produk) LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%$search%') OR (a.nama_produk LIKE '%$search%'))";
        }

        $mainQuery = "SELECT a.kd_produk,a.nama_produk,b.nm_satuan FROM mst.t_produk a 
            LEFT JOIN mst.t_satuan b 
            ON a.kd_satuan=b.kd_satuan";
        $queryData = " $mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch limit $limit offset $offset) a";

        $getTotal = $this->db->query($queryTotal);
        $getData = $this->db->query($queryData);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataLokasi($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "WHERE ((lower(a.kd_lokasi) LIKE '%" . strtolower($search) . "%') OR (a.kd_lokasi LIKE '%$search%') OR (a.nama_lokasi LIKE '%$search%'))";
        }

        $mainQuery = "SELECT * from mst.t_lokasi a";
        $queryData = " $mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch limit $limit offset $offset) a";

        $getTotal = $this->db->query($queryTotal);
        $getData = $this->db->query($queryData);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataBlok($limit, $offset, $search = "", $kdLokasi = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "WHERE ((lower(a.nama_blok) LIKE '%" . strtolower($search) . "%') OR (a.nama_blok LIKE '%$search%') OR (a.kd_lokasi LIKE '%$search%'))";
        }

        $mainQuery = "SELECT * from mst.t_blok a WHERE a.kd_lokasi = '$kdLokasi'";
        $queryData = " $mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch limit $limit offset $offset) a";

        $getTotal = $this->db->query($queryTotal);
        $getData = $this->db->query($queryData);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataSubBlok($limit, $offset, $search = "", $kdLokasi = "", $kdBlok = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "WHERE ((lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') OR (a.nama_sub_blok LIKE '%$search%'))";
        }

        $mainQuery = "SELECT * from mst.t_sub_blok a WHERE a.kd_lokasi = '$kdLokasi' AND a.kd_blok = '$kdBlok'";
        $queryData = " $mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch limit $limit offset $offset) a";

        $getTotal = $this->db->query($queryTotal);
        $getData = $this->db->query($queryData);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function insertBarangPecah($data) {
        return $this->db->insert_batch('mst.t_inventory_pecah', $data);
    }

    public function insertBarangPecahDetail($data) {
        return $this->db->insert_batch('mst.t_inventory_pecah_detail', $data);
    }

}
