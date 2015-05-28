<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_barter_barang_model
 *
 * @author Yakub
 */
class monitoring_barter_barang_model extends MY_Model {

    //put your code here

    function __construct() {
        parent::__construct();
    }

    public function getDataBarter($limit, $offset, $search = "", $kdSupplier, $tglAwal, $tglAkhir, $status, $jnsTranfer) {
        $result = '';
        $total = 0;
        $sqlFilter = '';
        $sqlSearch = '';
        $clause = '';

        if (!empty($kdSupplier)) {
            $sqlFilter .= " AND a.kd_supplier = '$kdSupplier'";
        }

        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $sqlFilter .= " AND a.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
        }

        if ($status != '') {
            $sqlFilter .= " AND a.status = '$status'";
        }

        if ($jnsTranfer != '') {
            $sqlFilter .= " AND a.jenis_transfer = '$jnsTranfer'";
        }

        if (!empty($search)) {
            $sqlSearch = " AND ((lower(a.no_transfer_stok) LIKE '%" . strtolower($search) . "%') OR (a.no_transfer_stok LIKE '%$search%') OR (a.no_transfer_stok = '$search'))";
        }

        $mainQuery = "SELECT a.*,b.nama_supplier FROM inv.t_barter_barang a, mst.t_supplier b WHERE a.kd_supplier=b.kd_supplier";
        $queryData = "$mainQuery $sqlFilter $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlFilter $sqlSearch) a";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            //'query' => $this->db->last_query(),
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataBarangDetail($limit, $offset, $noTransfer="", $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = " AND (b.no_sb LIKE '%$search%') OR (b.no_sb = '%$search%')";
        }

        $mainQuery = "select b.*,c.nama_produk
                      from inv.t_surat_barter a,
                      inv.t_surat_barter_detail b left join mst.t_produk c
                      on b.kd_produk=c.kd_produk
                      where a.no_transfer_stok = '$noTransfer'
                      and a.no_sb = b.no_sb";
        $queryData = "$mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch) a";

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
        );

        return json_encode($success);
    }

    public function getDataSupplier($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlFilter = '';
        $sqlSearch = '';


        if (!empty($search)) {
            $sqlSearch = " WHERE (a.kd_supplier LIKE '%$search%') OR (a.nama_supplier LIKE '%$search%') OR (a.alamat = '$search')";
        }

        $mainQuery = "SELECT a.kd_supplier,a.nama_supplier,a.alamat FROM mst.t_supplier a";
        $queryData = "$mainQuery $sqlFilter $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlFilter $sqlSearch) a";

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
        );

        return json_encode($success);
    }

}
