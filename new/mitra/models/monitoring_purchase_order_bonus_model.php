<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_purchase_order_bonus_model
 *
 * @author Yakub
 */
class monitoring_purchase_order_bonus_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    //put your code here
    public function getDataSupplier($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "AND ((lower(purchase.t_purchase.kd_supplier) LIKE '%" . strtolower($search) . "%') OR (purchase.t_purchase.kd_supplier LIKE '%$search%'))";
        }

        $mainQuery = "SELECT DISTINCT a.kd_suplier_po, a.no_po_induk,b.nama_supplier 
                      FROM purchase.t_purchase a LEFT JOIN mst.t_supplier b ON a.kd_suplier_po = b.kd_supplier
                      WHERE a.no_po_induk <> '' ";
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

    public function getDataNOPOInduk($limit, $offset, $kdSupplierPO, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if (!empty($search)) {
            $sqlSearch = "AND ((lower(purchase.t_purchase.no_po_induk) LIKE '%" . strtolower($search) . "%') OR (purchase.t_purchase.no_po_induk LIKE '%$search%'))";
        }

        $mainQuery = "SELECT DISTINCT kd_suplier_po, no_po_induk 
                      FROM purchase.t_purchase
                      WHERE no_po_induk <> '' and kd_suplier_po = '$kdSupplierPO'";
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

    public function get_rows($kdSupplier = "", $tglAwal = "", $tglAkhir = "", $approvalPo = "", $closePo = "", $konsinyasi = "", $peruntukanSup = "", $peruntukanDist = "", $noPoInduk = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        if ($kdSupplier != "") {
            $where .= " AND b.kd_suplier_po = '$kdSupplier' ";
        }
        if ($approvalPo != "" && $approvalPo != "A") {
            $where .= " AND b.approval_po = '$approvalPo' ";
        }

        if ($closePo != "" && $closePo != "A") {
            $where .= " AND b.close_po = '$closePo' ";
        }

        if ($noPoInduk != "") {
            $where .= " AND b.no_po_induk = '$noPoInduk' ";
        }

        if ($peruntukanSup != "") {
            $where .= " AND b.kd_peruntukan = '$peruntukanSup' ";
        }
        if ($peruntukanDist != "") {
            $where .= " AND b.kd_peruntukan = '$peruntukanDist' ";
        }
        switch ($konsinyasi) {
            case '0':
            case '1':
                $where .= " AND b.konsinyasi = '$konsinyasi' ";
                break;
            case '2':
                $where .= " AND b.no_po like '" . GET_PB_REQUEST . "%'";
                break;
            case '3':
                $where .= " AND b.no_po like '" . GET_ASSET_REQUEST . "%'";
                break;
        }

        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND b.tanggal_po between '$tglAwal' AND '$tglAkhir' ";
        }

        if ($search != "") {
            $sql_search = " AND ((lower(b.no_po) LIKE '%" . strtolower($search) . "%') OR (lower(f.no_ro) LIKE '%" . strtolower($search) . "%'))";
        }
        $sql = "select distinct coalesce(f.no_ro, 'NON-PR') no_ro,
					b.no_po,b.tgl_berlaku_po, b.tanggal_po, b.kd_suplier_po kd_supplier, c.nama_supplier,
					case b.konsinyasi when '0' then 'NORMAL' when '1' then 'KONSINYASI' end type_purchase,
					case b.approval_po when '0' then 'Belum Approve' when '1' then 'Approve' when '9' then 'Reject' end status_po,  
					case b.close_po when '0' then 'Open' when '1' then 'Close' else '-' end is_close_po,
					d.no_do, e.tanggal_terima tanggal_do,
                                        CASE WHEN b.kd_peruntukan ='1' THEN 'Distribusi' ELSE 'Supermarket' END peruntukan
					from purchase.t_purchase b
					join purchase.t_purchase_detail f on b.no_po = f.no_po
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_dtl_receive_order d on d.no_po = b.no_po
					left join purchase.t_receive_order e on e.no_do = d.no_do
					where 1=1 $sql_search $where
					order by b.tanggal_po desc limit $length offset $offset";
        //return $sql;
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select distinct coalesce(f.no_ro, 'NON-PR') no_ro
					from purchase.t_purchase b
					join purchase.t_purchase_detail f on b.no_po = f.no_po
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_dtl_receive_order d on d.no_po = b.no_po
					left join purchase.t_receive_order e on e.no_do = d.no_do
					where 1=1 $sql_search $where) as tabel limit 1";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->result();
            $total = $row->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'query' => $this->db->last_query(),
            'data' => $rows
        ));
        //$results = '{"success":true, "record":' . $total . ', "data":' . json_encode($rows) . '}';
        return $results;
    }

}
