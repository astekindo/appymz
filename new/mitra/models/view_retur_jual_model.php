<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View_retur_jual_model extends MY_Model {

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
    public function search_noretur($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_retur) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_retur,tgl_retur from sales.t_retur_sales " . $sql_search . " order by no_retur desc  
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

    public function search_member($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(kd_member) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select kd_member,nmmember from mst.t_member " . $sql_search . "  
                  limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from mst.t_member";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_rows($kd_produk = "", $tglAwal = "", $tglAkhir = "", $no_retur = "", $no_so = "", $kd_member = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        $left = " left ";
        if ($kd_produk != "") {
            $where .= " AND a.kd_produk = '$kd_produk' ";
        }

        if ($no_retur != "") {
            $where .= " AND a.no_retur = '$no_retur' ";
        }

        if ($no_so != "") {
            $where .= " AND b.no_so = '$no_so' ";
        }

        if ($kd_member != "") {
            $where .= " AND a.kd_member = '$kd_member' ";
        }

        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND b.tgl_retur between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($search != "") {
            $sql_search = " AND ((lower(a.no_retur) LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
            $this->db->where($sql_search);
        }
        // $this->db->where('status','0');
        $sql = "select a.no_retur,a.kd_produk,a.qty,b.no_so,b.tgl_retur,c.nama_produk, c.kd_produk_supp
                from sales.t_retur_sales_detail a, sales.t_retur_sales b, mst.t_produk c
                where a.no_retur = b.no_retur
                and a.kd_produk = c.kd_produk
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
        $sql2 = "select count(*) as total from (select a.no_retur,a.kd_produk,a.qty,b.no_so,b.tgl_retur,c.nama_produk ,c.kd_produk_supp
                from sales.t_retur_sales_detail a, sales.t_retur_sales b, mst.t_produk c
                where a.no_retur = b.no_retur
                and a.kd_produk = c.kd_produk
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

    public function get_data_html($no_retur = '') {
       $sql = "select 'RETUR PENJUALAN' title,a.*
                        from sales.t_retur_sales a
                        where no_retur = '$no_retur'
                        ";

        $query = $this->db->query($sql);


        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = " select 'RETUR PENJUALAN' title, a.*,b.nama_lokasi || ' - ' ||  c.nama_blok || ' - ' || d.nama_sub_blok lokasi, e.nama_produk, e.kd_produk_supp, f.nm_satuan 
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
        $query1 = $this->db->query($sql_detail);
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
