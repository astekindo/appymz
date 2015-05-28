<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_view_invoice_model extends MY_Model {

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
    public function search_noinvoice($kd_peruntukan ="", $search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "AND (lower(no_invoice) LIKE '%" . strtolower($search) . "%' )";
        }
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $sql_search = "AND kd_peruntukan ='$kd_peruntukan'";
        }

        $sql1 = "select no_invoice,tgl_invoice from purchase.t_invoice where 1=1 AND no_invoice like 'IK%' OR konsinyasi ='1' " . $sql_search . " order by tgl_invoice desc 
                  limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from purchase.t_invoice
                        where 1=1 ". $sql_search ;

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function search_produk($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(kd_produk) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select kd_produk,nama_produk from mst.t_produk " . $sql_search . "  
                  limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_produk";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    

    public function get_rows($peruntukan_sup = "",$peruntukan_dist ="",$tglAwal = "", $tglAkhir = "", $no_invoice = "", $kd_supplier = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        $left = " left ";
        
        if ($no_invoice != "") {
            $where .= " AND a.no_invoice = '$no_invoice' ";
        }
        if ($peruntukan_sup != "") {
            $where .= " AND b.kd_peruntukan = '$peruntukan_sup' ";
        }
        if ($peruntukan_dist != "") {
            $where .= " AND b.kd_peruntukan = '$peruntukan_dist' ";
        }
        if ($kd_supplier != "") {
            $where .= " AND b.kd_supplier = '$kd_supplier' ";
        }
        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND b.tgl_invoice between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($search != "") {
            $sql_search = " AND ((lower(a.no_invoice) LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
            $this->db->where($sql_search);
        }
        // $this->db->where('status','0');
        $sql = "select a.no_invoice,a.kd_produk,a.no_do,a.qty,a.harga_supplier,a.rp_dpp,a.rp_jumlah,a.rp_total_diskon,a.rp_ajd_jumlah,a.harga_net, b.tgl_invoice,b.kd_supplier,c.nama_produk,d.nama_supplier
                from purchase.t_invoice_detail a, purchase.t_invoice b, mst.t_produk c, mst.t_supplier d
                where a.no_invoice = b.no_invoice
                and a.kd_produk = c.kd_produk
                and b.kd_supplier = d.kd_supplier
                
                " . $sql_search . "
		" . $where . "
		order by b.tgl_invoice desc
		limit " . $length . " offset " . $offset . "";
        $query = $this->db->query($sql);
        //print_r($this->db->last_query());exit;	
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select a.no_invoice,a.kd_produk,a.no_do,a.qty,a.harga_supplier,a.rp_dpp,a.rp_jumlah,a.rp_total_diskon,a.rp_ajd_jumlah,a.harga_net, b.tgl_invoice,b.kd_supplier,c.nama_produk,d.nama_supplier
                from purchase.t_invoice_detail a, purchase.t_invoice b, mst.t_produk c, mst.t_supplier d
                where a.no_invoice = b.no_invoice
                and a.kd_produk = c.kd_produk
                and b.kd_supplier = d.kd_supplier
            	" . $sql_search . "
		" . $where . "
		) as tabel limit 1";

        $query = $this->db->query($sql2);
        // print_r($this->db->last_query());exit;
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_data_html($no_invoice = '') {
        $sql = "select 'VIEW FORM INVOICE' title, a.*, b.nama_supplier from purchase.t_invoice a, mst.t_supplier b
                where a.kd_supplier = b.kd_supplier
                and a.no_invoice = '$no_invoice'";

        $query = $this->db->query($sql);


        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql1 = "SELECT a.*,b.*,nama_produk, nm_satuan
				FROM purchase.t_invoice a
				JOIN purchase.t_invoice_detail b
					ON a.no_invoice = b.no_invoice
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
                and a.no_invoice = '$no_invoice'";
        
        $query1 = $this->db->query($sql1);
        $data['detail'] = $query1->result();

        return $data;
    }

    public function insert_row($table = '', $data = NULL) {
        $result = $this->db->insert($table, $data);
        //print_r($this->db->last_query());
        return $result;
    }

    public function query_update($sql = "") {
        return $this->db->query($sql);
    }

    public function update_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

    public function delete_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

}
