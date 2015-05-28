<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Close_bstt_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_rows($kd_collector = "", $search = "",$start,$limit) {
       $this->db->select('no_bstt,tanggal,total_faktur');
                
        if($search != "") {
            $this->db->like('no_bstt',$search);
        }
        $this->db->where('kd_collector', $kd_collector);
        if(!empty($start) && !empty($limit) ) {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get('sales.t_bstt');

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';
//print_r($this->db->last_query());
        return $results;
    }
     public function search_collector($search = "",$start,$limit) {
       $this->db
            ->select('*');
           
        if($search != "") {
            $this->db->like('kd_collector',$search);
        }
       
        if(!empty($start) && !empty($limit) ) {
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get('mst.t_collection');

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';
//print_r($this->db->last_query());
        return $results;
    }

    public function get_rows_detail($no_bstt) {
        $sql = <<<EOT
select a.*,c.nama_pelanggan from sales.t_bstt_detail a
join sales.t_bstt b on a.no_bstt = b.no_bstt
join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan
where a.no_bstt ='$no_bstt'
EOT;

//        $this->db->select("b.kd_produk, c.nama_produk, b.qty, b.qty_adj, sum(a.qty_po) qty_po")
//            ->from("purchase.t_purchase_detail a, purchase.t_dtl_purchase_request b, mst.t_produk c")
//            ->where('a.no_ro',$no_po)
//            ->where('a.kd_produk', 'b.kd_produk')
//            ->where('a.kd_produk', 'c.kd_produk')
//            ->group_by("b.kd_produk, c.nama_produk, b.qty, b.qty_adj");

//        $query = $this->db->get();

        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
//print_r($this->db->last_query());
        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

    public function update_close_bstt($no_bstt = null, $data = null) {
        $this->db->where('no_bstt', $no_bstt);
        return $this->db->update('sales.t_bstt', $data);
    }
}