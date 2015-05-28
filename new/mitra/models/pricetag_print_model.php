<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pricetag_print_model extends MY_Model {

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
    public function get_data_print($kd_cetak = '') {
        $sql = "select * from mst.t_log_pricetag where kd_cetak = '$kd_cetak'";
        $query = $this->db->query($sql);
        $data = $query->row();

        $result = json_decode($data->pricetag);
        return $result;
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_log_pricetag', $data);
    }

    public function search_produk_pricetag($search = "", $offset, $length) {
        if ($search != "") {
            $this->db->where("((lower(a.nama_produk) LIKE '%" . strtolower($search) . "%') OR (a.kd_produk LIKE '%" . $search . "%') OR (a.kd_produk_supp LIKE '%" . $search . "%') OR (a.kd_produk_lama LIKE '%" . $search . "%'))", NULL);
        }
        $this->db->select("c.*, a.nama_produk, b.nm_satuan, round(((((c.rp_jual_supermarket * ((100 - c.disk_persen_kons1) / 100) - c.disk_amt_kons1) * ((100 - c.disk_persen_kons2) / 100) - c.disk_amt_kons2) 
* ((100 - c.disk_persen_kons3) / 100) - c.disk_amt_kons3) * 
((100 - c.disk_persen_kons4) / 100) - c.disk_amt_kons4) - c.disk_amt_kons5, 0) net_harga_jual", false);
        $this->db->where("a.aktif", 1);
        $this->db->join("mst.t_satuan b", "a.kd_satuan = b.kd_satuan", "inner");
        $this->db->join("mst.t_diskon_sales c", "a.kd_produk = c.kd_produk", "inner");
        $this->db->order_by("a.nama_produk");
        $query = $this->db->get("mst.t_produk a", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select("count(*) AS total");
        if ($search != "") {
            $this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (kd_produk LIKE '%" . $search . "%') OR (kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
        }
        $this->db->where("aktif", 1);
        $query = $this->db->get("mst.t_produk");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}