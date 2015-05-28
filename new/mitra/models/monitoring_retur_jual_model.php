<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_retur_jual_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }
   public function search_noretur($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_retur) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_retur,tgl_retur,no_so from sales.t_retur_sales " . $sql_search . " order by no_retur desc  
                  limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from sales.t_retur_sales";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    } 
    public function search_salesorder($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_so) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_so, tgl_so, rp_total, rp_diskon, rp_diskon_tambahan, rp_grand_total, keterangan, kirim_so, userid 
                 from sales.t_sales_order " . $sql_search . "  order by tgl_so desc
		limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from sales.t_sales_order";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_rows($tglAwal = "", $tglAkhir = "", $no_retur = "", $no_so = "", $kd_member = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        $left = " left ";
       
        if ($no_retur != "") {
            $where .= " AND no_retur = '$no_retur' ";
        }

        if ($no_so != "") {
            $where .= " AND no_so = '$no_so' ";
        }

        if ($kd_member != "") {
            $where .= " AND a.kd_member = '$kd_member' ";
        }

        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND tgl_retur between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($search != "") {
            $sql_search = " AND ((lower(no_retur) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        // $this->db->where('status','0');
        $sql = "select * from sales.t_retur_sales
                where 1=1
		" . $sql_search . "
		" . $where . "
		
		limit " . $length . " offset " . $offset . "";
        $query = $this->db->query($sql);
        //print_r($this->db->last_query());exit;	
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select * from sales.t_retur_sales where 1=1
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
    public function get_rows_detail($no_retur = "") {
        
        $sql1 = "select a.*,b.nama_produk from sales.t_retur_sales_detail a, mst.t_produk b
                where a.no_retur = '$no_retur' and a.kd_produk = b.kd_produk";

        $query = $this->db->query($sql1);
       
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
}
