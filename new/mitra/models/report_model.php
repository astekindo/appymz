<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Untuk preporting, karena model pemilihan data bisa multi-select, dibuatkan beberapa method khusus.
 *
 * @property mixed db
 */
class Report_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}

    /**
     * @param string $search parameter pencarian (opsional)
     * @param $limit
     * @param $start
     * @return array(data, total)
     * @author bambang
     * @lastedited 5 may 2014
     */
    public function get_user($search = '', $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(username)', strtolower($search));
        }

        $this->db->select('kd_user,username');
        $this->db->stop_cache();
        //hitung total dulu
        $results['total'] = $this->db->count_all_results('secman.t_user');

        //lalu ambil data
        $this->db->order_by('kd_user desc, kd_cabang desc, kd_jabatan desc');
        $query = $this->db->get('secman.t_user', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_shift($search = '', $users = null, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $where = "(lower(username) LIKE '%" . strtolower($search) . "%' or no_open_saldo LIKE '%" . $search . "%')";
            $this->db->where($where, null, false);
        }
        if($users) {
            if(strpos($users,',',1)) {
                $this->db->where_in('username',explode(',',$users));
            } else {
                $this->db->where('username',$users);
            }
        }

        $this->db->select('no_open_saldo,username');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_open_kasir');

        $this->db->order_by('no_open_saldo desc, jam_buka_saldo asc');
        $query = $this->db->get('sales.t_open_kasir', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_member($search = '', $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(a.nmmember)', strtolower($search));
        }

        $this->db->select('kd_member,nmmember');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_member');

        $this->db->order_by('kd_member desc');
        $query = $this->db->get('mst.t_member', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_ukuran($search = '', $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(nama_ukuran)', strtolower($search));
        }

        $this->db->select('kd_ukuran,nama_ukuran');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_ukuran');

        $this->db->order_by('nama_ukuran asc');
        $query = $this->db->get('mst.t_ukuran', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_satuan($search = '', $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(nm_satuan)', strtolower($search));
        }

        $this->db->select('kd_satuan, nm_satuan');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_satuan');

        $this->db->order_by('nm_satuan asc');
        $query = $this->db->get('mst.t_satuan', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_produk($search, $supplier, $kategori1, $kategori2, $kategori3, $kategori4, $ukuran, $satuan, $konsinyasi, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $where = "(lower(a.nama_produk) LIKE '%" . strtolower($search) . "%')";
            $this->db->or_where($where, null, false);
        }

        if ($supplier != '') {
            if(strpos($supplier,',',1)) {
                $this->db->where_in('b.kd_supplier',explode(',',$supplier));
            } else {
                $this->db->where('b.kd_supplier', $supplier);
            }
        }
        if ($kategori1 != '') {
            strpos($kategori1,',',1)
              ? $this->db->where_in('a.kd_kategori1',explode(',',$kategori1))
              : $this->db->where('a.kd_kategori1',$kategori1);
        }
        if ($kategori2 != '') {
            strpos($kategori2,',',1)
              ? $this->db->where_in('a.kd_kategori2',explode(',',$kategori2))
              : $this->db->where('a.kd_kategori2',$kategori2);
        }
        if ($kategori3 != '') {
            strpos($kategori3,',',1)
              ? $this->db->where_in('a.kd_kategori3',explode(',',$kategori3))
              : $this->db->where('a.kd_kategori3',$kategori3);
        }
        if ($kategori4 != '') {
            strpos($kategori4,',',1)
              ? $this->db->where_in('a.kd_kategori4',explode(',',$kategori4))
              : $this->db->where('a.kd_kategori4',$kategori4);
        }
        if ($ukuran != '') {
            strpos($ukuran,',',1)
              ? $this->db->where_in('a.kd_ukuran',explode(',',$ukuran))
              : $this->db->where('a.kd_ukuran',$ukuran);
        }
        if ($satuan != '') {
            strpos($satuan,',',1)
              ? $this->db->where_in('a.kd_satuan',explode(',',$satuan))
              : $this->db->where('a.kd_satuan',$satuan);
        }
        if ($konsinyasi && $konsinyasi == 'K') {
            $this->db->where('a.is_konsinyasi', 1);
        }
        if ($konsinyasi && $konsinyasi == 'N') {
            $this->db->where('a.is_konsinyasi', 0);
        }

        $this->db->select('a.kd_produk, a.nama_produk')
          ->join('mst.t_supp_per_brg b', 'a.kd_produk = b.kd_produk');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_produk a');

        $this->db->order_by('a.nama_produk asc');
        $query = $this->db->get('mst.t_produk a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
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

    public function get_kategori2($search, $kategori1, $limit, $start){
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("lower(a.nama_kategori2_p) LIKE '%" . strtolower($search) . "%'", null, false);
        }
        if ($kategori1 != '') {
            if(strpos($kategori1,',',1)) {
                $this->db->where_in('a.kd_kategori1',explode(',',$kategori1));
            } else {
                $this->db->where('a.kd_kategori1',$kategori1);
            }
        }
        $this->db->select(
            "a.kd_kategori1, a.kd_kategori2,
            a.kd_kategori2_p kd_kategori,
            a.nama_kategori1, a.nama_kategori2,
            a.nama_kategori2_p nama_kategori ,
            CASE WHEN a.kategori2_aktif THEN 'Ya' ELSE 'Tidak' END aktif "
            , false);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('report.v_list_kategori a');

        $this->db->order_by('a.kd_kategori2_p asc, a.nama_kategori1 asc, a.nama_kategori2 asc');

        $query = $this->db->get('report.v_list_kategori a',$limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_kategori3($search, $kategori1, $kategori2, $limit, $start){
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("lower(a.nama_kategori3_p) LIKE '%" . strtolower($search) . "%'", null, false);
        }
        if ($kategori2 != '') {
            if(strpos($kategori2,',',1)) {
                $this->db->where_in('a.kd_kategori2_p',explode(',',$kategori2));
            } else {
                $this->db->where('a.kd_kategori2_p', $kategori2);
            }
        }
        if ($kategori1 != '') {
            if(strpos($kategori1,',',1)) {
                $this->db->where_in('a.kd_kategori1',explode(',',$kategori1));
            } else {
                $this->db->where('a.kd_kategori1',$kategori1);
            }
        }

        $this->db->select(
            "a.kd_kategori1, a.kd_kategori2, a.kd_kategori3,
            a.kd_kategori3_p kd_kategori,
            a.nama_kategori1, a.nama_kategori2, a.nama_kategori3,
            a.nama_kategori3_p nama_kategori,
            CASE WHEN a.kategori3_aktif THEN 'Ya' ELSE 'Tidak' END aktif "
            , false);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('report.v_list_kategori a');
        $this->db->order_by('a.kd_kategori3_p asc, a.nama_kategori1 asc, a.nama_kategori2 asc, a.nama_kategori3 asc');

        $query = $this->db->get('report.v_list_kategori a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_kategori4($search, $kategori1, $kategori2, $kategori3, $limit, $start){
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("lower(a.nama_kategori4_p) LIKE '%" . strtolower($search) . "%'", null, false);
        }
        if ($kategori3 != '') {
            if(strpos($kategori3,',',1)) {
                $this->db->where_in('a.kd_kategori3_p',explode(',',$kategori3));
            } else {
                $this->db->where('a.kd_kategori3_p', $kategori3);
            }
        }
        if ($kategori2 != '') {
            if(strpos($kategori2,',',1)) {
                $this->db->where_in('a.kd_kategori2_p',explode(',',$kategori2));
            } else {
                $this->db->where('a.kd_kategori2_p', $kategori2);
            }
        }
        if ($kategori1 != '') {
            if(strpos($kategori1,',',1)) {
                $this->db->where_in('a.kd_kategori1',explode(',',$kategori1));
            } else {
                $this->db->where('a.kd_kategori1', $kategori1);
            }
        }

        $select = " a.kd_kategori4, b.kd_kategori3, c.kd_kategori2, d.kd_kategori1,
                    d.kd_kategori1 || c.kd_kategori2 || b.kd_kategori3 || a.kd_kategori4 kd_kategori,
                    a.nama_kategori4, b.nama_kategori3, c.nama_kategori2, d.nama_kategori1,
                    d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori ,
                    CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif ";

        $this->db->select(
          "a.kd_kategori1, a.kd_kategori2, a.kd_kategori3, a.kd_kategori4,
          a.kd_kategori4_p kd_kategori,
          a.nama_kategori1, a.nama_kategori2, a.nama_kategori3, a.nama_kategori4,
          a.nama_kategori4_p nama_kategori,
          CASE WHEN a.kategori4_aktif THEN 'Ya' ELSE 'Tidak' END aktif "
          , false);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('report.v_list_kategori a');

        $this->db->order_by('a.kd_kategori4_p asc, a.nama_kategori1 asc, a.nama_kategori2 asc, a.nama_kategori3 asc, a.nama_kategori4 asc');
        $query = $this->db->get('report.v_list_kategori a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_supplier($search, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(nama_supplier)', strtolower($search));
        }

        $this->db->select('kd_supplier,nama_supplier');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_supplier');

        $this->db->order_by('nama_supplier asc');
        $query = $this->db->get('mst.t_supplier', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_jns_bayar($search, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("(lower(nm_pembayaran) LIKE '%" . strtolower($search) . "%' )", null, false);
        }

        $this->db->select('kd_jenis_bayar, nm_pembayaran');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_jns_pembayaran');

        $this->db->order_by('kd_jenis_bayar asc');
        $query = $this->db->get('mst.t_jns_pembayaran', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_no_so($search, $tgl_awal, $tgl_akhir, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("(lower(no_so) LIKE '%" . strtolower($search) . "%' )", null, false);
        }
        if($tgl_awal && $tgl_akhir) {
            $this->db->where("(tgl_so between $tgl_awal and $tgl_akhir)");
        }
        $this->db->select('no_so');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('sales.t_sales_order');

        $this->db->order_by('tgl_so asc');
        $query = $this->db->get('sales.t_sales_order', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_lokasi($search) {
        $where = 'where aktif';
        if($search) {
            $where .= " and nama_lokasi like '%$search%'";
        }
        $results= array('data' => null, 'total' => 0);
        $results['lq'] = "select kd_lokasi, nama_lokasi, nama_lokasi2 from mst.t_lokasi $where order by kd_lokasi asc";

        $query = $this->db->query($results['lq']);
        $results['data'] = $query->result();

        return $results;
    }

    public function get_sub_blok($search) {
        $where = 'where a.aktif';
        if($search) {
            $where .= " and (c.nama_lokasi like '%$search%' or" .
              " b.nama_blok like '%$search%' or a.nama_sub_blok like '%$search%')";
        }
        $results= array('data' => null, 'total' => 0);
        $results['lq'] = <<<EOT
select
  a.kd_lokasi,
  a.kd_blok,
  a.kd_sub_blok,
  a.kd_lokasi || a.kd_blok || a.kd_sub_blok kd_gudang,
  c.nama_lokasi2 || '-' || b.nama_blok2 || '-' || a.nama_sub_blok2 kd_gudang2,
  c.nama_lokasi,
  b.nama_blok,
  a.nama_sub_blok,
  c.nama_lokasi || '-' || b.nama_blok || '-' || a.nama_sub_blok nama_gudang
from mst.t_sub_blok a
  join mst.t_blok b on a.kd_blok = b.kd_blok
  join mst.t_lokasi c on a.kd_lokasi = c.kd_lokasi and b.kd_lokasi = c.kd_lokasi
$where
order by a.kd_lokasi asc, a.kd_blok asc, a.kd_sub_blok asc
EOT;

        $query = $this->db->query($results['lq']);
        $results['data'] = $query->result();

        return $results;
    }

    public function get_no_po($tgl_awal, $tgl_akhir, $supplier, $no_ro, $konsinyasi, $search, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        $this->db->where('a.approval_po', '1');
        if ($search != "") {
            $this->db->like('lower(a.no_po)', strtolower($search));
        }
        if ($supplier != '') {
            if(strpos($supplier,',',1)) {
                $this->db->where_in('a.kd_supplier_po',explode(',',$supplier));
            } else {
                $this->db->where('a.kd_supplier_po', $supplier);
            }
        }

        if ($no_ro != '') {
            if(strpos($no_ro,',',1)) {
                $this->db->where_in('a.no_ro',explode(',',$no_ro));
            } else {
                $this->db->where('a.no_ro', $no_ro);
            }
        }

        if ($konsinyasi == "Y") {
            $this->db->where('a.konsinyasi', "$konsinyasi");
        }
        if($tgl_awal && $tgl_akhir) {
            $this->db->where("((a.tanggal_po between '$tgl_awal' and '$tgl_akhir') or (a.tgl_berlaku_po between '$tgl_awal' and '$tgl_akhir'))", NULL, FALSE);
        }

        $this->db->select('a.no_po, a.tanggal_po, a.kd_suplier_po, b.nama_supplier')
          ->join('mst.t_supplier b', 'a.kd_suplier_po= b.kd_supplier');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('purchase.t_purchase a');

        $this->db->order_by('a.no_po asc');
        $query = $this->db->get('purchase.t_purchase a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_no_ro($tgl_awal, $tgl_akhir, $supplier, $konsinyasi, $search, $limit, $start) {
        $results= array('data' => null, 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(a.no_po)', strtolower($search));
        }
        if ($supplier != '') {
            if(strpos($supplier,',',1)) {
                $this->db->where_in('a.kd_supplier',explode(',',$supplier));
            } else {
                $this->db->where('a.kd_supplier', $supplier);
            }
        }
        if ($konsinyasi == "Y") {
            $this->db->where('a.konsinyasi', "$konsinyasi");
        }
        if($tgl_awal && $tgl_akhir) {
            $this->db->where("((a.created_date between '$tgl_awal' and '$tgl_akhir') or (a.tanggal_terima between '$tgl_awal' and '$tgl_akhir'))");
        }

        $this->db->select('a.no_do, a.kd_supplier, b.nama_supplier, a.created_date, a.tanggal_terima')
          ->join('mst.t_supplier b', 'a.kd_supplier= b.kd_supplier');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('purchase.t_receive_order a');

        $this->db->order_by('no_do asc');
        $query = $this->db->get('purchase.t_receive_order a', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }
}
