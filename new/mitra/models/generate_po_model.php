<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Generate_po_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($kd_supplier, $tgl_min, $tgl_max, $as_ref = false)
    {
        $results= array('data' => array(), 'total' => 0);

        if($as_ref) {
            $sql = "d.kd_supplier, to_char(c.tgl_so, 'YYYYMM') blth, a.kd_produk, sum(a.qty) qty";
            $group ="d.kd_supplier, blth, a.kd_produk";
        } else {
            $sql = " d.kd_supplier, e.nama_supplier, to_char(c.tgl_so, 'YYYYMM') blth,
            a.kd_produk, b.nama_produk, sum(a.qty) qty, f.nm_satuan";
            $group = 'd.kd_supplier, blth, a.kd_produk, nama_produk,
            nama_supplier, f.nm_satuan';
        }
        $this->db->start_cache();

        $this->db->select($sql, false)
        ->from('sales.t_sales_order_detail a ')
        ->join('mst.t_produk b', 'a.kd_produk = b.kd_produk')
        ->join('sales.t_sales_order c', 'a.no_so = c.no_so')
        ->join('mst.t_supp_per_brg d', 'd.kd_produk = a.kd_produk')
        ->join('mst.t_supplier e', 'd.kd_supplier = e.kd_supplier')
        ->join('mst.t_satuan f', 'b.kd_satuan = f.kd_satuan')
        ->where('d.aktif', "true")->where('b.is_konsinyasi', '1')
        ->where('a.flag_gen_konsinyasi', 0)->where('e.kd_supplier', $kd_supplier)
        ->where("c.tgl_so between '$tgl_min' and '$tgl_max'", null, false);
        $this->db->stop_cache();

        $results['total'] = $this->db->count_all_results();

        //order_by('blth desc');
        $this->db->group_by($group);
        $query = $this->db->get();
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();
        $results['success'] = (!empty($results['data']) || !empty($results['total']) );

        $this->db->flush_cache();
        return $results;
    }

    public function get_references($kd_supplier, $kd_produk, $blth, $status)
    {
        $query = $this->db->query("select qty from purchase.t_gen_order_konsinyasi
            where kd_supplier = '$kd_supplier' and blth = '$blth' and
            kd_produk = '$kd_produk' and status = 0");
        if($query->num_rows() > 0 ) {
            return $query->row()->qty;
        }
    }

    public function get_data_harga($kd_produk)
    {
        $query = "select * from mst.t_supp_per_brg where no_bukti = (
          select no_bukti from (
            select * from mst.t_supp_per_brg where kd_produk = '$kd_produk' 
            and tgl_start_diskon <= current_date and aktif_diskon = 1 
            order by tgl_start_diskon desc
          ) x limit 1
        ) and kd_produk = '$kd_produk'";
        $query          = $this->db->query($query);
        $result['data'] = $query->row();
        $result['lq']   = $this->db->last_query();
        $result['success'] = $query->num_rows() > 0; 

        return $result;
    }

    public function update_qty_po($kd_supplier, $kd_produk, $blth, $data)
    {
        $this->db->where('kd_supplier', $kd_supplier)->where('kd_produk', $kd_produk)
        ->where('blth', $blth);
        $result['success'] = $this->db->update('purchase.t_gen_order_konsinyasi', $data);
        $result['lq'] = $this->db->last_query();
        return $result;
    }

    public function insert_data_po($data)
    {
        $result['success']= $this->db->insert('purchase.t_gen_order_konsinyasi', $data);
        $result['lq']     = $this->db->last_query();
        return $result;
    }
    
    public function update_data_so($blth, $kd_produk, $data)
    {
        $this->db->where("no_so like 'SOS$blth%'", null, false)
        ->where('kd_produk', $kd_produk)->where('flag_gen_konsinyasi', 0);
        $result['success']  = $this->db->update('sales.t_sales_order_detail', $data);
        $result['lq']       = $this->db->last_query();
        return $result;
    }
    
    public function insert_data_po_bulk($data)
    {
        $result['success']= $this->db->insert_batch('purchase.t_gen_order_konsinyasi', $data);
        $result['lq']     = $this->db->last_query();
        return $result;
    }
}
