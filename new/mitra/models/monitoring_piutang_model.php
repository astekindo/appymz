<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_piutang_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get_rows($search,$no_so, $member, $status, $tgl_min, $tgl_max, $start, $limit) {
        $where = 'where';
        switch($status) {
            case 2:
                //piutang belum lunas
                $where .= ' c.rp_dp < a.rp_grand_total and a.rp_kurang_bayar > 0';
                break;
            case 3:
                //piutang lunas
                $where .= ' c.rp_dp < a.rp_grand_total and a.rp_kurang_bayar = 0';
                break;
            case 4:
                //tanpa piutang
                $where .= ' c.rp_dp = a.rp_grand_total and a.rp_kurang_bayar = 0';
                break;
            default:
                $where .= ' true';
                //semua
                break;
        }
        if($search != '') $where .= " and (a.tgl_so::char like '%$search%' or a.no_so like '%$search%' or a.kd_member like '%$search%' or d.nm_member like '%$search%')";
        if($no_so != '') $where .= " and a.no_so = '$no_so'";
        if($member != '') $where .= " and a.kd_member = '$member'";

        if($tgl_min != '' && $tgl_max == '') {
            $where .= " and a.tgl_so > '$tgl_min'";
        } elseif($tgl_min == '' && $tgl_max != '') {
            $where .= " and a.tgl_so < '$tgl_max'";
        } elseif($tgl_min != '' && $tgl_max != '') {
            $where .= " and a.tgl_so between '$tgl_min' and '$tgl_max'";
        }
        $sql = <<<EOT
    select
        a.tgl_so, a.no_so, coalesce(a.kd_member,'') kd_member, coalesce(d.nm_member,'') nm_member, coalesce(a.kirim_so,'') nm_penerima, a.rp_grand_total as rp_total,
        c.rp_dp,coalesce(sum(b.rp_bayar),0) rp_bayar, a.rp_kurang_bayar
    from sales.t_sales_order a
    full outer join (
        select x.no_faktur, x.no_pelunasan_piutang, coalesce(x.rp_bayar,0) rp_bayar
        from sales.t_piutang_detail x
        join (
            select no_pelunasan_piutang, tanggal as tgl_bayar, rp_extra_diskon, sum(rp_pelunasan) rp_pelunasan, sum(rp_selisih) rp_selisih, rp_sisa_piutang
            from sales.t_piutang_pelunasan
            group by no_pelunasan_piutang,tgl_bayar,rp_extra_diskon,rp_sisa_piutang
        ) y on x.no_pelunasan_piutang = y.no_pelunasan_piutang
    ) b on a.no_so = b.no_faktur
    join (
        select no_so, sum(rp_total) rp_dp from sales.t_sales_order_bayar group by no_so
    ) c on a.no_so = c.no_so
    left join (
        select kd_member, nmmember as nm_member from mst.t_member
    ) d on a.kd_member= d.kd_member
    $where
    group by a.tgl_so, a.no_so, a.kd_member, d.nm_member, a.kirim_so, rp_total, a.rp_grand_total, a.rp_kurang_bayar, c.rp_dp
    order by no_so desc offset $start limit $limit
EOT;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['rows'] = $query->result();
        } else {
            $result['rows'] = '';
        }
        $result['query'] = $this->db->last_query();
        $sql_count = <<<EOT
    select count(distinct a.no_so) from sales.t_sales_order a
    full outer join (
        select x.no_faktur, x.no_pelunasan_piutang, coalesce(x.rp_bayar,0) rp_bayar
        from sales.t_piutang_detail x
        join (
            select no_pelunasan_piutang, tanggal as tgl_bayar, rp_extra_diskon, sum(rp_pelunasan) rp_pelunasan, sum(rp_selisih) rp_selisih, rp_sisa_piutang
            from sales.t_piutang_pelunasan
            group by no_pelunasan_piutang,tgl_bayar,rp_extra_diskon,rp_sisa_piutang
        ) y on x.no_pelunasan_piutang = y.no_pelunasan_piutang
    ) b on a.no_so = b.no_faktur
    join (
        select no_so, sum(rp_total) rp_dp from sales.t_sales_order_bayar group by no_so
    ) c on a.no_so = c.no_so
    left join (
        select kd_member, nmmember as nm_member from mst.t_member
    ) d on a.kd_member= d.kd_member
    $where
EOT;
        $this->db->flush_cache();
        $query = $this->db->query($sql_count);

        $result['total'] = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $result['total'] = $row->count;
        }
        return $result;
	}

    function get_members($query, $start, $limit) {
        $where = '';
        if($query!='') $where .= "where nmmember like '%$query%'";
        $sql = "select kd_member, nmmember as nm_member from mst.t_member $where order by kd_member asc offset $start limit $limit";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['rows'] = $query->result();
        } else {
            $result['rows'] = '';
        }
        $sql_count = "select kd_member, nmmember as nm_member from mst.t_member $where order by kd_member asc";
        $this->db->flush_cache();
        $query = $this->db->query($sql_count);

        $result['total'] = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $result['total'] = $row->count;
        }
        return $result;
    }

    public function search_salesorder($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_so) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_so, tgl_so, rp_total, rp_diskon, rp_diskon_tambahan, rp_grand_total, keterangan, kirim_so, userid
                 from sales.t_sales_order " . $sql_search . "  order by tgl_so desc limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $result['rows'] = array();
        if ($query->num_rows() > 0) {
            $result['rows'] = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = 'select count(no_so) as total from sales.t_sales_order';

        $query = $this->db->query($sql2);

        $result['total'] = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $result['total'] = $row->total;
        }

        return $result;
    }

    public function get_data_per_so($no_so) {
        $sql_header = <<<EOT
select no_so, tgl_so, kd_member, created_by, kirim_so, kirim_alamat_so, kirim_telp_so, rp_kurang_bayar,
    case when rp_kurang_bayar = 0 then 'LUNAS' else 'BELUM LUNAS' end status_bayar
    from sales.t_sales_order where no_so = '$no_so'
EOT;
        $sql_dp = <<<EOT
select
    a.no_so as no_bukti
    , (
        substring(no_so from 4 for 4)
        || '-' || substring(no_so from 8 for 2)
        || '-' || substring(no_so from 10 for 2)
    )::DATE tanggal
    , b.nm_pembayaran
    , a.rp_jumlah as rp_bayar
    , null::VARCHAR nomor_bank
    , a.no_kartu as nomor_ref
    , null::DATE tgl_jth_tempo
    , a.keterangan
from
    sales.t_sales_order_bayar a
    , mst.t_jns_pembayaran b
where
    b.kd_jenis_bayar = a.kd_jns_pembayaran and
    a.no_so = '$no_so'
EOT;
        $sql_bayar = <<<EOT
select
    a.no_pelunasan_piutang as no_bukti
    , c.tanggal
    , d.nm_pembayaran
    , b.rp_bayar
    , b.nomor_bank
    , b.nomor_ref
    , b.tgl_jth_tempo
    , c.keterangan
from
    sales.t_piutang_detail a
    , sales.t_piutang_bayar b
    , sales.t_piutang_pelunasan c
    , mst.t_jns_pembayaran d
where
    b.no_pelunasan_piutang = a.no_pelunasan_piutang and
    c.no_pelunasan_piutang = a.no_pelunasan_piutang and
    d.kd_jenis_bayar = b.kd_jns_bayar and
    a.no_faktur = '$no_so'
EOT;
        $sql_kirim = <<<EOT
select
    a.kd_produk
    , b.kd_produk_lama
    , b.nama_produk
    , a.qty
    , a.is_kirim
    , case when is_kirim = '0' then a.qty else c.qty_kirim end qty_kirim
    , b.nm_satuan
    , a.rp_harga
    , a.rp_diskon
    , a.rp_ekstra_diskon
    , a.rp_total
from sales.t_sales_order_detail a
join (
    select b.kd_produk, b.kd_produk_lama, b.nama_produk, c.nm_satuan
    from mst.t_produk b,
    mst.t_satuan c
    where c.kd_satuan = b.kd_satuan
) b on b.kd_produk = a.kd_produk
full outer join (
    select x.kd_produk, sum(x.qty) as qty_kirim
    from sales.t_surat_jalan_detail x
        , sales.t_surat_jalan y
        , sales.t_sales_delivery_order z
    where
        x.no_sj = y.no_sj and
        y.no_do = z.no_do and
        z.no_so = '$no_so'
    group by
        x.kd_produk
) c on a.kd_produk = c.kd_produk
where a.no_so = '$no_so'
EOT;
$sql_retur = <<<EOT
select b.no_so
    , a.no_retur
    , a.kd_produk
    , d.kd_produk_lama
    , d.nama_produk
    , a.qty
    , e.nm_satuan
    , a.rp_jumlah
    , a.rp_disk
    , a.rp_potongan
    , a.rp_total
from sales.t_retur_sales_detail a
join sales.t_retur_sales b on a.no_retur = b.no_retur
join mst.t_produk d on d.kd_produk = a.kd_produk
join mst.t_satuan e on e.kd_satuan = d.kd_satuan
where b.no_so_retur = '$no_so'
EOT;

        $query = $this->db->query($sql_header);
        if ($query->num_rows() > 0) {
            $result['header'] = $query->row();
        }
        $query = $this->db->query($sql_kirim);
        if ($query->num_rows() > 0) {
            $result['detail_penjualan'] = $query->result();
        }
        $query = $this->db->query($sql_dp);
        if ($query->num_rows() > 0) {
            $result['detail_pembayaran'] = $query->result();
        }
        $query = $this->db->query($sql_bayar);
        if ($query->num_rows() > 0) {
            $bayar_cicil = $query->result();
        }
        foreach ($bayar_cicil as $bayar) {
            $result['detail_pembayaran'][] = $bayar;
        }
        $query = $this->db->query($sql_retur);
        if ($query->num_rows() > 0) {
            $result['detail_retur'] = $query->result();
        }

        unset($bayar_cicil);
        $this->db->flush_cache();

        return $result;
    }
}
