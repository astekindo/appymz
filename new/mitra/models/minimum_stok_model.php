<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Minimum_stok_model extends MY_Model {

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
    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%')";
        }
        $sql1 = "select *  from (
                select a.kd_produk, b.nama_produk, sum(a.qty_oh) stok, c.nm_satuan,b.min_stok, b.max_stok,b.pct_alert, b.min_stok + (b.pct_alert/100 * b.min_stok) limit_stok
                from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c
                where a.kd_produk = b.kd_produk
                and b.kd_satuan = c.kd_satuan
                group by a.kd_produk,b.nama_produk, c.nm_satuan, b.min_stok, b.max_stok, b.pct_alert,limit_stok) z
                where stok <= limit_stok " . $sql_search . " order by nama_produk LIMIT " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (select *  from (
                select a.kd_produk,  b.nama_produk, sum(a.qty_oh) stok,c.nm_satuan,b.min_stok, b.max_stok,b.pct_alert, b.min_stok + (b.pct_alert/100 * b.min_stok) limit_stok
                from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c
                where a.kd_produk = b.kd_produk
                and b.kd_satuan = c.kd_satuan
                group by a.kd_produk,b.nama_produk, c.nm_satuan, b.min_stok, b.max_stok, b.pct_alert,limit_stok) z
                where stok <= limit_stok " . $sql_search . " ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_notificationpo($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = " select a.no_po, a.tanggal_po, a.kd_suplier_po, b.nama_supplier, a.masa_berlaku_po, a.tgl_berlaku_po,
					case when kd_peruntukan = 0 then 'Supermarket' else 'Distribusi' end peruntukan,
					a.rp_jumlah_po, a.rp_ppn_po, a.rp_diskon_po, rp_dp, a.rp_total_po, a.remark, a.kirim_po, a.alamat_kirim_po
					from purchase.t_purchase a, mst.t_supplier b
					where (a.tgl_berlaku_po - current_date) between 0 and (
					select CAST(coalesce(nilai_parameter, '0') AS integer) from mst.t_parameter
					where kd_parameter = '1')
					and a.close_po = 0
					and a.kd_suplier_po = b.kd_supplier
					and a.approval_po = '1' " . $sql_search . " order by no_po LIMIT " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (select *  from (
                select a.kd_produk,  b.nama_produk, sum(a.qty_oh) stok,c.nm_satuan,b.min_stok, b.max_stok,b.pct_alert, b.min_stok + (b.pct_alert/100 * b.min_stok) limit_stok
                from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c
                where a.kd_produk = b.kd_produk
                and b.kd_satuan = c.kd_satuan
                group by a.kd_produk,b.nama_produk, c.nm_satuan, b.min_stok, b.max_stok, b.pct_alert,limit_stok) z
                where stok <= limit_stok " . $sql_search . " ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_notificationinvoice($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = " select a.no_invoice, a.tgl_invoice, a.tgl_jth_tempo, a.kd_supplier, b.nama_supplier, a.no_bukti_supplier, a.tgl_terima_invoice,
					a.rp_jumlah, a.rp_diskon, a.rp_ppn, a.rp_total, a.rp_pelunasan_hutang
					from purchase.t_invoice a , mst.t_supplier b
					where a.kd_supplier = b.kd_supplier
					and a.status <> 2
					and (a.tgl_jth_tempo - current_date) between 0 and (
					select CAST(coalesce(nilai_parameter, '0') AS integer) from mst.t_parameter
					where kd_parameter = '2') " . $sql_search . " order by no_invoice LIMIT " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (select *  from (
                select a.kd_produk,  b.nama_produk, sum(a.qty_oh) stok,c.nm_satuan,b.min_stok, b.max_stok,b.pct_alert, b.min_stok + (b.pct_alert/100 * b.min_stok) limit_stok
                from inv.t_brg_inventory a, mst.t_produk b, mst.t_satuan c
                where a.kd_produk = b.kd_produk
                and b.kd_satuan = c.kd_satuan
                group by a.kd_produk,b.nama_produk, c.nm_satuan, b.min_stok, b.max_stok, b.pct_alert,limit_stok) z
                where stok <= limit_stok " . $sql_search . " ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_notificationhargajual($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = "SELECT * from (
            SELECT  CASE WHEN coalesce(f.rp_het_cogs, 0) = 0 THEN coalesce(f.rp_het_harga_beli, 0) else coalesce(f.rp_het_cogs, 0) END batas_jual,
            z.net_harga_jual, d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 as nama_kategori,
            f.kd_produk, f.nama_produk, e.nm_satuan, g.nama_ukuran, f.rp_cogs, f.rp_het_harga_beli, coalesce(f.rp_het_cogs, 0) rp_het_cogs, f.rp_jual_supermarket, f.rp_jual_distribusi
            FROM mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g,
            (select kd_produk, round(((((h.rp_jual_supermarket * ((100 - h.disk_persen_kons1) / 100)) * ((100 - h.disk_persen_kons2) / 100)) *
            ((100 - h.disk_persen_kons3) / 100)) * ((100 - h.disk_persen_kons4) / 100)) - h.disk_amt_kons5, 2) - h.disk_amt_kons1
            - h.disk_amt_kons2 - h.disk_amt_kons3 - h.disk_amt_kons4 - h.disk_amt_kons5  net_harga_jual from mst.t_diskon_sales h) z
            WHERE e.kd_satuan=f.kd_satuan
            AND g.kd_ukuran = f.kd_ukuran
            and f.kd_produk = z.kd_produk
            AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4 
            AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1 
            AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
            AND d.kd_kategori1=c.kd_kategori1 
            AND f.aktif = 1) x
        WHERE x.net_harga_jual < batas_jual $sql_search order by x.nama_produk LIMIT $length offset $offset";

        $query = $this->db->query($sql1);
        //var_dump($this->db->last_query());
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (
        SELECT * from (
            SELECT  CASE WHEN coalesce(f.rp_het_cogs, 0) = 0 THEN coalesce(f.rp_het_harga_beli, 0) else coalesce(f.rp_het_cogs, 0) END batas_jual,
            z.net_harga_jual, d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 as nama_kategori,
            f.kd_produk, f.nama_produk, e.nm_satuan, g.nama_ukuran, f.rp_cogs, f.rp_het_harga_beli, coalesce(f.rp_het_cogs, 0) rp_het_cogs, f.rp_jual_supermarket, f.rp_jual_distribusi
            FROM mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g,
            (select kd_produk, round(((((h.rp_jual_supermarket * ((100 - h.disk_persen_kons1) / 100)) * ((100 - h.disk_persen_kons2) / 100)) *
            ((100 - h.disk_persen_kons3) / 100)) * ((100 - h.disk_persen_kons4) / 100)) - h.disk_amt_kons5, 2) - h.disk_amt_kons1
            - h.disk_amt_kons2 - h.disk_amt_kons3 - h.disk_amt_kons4 - h.disk_amt_kons5  net_harga_jual from mst.t_diskon_sales h) z
            WHERE e.kd_satuan=f.kd_satuan
            AND g.kd_ukuran = f.kd_ukuran
            and f.kd_produk = z.kd_produk
            AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
            AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
            AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
            AND d.kd_kategori1=c.kd_kategori1
            AND f.aktif = 1) x
        WHERE x.net_harga_jual < batas_jual $sql_search order by x.nama_produk
        ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_listapprovalhargajual($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(no_bukti) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = "select distinct no_bukti, tanggal, keterangan, created_by, updated_by
                from mst.t_diskon_sales_temp
                where status = 0 " . $sql_search . " order by no_bukti LIMIT " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (select *  from (select distinct no_bukti, tanggal, keterangan, created_by, updated_by
                from mst.t_diskon_sales_temp
                where status = 0 " . $sql_search . ") z
                 ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    // sampai sini -----------------------------------------------
    public function get_listapprovalhargajual2($search = "", $offset, $length) {
//		$sql_search = "";
//		if($search != ""){
//			$sql_search = " AND (lower(no_bukti) LIKE '%" . strtolower($search) . "%')";
//		}

        $sql1 = "select b.kd_produk, c.nama_produk, a.rp_het_harga_beli het_beli_lama, a. rp_cogs cogs_lama, a.rp_het_cogs het_cogs_lama,
case when a.disk_amt_kons1 > 0 then 'Rp. ' || a.disk_amt_kons1 else a.disk_amt_kons1 || ' %' end diskon_kons1,
case when a.disk_amt_kons2 > 0 then 'Rp. ' || a.disk_amt_kons2 else a.disk_amt_kons2 || ' %' end diskon_kons2,
case when a.disk_amt_kons3 > 0 then 'Rp. ' || a.disk_amt_kons3 else a.disk_amt_kons3 || ' %' end diskon_kons3,
case when a.disk_amt_kons4 > 0 then 'Rp. ' || a.disk_amt_kons4 else a.disk_amt_kons4 || ' %' end diskon_kons4,
case when a.disk_amt_kons5 > 0 then 'Rp. ' || a.disk_amt_kons5 else a.disk_amt_kons5 || ' %' end diskon_kons5,
case when a.disk_amt_member1 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member1,
case when a.disk_amt_member2 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member2,
case when a.disk_amt_member3 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member3,
case when a.disk_amt_member4 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member4,
case when a.disk_amt_member5 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member5,
case when a.is_bonus = 1 then 'Bonus' else 'Non Bonus' end is_bonus,
a.qty_beli_bonus,
case when a.kd_produk_bonus is not null then a.kd_produk_bonus else a.kd_kategori1_bonus || '-' || a.kd_kategori2_bonus || '-' || a.kd_kategori3_bonus || '-' || a.kd_kategori4_bonus end produk_bonus,
a.qty_bonus, case when a.is_bonus_kelipatan = 1 then 'Ya' else 'Tidak' end is_bonus_kelipatan,
a.qty_beli_member,
case when a.kd_produk_member is not null then a.kd_produk_member else a.kd_kategori1_member || '-' || a.kd_kategori2_member || '-' || a.kd_kategori3_member || '-' || a.kd_kategori4_member end produk_bonus_member,
a.qty_member, case when a.is_member_kelipatan = 1 then 'Ya' else 'Tidak' end is_member_kelipatan,
a.net_hrg_supplier_sup_inc, a.rp_cogs, a.rp_ongkos_kirim, a.pct_margin, a.rp_margin, a.rp_het_harga_beli, a.rp_het_cogs, a.rp_jual_supermarket, a.rp_jual_distribusi
from mst.t_diskon_sales_temp a, mst.t_diskon_sales b, mst.t_produk c
where a.no_bukti = '$search'
and a.kd_produk = b.kd_produk
and a.kd_produk = c.kd_produk  order by c.nama_produk LIMIT " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (select *  from (select b.kd_produk, c.nama_produk, a.rp_het_harga_beli het_beli_lama, a. rp_cogs cogs_lama, a.rp_het_cogs het_cogs_lama,
case when a.disk_amt_kons1 > 0 then 'Rp. ' || a.disk_amt_kons1 else a.disk_amt_kons1 || ' %' end diskon_kons1,
case when a.disk_amt_kons2 > 0 then 'Rp. ' || a.disk_amt_kons2 else a.disk_amt_kons2 || ' %' end diskon_kons2,
case when a.disk_amt_kons3 > 0 then 'Rp. ' || a.disk_amt_kons3 else a.disk_amt_kons3 || ' %' end diskon_kons3,
case when a.disk_amt_kons4 > 0 then 'Rp. ' || a.disk_amt_kons4 else a.disk_amt_kons4 || ' %' end diskon_kons4,
case when a.disk_amt_kons5 > 0 then 'Rp. ' || a.disk_amt_kons5 else a.disk_amt_kons5 || ' %' end diskon_kons5,
case when a.disk_amt_member1 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member1,
case when a.disk_amt_member2 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member2,
case when a.disk_amt_member3 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member3,
case when a.disk_amt_member4 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member4,
case when a.disk_amt_member5 > 0 then 'Rp. ' || a.disk_amt_member1 else a.disk_amt_member1 || ' %' end diskon_member5,
case when a.is_bonus = 1 then 'Bonus' else 'Non Bonus' end is_bonus,
a.qty_beli_bonus,
case when a.kd_produk_bonus is not null then a.kd_produk_bonus else a.kd_kategori1_bonus || '-' || a.kd_kategori2_bonus || '-' || a.kd_kategori3_bonus || '-' || a.kd_kategori4_bonus end produk_bonus,
a.qty_bonus, case when a.is_bonus_kelipatan = 1 then 'Ya' else 'Tidak' end is_bonus_kelipatan,
a.qty_beli_member,
case when a.kd_produk_member is not null then a.kd_produk_member else a.kd_kategori1_member || '-' || a.kd_kategori2_member || '-' || a.kd_kategori3_member || '-' || a.kd_kategori4_member end produk_bonus_member,
a.qty_member, case when a.is_member_kelipatan = 1 then 'Ya' else 'Tidak' end is_member_kelipatan,
a.net_hrg_supplier_sup_inc, a.rp_cogs, a.rp_ongkos_kirim, a.pct_margin, a.rp_margin, a.rp_het_harga_beli, a.rp_het_cogs, a.rp_jual_supermarket, a.rp_jual_distribusi
from mst.t_diskon_sales_temp a, mst.t_diskon_sales b, mst.t_produk c
where a.no_bukti = '$search'
and a.kd_produk = b.kd_produk
and a.kd_produk = c.kd_produk) z
                 ) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row($id = NULL) {
        $this->db->where("id_ro", $id);
        $query = $this->db->get('tt_receive_order');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function insert_row($data = NULL) {
        return $this->db->insert('tt_receive_order', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('id_ro', $id);
        return $this->db->update('tt_receive_order', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $this->db->where('id_ro', $id);
        return $this->db->delete('tt_receive_order');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all() {
        $this->db->where("aktif is true", NULL);
        $this->db->order_by("id_ro", 'asc');
        $query = $this->db->get("tt_receive_order");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_notif_lokasidefault($search = '', $limit, $start) {
        $results= array('data' => null, 'total' => 0);
        $where = '';
        if ($search != "") {
            $search = strtolower($search);
            $where = "and ( (nama_kategori1 like '%$search%')  or (nama_kategori2 like '%$search%') " .
              "or (nama_kategori3 like '%$search%')  or (nama_kategori4 like '%$search%') " .
              "or (nama_produk like '%$search%') )";
            $this->db->where($where, null, false);
        }

        $count = <<<EOT
select count(kd_produk) from
(
  select kd_kategori1,kd_kategori2, kd_kategori3, kd_kategori4,a.kd_produk, nama_produk, a.kd_satuan from  mst.t_produk a
    left join (select * from mst.t_produk_lokasi c where c.flag_default = 1) b on a.kd_produk = b.kd_produk
    where b.kd_produk is null and aktif = 1
) z,
mst.t_kategori1 q, mst.t_kategori2 w, mst.t_kategori3 e, mst.t_kategori4 r
where z.kd_kategori1 = q.kd_kategori1
and z.kd_kategori1 = w.kd_kategori1
and z.kd_kategori2 = w.kd_kategori2
and z.kd_kategori1 = e.kd_kategori1
and z.kd_kategori2 = e.kd_kategori2
and z.kd_kategori3 = e.kd_kategori3
and z.kd_kategori1 = r.kd_kategori1
and z.kd_kategori2 = r.kd_kategori2
and z.kd_kategori3 = r.kd_kategori3
and z.kd_kategori4 = r.kd_kategori4
EOT;

        $results['lq'] = <<<EOT
select q.nama_kategori1,w.nama_kategori2,e.nama_kategori3,r.nama_kategori4, nm_satuan, kd_produk,nama_produk from
(select kd_kategori1,kd_kategori2, kd_kategori3, kd_kategori4,a.kd_produk, nama_produk, a.kd_satuan from
mst.t_produk a
left join (select * from mst.t_produk_lokasi c where c.flag_default = 1
) b on a.kd_produk = b.kd_produk
where b.kd_produk is null and aktif = 1) z,
mst.t_kategori1 q, mst.t_kategori2 w, mst.t_kategori3 e, mst.t_kategori4 r, mst.t_satuan t
where t.kd_satuan = z.kd_Satuan
and z.kd_kategori1 = q.kd_kategori1
and z.kd_kategori1 = w.kd_kategori1
and z.kd_kategori2 = w.kd_kategori2
and z.kd_kategori1 = e.kd_kategori1
and z.kd_kategori2 = e.kd_kategori2
and z.kd_kategori3 = e.kd_kategori3
and z.kd_kategori1 = r.kd_kategori1
and z.kd_kategori2 = r.kd_kategori2
and z.kd_kategori3 = r.kd_kategori3
and z.kd_kategori4 = r.kd_kategori4
$where
order by q.nama_kategori1,w.nama_kategori2,e.nama_kategori3,r.nama_kategori4, nm_satuan asc
limit $limit offset $start
EOT;
        $query = $this->db->query($count);
        $results['total'] = $query->row()->count;
        if($results['total'] > 0) {
            $query = $this->db->query($results['lq']);
            $results['data'] = $query->result();
        }
        return $results;
    }
}