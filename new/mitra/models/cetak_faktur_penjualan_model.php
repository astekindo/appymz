<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_faktur_penjualan_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    public function search_salesorder($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_so) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_so, tgl_so, rp_total, rp_diskon, rp_diskon_tambahan, rp_grand_total, keterangan, kirim_so, userid 
                 from sales.t_sales_order_dist " . $sql_search . "  order by tgl_so desc
		limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);
        
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from sales.t_sales_order_dist";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows($tglAwal = "", $tglAkhir = "", $no_so = "",$kd_pelanggan = "", $search = "", $offset, $length) {
        $sql_search = "";
       if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND a.tgl_faktur between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($no_so != "") {
            $where .= " AND a.no_so = '$no_so' ";
        }
        if ($kd_pelanggan != "") {
            $where .= " AND a.kd_pelanggan = '$kd_pelanggan' ";
        }
        if ($search != "") {
            
            $sql_search = " AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select a.*,c.nama_pelanggan from sales.t_faktur_jual a
                join sales.t_sales_order_dist b on a.no_so = b.no_so
                join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan
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
         $sql2 = "select count(*) as total from (select a.*,c.nama_pelanggan from sales.t_faktur_jual a
                join sales.t_sales_order_dist b on a.no_so = b.no_so
                join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan
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
    public function get_rows_detail($no_faktur = "") {
        
        $sql1 = "select a.*,b.* 
                from sales.t_faktur_jual_detail a , sales.t_faktur_jual b
                where a.no_faktur = b.no_faktur
                and a.no_faktur = '$no_faktur'";
				

        $query = $this->db->query($sql1);
       
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
}