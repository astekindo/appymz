<?php
/**
 * Created by PhpStorm.
 * User: FIDZAL
 * Date: 5/19/14
 * Time: 8:07 PM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_paket_model extends MY_Model {

    public function __construct(){
        parent::__construct();
    }

    public function get_rows($search, $limit, $start) {
        $sql_search = '';
        $results= array('data' => null, 'total' => 0);

        if ($search != "") {
            $sql_search = "AND (lower(f.nama_produk) LIKE '%" . strtolower($search) . "%')";
        }
        $count = "SELECT count(*) as total
            FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g
            WHERE e.kd_satuan=f.kd_satuan
            AND g.kd_ukuran = f.kd_ukuran
            AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
            AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
            AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
            AND d.kd_kategori1=c.kd_kategori1
            AND f.is_barang_paket=0
            AND f.aktif = 1 $sql_search";

        $fetch = "SELECT d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori,
            d.nama_kategori1,c.nama_kategori2,b.nama_kategori3,a.nama_kategori4,f.*,
            CASE WHEN f.kd_peruntukkan = '0' THEN 'Supermarket' ELSE 'Distribusi' END kd_peruntukkan,
            CASE WHEN f.aktif = 1 THEN 'Ya' ELSE 'Tidak' END aktif, e.*
            FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g
            WHERE e.kd_satuan=f.kd_satuan
            AND	g.kd_ukuran = f.kd_ukuran
            AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
            AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
            AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
            AND d.kd_kategori1=c.kd_kategori1
            AND f.is_barang_paket=0
            AND f.aktif = 1 $sql_search ORDER BY kd_produk DESC LIMIT $limit OFFSET $start";

        $results['lq'] = $count;
        $query = $this->db->query($count);
        if($query->num_rows() > 0){
            $row = $query->row();
            $results['total'] = $row->total;
            $query = $this->db->query($fetch);
            $results['data'] = $query->result();
        }

        return $results;
    }

    public function get_produk($search, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $where = "(lower(a.nama_produk) LIKE '%" . strtolower($search) . "%')";
            $this->db->or_where($where, null, false);
        }
        $this->db->where('a.is_barang_paket',1);

        $barang = 'a.kd_produk, ' .
          'a.kd_produk_lama, ' .
          'a.kd_produk_supp, ' .
          'a.nama_produk, ' .
          '(SELECT COALESCE(sum(qty_oh),0,sum(qty_oh)) jml_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk) jml_stok';
        $this->db->select($barang,false);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_produk a');

        $this->db->order_by('a.nama_produk asc');
        $query = $this->db->get('mst.t_produk a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_produk_detail($kd_produk) {
        $results['lq'] = <<<EOT
SELECT distinct on(e.kd_produk) e.kd_produk, g.*,h.nama_ukuran, f.nm_satuan, e.*,
    d.nama_kategori1, c.nama_kategori2, b.nama_kategori3, a.nama_kategori4,j.pct_alert, k.qty_paket,
    (SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_bonus) as nama_produk_bonus,
    (SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_member) as nama_produk_member,
    coalesce(kd_peruntukkan, '0', kd_peruntukkan) as kd_peruntukkan,
    coalesce(g.hrg_beli_sup, 0, g.hrg_beli_sup) as hrg_beli_sup,
    coalesce(g.rp_ongkos_kirim, 0, g.rp_ongkos_kirim) as rp_ongkos_kirim,
    coalesce(g.pct_margin, 0, g.pct_margin) as pct_margin,
    coalesce(g.rp_margin, 0, g.rp_margin) as rp_margin,
    coalesce(g.rp_het_harga_beli, 0, g.rp_het_harga_beli) as rp_het_harga_beli,
    coalesce(g.rp_jual_supermarket, 0, g.rp_jual_supermarket) as rp_jual_supermarket,
    coalesce(i.net_hrg_supplier_sup_inc, 0, i.net_hrg_supplier_sup_inc) as net_hrg_supplier_sup_inc,
    coalesce(i.net_hrg_supplier_dist_inc, 0, i.net_hrg_supplier_dist_inc) as net_hrg_supplier_dist_inc,
    coalesce(g.rp_jual_distribusi, 0, g.rp_jual_distribusi) as rp_jual_distribusi,
    coalesce(g.qty_beli_bonus, 0) as qty_beli_bonus,
    coalesce(g.qty_bonus, 0) as qty_bonus,
    coalesce(g.qty_beli_member, 0) as qty_beli_member,
    coalesce(g.qty_member, 0) as qty_member
    FROM mst.t_produk e
    JOIN mst.t_kategori4 a
        ON e.kd_kategori3=a.kd_kategori3 AND e.kd_kategori2=a.kd_kategori2 AND e.kd_kategori1=a.kd_kategori1 AND e.kd_kategori4=a.kd_kategori4
    JOIN mst.t_kategori3 b
        ON b.kd_kategori3=e.kd_kategori3 AND b.kd_kategori2=e.kd_kategori2 AND b.kd_kategori1=e.kd_kategori1
    JOIN mst.t_kategori2 c
        ON c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
    JOIN mst.t_kategori1 d
        ON d.kd_kategori1 = c.kd_kategori1
    LEFT JOIN mst.t_satuan f
        ON f.kd_satuan = e.kd_satuan
    LEFT JOIN mst.t_ukuran h
        ON h.kd_ukuran = e.kd_ukuran
    LEFT JOIN mst.t_diskon_sales g
        ON g.kd_produk = e.kd_produk
    LEFT JOIN mst.t_supp_per_brg i
        ON i.kd_produk = e.kd_produk
    LEFT JOIN inv.t_stok_setting j
        ON e.kd_produk = j.kd_produk
    LEFT JOIN (select kd_produk, sum(qty_oh) qty_paket from inv.t_brg_inventory group by kd_produk) k
        ON e.kd_produk = k.kd_produk
    WHERE
    e.kd_produk = '$kd_produk'
EOT;
        $query = $this->db->query($results['lq']);
        $results['total'] = $query->num_rows();
        $results['data'] = $query->result();

        return $results;
    }

    public function get_kategori1($search, $limit, $start){
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("(lower(nama_kategori1) LIKE '%" . strtolower($search) . "%' )", null, false);
        }

        $this->db->select('kd_kategori1, nama_kategori1');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_kategori1');

        $this->db->order_by('kd_kategori1 desc');
        $query = $this->db->get('mst.t_kategori1', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;

    }

    public function get_produk_paket($kd_produk = ""){
        $this->db->select("a.*, nama_produk", FALSE);
        $this->db->join("mst.t_produk b","b.kd_produk = a.kd_produk_paket");
        $this->db->where("a.kd_produk",$kd_produk);
        $query = $this->db->get("mst.t_produk_paket a");

        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }

        return $rows;
    }

    public function get_detail_paket($kd_produk_paket) {
        $results = array('data' => null, 'total' => 0);
        $query = $this->db->query("select c.* , b.nama_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory a " .
          "join mst.t_produk b on a.kd_produk = b.kd_produk " .
          "join mst.t_produk_paket c on a.kd_produk = c.kd_produk " .
          "where a.kd_produk in(select kd_produk from mst.t_produk_paket where kd_produk_paket = '$kd_produk_paket') " .
          "group by c.kd_produk, b.nama_produk, c.kd_produk_paket");

        $results['total']   = $query->num_rows();
        $results['lq']      = $this->db->last_query();
        $results['data']    = $query->result();

        return $results;
    }

    public function search_produk_paket($kd_produk = "", $search = "", $length, $offset){
        $results = array('data' => null, 'total' => 0);
        $sql_search = "";
        if($kd_produk != "") {
            $sql_search = " AND a.kd_produk <> '$kd_produk ";
        }
        if($search != "") {
            $sql_search = " AND (lower(a.nama_produk) LIKE '%" . strtolower($search) . "%') OR a.kd_produk LIKE '%".$search."%'";
        }
        $query = $this->db->query("SELECT a.kd_produk,nama_produk,
            (SELECT COALESCE(sum(qty_oh),0) jml_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk) jml_stok,
            b.rp_jual_supermarket, coalesce(a.rp_cogs, 0) rp_cogs FROM mst.t_produk a
            JOIN mst.t_diskon_sales b ON a.kd_produk = b.kd_produk WHERE 1=1
            $sql_search ORDER BY nama_produk ASC LIMIT $length OFFSET $offset");
        $results['data'] = $query->result();

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_produk a WHERE 1=1 $sql_search";

        $results['total']   = 0;
        $query              = $this->db->query($sql2);
        $results['total']   = $query->row()->total;

        return $results;

    }

    public function get_stok_oh($kd_produk) {
        $query = $this->db->query("SELECT sum(qty_oh) qty FROM inv.t_brg_inventory where kd_produk = '$kd_produk' group by kd_produk;");
        return $query->row()->qty;
    }

    public function cek_duplikat($kd_produk, $kd_produk_paket) {
        $query = $this->db->query("select count(kd_produk) from mst.t_produk_paket " .
          "where kd_produk = '$kd_produk' and kd_produk_paket = '$kd_produk_paket'");
        return $query->row()->count;
    }

    public function cek_lokasi_default($kd_produk_paket) {
        $query = $this->db->query("select count(*) from mst.t_produk_lokasi " .
          "where kd_produk = '$kd_produk_paket' and flag_default = 1");
        return $query->row()->count;
    }

    public function get_lokasi_default($kd_produk_paket, $strict = false) {
        $where = $strict ? ' and flag_default = 1' : 'order by flag_default desc limit 1';

        $query = $this->db->query("select * from mst.t_produk_lokasi " .
          "where kd_produk = '$kd_produk_paket' $where");
        if(!$strict && $query->num_rows() == 0) {
            $query = $this->db->query("select * from inv.t_brg_inventory " .
              "where kd_produk = '$kd_produk_paket' limit 1");
        }
        return $query->row();
    }

    public function save_mutasi($data) {
        return $this->db->insert('inv.t_mutasi_barang', $data);
    }

    public function get_stok_lokasi($kd_produk,$lokasi,$blok,$sub_blok) {
        $query = $this->db->query("select qty_oh from inv.t_brg_inventory " .
          "where kd_produk = '$kd_produk' and kd_lokasi = '$lokasi' " .
          "and kd_blok = '$blok' and kd_sub_blok = '$sub_blok'");
        return $query->row()->qty_oh;
    }

    public function update_stok($qty_oh, $params) {
        $where = '';
        foreach($params as $key => $value) {
            $where .= " AND $key = '$value'";
        }
        $count = $this->db->query("select count(*) from inv.t_brg_inventory WHERE 1=1 $where");
        if(intval($count->row()->count) < 1) {
            $sql = "INSERT INTO inv.t_brg_inventory(kd_produk, kd_lokasi, kd_blok, kd_sub_blok, qty_oh) VALUES " .
              "('$params[kd_produk]', '$params[kd_lokasi]', '$params[kd_blok]', '$params[kd_sub_blok]', $qty_oh)";
        } else {
            $sql = "UPDATE inv.t_brg_inventory set qty_oh = $qty_oh WHERE 1=1 $where";
        }
        return $this->db->query($sql);
//        return $sql;
    }

    public function save_mutasi_detail($data) {
        return $this->db->insert('inv.t_mutasi_barang_detail', $data);
    }

    public function update_barang_paket($kd_produk, $data) {
        $this->db->where('kd_produk',$kd_produk);
        return $this->db->update('mst.t_produk', $data);
    }

    public function save_data_harga($kd_produk, $data) {
        $query = $this->db->get_where('mst.t_diskon_sales', array('kd_produk' => $kd_produk));
        if($query->num_rows() > 0) {
//            $this->db->insert('mst.t_diskon_sales_history', $query->row());
            $this->db->where('kd_produk',$kd_produk);
            return $this->db->update('mst.t_diskon_sales', $data);
        } else {
            $data['kd_produk'] = $kd_produk;
            return $this->db->insert('mst.t_diskon_sales', $data);
        }
    }

    public function save_data_harga_beli($kd_produk, $data) {
        $query = $this->db->get_where('mst.t_supp_per_brg', array('kd_produk' => $kd_produk));
        if($query->num_rows() > 0) {
            $this->db->insert('mst.t_supp_per_brg_history', $query->row());
            $this->db->where('kd_produk',$kd_produk);
            return $this->db->update('mst.t_supp_per_brg', $data);
        } else {
            $data['kd_produk'] = $kd_produk;
            return $this->db->insert('mst.t_supp_per_brg', $data);
        }
    }

    public function get_top_supp($kd_supplier)
    {
        $this->db->select('top')->from('mst.t_supplier')->where('kd_supplier', $kd_supplier);
        $query = $this->db->get();
        return intval($query->row()->top);
    }

    public function save_data_paket($data) {
        return $this->db->insert('mst.t_produk_paket', $data);
    }

    public function save_data_inventory($data) {
        return $this->db->insert('inv.t_trx_inventory', $data);
    }

}