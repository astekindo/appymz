<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class In_out_stok_model extends MY_Model {

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
    public function insert_row($table = '', $data = NULL) {
        $result = $this->db->insert($table, $data);
        //print_r($this->db->last_query());
        return $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_kode_sequence($kode_proses, $digit) {
        $query = $this->db->query("SELECT mst.get_sequence('" . $kode_proses . "', " . $digit . ") id");
        $kode = "";
        if ($query->num_rows() > 0) {
            $kode = $query->row()
                    ->id;
        }

        return $kode;
    }

    public function update_row($kd_supplier = '', $kd_produk = '', $waktu_top = '', $datau = NULL) {
        $this->db->where('kd_supplier', $kd_supplier);
        $this->db->where('kd_produk', $kd_produk);
        $this->db->where('waktu_top', $waktu_top);
        return $this->db->update('mst.t_supp_per_brg', $datau);
        // print_r($this->db->last_query());
    }

    public function search_lokasi($search = "", $offset, $length, $kd_produk) {
        $sql_search = '';
        if ($search != '') {
            $sql_search = " AND (lower(c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = <<<EOT
select distinct on(kd_lokasi_asal,kd_produk)
d.kd_lokasi || d.kd_blok || d.kd_sub_blok kd_lokasi_asal
, nama_lokasi2 || '-' || nama_blok2 || '-' || nama_sub_blok2 nama_lokasi_asal
, a.kd_produk
, kd_produk_lama
, nama_produk
, rp_cogs
, f.qty_oh
, g.nm_satuan
, h.nama_ukuran
, 0 qty_in
, 0 qty_out
, '' keterangan
from mst.t_produk_lokasi a
join mst.t_lokasi b on b.kd_lokasi = a.kd_lokasi
join mst.t_blok c on c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
join mst.t_sub_blok d on d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = a.kd_blok AND d.kd_lokasi = a.kd_lokasi
join mst.t_produk e on e.kd_produk = a.kd_produk
join inv.t_brg_inventory f on f.kd_produk = a.kd_produk
join mst.t_satuan g on g.kd_satuan = e.kd_satuan
join mst.t_ukuran h on h.kd_ukuran = e.kd_ukuran
where a.kd_produk = '$kd_produk' $sql_search
order by kd_lokasi_asal asc, kd_produk asc
LIMIT $length OFFSET $offset
EOT;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) FROM (
                    SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
		            FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					" . $sql_search . ") b";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function search_stok_produk($kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $kd_ukuran = "", $kd_satuan = "", $tanggal = "", $list = "", $search = '', $offset, $length) {
        $where = '';
//        $select ='';
//        $join = '';

        if ($kd_satuan != '') {
            $where .= " AND a.kd_satuan = '$kd_satuan'";
        }
        if ($kd_ukuran != '') {

            $where .= " AND a.kd_ukuran = '$kd_ukuran'";
        }
        if ($search != '') {
            $where .= " AND (
							(lower(a.nama_produk) LIKE '%" . strtolower($search) . "%')
							OR
							(lower(a.kd_produk) LIKE '%" . strtolower($search) . "%')
							OR
							(lower(a.kd_produk_lama) LIKE '%" . strtolower($search) . "%')
						)";
        }
        if ($list != '') {
            $where .= " AND (
							a.kd_produk in(" . $list . ")
							OR
							a.kd_produk_lama in(" . $list . ")
						)";
        }
        if ($kd_kategori1 != '') $where .= " AND a.kd_kategori1 = '$kd_kategori1' ";

        if ($kd_kategori2 != '') $where .= " AND a.kd_kategori2 = '$kd_kategori2' ";

        if ($kd_kategori3 != '') $where .= " AND a.kd_kategori3 = '$kd_kategori3' ";

        if ($kd_kategori4 != '') $where .= " AND a.kd_kategori4 = '$kd_kategori4' ";

        $sql = "select distinct on(kd_lokasi_asal, kd_produk) d.kd_lokasi || d.kd_blok || d.kd_sub_blok kd_lokasi_asal,
            e.nama_lokasi2 || ' - ' || f.nama_blok2 || ' - ' || g.nama_sub_blok2 nama_lokasi_asal,
            a.kd_produk, a.kd_produk_lama, a.nama_produk, a.rp_cogs, b.nm_satuan, c.nama_ukuran, d.qty_oh, 0 qty_in, 0 qty_out, '' keterangan
            from mst.t_produk a, mst.t_satuan b, mst.t_ukuran c,
            inv.t_brg_inventory d, mst.t_lokasi e, mst.t_blok f, mst.t_sub_blok g
            where
            d.kd_lokasi = e.kd_lokasi
            and d.kd_blok = f.kd_blok
            and e.kd_lokasi = f.kd_lokasi
            and d.kd_sub_blok = g.kd_sub_blok
            and e.kd_lokasi = g.kd_lokasi
            and f.kd_blok = g.kd_blok
            and (a.kd_satuan = b.kd_satuan OR '' = b.kd_satuan)
            and (a.kd_ukuran = c.kd_ukuran OR  a.kd_ukuran = '')
            and a.kd_produk = d.kd_produk
            $where
            ORDER BY kd_lokasi_asal, a.kd_produk";

        $query = $this->db->query($sql);
        //print_r($this->db->last_query());
        if ($query->num_rows() > 0) {
            $result['rows'] = $query->result();
        } else {
            $result['rows'] = '';
        }
//        $result['rows'] = $this->db->last_query();
        $result['total'] = $query->num_rows();

        return $result;
    }

    public function cek_exists_brg_inv($kd_produk = null, $kd_lokasi = null, $kd_blok = null, $kd_sub_blok = null) {
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

    public function get_summary_print($no_bukti) {
        $sql = <<<EOT
SELECT DISTINCT
no_bukti, created_by, created_date, tanggal, status, approve_by, approve_date, keterangan_approve
FROM inv.t_inout_stok WHERE no_bukti = '$no_bukti'
EOT;
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function get_detail_print($no_bukti) {
        $sql = <<<EOT
SELECT
  a.kd_produk,
  b.nama_produk,
  CASE WHEN a.qty_in = 0 THEN a.qty_out ELSE a.qty_in END qty,
  b.nm_satuan,
  CASE WHEN a.qty_in = 0 THEN 'Out' ELSE 'In' END mov_type,
  c.nama_sub_blok,
  a.keterangan

FROM inv.t_inout_stok a
INNER JOIN (
  SELECT mst.t_produk.kd_produk,mst.t_produk.nama_produk,mst.t_satuan.nm_satuan
  FROM mst.t_produk
  INNER JOIN mst.t_satuan ON mst.t_produk.kd_satuan=mst.t_satuan.kd_satuan
) b ON a.kd_produk = b.kd_produk
INNER JOIN (
  SELECT
    mst.t_lokasi.kd_lokasi || mst.t_blok.kd_blok || mst.t_sub_blok.kd_sub_blok as kd_sub_blok,
    mst.t_lokasi.nama_lokasi2 || ' - ' || mst.t_blok.nama_blok2 || ' - ' || mst.t_sub_blok.nama_sub_blok2 as nama_sub_blok
  FROM
    mst.t_lokasi
  INNER JOIN mst.t_blok ON mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi
  INNER JOIN mst.t_sub_blok ON mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi
  AND mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok
) c ON a.kd_lokasi || a.kd_blok || a.kd_sub_blok = c.kd_sub_blok
WHERE a.no_bukti = '$no_bukti'
EOT;
        $query = $this->db->query($sql);
        return $query->result();
    }

}
