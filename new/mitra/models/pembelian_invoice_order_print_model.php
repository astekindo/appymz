<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pembelian_invoice_order_print_model extends MY_Model {

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
    public function get_rows($kd_supplier="",$kd_peruntukan = "", $search = "", $offset, $length) {
        $sql_search = "";

        if ($kd_supplier != "") {
            $where .= " AND a.kd_supplier = '$kd_supplier' ";
        }
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $sql_search = "AND a.kd_peruntukan ='$kd_peruntukan'";
        }
        if ($search != "") {
            
            $sql_search = " AND (lower(a.no_invoice) LIKE '%" . strtolower($search) . "%') OR (lower(a.no_bukti_supplier) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select a.*,b.nama_supplier from purchase.t_invoice a
                join mst.t_supplier b ON a.kd_supplier = b.kd_supplier
                where 1=1 
                " . $sql_search . "
		" . $where . "
		limit " . $length . " offset " . $offset . "";
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();

        $sql2 = "select count(*) as total from (select a.*,b.nama_supplier from purchase.t_invoice a
                join mst.t_supplier b ON a.kd_supplier = b.kd_supplier
                where 1=1 
                " . $sql_search . "
		" . $where . "
		) as tabel limit 1";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_rows_detail($no_invoice = "") {
        
        $sql1 = "SELECT a.*,b.*,nama_produk, nm_satuan
				FROM purchase.t_invoice a
				JOIN purchase.t_invoice_detail b
					ON a.no_invoice = b.no_invoice
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
                and a.no_invoice = '$no_invoice'";
				

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
    public function get_data_print($no_invoice = '') {
       
        $sql = "select 'FORM INVOICE' title, a.*, b.nama_supplier,b.pkp  from purchase.t_invoice a, mst.t_supplier b
                where a.kd_supplier = b.kd_supplier
                and a.no_invoice = '$no_invoice'";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        
        $sql_detail = "SELECT a.*,b.*,nama_produk, nm_satuan, e.tanggal,e.tanggal_terima
				FROM purchase.t_invoice a
				JOIN purchase.t_invoice_detail b
					ON a.no_invoice = b.no_invoice
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
				JOIN purchase.t_receive_order e
					ON b.no_do = e.no_do
				
                where a.no_invoice = '$no_invoice'";
						

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        // print_r($this->db->last_query());exit;
        return $data;
    }

}