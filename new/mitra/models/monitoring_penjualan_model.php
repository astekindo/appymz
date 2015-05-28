<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_penjualan_model
 *
 * @author Yakub
 */
class monitoring_penjualan_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataSales($length, $offset, $bulan = "", $hariDari = "", $hariSampai = "", $isKasir = "", $isNoStruk = "", $isPicPenerima = "", $kasir = "", $noStruk = "", $pic = "", $modeKasir = "", $statusSetoran = "") {
        $total = 0;
        $queryResults = '';
        $sqlFilter = '';
        if (!empty($bulan)) {
            $sqlFilter.="And to_char(tgl_so,'YYYYMM') ='" . date('Ym', strtotime($bulan)) . "'";
        }
        if (!empty($hariDari) && !empty($hariSampai)) {
            $sqlFilter.="And tgl_so BETWEEN '" . date('Y-m-d', strtotime($hariDari)) . "' AND '" . date('Y-m-d', strtotime($hariSampai)) . "'";
        }
        if ($isKasir == 'true') {
            $sqlFilter.="AND upper(userid) like '" . strtoupper($kasir) . "%' ";
        }
        if ($isNoStruk == 'true') {
            $sqlFilter .= "AND no_so='" . $noStruk . "'";
        }
        if ($isPicPenerima == 'true') {
            $sqlFilter .= "AND upper(kirim_so) like '" . strtoupper($pic) . "%' ";
        }

        if (!empty($modeKasir)) {
            switch ($modeKasir) {
                case 'SUPERMARKET':
                    $sqlFilter .= " and b.is_mode=1";
                    break;
                case 'BAZAR':
                    $sqlFilter .= " and b.is_mode=2";
                    break;
            }
        }

        if (!empty($statusSetoran)) {
            switch ($statusSetoran) {
                case 'SUDAH SETOR':
                    $sqlFilter .= " AND b.no_setor_kasir is not null";
                    break;
                case 'BELUM STORE':
                    $sqlFilter .= " and b.no_setor_kasir is null";
                    break;
            }
        }

        $getDataQuery = "select a.tgl_so,no_so ,a.rp_grand_total,a.no_open_saldo,a.status,a.userid,
                             a.kirim_so,a.kirim_alamat_so ,a.kirim_telp_so,b.no_setor_kasir 
                             from sales.t_sales_order a, sales.t_open_kasir b
                             where a.no_open_saldo=b.no_open_saldo  $sqlFilter limit $length offset $offset";
        $getTotalQuery = "select count(*) as total from ( select a.tgl_so,no_so ,a.rp_grand_total,a.no_open_saldo,a.status,a.userid,
                             a.kirim_so,a.kirim_alamat_so ,a.kirim_telp_so,b.no_setor_kasir 
                             from sales.t_sales_order a, sales.t_open_kasir b
                             where a.no_open_saldo=b.no_open_saldo $sqlFilter)a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);
        $total = $queryGetTotal->row()->total;

        if ($queryGetData->num_rows() > 0) {
            foreach ($queryGetData->result_array() as $data) {
                if ($data['status'] == 1) {
                    $data['status'] = 'Lunas';
                } else if ($data['status'] == 8) {
                    $data['status'] = 'Pending';
                } else if ($data['status'] == 9) {
                    $data['status'] = 'Reject';
                } else {
                    $data['status'] = 'Unknown';
                }

                if (empty($data['no_setor_kasir']) || $data['no_setor_kasir'] == null) {
                    $data['status_store'] = 'BELUM STORE';
                } else {
                    $data['status_store'] = 'SUDAH STORE';
                }
                $data['rp_grand_total'] = number_format($data['rp_grand_total'], 0, ',', ',');
                $data['userid'] = strtoupper($data['userid']);
                $queryResults[] = $data;
            }
            //$queryResults = $queryGetData->result();
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function getDataSalesDetail($limit, $offset, $search, $noSo) {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND (b.nama_produk LIKE '%" . strtolower($search) . "%') OR (a.qty LIKE '%" . $search . "%'))";
        }
        $getDataQuery = "SELECT a.kd_produk,b.nama_produk,a.qty,a.rp_harga,a.is_kirim,a.rp_ekstra_diskon,a.rp_total,a.keterangan
                         FROM sales.t_sales_order_detail a,mst.t_produk b where b.kd_produk=a.kd_produk AND a.no_so ='$noSo'
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT count(*) AS total FROM (SELECT a.kd_produk,b.nama_produk,a.qty,a.rp_harga,a.is_kirim,a.rp_ekstra_diskon,a.rp_total,a.keterangan
                          FROM sales.t_sales_order_detail a,mst.t_produk b where b.kd_produk=a.kd_produk AND a.no_so ='$noSo'
                          $sqlSearch LIMIT $limit OFFSET $offset)a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            foreach ($queryGetData->result_array() as $data) {
                if ($data['is_kirim'] == 0) {
                    $data['is_kirim'] = 'TIDAK';
                } else {
                    $data['is_kirim'] = 'YA';
                }
                $data['rp_total'] = number_format($data['rp_total'], 0, ',', ',');
                $data['rp_harga'] = number_format($data['rp_harga'], 0, ',', ',');
                $queryResults[] = $data;
            }
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function getDataSalesBonus($limit, $offset, $search, $noSo) {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND (b.nama_produk LIKE '%" . strtolower($search) . "%') OR (a.qty_bonus LIKE '%" . $search . "%'))";
        }
        $getDataQuery = "SELECT a.kd_produk,a.nama_produk,b.kd_produk as kd_produk_bonus,b.kd_produk_bonus,b.qty_bonus
                        FROM mst.t_produk a,sales.t_sales_order_bonus b where a.kd_produk=b.kd_produk AND b.no_so='$noSo'
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT count(*) AS total FROM (SELECT a.kd_produk,a.nama_produk,b.kd_produk as kd_produk_bonus,b.kd_produk_bonus,b.qty_bonus
                          FROM mst.t_produk a,sales.t_sales_order_bonus b where a.kd_produk=b.kd_produk AND b.no_so='$noSo'
                          $sqlSearch LIMIT $limit OFFSET $offset)a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            $queryResults = $queryGetData->result();
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function getDataDetailBayar($limit, $offset, $search, $noSo) {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND (a.no_open_saldo LIKE '%" . strtolower($search) . "%') OR (a.userid LIKE '%" . $search . "%'))";
        }
        $getDataQuery = "SELECT a.*,b.nm_pembayaran 
                         FROM sales.t_sales_order_bayar a,mst.t_jns_pembayaran b 
                         WHERE a.kd_jns_pembayaran=b.kd_jenis_bayar AND a.no_so='$noSo'
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT COUNT(*) AS total FROM (SELECT a.*,b.nm_pembayaran 
                         FROM sales.t_sales_order_bayar a,mst.t_jns_pembayaran b 
                         WHERE a.kd_jns_pembayaran=b.kd_jenis_bayar AND a.no_so='$noSo'
                          $sqlSearch LIMIT $limit OFFSET $offset)a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            $queryResults = $queryGetData->result();
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function getDataPengirimanBarang($limit, $offset, $search, $noSo) {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND (a.no_open_saldo LIKE '%" . strtolower($search) . "%') OR (a.userid LIKE '%" . $search . "%'))";
        }
        $getDataQuery = "select y.no_so, y.tgl_so, y.kd_produk,z.nama_produk, y.qty_kirim, coalesce(x.qty_dikirim, 0) qty_dikirim ,
                         kirim_so,kirim_alamat_so,kirim_telp_so from 
                         (select a.no_so, a.tgl_so, b.kd_produk, b.qty qty_kirim,kirim_so,kirim_alamat_so,kirim_telp_so 
                         from sales.t_sales_order_detail b, sales.t_sales_order a 
                         where a.no_so = b.no_so
                         and b.is_kirim = 1) y
                         LEFT JOIN
                         (select c.no_so, e.kd_produk, sum(e.qty) qty_dikirim
                         from sales.t_sales_delivery_order c, sales.t_surat_jalan d, sales.t_surat_jalan_detail e
                         where c.no_do = d.no_do
                         and d.no_sj = e.no_sj group by c.no_so, e.kd_produk) x 
                         on x.no_so = y.no_so and y.kd_produk = x.kd_produk
                         JOIN mst.t_produk z on y.kd_produk = z.kd_produk where y.no_so ='$noSo'
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT COUNT(*) AS total FROM 
                         (select y.no_so, y.tgl_so, y.kd_produk,z.nama_produk, y.qty_kirim, coalesce(x.qty_dikirim, 0) qty_dikirim ,
                         kirim_so,kirim_alamat_so,kirim_telp_so from 
                         (select a.no_so, a.tgl_so, b.kd_produk, b.qty qty_kirim,kirim_so,kirim_alamat_so,kirim_telp_so
                         from sales.t_sales_order_detail b, sales.t_sales_order a 
                         where a.no_so = b.no_so
                         and b.is_kirim = 1) y
                         LEFT JOIN
                         (select c.no_so, e.kd_produk, sum(e.qty) qty_dikirim
                         from sales.t_sales_delivery_order c, sales.t_surat_jalan d, sales.t_surat_jalan_detail e
                         where c.no_do = d.no_do 
                         and d.no_sj = e.no_sj group by c.no_so, e.kd_produk) x 
                         on x.no_so = y.no_so and y.kd_produk = x.kd_produk
                         JOIN mst.t_produk z on y.kd_produk = z.kd_produk where y.no_so ='$noSo'
                         $sqlSearch LIMIT $limit OFFSET $offset)a";

        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            foreach ($queryGetData->result_array() as $data) {
                $data['qty_sisa'] = ($data['qty_kirim'] - $data['qty_dikirim']);
                $queryResults[] = $data;
            }
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

}
