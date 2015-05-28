<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_view_pelunasan_model extends MY_Model {

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
    public function search_nobukti($kd_peruntukan ="",$search = "", $offset, $length) {
        $sql_search = " ";
        $peruntukan =" ";
        if ($search != "") {
            $sql_search = "AND (lower(no_bukti) LIKE '%" . strtolower($search) . "%' )";
        }
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0') {
            $peruntukan = "AND kd_peruntukan ='$kd_peruntukan'";
        }
        $sql1 = "select no_bukti,tanggal from purchase.t_pelunasan_hutang where 1=1 " . $sql_search . " ".$peruntukan." and no_bukti like 'PK%' order by tanggal desc 
                  limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);
        
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total 
			from purchase.t_pelunasan_hutang
                        where 1=1 ".$peruntukan." and no_bukti like 'PK%'";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    

    public function get_rows($tglAwal = "", $tglAkhir = "", $no_bukti = "", $kd_supplier = "", $search = "", $offset, $length) {
        $sql_search = "";
        $where = "";
        $left = " left ";
        
        if ($no_bukti != "") {
            $where .= " AND a.no_bukti = '$no_bukti' ";
        }

        if ($kd_supplier != "") {
            $where .= " AND b.kd_supplier = '$kd_supplier' ";
        }
        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND a.tanggal between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($search != "") {
            $sql_search = " AND ((lower(a.no_bukti) LIKE '%" . strtolower($search) . "%') )";
            $this->db->where($sql_search);
        }
        // $this->db->where('status','0');
        $sql = "select a.*,b.nama_supplier
                from purchase.t_pelunasan_hutang a,  mst.t_supplier b
                where  a.kd_supplier = b.kd_supplier
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
        $sql2 = "select count(*) as total from (select a.*,b.nama_supplier
                from purchase.t_pelunasan_hutang a,  mst.t_supplier b
                where  a.kd_supplier = b.kd_supplier
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

    public function get_data_html($no_bukti = '') {
        $sql = "select 'VIEW FORM PEMBAYARAN HUTANG' title, a.*, b.nama_supplier from purchase.t_pelunasan_hutang a, mst.t_supplier b
                where a.kd_supplier = b.kd_supplier
                and a.no_bukti = '$no_bukti'";

        $query = $this->db->query($sql);


        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql1 = "select a.*,b.*,a.rp_diskon as potongan
                        from purchase.t_pelunasan_detail a ,purchase.t_invoice b
                        where a.no_invoice = b.no_invoice
                        and a.no_bukti = '$no_bukti' ";
        
        $query1 = $this->db->query($sql1);
        $data['detail'] = $query1->result();
        
        $this->db->flush_cache();
		$sql_detail_bayar = "select c.*,d.nm_pembayaran 
                                    from purchase.t_pelunasan_bayar c, mst.t_jns_pembayaran d
                                    where c.kd_jns_bayar = d.kd_jenis_bayar
                                    and c.no_bukti = '$no_bukti'
                                    ";
		
		$query_detail_bayar = $this->db->query($sql_detail_bayar);
		
		$data['detail_bayar'] = $query_detail_bayar->result();
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
