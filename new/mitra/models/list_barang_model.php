<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class List_barang_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $kd_ukuran = "", $kd_satuan = "", $kd_supplier = "", $is_konsinyasi = "", $search = "", $offset, $length) {
        $where = "";
        if ($kd_kategori1 != '') {
            $where .= " AND d.kd_kategori1 = '$kd_kategori1' ";
        }

        if ($kd_kategori2 != '') {
            $where .= " AND c.kd_kategori2 = '$kd_kategori2' ";
        }

        if ($kd_kategori3 != '') {
            $where .= " AND b.kd_kategori3 = '$kd_kategori3' ";
        }

        if ($kd_kategori4 != '') {
            $where .= " AND a.kd_kategori4 = '$kd_kategori4' ";
        }
        if ($kd_satuan != '') {
            $where .= " AND e.kd_satuan = '$kd_satuan'";
        }
        if ($kd_ukuran != '') {
            $where .= " AND j.kd_ukuran = '$kd_ukuran'";
        }
        if ($kd_supplier != '') {
            $where .= " AND h.kd_supplier = '$kd_supplier'";
        }
        if ($is_konsinyasi != '') {
            $where .= " AND f.is_konsinyasi = '$is_konsinyasi'";
        }
        $sql_search = "";
        if ($search != "") {
            $sql_search = "AND ((lower(f.nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(f.kd_produk_lama) LIKE '%" . strtolower($search) . "%')OR (lower(f.kd_produk) LIKE '%" . strtolower($search) . "%'))";
        }

        $sql1 = "SELECT i.nama_supplier, h.kd_supplier, h.hrg_supplier, d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori,
                    d.nama_kategori1,c.nama_kategori2,b.nama_kategori3,a.nama_kategori4, g.*,f.*, (f.rp_cogs * f.pct_margin) margin_cogs, (f.rp_cogs_dist * f.pct_margin_dist) margin_cogs_dist,  
                    CASE WHEN f.kd_peruntukkan = '1' THEN 'Distribusi' ELSE 'Supermarket' END kd_peruntukkan,
                    CASE WHEN f.aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif, e.*
                    FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f
                    left join mst.t_diskon_sales g on f.kd_produk = g.kd_produk
                    left join mst.t_supp_per_brg h on f.kd_produk = h.kd_produk
                    join mst.t_supplier i on h.kd_supplier = i.kd_supplier
                    join mst.t_ukuran j on f.kd_ukuran = j.kd_ukuran
                    WHERE e.kd_satuan=f.kd_satuan
                    AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4 
                    AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1 
                    AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
                    AND d.kd_kategori1=c.kd_kategori1 
                    AND f.aktif = 1 " . $sql_search . " " . $where . " ORDER BY f.kd_produk, f.no_urut ASC LIMIT " . $length . " OFFSET " . $offset;
        $query = $this->db->query($sql1);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total
                    FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f
                    left join mst.t_diskon_sales g on f.kd_produk = g.kd_produk
                    left join mst.t_supp_per_brg h on f.kd_produk = h.kd_produk
                    join mst.t_supplier i on h.kd_supplier = i.kd_supplier
                    join mst.t_ukuran j on f.kd_ukuran = j.kd_ukuran
                    WHERE e.kd_satuan=f.kd_satuan
                    AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4 
                    AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1 
                    AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
                    AND d.kd_kategori1=c.kd_kategori1 
                    AND f.aktif = 1 " . $sql_search . " " . $where;

        $query_count = $this->db->query($sql2);
        $total = 0;
        if ($query_count->num_rows() > 0) {
            $row = $query_count->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_row($id = NULL) {
        $sql = "SELECT d.nama_kategori1,c.nama_kategori2,b.nama_kategori3,a.nama_kategori4,f.*, g.*,
					f.kd_produk_lama,f.kd_produk_supp,e.nm_satuan,
					CASE WHEN f.kd_peruntukkan = '1' THEN 1 ELSE 0 END kd_peruntukkan,
					CASE WHEN f.aktif =1 THEN 1 ELSE 0 END aktif
					FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f
					left join mst.t_diskon_sales g on f.kd_produk = g.kd_produk
					WHERE f.kd_produk='$id'
					AND e.kd_satuan=f.kd_satuan
					AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4 
					AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1 
					AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
					AND d.kd_kategori1=c.kd_kategori1 
					AND f.aktif = 1";

        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_produk', $data);
    }

    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_produk', $id);
        return $this->db->update('mst.t_produk', $data);
    }

    public function delete_row($id = NULL) {
        $data = array(
            'aktif' => 'FALSE'
        );
        $this->db->where('kd_produk', $id);
        return $this->db->update('mst.t_produk', $data);
    }

    public function get_kategori4($id1 = NULL, $id2 = NULL, $id3 = NULL) {
        $query = $this->db->query("SELECT a.kd_kategori4,a.nama_kategori4
									FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d
									WHERE a.kd_kategori1='$id1' AND a.kd_kategori2='$id2' AND a.kd_kategori3='$id3'
									AND b.kd_kategori3=a.kd_kategori3 AND b.kd_kategori2=a.kd_kategori2 AND b.kd_kategori1=a.kd_kategori1 
									AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
									AND d.kd_kategori1=c.kd_kategori1 
									AND a.aktif = true
									ORDER BY a.nama_kategori4 ASC");
        $rows = $query->result();

        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_satuan() {
        $query = $this->db->query("SELECT kd_satuan,nm_satuan
									FROM mst.t_satuan
									ORDER BY nm_satuan ASC");
        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

}
