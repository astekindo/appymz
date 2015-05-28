<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi_barang_model extends MY_Model {

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
        return $this->db->insert($table, $data);
    }

    public function select_inventory($where = "") {
        $this->db->where($where);
        $query = $this->db->get('inv.t_brg_inventory');

        $results = FALSE;

        if ($query->num_rows() > 0) {
            $results = TRUE;
        }

        return $results;
    }

    public function select_inv_barang($peruntukan, $search = null, $no_mutasi_stok = null, $lokasi, $blok, $subblok, $type, $start, $limit) {
        $this->db->start_cache();
        // if(intval($type) == 1) {
        //     $this->db->distinct('a.kd_produk');
        // } else {
        //     $this->db->distinct('a.kd_produk,a.kd_lokasi,a.kd_blok,a.kd_sub_blok');
        // }

        if(!empty($no_mutasi_stok)) {
            $this->db->select("distinct on(a.kd_produk) a.kd_produk, b.nama_produk, c.nm_satuan,a.qty_oh, e.qty qty_mutasi", FALSE);
            $this->db->join('inv.t_mutasi_barang_detail e', 'e.kd_produk = d.kd_produk')
                ->where('e.no_mutasi_stok', $no_mutasi_stok);
        } else {
            $this->db->select("distinct on(a.kd_produk) a.kd_produk, b.nama_produk, c.nm_satuan,a.qty_oh", FALSE);
        }
        $this->db->join('mst.t_produk  b', 'b.kd_produk = d.kd_produk')
            ->join('mst.t_satuan c', 'b.kd_satuan = c.kd_satuan')
            ->join('inv.t_brg_inventory a', 'a.kd_produk = d.kd_produk', 'left')
            ->where('a.kd_lokasi', $lokasi)
            ->where('a.kd_blok', $blok)
            ->where('a.kd_sub_blok', $subblok)
            ->join('mst.t_lokasi e', 'e.kd_lokasi = d.kd_lokasi');

        if ($search != "") {
            $this->db->where("((lower(b.kd_produk) LIKE '%" . strtolower($search) . "%') OR
            (lower(b.kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR
            (lower(b.kd_produk_supp) LIKE '%" . strtolower($search) . "%') OR
            (lower(b.nama_produk) LIKE '%" . strtolower($search) . "%'))", NULL);
        }

        if(is_int($peruntukan) && $peruntukan != 2) {
            $this->db->where('e.kd_peruntukan', "$peruntukan");
        }
        $this->db->stop_cache();

        $results['total'] = $this->db->count_all_results('mst.t_produk_lokasi d');

        $this->db->order_by('a.kd_produk asc');
        $query = $this->db->get('mst.t_produk_lokasi d', $limit, $start);
        if($results['total'] > 0) {
            $results['data'] = $query->result();
        }
        $results['lq'] = $this->db->last_query();

        $this->db->flush_cache();
        return $results;
    }


    public function search_lokasi($search = "", $peruntukan = 2, $offset, $length) {
        $this->db->select("CASE WHEN kd_peruntukan = '1' THEN 'Distribusi' ELSE 'Supermarket' END peruntukan, kd_lokasi, nama_lokasi, kd_peruntukan ", FALSE);

        if ($search != "") {
            $this->db->where("(lower(nama_lokasi) LIKE '%" . $search . "%')", NULL);
        }
        $this->db->where("aktif is TRUE");
        $lokasi_ref = null;
        $kd_peruntukan = 1;
        if(is_array($peruntukan)) {
            $kd_peruntukan = $peruntukan['peruntukan'];
            $lokasi_ref = $peruntukan['lokasi'];
        } else {
            $kd_peruntukan = intval($peruntukan);
        }
        if($kd_peruntukan != 2) {
            $this->db->where("kd_peruntukan = '$kd_peruntukan'");
        } elseif(!empty($lokasi_ref)) {
            $this->db->where("kd_peruntukan in (select kd_peruntukan from mst.t_lokasi where kd_lokasi = '$lokasi_ref')", NULL, FALSE);
        }
        $query = $this->db->get('mst.t_lokasi', $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function get_subblok($kd_lokasi = "", $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub,
        c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub,
        a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi,
        a.nama_sub_blok, a.kapasitas, CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
        FROM mst.t_sub_blok a
        join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
        join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
        where a.kd_lokasi='$kd_lokasi'" . $sql_search . "
        LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
                    join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
                    join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
                    where a.kd_lokasi='$kd_lokasi'" . $sql_search . "";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{"success":true,"record":' . $total . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    //------------out
    public function get_subblok_out($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE (lower(c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas,
                    CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
                    FROM mst.t_sub_blok a
                    join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
                    join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
                    " . $sql_search . "
                    LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

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

        $results = '{"success":true,"record":' . $total . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function get_subbloktujuan($kd_produk = '', $kd_lokasi = '', $search = "", $offset, $length, $peruntukan = 0) {
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
        if(!empty($kd_produk)) {
            $this->db->where('d.kd_produk', $kd_produk);
        }
        $this->db->where('a.aktif = true', NULL, FALSE);
        if(intval($peruntukan) != 2) {
            $this->db->where('c.kd_peruntukan', "$peruntukan");
        }
        if(!empty($kd_lokasi)) {
            $this->db->where('a.kd_lokasi', "$kd_lokasi");
        }

        if (!empty($search)) {
            $this->db->where("(a.kd_lokasi LIKE '%" . strtolower($search)
              . "%' OR a.kd_blok LIKE '%" . strtolower($search)
              . "%' OR a.kd_sub_blok LIKE '%" . strtolower($search)
              . "%' OR lower(c.nama_lokasi) LIKE '%" . strtolower($search)
              . "%' OR lower(b.nama_blok) LIKE '%" . strtolower($search)
              . "%' OR lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%')"
              , NULL, FALSE);
        }

        $sql1 = "distinct on (a.kd_lokasi, a.kd_blok, a.kd_sub_blok) a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub,
        c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub,
        a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi,
        a.nama_sub_blok, a.kapasitas, CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif";

        $this->db->select($sql1, FALSE)
        ->join('mst.t_blok b', 'b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi')
        ->join('mst.t_lokasi c', 'c.kd_lokasi = b.kd_lokasi')
        ->join('mst.t_produk_lokasi d', 'd.kd_lokasi = c.kd_lokasi and d.kd_blok = b.kd_blok and d.kd_sub_blok = a.kd_sub_blok');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_sub_blok a');

        $this->db->order_by('a.kd_lokasi asc, a.kd_blok asc, a.kd_sub_blok asc');
        $query = $this->db->get('mst.t_sub_blok a', $length, $offset);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_subbloktujuan_out($search = "", $offset, $length, $lokasi) {
        $sql_search = "";
        if ($search != "" || $lokasi != "") {
            $sql_search = " WHERE ((a.kd_lokasi || a.kd_blok || a.kd_sub_blok )<> '$lokasi') and (a.aktif IS true) and (lower(c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas,
                    CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
                    FROM mst.t_sub_blok a
                    join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
                    join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
                    " . $sql_search . "
                    LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

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

        $results = '{"success":true,"record":' . $total . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    //------------in
    public function get_form_in($search = null, $peruntukan = 0, $offset, $length, $all = false) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if (!empty($search)) {
            $this->db->like('no_mutasi_stok',"$search");
        }
        if(!$all) {
            $this->db->where('status', 0);
        }
        if(intval($peruntukan) != 2) {
            $this->db->where('tujuan', "$peruntukan");
        }

        $this->db->select('no_mutasi_stok,tgl_mutasi,no_ref,nama_pengambil,keterangan,DATE(now()) as tgl_mutasi_in', FALSE);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('inv.t_mutasi_barang');

        $this->db->order_by('no_mutasi_stok desc');
        $query = $this->db->get('inv.t_mutasi_barang', $length, $offset);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_form_in_detail($search = "") {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE e.no_mutasi_stok='$search'";
        }

        $sql = "SELECT
                a.kd_produk,
                a.kd_lokasi_awal || a.kd_blok_awal || a.kd_sub_blok_awal as sub_asal,
                b.nama_sub_blok as nama_sub_asal,
                a.kd_lokasi_tujuan as sub_tujuan,
                d.nama_sub_blok as nama_sub_tujuan,
                a.qty,
                c.nama_produk,
                c.nm_satuan
                FROM inv.t_mutasi_barang_detail a
                INNER JOIN inv.t_mutasi_barang e ON a.no_mutasi_stok=e.no_mutasi_stok and e.status=0 and e.approval_out IS NOT NULL and e.approval_in IS NULL
                INNER JOIN (SELECT
                        mst.t_lokasi.kd_lokasi || mst.t_blok.kd_blok || mst.t_sub_blok.kd_sub_blok as kd_sub_blok,
                        mst.t_lokasi.nama_lokasi || mst.t_blok.nama_blok || mst.t_sub_blok.nama_sub_blok as nama_sub_blok
                        FROM
                        mst.t_lokasi
                        INNER JOIN mst.t_blok ON mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi
                        INNER JOIN mst.t_sub_blok ON mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi
                        AND mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok
                ) b ON a.kd_lokasi_awal || a.kd_blok_awal || a.kd_sub_blok_awal = kd_sub_blok
                INNER JOIN (SELECT
                        mst.t_produk.kd_produk,mst.t_produk.nama_produk,mst.t_satuan.nm_satuan
                        FROM mst.t_produk INNER JOIN mst.t_satuan ON mst.t_produk.kd_satuan=mst.t_satuan.kd_satuan
                ) c ON a.kd_produk=c.kd_produk
                LEFT JOIN (SELECT
                        mst.t_lokasi.kd_lokasi || mst.t_blok.kd_blok || mst.t_sub_blok.kd_sub_blok as kd_sub_blok,
                        mst.t_lokasi.nama_lokasi || mst.t_blok.nama_blok || mst.t_sub_blok.nama_sub_blok as nama_sub_blok
                        FROM
                        mst.t_lokasi
                        INNER JOIN mst.t_blok ON mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi
                        INNER JOIN mst.t_sub_blok ON mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi
                        AND mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok
                ) d ON a.kd_lokasi_tujuan || a.kd_blok_tujuan || a.kd_sub_blok_tujuan =d.kd_sub_blok
                " . $sql_search;

        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function get_subbloktujuan_in($search = "", $kd_peruntukan, $offset, $length, $lokasi) {
        $sql_search = " WHERE c.kd_peruntukan = '$kd_peruntukan' AND (a.aktif IS true)";
        if ($search != "" || $lokasi != "") {
            $sql_search = " AND ((a.kd_lokasi || a.kd_blok || a.kd_sub_blok )<> '$lokasi') and (lower(c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }

        if ($search == "" && $lokasi != "") {
            $sql_search = " AND a.kd_lokasi = '$lokasi'";
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub,
        c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_sub,
        a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi,
        a.nama_sub_blok, a.kapasitas, CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
        FROM mst.t_sub_blok a
        join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
        join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
        " . $sql_search . "
        LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

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

        $results = '{"success":true,"record":' . $total . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function update_mutasi_in($id, $data) {
        $this->db->where('no_mutasi_stok', $id);
        return $this->db->update('inv.t_mutasi_barang_detail', $data);
    }

    public function update_mutasi($id1 = '', $data = NULL) {
        $this->db->where('no_mutasi_stok', $id1);
        return $this->db->update('inv.t_mutasi_barang', $data);
    }

    //----end in
    public function query_update($sql) {
        return $this->db->query($sql);
    }

    public function search_mutasi($search = "", $offset, $length) {
        $this->db->select("*", FALSE);

        if ($search != "") {
            $this->db->where("(lower(no_ref) LIKE '%" . $search . "%') OR (lower(no_mutasi_stok) LIKE '%" . $search . "%')", NULL);
        }
        $this->db->where("status = 10");
        $query = $this->db->get('inv.t_mutasi_barang', $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function outstanding_mutasi() {
        $sql = <<<EOT
select a.no_mutasi_stok, a.tgl_mutasi, b.kd_produk, c.nama_produk, d.nm_satuan,
e.nama_lokasi lokasi_awal, f.nama_lokasi lokasi_tujuan, b.qty, a.userid, a.no_ref, a.approval_out, a.tgl_approval_out
from inv.t_mutasi_barang a,  inv.t_mutasi_barang_detail b, mst.t_produk c, mst.t_satuan d, mst.t_lokasi e, mst.t_lokasi f
where a.status = 0
and a.no_mutasi_stok = b.no_mutasi_stok
and b.kd_produk = c.kd_produk
and c.kd_satuan = d.kd_satuan
and e.kd_lokasi = b.kd_lokasi_awal
and f.kd_lokasi = b.kd_lokasi_tujuan
order by a.tgl_mutasi asc
EOT;
        $query = $this->db->query($sql);
        $rows = array();
        if($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;

    }

    function get_summary_print($no_mutasi_stok) {
        $query = $this->db->get_where('inv.t_mutasi_barang',array('no_mutasi_stok' => $no_mutasi_stok));
        return $query->row();
    }

    function get_detail_print_out($no_mutasi_stok) {
        $sql = <<<EOT
SELECT
a.kd_produk || '\n' || coalesce (c.kd_produk_supp,'-') as kd_produk,
c.nama_produk,
a.qty,
c.nm_satuan,
b.nama_sub_blok as lokasi_asal,
d.nama_lokasi2 as lokasi_tujuan
FROM inv.t_mutasi_barang_detail a
INNER JOIN
    inv.t_mutasi_barang e ON a.no_mutasi_stok=e.no_mutasi_stok and e.status=0 and e.approval_out is not NULL and e.approval_in is null
INNER JOIN (
    SELECT
    mst.t_lokasi.kd_lokasi || mst.t_blok.kd_blok || mst.t_sub_blok.kd_sub_blok as kd_sub_blok,
    mst.t_lokasi.nama_lokasi2 || ' - ' || mst.t_blok.nama_blok2 || ' - ' || mst.t_sub_blok.nama_sub_blok2 as nama_sub_blok
    FROM
    mst.t_lokasi
    INNER JOIN mst.t_blok ON mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi
    INNER JOIN mst.t_sub_blok ON mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi
    AND mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok
) b ON a.kd_lokasi_awal || a.kd_blok_awal || a.kd_sub_blok_awal = kd_sub_blok
INNER JOIN (
    SELECT
    mst.t_produk.kd_produk,mst.t_produk.kd_produk_supp,mst.t_produk.nama_produk,mst.t_satuan.nm_satuan
    FROM mst.t_produk
    INNER JOIN mst.t_satuan ON mst.t_produk.kd_satuan=mst.t_satuan.kd_satuan
) c ON a.kd_produk=c.kd_produk
INNER JOIN (select mst.t_lokasi.kd_lokasi, mst.t_lokasi.nama_lokasi2 from mst.t_lokasi) d on a.kd_lokasi_tujuan = d.kd_lokasi
WHERE e.no_mutasi_stok='$no_mutasi_stok'
EOT;

        $query = $this->db->query($sql);
//        return $this->db->last_query();
        return $query->result();
    }

    function get_detail_print_in($no_mutasi_stok) {
        $sql = <<<EOT
SELECT
a.kd_produk as kd_produk,
c.nama_produk,
a.qty,
c.nm_satuan,
b.nama_lokasi2 as lokasi_asal,
d.nama_sub_blok as lokasi_tujuan
FROM inv.t_mutasi_barang_detail a
INNER JOIN
    inv.t_mutasi_barang e ON a.no_mutasi_stok=e.no_mutasi_stok and e.status=1 and e.approval_out is not NULL and e.approval_in is not null
INNER JOIN (select mst.t_lokasi.kd_lokasi, mst.t_lokasi.nama_lokasi2 from mst.t_lokasi) b on a.kd_lokasi_awal = b.kd_lokasi
INNER JOIN (
    SELECT
    mst.t_produk.kd_produk,mst.t_produk.kd_produk_supp,mst.t_produk.nama_produk,mst.t_satuan.nm_satuan
    FROM mst.t_produk
    INNER JOIN mst.t_satuan ON mst.t_produk.kd_satuan=mst.t_satuan.kd_satuan
) c ON a.kd_produk=c.kd_produk
INNER JOIN (
    SELECT
    mst.t_lokasi.kd_lokasi || mst.t_blok.kd_blok || mst.t_sub_blok.kd_sub_blok as kd_sub_blok,
    mst.t_lokasi.nama_lokasi2 || '-' || mst.t_blok.nama_blok2 || '-' || mst.t_sub_blok.nama_sub_blok2 as nama_sub_blok
    FROM
    mst.t_lokasi
    INNER JOIN mst.t_blok ON mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi
    INNER JOIN mst.t_sub_blok ON mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi
    AND mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok
) d ON a.kd_lokasi_tujuan || a.kd_blok_tujuan || a.kd_sub_blok_tujuan = kd_sub_blok
WHERE e.no_mutasi_stok='$no_mutasi_stok'
EOT;

        $query = $this->db->query($sql);
//        return $this->db->last_query();
        return $query->result();
    }

    public function search_produk($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(kd_produk) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql = "select kd_produk,nama_produk from mst.t_produk $sql_search limit $length offset $offset";
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql1 = "select count(kd_produk) as total from mst.t_produk";

        $query = $this->db->query($sql1);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{"success": true, "record": ' . $total . ', "data": ' . json_encode($rows) . '}';

        return $results;
    }

    public function get_detail_mutasi($no_mutasi) {
        $sql = <<<EOT
  SELECT distinct on
    (kd_produk, kd_lokasi_awal, kd_blok_awal, kd_sub_blok_awal, kd_lokasi_tujuan, kd_blok_tujuan, kd_sub_blok_tujuan)
    a.no_mutasi_stok, j.status, a.kd_produk, b.kd_produk_lama, b.nama_produk, i.nm_satuan,
    c.kd_lokasi || d.kd_blok || e.kd_sub_blok kd_lokasi_awal,
    c.nama_lokasi2 || '-' || d.nama_blok2 || '-' || e.nama_sub_blok2 lokasi_awal,
    case when j.status = 0 then
        f.kd_lokasi
    else
        f.kd_lokasi || g.kd_blok || h.kd_sub_blok
    end kd_lokasi_tujuan,
    case when j.status = 0 then
        f.nama_lokasi2
    else
        f.nama_lokasi2 || '-' || g.nama_blok2 || '-' || h.nama_sub_blok2
    end lokasi_tujuan,
    a.qty
  FROM inv.t_mutasi_barang_detail a
    JOIN mst.t_produk b on a.kd_produk = b.kd_produk
    JOIN mst.t_lokasi c on a.kd_lokasi_awal = c.kd_lokasi
    JOIN mst.t_blok d on  a.kd_lokasi_awal = c.kd_lokasi and a.kd_blok_awal = d.kd_blok
    JOIN mst.t_sub_blok e on a.kd_lokasi_awal = c.kd_lokasi and a.kd_blok_awal = d.kd_blok and a.kd_sub_blok_awal = e.kd_sub_blok
    JOIN mst.t_lokasi f on a.kd_lokasi_tujuan = f.kd_lokasi

    FULL OUTER JOIN mst.t_blok g on  a.kd_lokasi_tujuan = f.kd_lokasi and a.kd_blok_tujuan = g.kd_blok
    FULL OUTER JOIN mst.t_sub_blok h on a.kd_lokasi_tujuan = f.kd_lokasi and a.kd_blok_tujuan = g.kd_blok and a.kd_sub_blok_tujuan = h.kd_sub_blok

    JOIN mst.t_satuan i on b.kd_satuan = i.kd_satuan
    JOIN inv.t_mutasi_barang j on a.no_mutasi_stok = j.no_mutasi_stok
  WHERE a.no_mutasi_stok = '$no_mutasi'
EOT;

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_data_html($no_bukti) {
        if($no_bukti == '') return null;
        $sql_header = <<<EOT
        SELECT distinct on (a.no_mutasi_stok)
            a.no_mutasi_stok,
            a.tgl_mutasi,
            a.keterangan,
            a.created_by,
            a.no_ref,
            a.status,
            a.nama_pengambil,
            b.kd_lokasi_awal,
            b.kd_lokasi_tujuan,
            c.nama_lokasi nama_lokasi_awal,
            d.nama_lokasi nama_lokasi_tujuan
          FROM inv.t_mutasi_barang a
          JOIN inv.t_mutasi_barang_detail b on a.no_mutasi_stok = b.no_mutasi_stok
          JOIN mst.t_lokasi c on b.kd_lokasi_awal = c.kd_lokasi
          JOIN mst.t_lokasi d on b.kd_lokasi_tujuan = d.kd_lokasi
          WHERE a.no_mutasi_stok = '$no_bukti'
EOT;

        $query = $this->db->query($sql_header);
        $result['header'] = $query->row();
        $result['detail'] = $this->get_detail_mutasi($no_bukti);
        return $result;
    }

    public function get_status_mutasi($no_mutasi) {
        $query = $this->db->query("select status from inv.t_mutasi_barang where no_mutasi_stok = '$no_mutasi'");
        $result = $query->row();
        $status = 'mb';
        switch($result->status) {
            case 0:
                $status = 'out';
                break;
            case 1:
                $status = 'in';
                break;
            default:
                break;
        }
        return $status;
    }

    public function get_kd_peruntukan($kd_lokasi) {
        $query = $this->db->query("select kd_peruntukan from mst.t_lokasi where kd_lokasi = '$kd_lokasi'");
        if($query->num_rows() === 1) {
            return $query->row()->kd_peruntukan;
        } else {
            return '0';
        }
    }

    public function get_preset_lokasi($kd_produk,$lokasi,$blok,$sub_blok,$nocross = true, $tujuan = null) {
        $sql = "select * from mst.t_produk_lokasi where kd_produk = '$kd_produk' and kd_lokasi = '$lokasi' and kd_blok = '$blok' and kd_sub_blok = '$sub_blok'";
        if($nocross) {
            $sql .= "and kd_peruntukan::char = (select kd_peruntukan from mst.t_lokasi where kd_lokasi = '$tujuan')";
        }
    }
}