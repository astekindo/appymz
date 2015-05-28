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
class Penjualan_sj_distribusi_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function get_nodo($kd_pelanggan = "",$no_so ="", $search = "", $offset, $length) {
        $search = strtolower($search);
        $this->db->select("a.*", FALSE);
        $this->db->distinct();

        if ($search != "") {
            $this->db->where("((lower(a.no_do) LIKE '%" . $search . "%') OR (a.pic_penerima LIKE '%" . $search . "%'))", NULL);
        }
        $this->db->where("a.kd_peruntukan", '1');
        $this->db->where("a.kd_pelanggan", $kd_pelanggan);
        $this->db->join("sales.t_sales_delivery_order_dist_detail b", 'b.no_do = a.no_do');
        $this->db->where("a.no_so", $no_so);
        $this->db->where('b.qty > coalesce(b.qty_sj, 0)'); 
        $query = $this->db->get('sales.t_sales_delivery_order_dist a', $length, $offset);
        
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $this->db->flush_cache();
        // print_r($this->db->last_query()); die();
        $this->db->select("count(*) AS total");

        if ($search != "") {
            $this->db->where("((lower(a.no_do) LIKE '%" . $search . "%') OR (a.pic_penerima LIKE '%" . $search . "%'))", NULL);
        }
        $this->db->where("a.kd_peruntukan", '1');
        $this->db->where("a.kd_pelanggan", $kd_pelanggan);
        $this->db->where("a.no_so", $no_so);
        $this->db->join("sales.t_sales_delivery_order_dist_detail b", 'b.no_do = a.no_do');
        $this->db->where('b.qty > b.qty_sj'); 
        $query = $this->db->get('sales.t_sales_delivery_order_dist a', $length, $offset);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        
        return $results;
    }

    public function search_lokasi($search = "", $offset, $length) {
        $this->db->select("kd_lokasi,nama_lokasi", FALSE);

        if ($search != "") {
            $this->db->where("(lower(nama_lokasi) LIKE '%" . $search . "%')", NULL);
        }
        $this->db->where("aktif is TRUE");
        $this->db->where("kd_peruntukan ='1'");
        $query = $this->db->get('mst.t_lokasi', $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }
    public function get_do_detail($no_do = '', $search = '') {
        if ($search != '') {
            $this->db->where("((lower(b.nama_produk) LIKE '%" . $search . "%') OR (b.kd_produk LIKE '%" . $search . "%'))", NULL);
        }
        $this->db->select("a.*,a.qty qtydo,b.kd_produk,b.nama_produk,c.nm_satuan, sum(e.qty_oh)qty_oh");
        $this->db->join("mst.t_produk b", "b.kd_produk = a.kd_barang");
        $this->db->join("mst.t_satuan c", "c.kd_satuan = b.kd_satuan");
        $this->db->join("sales.t_sales_delivery_order_dist d", "d.no_do = a.no_do");
        $this->db->join("inv.t_brg_inventory e", "e.kd_produk = b.kd_produk", 'left');
        $this->db->where("a.no_do", $no_do);
        $where = "a.qty > case when a.qty_sj is null then 0 else a.qty_sj end";
        $this->db->where($where);
        $this->db->group_by("a.no_do,a.kd_barang,a.qty,a.qty_sj,b.kd_produk,b.nama_produk,c.nm_satuan");
        $query = $this->db->get("sales.t_sales_delivery_order_dist_detail a");
        //print_r($this->db->last_query());
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_noso($kd_pelanggan="", $search = "", $offset, $length) {
               
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
                select a.no_so, b.kd_produk, sum(qty) qty_do
                from sales.t_surat_jalan_dist a, sales.t_surat_jalan_dist_detail b
                where a.no_sj = b.no_sj group by a.no_so, b.kd_produk
            ) k on j.no_so = k.no_so and j.kd_produk = k.kd_produk
            where 
                coalesce(k.qty_do,0) < j.qty_order
                and kd_member = '$kd_pelanggan'
             ";

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
    public function get_rows_lokasi($kd_produk = '',$kd_lokasi = '', $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = "SELECT e.qty_oh,a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, d.nama_lokasi || '-' || c.nama_blok || '-' || b.nama_sub_blok nama_sub,
					a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_sub_blok,  c.nama_blok, d.nama_lokasi, b.kapasitas,
					CASE WHEN d.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
					FROM mst.t_produk_lokasi a
					JOIN mst.t_sub_blok b
						ON b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					JOIN mst.t_blok c
						ON c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
					JOIN mst.t_lokasi d
						ON d.kd_lokasi = a.kd_lokasi
                                        JOIN inv.t_brg_inventory e ON a.kd_produk = e.kd_produk 
                                                and e.kd_lokasi = a.kd_lokasi
                                                and e.kd_sub_blok = a.kd_sub_blok
                                                and e.kd_blok = a.kd_blok
					WHERE a.kd_produk = '$kd_produk'
                                            and a.kd_lokasi ='$kd_lokasi'
					" . $sql_search . "
					LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        //print_r($this->db->last_query());exit;

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					" . $sql_search . "";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function search_ekspedisi($search = "", $offset, $length) {
        if ($search != "") {
            $this->db->where("((lower(nama_ekspedisi) LIKE '%" . $search . "%') OR (kd_ekspedisi LIKE '%" . $search . "%'))", NULL);
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
            $this->db->where("((lower(nama_ekspedisi) LIKE '%" . $search . "%') OR (kd_ekspedisi LIKE '%" . $search . "%'))", NULL);
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
    

    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }

    public function update_do($id = NULL, $data = NULL) {
        $this->db->where('no_do', $id);
        return $this->db->update('sales.t_sales_delivery_order_dist', $data);
    }

    public function update_do_detail($id1 = '', $id2 = '', $data = NULL) {
        $this->db->where('no_do', $id1);
        $this->db->where('kd_barang', $id2);
        return $this->db->update('sales.t_sales_delivery_order_dist_detail', $data);
    }

    public function getdo_qty_sj($id1 = '', $id2 = '') {
        $sql = "select CASE WHEN qty_sj is null THEN 0 ELSE qty_sj END qty_sj 
                    from sales.t_sales_delivery_order_dist_detail 
                    where no_do='$id1' and kd_barang='$id2'";
        $query = $this->db->query($sql);
//                $this->db->select("qty_sj, CASE WHEN qty_sj is null THEN 0 ELSE qty_sj END qty_sjdo");
//            	$this->db->where('no_do', $id1);		
//                $this->db->where('kd_barang', $id2);
//                $query=$this->db->get('sales.t_sales_delivery_order_dist_detail');
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
        $query = $this->db->get('sales.t_sales_delivery_order_dist_detail');
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        //print_r($this->db->last_query());
        return $total;
    }

    public function get_data_print($no_sj = '') {
        $sql = "select 'SURAT JALAN (DISTRIBUSI) FORM' title, a.created_by,coalesce(c.rp_kurang_bayar, 0) rp_kurang_bayar, a.no_sj, a.tanggal, a.no_do, a.no_kendaraan, a.sopir, a.pic_penerima, a.alamat_penerima, a.no_telp_penerima, a.keterangan, c.no_so
                from sales.t_surat_jalan_dist a,  sales.t_sales_order_dist c
                where a.no_sj = '$no_sj' and a.no_so = c.no_so";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;
        
        $data['header'] = $query->row();
        $this->db->flush_cache();
        $sql_detail = "select d.nama_lokasi || ' - ' || e.nama_blok || ' - ' || f.nama_sub_blok lokasi,a.kd_produk, b.kd_produk_lama, b.kd_produk_supp, b.nama_produk, a.qty, a.no_do, c.nm_satuan, a.keterangan, d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 lokasi
                from sales.t_surat_jalan_dist_detail a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
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

}

?>
