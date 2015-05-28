<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_penukaran_point_model
 *
 * @author Yakub
 */
class cetak_penukaran_point_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    //put your code here
    public function getDataPenukaranPoint($limit, $offset, $search = "", $kdMember = "", $tgl = "") {
        $result = '';
        $total = 0;
        $sqlFilter = '';
        $sqlSearch = '';

        if (!empty($kdMember)) {
            $sqlFilter .= " WHERE a.kd_member = '$kdMember'";
        }

        if (!empty($tgl)) {
            if (!empty($kdMember)) {
                $sqlFilter .= " AND a.tanggal = '$tgl'";
            } else {
                $sqlFilter .= " WHERE a.tanggal = '$tgl'";
            }
        }

        if (!empty($search)) {
            $clause = '';
            if ($sqlFilter == '') {
                $clause = 'WHERE';
            } else {
                $clause = 'AND';
            }

            $sqlSearch = "$clause ((lower(a.no_bukti) LIKE '%" . strtolower($search) . "%') OR (b.nmmember LIKE '%$search%'))";
        }

        $mainQuery = 'SELECT a.*,b.nmmember,c.nama_produk '
                . 'FROM mst.t_point_trx_penukaran a '
                . 'LEFT JOIN mst.t_member b '
                . 'ON a.kd_member=b.kd_member '
                . 'LEFT JOIN mst.t_produk c '
                . 'ON a.kd_produk = c.kd_produk';
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

}
