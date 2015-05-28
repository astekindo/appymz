<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order_asset_model extends MY_Model {

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
    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function query_update($sql = "") {
        return $this->db->query($sql);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_po($kd_supplier = "", $search = "") {
        if ($search != '') {
            $where = " AND ((lower(no_po) LIKE '%" . $search . "%') OR (no_po LIKE '%" . $search . "%'))";
        }
        $sql = "select no_po from purchase.t_purchase where close_po = '0' 
                    AND konsinyasi = '0' AND no_po like '" . GET_ASSET_REQUEST . "%' 
                    AND kd_suplier_po = '" . $kd_supplier . "' " . $where . "";

        $query = $this->db->query($sql);
        //print_r($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_po_detail($no_po = '', $search = '') {
        if ($search != '') {
            $this->db->where("(lower(nama_produk) LIKE '%" . strtolower($search) . "%')", NULL);
        }
        $this->db->select(" a.kd_produk, a.qty_po,
							COALESCE(a.qty_po - sum(f.qty_terima), a.qty_po) AS qty_do, 
							COALESCE(a.qty_po - sum(f.qty_terima), a.qty_po) AS jumlah_barcode, 
							COALESCE(sum(f.qty_terima), 0) AS qty_terima, 
							b.nama_produk,b.kd_produk_supp,b.kd_produk_lama,c.nm_satuan,e.kd_supplier,e.nama_supplier", FALSE);
        $this->db->join("mst.t_produk b", "b.kd_produk = a.kd_produk");
        $this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
        $this->db->join("purchase.t_purchase d", "d.no_po = a.no_po");
        $this->db->join("mst.t_supplier e", "e.kd_supplier = d.kd_suplier_po");
        $this->db->join("purchase.t_dtl_receive_order f", "f.no_po = a.no_po and f.kd_produk = a.kd_produk", "left");
        $this->db->where("a.no_po", $no_po);
        $this->db->group_by(array("a.kd_produk", "a.no_po", "a.qty_po", " b.nama_produk", " b.kd_produk_supp", " b.kd_produk_lama", " c.nm_satuan", " e.kd_supplier", " e.nama_supplier"));
        $query = $this->db->get("purchase.t_purchase_detail a");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_stok_inventory($kd_produk = '',$kd_lokasi = '',$kd_blok = '',$kd_sub_blok = '') {
        $this->db->where("kd_produk", $kd_produk);
        $this->db->where("kd_lokasi", $kd_lokasi);
        $this->db->where("kd_blok", $kd_blok);
        $this->db->where("kd_sub_blok", $kd_sub_blok);
        $query = $this->db->get("inv.t_brg_inventory");
        $result = FALSE;

        if ($query->num_rows() > 0) {
            $result = TRUE;
        }

        return $result;
    }
    
    public function get_rows_lokasi($kd_produk = '', $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, d.nama_lokasi || '-' || c.nama_blok || '-' || b.nama_sub_blok nama_sub,
					a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_sub_blok,  c.nama_blok, d.nama_lokasi, b.kapasitas,
					CASE WHEN d.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
					FROM mst.t_produk_lokasi a
					JOIN mst.t_sub_blok b
						ON b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					JOIN mst.t_blok c
						ON c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
					JOIN mst.t_lokasi d
						ON d.kd_lokasi = a.kd_lokasi
					WHERE a.kd_produk = '$kd_produk' 
					" . $sql_search . "
					LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        // print_r($this->db->last_query());exit;

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					" . $sql_search . "";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}