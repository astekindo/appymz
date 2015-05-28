<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_retur_receive_order_model extends MY_Model {

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
    public function get_rows($kd_supplier="", $search = "", $offset, $length) {
        $sql_search = "";
        if ($kd_supplier != "") {
            $where .= " AND a.kd_suplier = '$kd_supplier' ";
        }
        if ($search != "") {
            $sql_search = " AND (lower(a.no_retur) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select a.*,b.nama_supplier from purchase.t_retur_purchase a 
                join mst.t_supplier b ON a.kd_suplier = b.kd_supplier
                where 1=1 and retur_type = '0'
                " . $sql_search . "
		" . $where . " order by a.no_retur desc
		limit " . $length . " offset " . $offset . "";
        
        $query = $this->db->query($sql);
        
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select a.*,b.nama_supplier from purchase.t_retur_purchase a 
                join mst.t_supplier b ON a.kd_suplier = b.kd_supplier
                where 1=1 and retur_type = '0'
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

    public function get_rows_detail($no_retur = "") {
        
        $sql1 = "SELECT a.*,b.*,nama_produk, nm_satuan,  a.rp_total grand_total
				FROM purchase.t_retur_purchase a
				JOIN purchase.t_retur_purchase_detail b
					ON a.no_retur = b.no_retur
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
                                where a.no_retur = '$no_retur' order by tgl_retur desc";
				

        $query = $this->db->query($sql1);
       
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
    public function get_data_print($no_retur = '') {
       
       $sql = "select 'RETUR PEMBELIAN' title, b.pkp, a.created_by, a.no_retur, a.tgl_retur, a.remark, b.nama_supplier, a.rp_jumlah, a.pcn_ppn, a.rp_ppn, a.rp_total, a.kd_suplier, b.nama_supplier 
					from purchase.t_retur_purchase a, mst.t_supplier b
					where a.no_retur = '$no_retur'
					and a.kd_suplier = b.kd_supplier
					and a.is_konsinyasi = 0";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        
        $sql_detail = "select 'RETUR PEMBELIAN' title, a.*,b.nama_produk, b.kd_produk_supp,c.nm_satuan ,d.no_faktur_pajak
							from purchase.t_retur_purchase_detail a, 
							mst.t_produk b, mst.t_satuan c ,purchase.t_invoice d
							where a.no_retur = '$no_retur'
							and a.kd_produk = b.kd_produk
							and b.kd_satuan = c.kd_satuan
							and a.no_invoice = d.no_invoice";
						

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        // print_r($this->db->last_query());exit;
        return $data;
    }
    
    public function get_data_html($no_retur = '') {
        $sql = "select 'RETUR RECEIVE ORDER' title, b.pkp,c.no_do,c.no_po, d.tanggal_po, e.tanggal, a.created_by, a.no_retur, a.tgl_retur, a.remark, b.nama_supplier, a.rp_jumlah, a.pcn_ppn, a.rp_ppn, a.rp_total, a.kd_suplier, b.nama_supplier 
                from purchase.t_retur_purchase a, mst.t_supplier b, purchase.t_retur_purchase_detail c, purchase.t_purchase d, purchase.t_receive_order e
                where a.no_retur = '$no_retur'
                and a.kd_suplier = b.kd_supplier
                and a.no_retur = c.no_retur
                and c.no_po = d.no_po
                and c.no_do = e.no_do
                and a.is_konsinyasi = 0";

        $query = $this->db->query($sql);


        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql1 = "select a.*,b.pcn_ppn,b.rp_ppn,b.rp_total as grand_total,
                    b.tgl_retur,c.nama_produk,d.nama_lokasi || ' - ' ||  e.nama_blok || ' - ' || f.nama_sub_blok lokasi, g.nama_supplier
                from purchase.t_retur_purchase_detail a, purchase.t_retur_purchase b, mst.t_produk c, mst.t_lokasi d ,mst.t_blok e, mst.t_sub_blok f, mst.t_supplier g
                where a.no_retur = b.no_retur
                and a.kd_produk = c.kd_produk
                and a.kd_lokasi = d.kd_lokasi
                and a.kd_blok = e.kd_blok
                and a.kd_lokasi = e.kd_lokasi
                and a.kd_sub_blok = f.kd_sub_blok
                and a.kd_blok = f.kd_blok
                and a.kd_lokasi = f.kd_lokasi
                and b.kd_suplier = g.kd_supplier
                and a.no_retur = '$no_retur'";
        $query1 = $this->db->query($sql1);
        $data['detail'] = $query1->result();

        return $data;
    }

}