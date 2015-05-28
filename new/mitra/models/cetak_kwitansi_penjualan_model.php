<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_kwitansi_penjualan_model
 *
 * @author Yakub
 */
class cetak_kwitansi_penjualan_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    //put your code here
    public function getRows($limit, $offset, $search = "", $kdPelanggan="") {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        $sqlSearch = "";
        if (!empty($kdPelanggan)) {
            $getDataQuery = "SELECT * FROM sales.t_sales_kwitansi WHERE kd_pelanggan = '$kdPelanggan'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_sales_kwitansi WHERE kd_pelanggan = '$kdPelanggan'";
        } else {
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(sales.t_sales_kwitansi.no_kwitansi) LIKE '%" . strtolower($search) . "%') OR (sales.t_sales_kwitansi.no_ref LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "SELECT * FROM sales.t_sales_kwitansi $sqlSearch LIMIT $limit OFFSET $offset";
            $getTotalQuery = "SELECT count(*) AS total FROM sales.t_sales_kwitansi $sqlSearch";
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

}
