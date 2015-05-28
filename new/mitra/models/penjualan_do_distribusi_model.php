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
class Penjualan_do_distribusi_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_nofaktur($kd_pelanggan="", $search = "", $offset, $length) {
        $sql_search = "";
        if($search != ""){
            $sql_search =  " AND (lower(a.no_so) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }       
        $sql = "
            select distinct kd_member,j.rp_kurang_bayar, j.no_so, j.tgl_so, j.kirim_so, j.kirim_alamat_so, j.kirim_telp_so, j.userid, j.keterangan from (
            select a.kd_member,a.rp_kurang_bayar,b.kd_produk,SUM(b.qty) qty_order, a.no_so, a.tgl_so, a.kirim_so, a.kirim_alamat_so, a.kirim_telp_so, a.userid, a.keterangan
            from sales.t_sales_order_dist a ,sales.t_sales_order_dist_detail b 
            WHERE b.no_so=a.no_so
            and a.status =  '1'
            AND b.is_kirim =  '1'
            GROUP BY a.kd_member,a.rp_kurang_bayar,b.kd_produk, a.no_so, a.tgl_so, a.kirim_so, a.kirim_alamat_so, a.kirim_telp_so, a.userid, a.keterangan
            ) j left join
            (
            select a.no_so, b.kd_barang, sum(qty) qty_do
            from sales.t_sales_delivery_order_dist a, sales.t_sales_delivery_order_dist_detail b
            where a.no_do = b.no_do
            group by a.no_so, b.kd_barang
            ) k on j.no_so = k.no_so
            and j.kd_produk = k.kd_barang
            where 
             coalesce(k.qty_do,0) < j.qty_order
             and kd_member = '$kd_pelanggan' ".$sql_search."";
        
        // $sql = "select a.*,b.kd_produk,b.qty as qty_so
        //         from sales.t_sales_order_dist a , sales.t_sales_order_dist_detail b
        //         where a.no_so = b.no_so and a.kd_member = '$kd_pelanggan' ".$sql_search."";
            $query = $this->db->query($sql .  " LIMIT ". $length . " OFFSET ".$offset);
            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }
        
            //print_r($this->db->last_query());
        
        
        $this->db->flush_cache();
        /**$this->db->select("count(distinct a.*) AS total");
        $this->db->join('sales.t_sales_order_detail  b', 'b.no_so=a.no_so');
        if ($search != "") {
            $this->db->where("((lower(a.no_so) LIKE '%" . $search . "%') OR (a.no_so LIKE '%" . $search . "%'))", NULL);
        }

        $this->db->where("a.status", '1');
        $this->db->where("b.is_kirim", '1');
        $query = $this->db->get("sales.t_sales_order a");**/

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
        

       $sql = "SELECT a.kd_produk, a.qty AS qty_so, a.qty - coalesce(z.qty_do, 0) as qty, b.nama_produk, c.nm_satuan, coalesce(e.qty_oh,0) qty_oh, coalesce(z.qty_do, 0) qty_do 
                FROM sales.t_sales_order_dist_detail a 
                JOIN mst.t_produk b ON b.kd_produk = a.kd_produk 
                JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan 
                JOIN sales.t_sales_order_dist d ON d.no_so = a.no_so 
                LEFT JOIN (select kd_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory group by kd_produk) e ON e.kd_produk = b.kd_produk 
                LEFT JOIN ( 
                        select x.kd_barang, sum(x.qty) qty_do 
                        from sales.t_sales_delivery_order_dist_detail x, sales.t_sales_delivery_order_dist y 
                        where x.no_do = y.no_do and y.no_so = '".$no_so."' group by x.kd_barang ) z on z.kd_barang = b.kd_produk 
                WHERE a.no_so = '".$no_so."' AND a.is_do = '0' AND a.is_kirim = 1 AND coalesce(z.qty_do, 0) < a.qty ";
//        $sql = "select a.qty, a.kd_produk, b.nama_produk, c.qty_oh, d.nm_satuan
//                from sales.t_sales_order_dist_detail a
//                JOIN mst.t_produk b ON a.kd_produk = b.kd_produk
//                LEFT JOIN inv.t_brg_inventory c ON b.kd_produk = c.kd_produk
//                LEFT JOIN mst.t_satuan d ON b.kd_satuan = d.kd_satuan
//                where a.no_so ='$no_so'";      
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
        return $this->db->update('sales.t_sales_order_dist_detail ', $data);
    }
    
    public function get_data_print($no_do = '') {
        $sql = "select 'DELIVERY ORDER (DISTRIBUSI) FORM' title, a.*, b.keterangan ket_kasir
                from sales.t_sales_delivery_order_dist a, sales.t_sales_order_dist b
                where a.no_so = b.no_so and a.no_do = '$no_do'";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select a.*, b.nama_produk, c.nm_satuan 
        from sales.t_sales_delivery_order_dist_detail a, mst.t_produk b, mst.t_satuan c 
        where  a.no_do = '$no_do'
        and a.kd_barang = b.kd_produk
        and b.kd_satuan = c.kd_satuan";

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        return $data;
    }

}

?>
