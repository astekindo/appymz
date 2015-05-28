<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of barterbarang_model
 *
 * @author faroq
 */
class barterbarang_model extends MY_Model {

    public function __construct(){
		parent::__construct();
	}


    public function get_rows($status, $search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $sql = "a.*, COALESCE(a.no_po,'-') no_po, b.nama_supplier, b.pic as pic_supplier, b.alamat as alamat_supplier, b.telpon as no_telp_supplier,
                CASE WHEN a.status = 0 THEN 'Created' WHEN a.status = 1 THEN 'Approval Ops'
                WHEN a.status = 2 THEN 'Approval Buyer' WHEN a.status = 3 THEN 'Surat Jalan'
                WHEN a.status = 4 THEN 'Barter In' WHEN a.status = 9 THEN 'Reject' END status";

        $this->db->start_cache();
        if(strpos($status, ',')) {
            $this->db->where("a.status in ($status)", null, false);
        } else {
            $this->db->where("a.status = $status", null, false);
        }
        if($search && strlen($search) > 0) {
            $this->db->where("(lower(a.no_transfer_stok) = '" .strtolower($search).
                "' or lower(b.nama_supplier) = '" .strtolower($search). "')", null, false);
        }
        $this->db->select($sql, false)->from('inv.t_barter_barang a')->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier')
        ->stop_cache();
        $result['total'] = $this->db->count_all_results();
        $this->db->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;

    }

    public function get_rows_kirim($search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $sql = "a.*, COALESCE(a.no_po,'-') no_po, b.nama_supplier, b.pic as pic_supplier,
                b.alamat as alamat_supplier, b.telpon as no_telp_supplier, c.qty, c.qty_kirim,
                CASE WHEN a.status = 0 THEN 'Created' WHEN a.status = 1 THEN 'Approval Ops'
                WHEN a.status = 2 THEN 'Approval Buyer' WHEN a.status = 3 THEN 'Surat Jalan'
                WHEN a.status = 4 THEN 'Barter In' WHEN a.status = 9 THEN 'Reject' END status";

        $this->db->start_cache();
        if($search && strlen($search) > 0) {
            $this->db->where("(lower(a.no_transfer_stok) = '" .strtolower($search).
                "' or lower(b.nama_supplier) = '" .strtolower($search). "')", null, false);
        }
        $this->db->select($sql, false)->from('inv.t_barter_barang a')->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier')
        ->join('(select no_transfer_stok, coalesce(sum(qty),0) qty, coalesce(sum(qty_kirim),0) qty_kirim
            from inv.t_barter_barang_detail group by no_transfer_stok) c', 'a.no_transfer_stok = c.no_transfer_stok')
        ->where('a.status in (2,3) and c.qty_kirim < c.qty', null, false)
        ->stop_cache();
        $result['total'] = $this->db->count_all_results();
        $this->db->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_rows_kembali($search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $sql = "DISTINCT ON (no_sb) a.*, COALESCE(a.no_po, '-') no_po, b.nama_supplier, b.pic as pic_supplier, d.no_sb as no_sb,
                b.alamat as alamat_supplier, b.telpon as no_telp_supplier, c.qty as qty_tr, c.qty_kirim, d.qty_sb, d.qty_kembali,
                CASE WHEN a.status = 0 THEN 'Created' WHEN a.status = 1 THEN 'Approval Ops'
                WHEN a.status = 2 THEN 'Approval Buyer' WHEN a.status = 3 THEN 'Surat Jalan'
                WHEN a.status = 4 THEN 'Barter In' WHEN a.status = 9 THEN 'Reject' END status";

        $this->db->start_cache();
        if($search && strlen($search) > 0) {
            $this->db->where("(lower(a.no_transfer_stok) = '" .strtolower($search).
                "' or lower(b.nama_supplier) = '" .strtolower($search).
                "' or lower(d.no_sb) = '" .strtolower($search).
                "')", null, false);
        }
        $this->db->select($sql, false)->from('inv.t_barter_barang a')->join('mst.t_supplier b', 'a.kd_supplier = b.kd_supplier')
        ->join('(select no_transfer_stok, coalesce(sum(qty),0) qty, coalesce(sum(qty_kirim),0) qty_kirim
            from inv.t_barter_barang_detail group by no_transfer_stok) c', 'a.no_transfer_stok = c.no_transfer_stok')
        ->join('(select x.no_sb, y.no_transfer_stok, sum(x.qty) qty_sb, coalesce(sum(x.qty_kembali), 0) qty_kembali
            from inv.t_surat_barter_detail x join inv.t_surat_barter y on x.no_sb = y.no_sb
            group by y.no_transfer_stok, x.no_sb) d', 'd.no_transfer_stok = a.no_transfer_stok', 'left')
        ->where('a.status in (3,4) and d.qty_kembali < d.qty_sb', null, false)
        ->stop_cache();
        $result['total'] = $this->db->count_all_results();
        $this->db->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_rows_detail($no_bukti, $in = false)
    {
        $result = array('total' => 0, 'data' => array());
        if($in) {
            $clause = 'm.no_sb,';
            $kolom  = '
            a.kd_lokasi,
            a.kd_blok,
            a.kd_sub_blok,
            a.kd_lokasi || a.kd_blok || a.kd_sub_blok as sub,
            d.nama_lokasi || e.nama_blok || f.nama_sub_blok as nama_sub,
            d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 as alias_sub,';
            $this->db->where('m.no_sb',$no_bukti)->where('qty_sb > qty_kembali', null, false);
            $this->db->join('mst.t_lokasi d', 'm.kd_lokasi = d.kd_lokasi','left')
            ->join('mst.t_blok e', 'm.kd_blok = e.kd_blok and m.kd_lokasi = e.kd_lokasi','left')
            ->join('mst.t_sub_blok f', 'm.kd_sub_blok = f.kd_sub_blok and m.kd_blok = f.kd_blok and m.kd_lokasi = f.kd_lokasi','left');
        } else {
            $clause = null;
            $this->db->where('a.no_transfer_stok',$no_bukti);
        }
        $sql = "distinct on(a.no_transfer_stok, $clause a.kd_produk_awal, a.kd_produk_tujuan, qty, qty_kirim)
            a.no_transfer_stok as no_transfer_stok,
            m.no_sb,
            a.kd_produk_awal as kd_produk_awal,
            b.nama_produk as nama_produk_awal,
            j.kd_satuan as kd_satuan_awal,
            j.nm_satuan as nm_satuan_awal,
            a.kd_produk_tujuan as kd_produk_tujuan,
            c.nama_produk as nama_produk_tujuan,
            k.kd_satuan as kd_satuan_tujuan,
            k.nm_satuan as nm_satuan_tujuan,
            COALESCE(a.qty, 0) qty,
            COALESCE(a.qty_kirim, 0) qty_kirim,
            COALESCE(m.qty_sb, 0) qty_sb,
            COALESCE(m.qty_kembali, 0) qty_kembali,
            COALESCE(l.qty_oh, 0) qty_oh";

        $this->db->start_cache();
        $this->db->select($sql, false)->from('inv.t_barter_barang_detail a')
        ->join('mst.t_produk b', 'a.kd_produk_awal = b.kd_produk')
        ->join('mst.t_produk c', 'a.kd_produk_tujuan = c.kd_produk')

        ->join('mst.t_satuan j', 'b.kd_satuan = j.kd_satuan')
        ->join('mst.t_satuan k', 'b.kd_satuan = k.kd_satuan')
        ->join('(select kd_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory group by kd_produk) l', 'a.kd_produk_awal = l.kd_produk')
        ->join('(select x.kd_produk, x.no_sb, x.kd_lokasi, x.kd_blok, x.kd_sub_blok, y.no_transfer_stok, sum(x.qty) qty_sb,
            coalesce(sum(x.qty_kembali), 0) qty_kembali from inv.t_surat_barter_detail x join inv.t_surat_barter y on x.no_sb = y.no_sb
            group by y.no_transfer_stok, x.no_sb, x.kd_lokasi, x.kd_blok, x.kd_sub_blok, x.kd_produk) m',
            'm.kd_produk = a.kd_produk_awal and m.no_transfer_stok = a.no_transfer_stok', 'left');
        $this->db->stop_cache();
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();
        $result['total'] = count($result['data']);

        $this->db->flush_cache();
        return $result;
    }

    public function get_by_databarang($kd_supplier, $params, $search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $this->db->start_cache();
        $this->db->select('b.kd_produk, b.nama_produk, c.nm_satuan, d.qty_oh')->from('mst.t_supp_per_brg a')
        ->join('mst.t_produk b','a.kd_produk = b.kd_produk')
        ->join('mst.t_satuan c','b.kd_satuan = c.kd_satuan')
        ->join('(select kd_produk, sum(qty_oh) qty_oh from inv.t_brg_inventory group by kd_produk) d', 'b.kd_produk = d.kd_produk')
        ->where('a.kd_supplier', $kd_supplier)
        ->where('a.aktif', "true")
        ->where('b.aktif', true);
        foreach ($params as $key => $value) {
            $this->db->where("b.$key",$value);
        }
        $this->db->stop_cache();
        $result['total'] = $this->db->count_all_results();
        $this->db->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_by_po($no_po, $search, $start, $limit)
    {
        # code...
    }

    public function insert_header_data($data) {
        $result['success'] = $this->db->insert('inv.t_barter_barang', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function update_header_data($no_transfer_stok, $data) {
        $this->db->where('no_transfer_stok', $no_transfer_stok);

        $result['success'] = $this->db->update('inv.t_barter_barang', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function insert_detail_data($data) {
        $result['success'] = $this->db->insert_batch('inv.t_barter_barang_detail', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function update_detail_data($no_transfer_stok, $kd_produk, $data) {
        $this->db->where('no_transfer_stok', $no_transfer_stok)->where('kd_produk_awal', $kd_produk);
        $result['success'] = $this->db->update('inv.t_barter_barang_detail', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function simpan_header_sb($data) {
        $result['success'] = $this->db->insert('inv.t_surat_barter', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function update_header_sb($no_sb, $data) {
        $this->db->where('no_sb', $no_sb);
        $result['success'] = $this->db->update('inv.t_surat_barter', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function simpan_detail_sb($data) {
        $result['success'] = $this->db->insert('inv.t_surat_barter_detail', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function update_detail_sb($no_sb, $kd_produk, $data) {
        $this->db->where('no_sb', $no_sb)->where('kd_produk', $kd_produk);
        $result['success'] = $this->db->update('inv.t_surat_barter_detail', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function update_stok_lokasi($kd_produk, $lokasi, $blok, $subblok, $jumlah) {
        $stok_lokasi = $this->db->query("select * from inv.t_brg_inventory where kd_produk = '$kd_produk'
            and kd_lokasi = '$lokasi' and kd_blok = '$blok' and kd_sub_blok = '$subblok' ");
        $this->db->flush_cache();

        if($stok_lokasi->num_rows() > 0) {
            $this->db->where('kd_produk', $kd_produk)->where('kd_lokasi', $lokasi)
            ->where('kd_blok', $blok)->where('kd_sub_blok', $subblok);
            $result['success'] = $this->db->update('inv.t_brg_inventory', array(
                'updated_date'  => date('Y-m-d'),
                'updated_by'    => $this->session->userdata('username'),
                'qty_oh'        => $jumlah
            ));
        } else {
            $result['success'] = $this->db->insert('inv.t_brg_inventory', array(
                'kd_produk'     => $kd_produk,
                'kd_lokasi'     => $lokasi,
                'kd_blok'       => $blok,
                'kd_sub_blok'   => $subblok,
                'qty_oh'        => $jumlah,
                'created_by'    => $this->session->userdata('username'),
                'created_date'  => date('Y-m-d'),
                'is_bonus'      => 0
            ));
        }
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function rekam_transaksi($data) {
        $result['success'] = $this->db->insert('inv.t_trx_inventory', $data);
        $result['lq']      = $this->db->last_query();
        return $result;
    }

    public function get_data_print($no_sb) {
        $sql = <<<EOT
select 'SURAT PENGANTAR BARTER' title, a.created_by, a.no_sb, a.tanggal,
a.no_transfer_stok, a.no_kendaraan, a.sopir, a.pic_penerima, a.alamat_penerima,
a.no_telp_penerima, a.keterangan, c.no_po
from inv.t_surat_barter a
join inv.t_barter_barang b on a.no_transfer_stok = b.no_transfer_stok
left join purchase.t_purchase c on b.no_po = c.no_po
where a.no_sb = '$no_sb'
EOT;

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = <<<EOT
        select
        a.kd_produk, b.kd_produk_lama, b.kd_produk_supp, b.nama_produk, a.qty, c.nm_satuan, a.keterangan,
        d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 lokasi,
        d.nama_lokasi2 || '-' || e.nama_blok2 || '-' || f.nama_sub_blok2 lokasi
        from inv.t_surat_barter_detail a, mst.t_produk b, mst.t_satuan c, mst.t_lokasi d, mst.t_blok e, mst.t_sub_blok f
        where a.no_sb = '$no_sb'
        and a.kd_produk = b.kd_produk
        and b.kd_satuan = c.kd_Satuan
        and a.kd_lokasi = d.kd_lokasi
        and a.kd_blok = e.kd_blok
        and a.kd_lokasi = e.kd_lokasi
        and a.kd_sub_blok = f.kd_sub_blok
        and a.kd_blok = f.kd_blok
        and a.kd_lokasi = f.kd_lokasi
EOT;



        $query_detail = $this->db->query($sql_detail);
        //print_r($this->db->last_query());
        $data['detail'] = $query_detail->result();

        return $data;
    }
}

?>
