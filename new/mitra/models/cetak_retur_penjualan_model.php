<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_retur_penjualan_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    public function get_list_so($search, $start, $limit) {
        $where = 'where a.no_so = b.no_so';
        if($search != '') {
            $search = strtolower($search);
            $where .= " and (lower(a.no_so) like '%$search%' or lower(a.no_retur) like '%$search%')";
        }
        $sql = "select distinct on(a.no_so, b.tgl_so) a.no_so, b.tgl_so
        from sales.t_retur_sales a, sales.t_sales_order b $where offset $start limit $limit";

        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select distinct on(a.no_so, b.tgl_so) a.no_so, b.tgl_so
        from sales.t_retur_sales a, sales.t_sales_order b $where) as tabel limit 1";

        $query = $this->db->query($sql2);
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows($no_so="", $search = "", $offset, $length) {
        $sql_search = "";
         if ($no_so != "") {
            $sql_search .= " AND no_so = '$no_so' ";
        }
        if ($search != "") {
            $sql_search = " AND (lower(no_retur) LIKE '%" . strtolower($search) . "%')";
        }
        $sql = "select * from sales.t_retur_sales where 1=1 $sql_search limit $length offset $offset";
        
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select * from sales.t_retur_sales where 1=1 $sql_search) as tabel limit 1";

        $query = $this->db->query($sql2);
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_rows_detail($no_retur = "") {
        
        $sql1 = "SELECT a.*,b.*,nama_produk,kd_produk_supp,  a.rp_total grand_total,a.rp_potongan potongan
				FROM sales.t_retur_sales a
				JOIN sales.t_retur_sales_detail b
					ON a.no_retur = b.no_retur
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
                and a.no_retur = '$no_retur'";
				

        $query = $this->db->query($sql1);
       
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
    public function get_data_print($no_retur = ''){	
		$sql = "select 'RETUR PENJUALAN' title,a.*
                        from sales.t_retur_sales a
                        where no_retur = '$no_retur'
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = " select 'RETUR PENJUALAN' title,a.qty + a.qty_retur_so + a.qty_retur_do qty_retur_total, a.*,b.nama_lokasi2 || '-' ||  c.nama_blok2 || '-' || d.nama_sub_blok2 lokasi, e.nama_produk, e.kd_produk_supp, f.nm_satuan
                                from sales.t_retur_sales_detail a, mst.t_produk e, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d, mst.t_satuan f
                                where a.no_retur = '$no_retur'
                                and a.kd_produk = e.kd_produk
                                and e.kd_satuan = f.kd_satuan
                                and a.kd_lokasi = b.kd_lokasi
                                and a.kd_blok = c.kd_blok
                                and a.kd_lokasi = c.kd_lokasi
                                and a.kd_blok = d.kd_blok
                                and a.kd_lokasi = d.kd_lokasi
                                and a.kd_sub_blok = d.kd_sub_blok";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}

}