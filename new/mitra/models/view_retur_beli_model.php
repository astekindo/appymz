<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View_retur_beli_model extends MY_Model {

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
            $sql_search = "and (lower(no_retur) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_retur,tgl_retur from purchase.t_retur_purchase where retur_type = '1' " . $sql_search . "  
                  order by tgl_retur desc limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from purchase.t_retur_purchase
                        where retur_type = '1'";

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

    public function get_rows($kd_produk = "", $tglAwal = "", $tglAkhir = "", $no_retur = "", $kd_supplier = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        $left = " left ";
        if ($kd_produk != "") {
            $where .= " AND a.kd_produk = '$kd_produk' ";
        }

        if ($no_retur != "") {
            $where .= " AND a.no_retur = '$no_retur' ";
        }

        if ($kd_supplier != "") {
            $where .= " AND b.kd_suplier = '$kd_supplier' ";
        }
        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND b.tgl_retur between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($search != "") {
            $sql_search = " AND ((lower(a.no_retur) LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . $search . "%'))";
            $this->db->where($sql_search);
        }
        // $this->db->where('status','0');
        $sql = "select a.no_retur,a.kd_produk,a.qty,b.tgl_retur,b.kd_suplier,c.nama_produk,d.nama_supplier
                from purchase.t_retur_purchase_detail a, purchase.t_retur_purchase b, mst.t_produk c, mst.t_supplier d
                where a.no_retur = b.no_retur
                and a.kd_produk = c.kd_produk
                and b.kd_suplier = d.kd_supplier
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
        $sql2 = "select count(*) as total from (select a.no_retur,a.kd_produk,a.qty,b.tgl_retur,b.kd_suplier,c.nama_produk,d.nama_supplier
                from purchase.t_retur_purchase_detail a, purchase.t_retur_purchase b, mst.t_produk c, mst.t_supplier d
                where a.no_retur = b.no_retur
                and a.kd_produk = c.kd_produk
                and b.kd_suplier = d.kd_supplier
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
        $sql = <<<EOT
select
    a.*,
    b.remark,b.pcn_ppn,b.rp_ppn,b.rp_total as grand_total,b.tgl_retur,
    c.nama_produk,c.kd_produk_supp,
    d.nama_lokasi || ' - ' ||  e.nama_blok || ' - ' || f.nama_sub_blok lokasi,
    g.nama_supplier,
    h.no_faktur_pajak
from
    purchase.t_retur_purchase_detail a,
    purchase.t_retur_purchase b,
    mst.t_produk c,
    mst.t_lokasi d,
    mst.t_blok e,
    mst.t_sub_blok f,
    mst.t_supplier g,
    purchase.t_invoice h
where
    a.no_retur = b.no_retur
    and a.no_invoice = h.no_invoice
    and a.kd_produk = c.kd_produk
    and a.kd_lokasi = d.kd_lokasi
    and a.kd_blok = e.kd_blok
    and a.kd_lokasi = e.kd_lokasi
    and a.kd_sub_blok = f.kd_sub_blok
    and a.kd_blok = f.kd_blok
    and a.kd_lokasi = f.kd_lokasi
    and b.kd_suplier = g.kd_supplier
    and a.no_retur = '$no_retur'
EOT;

        $query = $this->db->query($sql);


        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql1 = "select a.*,b.pcn_ppn,b.rp_ppn,b.rp_total as grand_total,h.no_faktur_pajak,
	b.tgl_retur,c.nama_produk,d.nama_lokasi || ' - ' ||  e.nama_blok || ' - ' || f.nama_sub_blok lokasi, g.nama_supplier
        from purchase.t_retur_purchase_detail a, purchase.t_retur_purchase b, mst.t_produk c, mst.t_lokasi d ,mst.t_blok e, mst.t_sub_blok f, mst.t_supplier g, purchase.t_invoice h
        where a.no_retur = b.no_retur
          and a.kd_produk = c.kd_produk
          and a.kd_lokasi = d.kd_lokasi
          and a.kd_blok = e.kd_blok
          and a.kd_lokasi = e.kd_lokasi
          and a.kd_sub_blok = f.kd_sub_blok
          and a.kd_blok = f.kd_blok
          and a.kd_lokasi = f.kd_lokasi
          and b.kd_suplier = g.kd_supplier
          and a.no_invoice = h.no_invoice
          and a.no_retur = '$no_retur'";
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
