<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_close_pr_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_rows($kd_supplier = "", $search = "",$start,$limit) {
       $this->db
            ->select('no_ro, tgl_ro, subject, waktu_top, keterangan1, keterangan2')
            ->where('close_ro', '0');
            //->where('konsinyasi', '0')
            //->where('status', '2');
        if($search != "") {
            $this->db->like('no_ro',$search);
        }
        $this->db->where('kd_supplier', $kd_supplier);
        if(!empty($start) && !empty($limit) ) {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get('purchase.t_purchase_request');

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';
//print_r($this->db->last_query());
        return $results;
    }

    public function get_rows_detail($no_ro) {
        $sql = <<<EOT
select b.kd_produk, c.nama_produk, b.qty, b.qty_adj, sum(a.qty_po) qty_po
from purchase.t_purchase_detail a, purchase.t_dtl_purchase_request b, mst.t_produk c
where a.no_ro = '$no_ro'
and a.kd_produk = b.kd_produk
and a.kd_produk = c.kd_produk
group by b.kd_produk, c.nama_produk, b.qty, b.qty_adj
EOT;

//        $this->db->select("b.kd_produk, c.nama_produk, b.qty, b.qty_adj, sum(a.qty_po) qty_po")
//            ->from("purchase.t_purchase_detail a, purchase.t_dtl_purchase_request b, mst.t_produk c")
//            ->where('a.no_ro',$no_po)
//            ->where('a.kd_produk', 'b.kd_produk')
//            ->where('a.kd_produk', 'c.kd_produk')
//            ->group_by("b.kd_produk, c.nama_produk, b.qty, b.qty_adj");

//        $query = $this->db->get();

        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
//print_r($this->db->last_query());
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function update_close_pr($no_ro = null, $data = null) {
        $this->db->where('no_ro', $no_ro);
        return $this->db->update('purchase.t_purchase_request', $data);
    }
}