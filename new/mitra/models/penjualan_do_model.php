<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjualan_do_model
 *
 * @author faroq
 */
class Penjualan_do_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_nofaktur($search = "", $offset, $length) {
        $where = "";
        if(!empty($search)){
            $where .= " and j.no_so like '%$search%' ";
        }
        $sql = "select distinct j.rp_kurang_bayar, j.no_so, j.tgl_so, j.kirim_so, j.kirim_alamat_so, j.kirim_telp_so, j.userid, j.keterangan from (
            select a.rp_kurang_bayar,b.kd_produk,SUM(b.qty) qty_order, a.no_so, a.tgl_so, a.kirim_so, a.kirim_alamat_so, a.kirim_telp_so, a.userid, a.keterangan
            from sales.t_sales_order a ,sales.t_sales_order_detail b 
            WHERE b.no_so=a.no_so
            and a.status =  '1'
            AND b.is_kirim =  '1'
            GROUP BY a.rp_kurang_bayar,b.kd_produk, a.no_so, a.tgl_so, a.kirim_so, a.kirim_alamat_so, a.kirim_telp_so, a.userid, a.keterangan
            ) j left join
            (
            select a.no_so, b.kd_barang, sum(qty) qty_do
            from sales.t_sales_delivery_order a, sales.t_sales_delivery_order_detail b
            where a.no_do = b.no_do
            group by a.no_so, b.kd_barang
            ) k on j.no_so = k.no_so
            and j.kd_produk = k.kd_barang
            where 
             coalesce(k.qty_do,0) < j.qty_order" . $where;
        
            $query = $this->db->query($sql .  " LIMIT ". $length . " OFFSET ".$offset);
            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }
         
            //print_r($this->db->last_query());
        
        
        $this->db->flush_cache();

        $query = $this->db->query("select count(*) AS total from (".$sql.") tabel");
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_so_detail($no_so = '', $search = '') {
        $sql = "select kd_produk, qty_so, qty, nama_produk, nm_satuan, qty_oh, qty_do from (
    SELECT a.kd_produk
    , sum(a.qty - a.qty_retur - a.qty_retur_do - a.qty_retur_so) AS qty_so
    , sum(a.qty - a.qty_retur - a.qty_retur_do - a.qty_retur_so) - coalesce(z.qty_do, 0) as qty
    , b.nama_produk
    , c.nm_satuan
    , e.qty_oh
    , coalesce(z.qty_do, 0) qty_do
    FROM sales.t_sales_order_detail a
    JOIN mst.t_produk b ON b.kd_produk = a.kd_produk
    JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan
    JOIN sales.t_sales_order d ON d.no_so = a.no_so
    LEFT JOIN (select kd_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory group by kd_produk) e ON e.kd_produk = b.kd_produk
    LEFT JOIN (
        select x.kd_barang, sum(x.qty) qty_do, sum(x.qty_retur_do) qty_retur_do
        from sales.t_sales_delivery_order_detail x, sales.t_sales_delivery_order y
        where x.no_do = y.no_do
        and y.no_so = '". $no_so ."'
        group by x.kd_barang
    ) z on z.kd_barang = b.kd_produk
    WHERE a.no_so =  '". $no_so ."'
    AND a.is_kirim =  1
    GROUP BY a.kd_produk, b.nama_produk, c.nm_satuan, z.qty_do, e.qty_oh
) detail
";
                
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        //print_r($query = $this->db->last_query());
        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }

    public function update_SO($id1 = '', $id2 = '', $data = NULL) {
        $this->db->where('no_so', $id1);
        $this->db->where('kd_produk', $id2);
        return $this->db->update('sales.t_sales_order_detail ', $data);
    }
    
    public function get_data_print($no_do = '') {
        $sql = "select 'DELIVERY ORDER FORM' title, a.*, b.keterangan ket_kasir
                from sales.t_sales_delivery_order a, sales.t_sales_order b
                where a.no_so = b.no_so and a.no_do = '$no_do'";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select a.*, b.nama_produk, c.nm_satuan 
        from sales.t_sales_delivery_order_detail a, mst.t_produk b, mst.t_satuan c 
        where  a.no_do = '$no_do'
        and a.kd_barang = b.kd_produk
        and b.kd_satuan = c.kd_satuan";

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        return $data;
    }

}

?>
