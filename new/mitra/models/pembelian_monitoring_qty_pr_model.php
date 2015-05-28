<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_monitoring_qty_pr_model extends MY_Model {

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
    public function get_rows($kd_supplier, $search = "", $offset, $length) {
        $sql = <<<EOT
SELECT
  a.no_ro, a.tgl_ro, a.subject, b.kd_supplier, b.nama_supplier,
  CASE a.close_ro WHEN '0' THEN 'OPEN' WHEN '1' THEN 'CLOSE' END is_open,
  a.created_by, a.created_date
FROM purchase.t_purchase_request a, mst.t_supplier b
WHERE a.kd_supplier LIKE '%$kd_supplier%'
AND a.status = '2'
AND a.kd_supplier = b.kd_supplier
EOT;
        if($search != '') {
            $sql .= " and (a.no_ro like '%$search%' or a.subject like '%$search%')";
        }
        $sql .=" LIMIT $length OFFSET $offset";

        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';
//        return $this->db->last_query();
        return $results;
    }

    public function get_rows_detail($no_ro) {
        $sql = <<<EOT
select a.kd_produk, b.kd_produk_supp, b.nama_produk, a.qty_adj qty_pr, a.qty_po, sum(e.qty_terima) qty_ro, c.nm_satuan
from  mst.t_produk b, mst.t_satuan c, purchase.t_dtl_purchase_request a
left join purchase.t_purchase_detail d on a.no_ro = d.no_ro and a.kd_produk = d.kd_produk
left join purchase.t_dtl_receive_order e on d.no_po = e.no_po and d.kd_produk = e.kd_produk
where a.no_ro = '$no_ro'
and a.kd_produk = b.kd_produk
and b.kd_satuan = c.kd_satuan
group by a.kd_produk, b.kd_produk_supp, b.nama_produk, a.qty_adj, a.qty_po, c.nm_satuan
EOT;
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';

        return $results;
    }

}