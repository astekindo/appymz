<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_purchase_request_print_model extends MY_Model {

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
    public function get_rows($kd_supplier,$kd_peruntukan, $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "(lower(no_ro) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
         if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $this->db->where('a.kd_peruntukan', $kd_peruntukan);
        }
        $this->db->select("no_ro,tgl_ro,subject,a.kd_supplier,nama_supplier,case a.status when '9' then 'Reject' when '1' then 'Approval Lvl 1' when '2' then 'Approval Lvl 2' else '' end status", FALSE);
        $this->db->where('a.kd_supplier', $kd_supplier);
        $this->db->where('konsinyasi', '0');
        $this->db->order_by('tgl_ro', 'DESC');
        $this->db->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_purchase_request a');

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
        $this->db->where('a.kd_supplier', $kd_supplier);
        $this->db->where('konsinyasi', '0');
        $this->db->where('a.status', '2');
        $this->db->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier');
        $query = $this->db->get('purchase.t_purchase_request a');

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
        $sql_search = "  (lower(a.no_ro) = '" . strtolower($search) . "') AND ";

        $sql1 = "SELECT  
					b.no_ro, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty, b.qty_po, 
					b.qty_adj, b.keterangan, b.keterangan1,b.approval1, d.nm_satuan,
					coalesce(sum(e.qty_oh), 0,sum(e.qty_oh)) jml_stok
				FROM 
					purchase.t_purchase_request a				
				JOIN 
					purchase.t_dtl_purchase_request b ON b.no_ro=a.no_ro			
				JOIN 
					mst.t_produk c ON c.kd_produk=b.kd_produk
				JOIN 
					mst.t_satuan d ON d.kd_satuan=c.kd_satuan
				LEFT JOIN 
					inv.t_brg_inventory e ON e.kd_produk = b.kd_produk
				WHERE " . $sql_search . " a.konsinyasi = '0'
				GROUP BY 
					b.no_ro, b.kd_produk, c.nama_produk, c.min_stok, c.max_stok, b.qty, b.qty_po, 
					b.qty_adj, b.keterangan, b.keterangan1,b.approval1, d.nm_satuan";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}