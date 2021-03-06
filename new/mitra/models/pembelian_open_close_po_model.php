<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_open_close_po_model extends MY_Model {

   public function __construct() {
        parent::__construct();
    }

    public function get_rows($kd_supplier = "",$tgl_awal = "",$tgl_akhir ="",$tgl ="",$tgl_awal_diperpanjang ="",$tgl_akhir_diperpanjang ="", $search = "",$start,$limit) {
        $where = "";
        $sql_search = "";
		if ($kd_supplier != ""){
			$where .= " AND a.kd_suplier_po = '$kd_supplier'";
		}
                if($tgl_awal != "" && $tgl_akhir != ""){
			$where .=  " AND a.tanggal_po between '$tgl_awal' AND '$tgl_akhir' ";
		}
                if($tgl_awal_diperpanjang != "" && $tgl_akhir_diperpanjang != ""){
			$where .=  " AND a.tgl_berlaku_po2 between '$tgl_awal_diperpanjang' AND '$tgl_akhir_diperpanjang' ";
		}
                if ($search != "") {
                    $sql_search =  " AND (lower(a.no_po) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                    $sql="select a.kd_suplier_po, b.nama_supplier, a.no_po, a.tanggal_po,a.tgl_berlaku_po,a.tgl_berlaku_po2,current_date + a.masa_berlaku_po as tgl_perpanjangan
                     from purchase.t_purchase a, mst.t_supplier b
                     where 
                     a.konsinyasi = '0'
                     and a.approval_po = '1'
                     and a.close_po = '0'
                     and  a.tgl_berlaku_po2 < '$tgl'
                     ".$where."
                     ".$sql_search."
                     and a.kd_suplier_po = b.kd_supplier
                     order by a.tanggal_po desc
                    limit ".$limit." offset ".$start."";
        
        $query = $this->db->query($sql);
       //print_r($query);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2=" select count(*) as total from (select a.kd_suplier_po, b.nama_supplier, a.no_po, a.tanggal_po,a.tgl_berlaku_po,a.tgl_berlaku_po2,a.tgl_berlaku_po2 + a.masa_berlaku_po as tgl_perpanjangan
                     from purchase.t_purchase a, mst.t_supplier b
                     where 
                     a.konsinyasi = '0'
                     and a.approval_po = '1'
                     and a.close_po = '0'
                     ".$where."
                     ".$sql_search."
                     and a.kd_suplier_po = b.kd_supplier
                     order by a.tanggal_po desc)as tabel";
        $query = $this->db->query($sql2);
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_rows_detail($no_po) {
        $sql1 = "SELECT a.kd_produk, nama_produk, qty_beli qty_po, sum(qty_terima) qty_ro, qty_beli- sum(qty_terima)qty_sisa,nm_satuan
                    FROM purchase.t_dtl_receive_order a
                    JOIN mst.t_produk b
                        ON a.kd_produk = b.kd_produk
                    JOIN mst.t_satuan c
                        ON b.kd_satuan = c.kd_satuan
                    WHERE a.no_po = '$no_po'
                    group by a.kd_produk,a.qty_beli, nama_produk, nm_satuan
                    order by kd_produk";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function update_open_closepo_detail($no_po = NULL, $data = NULL) {
        $this->db->where('no_po', $no_po);
        //print_r($this->db->last_query());
        return $this->db->update('purchase.t_purchase', $data);
    }
     public function query_update($sql = "") {
        return $this->db->query($sql);
    }
}