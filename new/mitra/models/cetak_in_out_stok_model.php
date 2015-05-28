<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_in_out_stok_model
 *
 * @author Yakub
 */
class cetak_in_out_stok_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataNoBukti($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "WHERE ((lower(a.no_bukti) LIKE '%" . strtolower($search) . "%') OR (a.no_bukti LIKE '%$search%'))";
        }

        $mainQuery = "SELECT DISTINCT a.no_bukti,a.tanggal,a.keterangan FROM inv.t_inout_stok a";
        $queryData = " $mainQuery  $sqlSearch limit $limit offset $offset";
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

    public function getDataInOutStok($limit, $offset, $search = "", $tglAwal = "", $tglAkhir = "", $lokasi = "", $noBukti = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';
        $clause = "WHERE";
        $sqlFilter = "";

        if ($tglAwal != "" && $tglAkhir != "") {
            if ($sqlFilter != "") {
                $clause = "AND";
            }
            $sqlFilter .=" $clause a.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
        }
        if ($lokasi != "") {
            if ($sqlFilter != "") {
                $clause = "AND";
            }
            $sqlFilter .= "$clause a.kd_lokasi LIKE '%$lokasi%'";
        }

        if ($noBukti != "") {
            if ($sqlFilter != "") {
                $clause = "AND";
            }
            $sqlFilter .=" $clause a.no_bukti LIKE '%$noBukti%'";
        }

        if (!empty($search)) {
            if ($sqlFilter != "") {
                $clause = "AND";
            }
            $sqlSearch = "$clause ((lower(a.no_bukti) LIKE '%" . strtolower($search) . "%') OR (a.no_bukti LIKE '%$search%'))";
        }

        $mainQuery = "SELECT a.*,b.nama_lokasi FROM inv.t_inout_stok a LEFT JOIN mst.t_lokasi b ON a.kd_lokasi=b.kd_lokasi $sqlFilter";
        $queryData = " $mainQuery  $sqlSearch limit $limit offset $offset";
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
            'query' => $this->db->last_query(),
            'data' => $result
        );

        return json_encode($success);
    }

}
