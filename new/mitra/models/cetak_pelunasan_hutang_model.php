<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_pelunasan_hutang_model extends MY_Model {

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
    public function get_rows($kd_supplier="",$kd_peruntukan="", $search = "", $offset, $length) {
        $sql_search = "";
        $peruntukan ="";
        if ($kd_supplier != "") {
            $where .= " AND a.kd_supplier = '$kd_supplier' ";
        }
        if ($search != "") {
            
            $sql_search = " AND (lower(a.no_bukti) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
         if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $peruntukan = " AND a.kd_peruntukan ='$kd_peruntukan'";
        }
        $sql = "select a.*,b.nama_supplier from purchase.t_pelunasan_hutang a
                join mst.t_supplier b ON a.kd_supplier = b.kd_supplier
                where 1=1 
                " . $sql_search . "
                " . $peruntukan . "
		" . $where . "
		limit " . $length . " offset " . $offset . "";
        
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
         $this->db->flush_cache();
         $sql2 = "select count(*) as total from (select a.*,b.nama_supplier from purchase.t_pelunasan_hutang a
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

    public function get_rows_detail($no_bukti = "") {
        
        $sql1 = "select a.*,b.*,a.rp_diskon as potongan, (b.rp_total -b.rp_pelunasan_hutang) sisa_invoice
                from purchase.t_pelunasan_detail a ,purchase.t_invoice b
                where a.no_invoice = b.no_invoice
                and a.no_bukti = '$no_bukti'";
				

        $query = $this->db->query($sql1);
       
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
    public function get_data_print($no_bukti = '') {
       
        $sql = "select 'PEMBAYARAN HUTANG FORM' title, a.*, b.nama_supplier from purchase.t_pelunasan_hutang a, mst.t_supplier b
                where a.kd_supplier = b.kd_supplier
                and a.no_bukti = '$no_bukti'";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        
        $sql_detail = "select a.*,b.*,a.rp_diskon as potongan,c.biaya_lain,c.grand_total
                        from purchase.t_pelunasan_detail a ,purchase.t_invoice b,purchase.t_pelunasan_hutang c
                        where a.no_invoice = b.no_invoice
                        and a.no_bukti = c.no_bukti
                        and a.no_bukti = '$no_bukti'  
                        ";
        
	$query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();
        
        $this->db->flush_cache();
        $sql_detail_bayar = "select c.*,d.nm_pembayaran 
                            from purchase.t_pelunasan_bayar c, mst.t_jns_pembayaran d
                            where c.kd_jns_bayar = d.kd_jenis_bayar
                            and c.no_bukti = '$no_bukti'
                            ";

        $query_detail_bayar = $this->db->query($sql_detail_bayar);
        $data['detail_bayar'] = $query_detail_bayar->result();
        
        $this->db->flush_cache();
        $sql_biaya_lain = "select c.*,d.nm_pembayaran 
                            from purchase.t_pelunasan_biaya_lain c, mst.t_jns_pembayaran d
                            where c.kd_jns_bayar = d.kd_jenis_bayar
                            and c.no_bukti = '$no_bukti'
                            ";

        $query_biaya_lain = $this->db->query($sql_biaya_lain);
        $data['detail_biaya_lain'] = $query_biaya_lain->result();
        // print_r($this->db->last_query());exit;
        return $data;
    }

}