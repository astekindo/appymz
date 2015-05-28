<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjualan_sj_model
 *
 * @author faroq
 */
class Penjualan_sj_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function get_nodo($search = "", $status, $offset, $length) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("((lower(no_do) LIKE '%" . strtolower($search) . "%') OR (pic_penerima LIKE '%" . strtolower($search) . "%'))", NULL);
        }
        $this->db->where("status", $status);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_sales_delivery_order');

        $this->db->order_by('no_do desc');
        $query = $this->db->get('sales.t_sales_delivery_order', $length, $offset);
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();

        return $results;
    }


    public function get_do_detail($no_do = '', $search = '') {
        $sql = "select no_do, kd_barang, sum(qty) qty, sum(qty_sj) qty_sj, sum(qtydo) qtydo, qty_oh,qty_retur_do,
        kd_produk, nama_produk, nm_satuan from (
            SELECT a.*, a.qty qtydo, b.kd_produk, b.nama_produk, c.nm_satuan, e.qty_oh
            FROM sales.t_sales_delivery_order_detail a
            JOIN mst.t_produk b ON b.kd_produk = a.kd_barang
            JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan
            JOIN sales.t_sales_delivery_order d ON d.no_do = a.no_do
            LEFT JOIN (select z.kd_produk, sum(z.qty_oh) qty_oh from inv.t_brg_inventory z group by z.kd_produk) e
            ON e.kd_produk = b.kd_produk
            WHERE a.no_do =  '$no_do'
            AND a.qty > case when a.qty_sj is null then 0 else a.qty_sj end
        ) a
        group by no_do, kd_barang, qty_oh, kd_produk, nama_produk, nm_satuan, qty_retur_do";
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_sj_detail($no_sj) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        $this->db->select("distinct on(a.kd_produk,a.no_sj,d.no_do) a.kd_produk, b.nama_produk, d.qty qty_do, d.qty_sj, e.nm_satuan, a.keterangan, a.qty_kembali, a.ket_kembali", false)
          ->join('mst.t_produk b', 'a.kd_produk = b.kd_produk')
          ->join('sales.t_surat_jalan c', 'a.no_sj = c.no_sj')
          ->join('sales.t_sales_delivery_order_detail d', 'a.kd_produk = d.kd_barang and c.no_do = d.no_do')
          ->join('mst.t_satuan e', 'b.kd_satuan = e.kd_satuan', 'left')
          ->where("c.no_sj", $no_sj);

        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_surat_jalan_detail a');

        $this->db->order_by('a.kd_produk desc');
        $query = $this->db->get('sales.t_surat_jalan_detail a');
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();

        return $results;
        $sql = "select kd_barang, sum(qty) qty, sum(qty_sj) qty_sj, sum(qtydo) qtydo, qty_oh,
        kd_produk, nama_produk, nm_satuan from (
            SELECT a.*, a.qty qtydo, b.kd_produk, b.nama_produk, c.nm_satuan, e.qty_oh
            FROM sales.t_sales_delivery_order_detail a
            JOIN mst.t_produk b ON b.kd_produk = a.kd_barang
            JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan
            JOIN sales.t_sales_delivery_order d ON d.no_do = a.no_do
            LEFT JOIN (select z.kd_produk, sum(z.qty_oh) qty_oh from inv.t_brg_inventory z group by z.kd_produk) e
            ON e.kd_produk = b.kd_produk
            WHERE a.no_do =  '$no_sj'
            AND a.qty > case when a.qty_sj is null then 0 else a.qty_sj end
        ) a
        group by no_do, kd_barang, qty_oh, kd_produk, nama_produk, nm_satuan";
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_nosj($search = "", $offset, $length) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("((lower(no_sj) LIKE '%" . strtolower($search) . "%') OR (pic_penerima LIKE '%" . strtolower($search) . "%'))", NULL);
        }

        $this->db->where('is_kembali', 0);
        $this->db->select("no_do, no_sj, tanggal, pic_penerima, keterangan");
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_surat_jalan');

        $this->db->order_by('no_sj desc');
        $query = $this->db->get('sales.t_surat_jalan', $length, $offset);
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();

        return $results;
    }

    public function get_nosj_kembali($search = "", $offset, $length) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("((lower(no_sj) LIKE '%" . strtolower($search) . "%') OR (pic_penerima LIKE '%" . strtolower($search) . "%'))", NULL);
        }

        $this->db->where('is_kembali', 1);
        $this->db->select("no_do, no_sj, tanggal, pic_penerima, tanggal_kembali, penerima, keterangan");
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_surat_jalan');

        $this->db->order_by('no_sj desc');
        $query = $this->db->get('sales.t_surat_jalan', $length, $offset);
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();

        return $results;
    }



    public function search_ekspedisi($search = "", $offset, $length) {
        if ($search != "") {
            $this->db->where("((lower(nama_ekspedisi) LIKE '%" . strtolower($search) . "%') OR (kd_ekspedisi LIKE '%" . strtolower($search) . "%'))", NULL);
        }
        $this->db->where("aktif", 1);
        $this->db->order_by("nama_ekspedisi");
        $query = $this->db->get("mst.t_ekpedisi", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select("count(*) AS total");
        if ($search != "") {
            $this->db->where("((lower(nama_ekspedisi) LIKE '%" . strtolower($search) . "%') OR (kd_ekspedisi LIKE '%" . strtolower($search) . "%'))", NULL);
        }
        $this->db->where("aktif", 1);
        $query = $this->db->get("mst.t_ekpedisi");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function insert_row($table, $data) {
        return $this->db->insert($table, $data);
    }

    public function update_do($id, $data) {
        $this->db->where('no_do', $id);
        return $this->db->update('sales.t_sales_delivery_order', $data);
    }

    public function update_do_detail($id1 = '', $id2 = '', $data = NULL) {
        $this->db->where('no_do', $id1);
        $this->db->where('kd_barang', $id2);
        return $this->db->update('sales.t_sales_delivery_order_detail', $data);
    }

    public function update_sj($no_sj, $data) {
        $this->db->where('no_sj', $no_sj);
        return $this->db->update('sales.t_surat_jalan', $data);
    }

    public function update_sj_detail($no_sj, $kd_produk, $data) {
        $this->db->where('no_sj', $no_sj);
        $this->db->where('kd_produk', $kd_produk);
        return $this->db->update('sales.t_surat_jalan_detail', $data);
    }

    public function getdo_qty_sj($id1 = '', $id2 = '') {
        $sql = "select CASE WHEN qty_sj is null THEN 0 ELSE qty_sj END qty_sj
                    from sales.t_sales_delivery_order_detail
                    where no_do='$id1' and kd_barang='$id2'";
        $query = $this->db->query($sql);
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->qty_sj;
        }
        //print_r($this->db->last_query());
        return $total;
    }

    public function checkdo_qty_qty_sj($id = '') {
        $this->db->select("count(*) as total");
        $this->db->where('no_do', $id);
        $where = "(qty <> qty_sj or qty_sj is null)";
        $this->db->where($where);
        $query = $this->db->get('sales.t_sales_delivery_order_detail');
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        return $total;
    }

    public function get_data_print($no_sj = '') {
        $sql = "select 'SURAT JALAN FORM' title, a.created_by,coalesce(c.rp_kurang_bayar, 0) rp_kurang_bayar, a.no_sj, a.tanggal, a.no_do, a.no_kendaraan, a.sopir, a.pic_penerima, a.alamat_penerima, a.no_telp_penerima, a.keterangan, c.no_so
                from sales.t_surat_jalan a, sales.t_sales_delivery_order b, sales.t_sales_order c
                where a.no_sj = '$no_sj' and a.no_do = b.no_do and b.no_so = c.no_so";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 lokasi,a.kd_produk, b.kd_produk_lama, b.kd_produk_supp, b.nama_produk, a.qty, c.nm_satuan, a.keterangan, d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 lokasi
                from sales.t_surat_jalan_detail a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
                where a.no_sj = '$no_sj'
                and a.kd_produk = b.kd_produk
                and b.kd_satuan = c.kd_Satuan
                and a.kd_lokasi = d.kd_lokasi
                and a.kd_blok = e.kd_blok
                and a.kd_lokasi = e.kd_lokasi
                and a.kd_sub_blok = f.kd_sub_blok
                and a.kd_blok = f.kd_blok
                and a.kd_lokasi = f.kd_lokasi";



        $query_detail = $this->db->query($sql_detail);
        //print_r($this->db->last_query());
        $data['detail'] = $query_detail->result();

        return $data;
    }

    public function update_brg_inv_sj($id = NULL, $id1 = NULL, $id2 = NULL, $id3 = NULL, $data = NULL) {
        $this->db->where('kd_produk', $id);
        $this->db->where('kd_lokasi', $id1);
        $this->db->where('kd_blok', $id2);
        $this->db->where('kd_sub_blok', $id3);
        return $this->db->update('inv.t_brg_inventory', $data);
    }

    public function cek_exists_brg_inv_sj($kd_produk = null, $kd_lokasi = null, $kd_blok = null, $kd_sub_blok = null) {
        $sql = "select qty_oh from inv.t_brg_inventory
                  where kd_produk='$kd_produk'
                  and kd_lokasi='$kd_lokasi'
                  and kd_blok='$kd_blok'
                  and kd_sub_blok='$kd_sub_blok'";

        $query = $this->db->query($sql);
        $rows = array();

        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }

    public function update_brg_inv($id = NULL, $id1 = NULL, $id2 = NULL, $id3 = NULL, $data = NULL) {
        $this->db->where('kd_produk', $id);
        $this->db->where('kd_lokasi', $id1);
        $this->db->where('kd_blok', $id2);
        $this->db->where('kd_sub_blok', $id3);
        return $this->db->update('inv.t_brg_inventory', $data);
    }

    public function get_lokasi_by_produk($kd_produk = '', $kd_lokasi, $search = '') {
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(a.nama_sub_blok)', strtolower($search));
        }
        if($kd_lokasi != '') {
            $this->db->where('a.kd_lokasi', $kd_lokasi);
        }

        $this->db->where('d.kd_produk', $kd_produk);
        $this->db->select(" a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub,
        c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub,
        c.nama_lokasi2 || '-' || b.nama_blok2 || '-' || a.nama_sub_blok2 nama_sub2,
        a.kd_sub_blok, a.kd_blok, a.kd_lokasi,
        b.nama_blok, c.nama_lokasi, a.nama_sub_blok,
        a.kapasitas, CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif", false)
          ->join('mst.t_blok b', 'b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi')
          ->join('mst.t_lokasi c', 'c.kd_lokasi = b.kd_lokasi')
          ->join('mst.t_produk_lokasi d', 'd.kd_lokasi = c.kd_lokasi and d.kd_blok = b.kd_blok and d.kd_sub_blok = a.kd_sub_blok');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_sub_blok a');

        $this->db->order_by('a.kd_lokasi asc, a.kd_blok asc, a.kd_sub_blok asc');
        $query = $this->db->get('mst.t_sub_blok a');
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;

    }

    function get_qty_by_lokasi($kd_produk, $lokasi, $blok, $subblok) {
        $result = array('data' => null, 'total' => 0);
        $this->db->select('coalesce(qty_oh, 0) qty_oh', false)->where('kd_produk',$kd_produk)->where('kd_lokasi',$lokasi)->where('kd_blok',$blok)->where('kd_sub_blok',$subblok);
        $query = $this->db->get('inv.t_brg_inventory');
        $result['total'] = $query->num_rows();
        $result['lq'] = $this->db->last_query();
        if($result['total'] > 0) $result['data'] = $query->row();

        return $result;
    }
}
