<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_harga_jual_model extends MY_Model {

    public function __construct(){
        parent::__construct();
    }

    public function get_outstanding_bonus($kd_produk, $tgl_start_bonus, $tgl_end_bonus)
    {
        $this->db->where('kd_produk', $kd_produk)
        ->where('tgl_start_bonus', $tgl_start_bonus)->where('tgl_end_bonus', $tgl_end_bonus);

        $result = $this->db->get('mst.t_bonus_sales_temp');
        return $result->num_rows() > 0;
    }

    public function insert_row_temp($data)
    {
        $result['success']  = $this->db->insert('mst.t_bonus_sales_temp', $data);
        $result['lq']       = $this->db->last_query();

        return $result;
    }

    public function get_produk($search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $this->db->start_cache();
        if($search != ""){
            $this->db->where("(lower(nama_produk) LIKE '%" . strtolower($search)
        	. "%') OR kd_produk LIKE '%".$search."%'", null, false);
        }
        $this->db->select('kd_produk,nama_produk')->from('mst.t_produk')
        ->where('aktif', 1)->stop_cache();

        $result['total'] = $this->db->count_all_results();

    		$this->db->order_by('nama_produk', 'asc')->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_kd_bonus_sales($search, $start, $limit)
    {
        $result = array('total' => 0, 'data' => array());
        $this->db->start_cache();
        if($search != ""){
            $this->db->where("(lower(kd_bonus_sales) LIKE '%" . strtolower($search) ."%'", null, false);
        }

        $this->db->select('kd_bonus_sales, tgl_start_bonus, tgl_end_bonus, keterangan, created_by')
        ->from('mst.t_bonus_sales_temp')->where('status', 0)
        ->stop_cache();

        $result['total'] = $this->db->count_all_results();

        $this->db->order_by('kd_bonus_sales', 'asc')->limit($limit,$start);
        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_row_kode_produk($kd_produk)
    {
      $result = array('success' => true, 'data' => null);

      $this->db->select('distinct on(a.kd_produk) a.kd_produk, a.nama_produk,   b.is_bonus,
        b.kd_produk_bonus, c.nama_produk as nama_produk_bonus, b.qty_beli_bonus, b.qty_bonus, b.is_bonus_kelipatan,
        b.kd_produk_member, d.nama_produk as nama_produk_member, b.qty_beli_member, b.qty_member, b.is_member_kelipatan,
        b.kd_kategori1_bonus, b.kd_kategori2_bonus, b.kd_kategori3_bonus, b.kd_kategori4_bonus,
        b.kd_kategori1_member, b.kd_kategori2_member, b.kd_kategori3_member, b.kd_kategori4_member,
        b.tgl_start_bonus, b.tgl_end_bonus', false)->from('mst.t_produk a')
        ->join('mst.t_diskon_sales b', 'a.kd_produk = b.kd_produk', 'left')
        ->join('mst.t_produk c', 'b.kd_produk_bonus = c.kd_produk', 'left')
        ->join('mst.t_produk d', 'b.kd_produk_member = d.kd_produk', 'left')
        ->where('a.kd_produk', $kd_produk);

        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->row();
        return $result;
    }

    public function get_approval_data($kd_bonus_sales, $tgl_start_bonus = null, $tgl_end_bonus = null)
    {
        $result = array('success' => true, 'data' => null);
        $this->db->start_cache();
        if(!empty($tgl_start_bonus) && !empty($tgl_end_bonus)){
            $this->db->where("(a.tgl_start_bonus = '$tgl_start_bonus' and a.tgl_end_bonus = '$tgl_end_bonus')", null, false);
        } elseif(!empty($tgl_start_bonus)) {
            $this->db->where('a.tgl_start_bonus', $tgl_start_bonus);
        } elseif(!empty($tgl_end_bonus)) {
            $this->db->where('a.tgl_end_bonus', $tgl_end_bonus);

        }

        $this->db->select("a.*,
            CASE is_bonus WHEN 1 THEN 'Ya' ELSE 'Tidak' END is_bonus,
            CASE is_bonus_paket WHEN 1 THEN 'Ya' ELSE 'Tidak' END is_bonus_paket,
            CASE is_bonus_kelipatan WHEN 1 THEN 'Ya' ELSE 'Tidak' END is_bonus_kelipatan,
            CASE is_member_kelipatan WHEN 1 THEN 'Ya' ELSE 'Tidak' END is_member_kelipatan,
            b.nama_produk, 'Approve' as status_approval, c.nama_produk nama_produk_bonus, d.nama_produk nama_produk_member,
            e.nama_kategori1 kategori1_bonus, f.nama_kategori2 kategori2_bonus, g.nama_kategori3 kategori3_bonus, h.nama_kategori4 kategori4_bonus,
            i.nama_kategori1 kategori1_member, j.nama_kategori2 kategori2_member, k.nama_kategori3 kategori3_member, l.nama_kategori4 kategori4_member", false)
        ->from('mst.t_bonus_sales_temp  a')
        ->join('mst.t_produk b', 'a.kd_produk = b.kd_produk')
        ->join('mst.t_produk c', 'a.kd_produk_bonus = c.kd_produk', 'left')
        ->join('mst.t_produk d', 'a.kd_produk_member = d.kd_produk', 'left')

        ->join('mst.t_kategori1 e', 'a.kd_kategori1_bonus = e.kd_kategori1', 'left')
        ->join('mst.t_kategori2 f', 'a.kd_kategori1_bonus = f.kd_kategori1 and a.kd_kategori2_bonus = f.kd_kategori2', 'left')
        ->join('mst.t_kategori3 g', 'a.kd_kategori1_bonus = g.kd_kategori1 and a.kd_kategori2_bonus = g.kd_kategori2 and '
            .'a.kd_kategori3_bonus = g.kd_kategori3', 'left')
        ->join('mst.t_kategori4 h', 'a.kd_kategori1_bonus = h.kd_kategori1 and a.kd_kategori2_bonus = h.kd_kategori2 and '
            .'a.kd_kategori3_bonus = h.kd_kategori3 and a.kd_kategori4_bonus = h.kd_kategori4', 'left')

        ->join('mst.t_kategori1 i', 'a.kd_kategori1_member = i.kd_kategori1', 'left')
        ->join('mst.t_kategori2 j', 'a.kd_kategori1_member = j.kd_kategori1 and a.kd_kategori2_member = j.kd_kategori2', 'left')
        ->join('mst.t_kategori3 k', 'a.kd_kategori1_member = k.kd_kategori1 and a.kd_kategori2_member = k.kd_kategori2 and '
            .'a.kd_kategori3_member = k.kd_kategori3', 'left')
        ->join('mst.t_kategori4 l', 'a.kd_kategori1_member = l.kd_kategori1 and a.kd_kategori2_member = l.kd_kategori2 and '
            .'a.kd_kategori3_member = l.kd_kategori3 and a.kd_kategori4_member = l.kd_kategori4', 'left')

        ->where('a.kd_bonus_sales', $kd_bonus_sales)
        ->where('a.status', 0);
        $this->db->stop_cache();
        $result['total'] = $this->db->count_all_results();

        $query           = $this->db->get();
        $result['lq']    = $this->db->last_query();
        $result['data']  = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function proses_approval($kd_bonus_sales, $kd_produk, $data)
    {
        $this->db->where('kd_bonus_sales', $kd_bonus_sales)->where('kd_produk', $kd_produk);
        $result['success']  = $this->db->update('mst.t_bonus_sales_temp', $data);
        $result['lq']       = $this->db->last_query();

        return $result;
    }

    public function update_diskon_sales($kd_produk, $data)
    {
        $this->db->where('kd_produk', $kd_produk);
        $result['success']  = $this->db->update('mst.t_diskon_sales', $data);
        $result['lq']       = $this->db->last_query();

        return $result;
    }
}
