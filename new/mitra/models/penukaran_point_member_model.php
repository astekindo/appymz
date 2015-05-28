<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penukaran_point_member_model
 *
 * @author Yakub
 */
class Penukaran_point_member_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataMember($length, $offset, $search = "", $kdMember) {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        $sqlSearch = "";
        if (!empty($kdMember)) {
            $getDataQuery = "SELECT * FROM mst.t_member WHERE kd_member = '$kdMember'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM mst.t_member WHERE kd_member = '$kdMember'";
        } else {
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(a.nmmember) LIKE '%" . strtolower($search) . "%') OR (a.nmmember LIKE '%" . strtolower($search) . "%') OR (a.jenis LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "SELECT * FROM mst.t_member a $sqlSearch LIMIT $length OFFSET $offset";
            $getTotalQuery = "select COUNT(*) AS total FROM mst.t_member a $sqlSearch";
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

    public function insert_trx($data)
    {
        return $this->db->insert_batch('mst.t_point_trx_penukaran', $data);
    }

    public function updatePointMember($kdMember, $poin) {
        return $this->db->query("UPDATE mst.t_member SET total_point = $poin WHERE kd_member = '$kdMember'");
    }

    public function get_data_print($no_bukti = '') {
        $sql = "select a.*, b.nmmember, b.alamat_pengiriman, b.telepon, b.hp, b.total_point, c.nama_produk
                from mst.t_point_trx_penukaran a, mst.t_member b, mst.t_produk c
                where a.kd_member = b.kd_member
                and a.kd_produk = c.kd_produk
                and a.no_bukti = '$no_bukti'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;
        return $query->result();  
    }


}
