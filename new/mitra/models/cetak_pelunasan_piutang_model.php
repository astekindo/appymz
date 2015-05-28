<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_pelunasan_piutang_model extends MY_Model {

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
    public function get_rows($tglAwal = "", $tglAkhir = "", $no_so = "", $search = "", $offset, $length) {
        $sql_search = "";
       if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND a.tanggal between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($no_so != "") {
            $where .= " AND b.no_faktur = '$no_so' ";
        }
        if ($search != "") {
            
            $sql_search = " AND (lower(a.no_pelunasan_piutang) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select distinct a.* from sales.t_piutang_pelunasan a, sales.t_piutang_detail b
                where a.no_pelunasan_piutang = b.no_pelunasan_piutang 
                " . $sql_search . "
		" . $where . "
		limit " . $length . " offset " . $offset . "";
        
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
         $this->db->flush_cache();
         $sql2 = "select count(*) as total from (select distinct a.* from sales.t_piutang_pelunasan a, sales.t_piutang_detail b
                    where a.no_pelunasan_piutang = b.no_pelunasan_piutang
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
        
        $sql1 = "select a.*,b.* 
                from sales.t_piutang_detail a , sales.t_sales_order b
                where a.no_faktur = b.no_so
                and a.no_pelunasan_piutang = '$no_bukti'";
				

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
        
        $sql_detail = "select a.*,b.*,a.rp_diskon as potongan
                        from purchase.t_pelunasan_detail a ,purchase.t_invoice b
                        where a.no_invoice = b.no_invoice
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
        // print_r($this->db->last_query());exit;
        return $data;
    }

}