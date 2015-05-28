<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_purchase_order_print_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows($kd_supplier, $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "(lower(no_ro) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $this->db->select('kd_suplier_po, nama_supplier, no_po, tanggal_po');
        $this->db->where('a.kd_suplier_po', $kd_supplier);
        $this->db->where('konsinyasi', '1');
        $this->db->where('approval_po', '1');
        $this->db->join('mst.t_supplier b', 'a.kd_suplier_po = b.kd_supplier');
        $query = $this->db->get('purchase.t_purchase a');

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();

        $this->db->select('count(*) as total');
        $sql_search = "";
        if ($search != "") {
            $sql_search = "(lower(no_ro) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $this->db->where('a.kd_suplier_po', $kd_supplier);
        $this->db->where('konsinyasi', '1');
        $this->db->where('approval_po', '1');
        $this->db->join('mst.t_supplier b', 'a.kd_suplier_po = b.kd_supplier');
        $query = $this->db->get('purchase.t_purchase a', $length, $offset);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_rows_detail($no_po) {

        $sql1 = "SELECT a.kd_produk, nama_produk, qty_po, nm_satuan,a.*
					FROM purchase.t_purchase_detail a
					JOIN mst.t_produk b
						ON a.kd_produk = b.kd_produk
					JOIN mst.t_satuan c
						ON b.kd_satuan = c.kd_satuan
					WHERE a.no_po = '$no_po'";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}