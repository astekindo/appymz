<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_barter_barang_model
 *
 * @author Yakub
 */
class cetak_barter_barang_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataBarter($limit, $offset, $search = "", $kdSupplier, $tanggalBarter) {
        $result = '';
        $total = 0;
        $sqlFilter = 'WHERE a.status = 3';
        $sqlSearch = '';

        if (!empty($kdSupplier)) {
            $sqlFilter .= "AND a.kd_supplier = '$kdSupplier'";
        }

        if (!empty($tanggalBarter)) {
            $sqlFilter .= "AND a.tanggal = '$tanggalBarter'";
        }
        if ($search) {
            $sqlSearch = "AND ((lower(a.no_transfer_stok) LIKE '%" . strtolower($search) . "%') OR (a.no_transfer_stok LIKE '%$search%') OR (a.no_transfer_stok = '$search'))";
        }

        $queryData = "SELECT a.*,b.nama_supplier FROM inv.t_barter_barang a LEFT JOIN mst.t_supplier b ON a.kd_supplier=b.kd_supplier $sqlFilter $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM(SELECT a.*,b.nama_supplier FROM inv.t_barter_barang a LEFT JOIN mst.t_supplier b ON a.kd_supplier=b.kd_supplier $sqlFilter $sqlSearch) a";

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

    public function getDataSuratBarter($limit, $offset, $noTransfer, $search = '') {
        $result = '';
        $total = 0;
        $sqlSearch = '';
        if (!empty($search)) {
            $sqlSearch = "AND (a.no_sb='$search') OR (a.no_sb LIKE '%$search%') OR (a.no_kendaraan LIKE '%$search%')";
        }
        $queryData = "SELECT a.* FROM inv.t_surat_barter a WHERE a.no_transfer_stok = '$noTransfer' $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM(SELECT a.* FROM inv.t_surat_barter a WHERE a.no_transfer_stok = '$noTransfer' $sqlSearch) a";

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
