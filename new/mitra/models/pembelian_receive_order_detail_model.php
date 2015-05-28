<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order_detail_model extends MY_Model {

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
    public function get_rows($kd_supplier, $search = "", $tgl_awal, $tgl_akhir, $offset, $length) {
        $sql_search = "a.konsinyasi::numeric = 0";
        if($kd_supplier != '') {
            $sql_search .= " AND a.kd_supplier = '$kd_supplier'";
        }
        if ($search != "") {
            $sql_search .= " AND (lower(no_do) LIKE '%" . strtolower($search) . "%')";
        }
        if($tgl_awal && $tgl_akhir) {
            $sql_search .= " AND tanggal_terima between '$tgl_awal' and '$tgl_akhir'";
        } elseif($tgl_awal && $tgl_akhir == '') {
            $sql_search .= " AND tanggal_terima > '$tgl_awal' ";
        } elseif($tgl_awal && $tgl_akhir == '') {
            $sql_search .= " AND tanggal_terima < '$tgl_akhir'";
        }

        $sql = <<<EOT
SELECT a.no_do as no_ro, a.kd_supplier, b.nama_supplier, a.tanggal, a.tanggal_terima, a.no_bukti_supplier
FROM purchase.t_receive_order a JOIN mst.t_supplier b on a.kd_supplier = b.kd_supplier
WHERE $sql_search order by a.no_do LIMIT $length OFFSET $offset
EOT;
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql1 = "SELECT count(a.no_do) as total FROM purchase.t_receive_order a WHERE $sql_search";
        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_data_ro($no_ro) {
        $sql_header = <<<EOT
SELECT
    a.no_do as no_ro, a.kd_supplier, b.nama_supplier, a.tanggal, a.tanggal_terima,
    a.no_bukti_supplier, a.created_by
FROM purchase.t_receive_order a JOIN mst.t_supplier b on a.kd_supplier = b.kd_supplier
WHERE a.no_do = '$no_ro'
EOT;
        $sql_brg = <<<EOT
SELECT
    b.kd_produk, coalesce(c.kd_produk_lama, '-') kd_produk_lama, coalesce(c.kd_produk_supp, '-') kd_produk_supp,
    c.nama_produk, d.nm_satuan, b.qty_beli, b.qty_terima, (b.qty_beli - b.qty_terima) sisa_terima,
    coalesce(sum(e.qty_oh), 0,sum(e.qty_oh)) jml_stok,
    f.nama_lokasi2 || '-' || g.nama_blok2 || '-' || h.nama_sub_blok2 blok_terima,
    b.keterangan
FROM
    purchase.t_receive_order a
    JOIN purchase.t_dtl_receive_order b ON b.no_do=a.no_do
    JOIN mst.t_produk c ON c.kd_produk=b.kd_produk
    JOIN mst.t_satuan d ON d.kd_satuan=c.kd_satuan
    LEFT JOIN inv.t_brg_inventory e ON e.kd_produk = b.kd_produk
    JOIN mst.t_lokasi f on f.kd_lokasi = b.kd_lokasi
    JOIN mst.t_blok g on g.kd_blok = b.kd_blok
    JOIN mst.t_sub_blok h on h.kd_sub_blok = b.kd_sub_blok
WHERE b.no_do = '$no_ro'
GROUP BY
    b.kd_produk, c.kd_produk_lama, c.kd_produk_supp, c.nama_produk, c.min_stok,
    c.max_stok, b.qty_beli, b.qty_terima, b.keterangan,d.nm_satuan,
    f.nama_lokasi2, g.nama_blok2, h.nama_sub_blok2
ORDER BY b.kd_produk asc, blok_terima desc
EOT;
        $results = array('header' => null, 'detail' => null);
        $query = $this->db->query($sql_header);
        if ($query->num_rows() > 0) {
            $results['header'] = $query->row();
        }
        $query = $this->db->query($sql_brg);
        if ($query->num_rows() > 0) {
            $results['detail'] = $query->result();
        }

        return $results;
    }
}