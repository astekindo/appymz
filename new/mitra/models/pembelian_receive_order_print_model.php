<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order_print_model extends MY_Model {

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
    public function get_rows($kd_supplier, $kd_peruntukan, $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "(lower(no_do) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $this->db->where('a.kd_peruntukan', $kd_peruntukan);
        }
        $this->db->select('no_do,tanggal, tanggal_terima,no_bukti_supplier,a.kd_supplier,nama_supplier');
        $this->db->where('a.kd_supplier', $kd_supplier);
        $this->db->where('a.konsinyasi', '0');
        $this->db->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_receive_order a');

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();

        $this->db->select('count(*) as total');
        $sql_search = "";
        if ($search != "") {
            $sql_search = "(lower(no_do) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $this->db->where('a.kd_supplier', $kd_supplier);
        $this->db->where('a.konsinyasi', '0');
        $this->db->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_receive_order a');

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_rows_detail($search = "") {
        $sql_search = "";
        $sql_search = "  AND (lower(a.no_do) = '" . strtolower($search) . "') ";

        $sql1 = "SELECT  
					b.no_do, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty_beli, b.qty_terima, 
					b.keterangan,d.nm_satuan,
					coalesce(sum(e.qty_oh), 0,sum(e.qty_oh)) jml_stok
				FROM 
					purchase.t_receive_order a				
				JOIN 
					purchase.t_dtl_receive_order b ON b.no_do=a.no_do			
				JOIN 
					mst.t_produk c ON c.kd_produk=b.kd_produk
				JOIN 
					mst.t_satuan d ON d.kd_satuan=c.kd_satuan
				LEFT JOIN 
					inv.t_brg_inventory e ON e.kd_produk = b.kd_produk
				WHERE 1=1 " . $sql_search . " 
				GROUP BY 
					b.no_do, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty_beli, b.qty_terima, 
					b.keterangan,d.nm_satuan";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}