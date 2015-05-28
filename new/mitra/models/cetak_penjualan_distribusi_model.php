<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_penjualan_distribusi_model
 *
 * @author Yakub
 */
class cetak_penjualan_distribusi_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataSalesOrder($limit, $offset, $search = "", $kdMember = "", $tanggalSO = "") {
        $result = '';
        $total = 0;
        $sqlFilter = '';
        $sqlSearch = '';

        $mainQuery = 'SELECT a.*,b.nama_pelanggan  FROM sales.t_sales_order_dist a LEFT JOIN mst.t_pelanggan_dist b ON b.kd_pelanggan=a.kd_member';

        if (!empty($kdMember)) {
            $sqlFilter .= "WHERE a.kd_member = '$kdMember'";
        }
        if (!empty($tanggalSO)) {
            if (!empty($kdMember)) {
                $sqlFilter .= "AND a.tgl_so = '$tanggalSO'";
            } else {
                $sqlFilter .= "WHERE a.tgl_so = '$tanggalSO'";
            }
        }
        if (!empty($search)) {
            $clause = '';
            if ($sqlFilter == '') {
                $clause = 'WHERE';
            } else {
                $clause = 'AND';
            }

            $sqlSearch = "$clause ((lower(a.no_so) LIKE '%" . strtolower($search) . "%') OR (a.no_ref LIKE '%$search%') OR (b.nama_pelanggan LIKE '%$search%'))";
        }

        $queryData = " $mainQuery $sqlFilter $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlFilter $sqlSearch) a";

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

    public function getDataSalesOrderDetail($limit, $offset, $noSO, $search = '') {
        $result = '';
        $total = 0;
        $sqlSearch = '';
        if (!empty($search)) {
            $sqlSearch = "AND (a.qty = '$search') OR (a.no_so LIKE '%$search%') OR (a.no_so = '$search')";
        }
        $queryData = "SELECT a.* FROM sales.t_sales_order_dist_detail a WHERE a.no_so = '$noSO' $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM(SELECT a.* FROM sales.t_sales_order_dist_detail a WHERE a.no_so = '$noSO' $sqlSearch) a";

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
