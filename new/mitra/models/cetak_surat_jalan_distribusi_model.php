<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_surat_jalan_model
 *
 * @author Yakub
 */
class cetak_surat_jalan_distribusi_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataSuratJalanDist($limit, $offset, $search = "", $tglAwal = "", $tglAkhir = "") {
        $result = '';
        $total = 0;
        $sqlFilter = '';
        $sqlSearch = '';

        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $sqlFilter = " WHERE a.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
        }

        if (!empty($search)) {
            $clause = '';
            if ($sqlFilter == '') {
                $clause = 'WHERE';
            } else {
                $clause = 'AND';
            }

            $sqlSearch = "$clause ((lower(a.no_do) LIKE '%" . strtolower($search) . "%') OR (a.no_kendaraan LIKE '%$search%'))";
        }

        $mainQuery = 'SELECT * FROM sales.t_surat_jalan_dist a';
        $queryData = " $mainQuery $sqlFilter $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlFilter $sqlSearch limit $limit offset $offset) a";

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

    public function getDataSuratJalanDistDetail($limit, $offset, $noSJ, $search = '') {
        $result = '';
        $total = 0;
        $sqlSearch = '';
        if (!empty($search)) {
            $sqlSearch = "AND (b.nama_produk LIKE '%$search%') AND (a.no_sj LIKE '%$noSJ%')";
        }
        $queryData = "SELECT a.*,b.nama_produk FROM "
                . "sales.t_surat_jalan_dist_detail a "
                . "LEFT JOIN mst.t_produk b "
                . "ON a.kd_produk = b.kd_produk WHERE a.no_sj = '$noSJ' $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM"
                . "(SELECT a.*,b.nama_produk FROM "
                . "sales.t_surat_jalan_dist_detail a LEFT JOIN"
                . " mst.t_produk b ON a.kd_produk = b.kd_produk WHERE a.no_sj = '$noSJ' $sqlSearch limit $limit offset $offset) a";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
                //'data' => $this->db->last_query()
        );

        return json_encode($success);
    }

}
