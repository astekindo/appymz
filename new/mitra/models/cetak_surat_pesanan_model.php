<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_surat_pesanan_model
 *
 * @author Yakub
 */
class cetak_surat_pesanan_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getSuratPesanan($limit, $offset, $kdSupplier, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = '';

        if ($search) {
            $sqlSearch = "AND ((lower(a.no_sp) LIKE '%" . strtolower($search) . "%') OR (a.no_sp LIKE '%$search%'))";
        }

        $mainQuery = "SELECT a.no_sp,a.tgl_sp,a.kd_suplier,b.nama_supplier            
                      FROM purchase.t_surat_pesanan a            
                      LEFT JOIN mst.t_supplier b            
                      ON b.kd_supplier=a.kd_suplier  
                      WHERE a.kd_suplier = '$kdSupplier' or a.kd_suplier LIKE '%$kdSupplier%'";
        $queryData = "$mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch) a";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataSuratPesananDetail($limit, $offset, $noSP, $search = '') {
        $result = '';
        $total = 0;
        $sqlSearch = '';
        if (!empty($search)) {
            $sqlSearch = "AND (a.kd_produk='$search') OR (a.kd_produk LIKE '%$search%')";
        }
        $mainQuery = "SELECT a.*,b.nama_produk,c.nm_satuan 
                      FROM purchase.t_surat_pesanan_detail a 
                      LEFT JOIN mst.t_produk b 
                      ON a.kd_produk=b.kd_produk
                      LEFT JOIN mst.t_satuan c 
                      ON b.kd_satuan=c.kd_satuan 
                      WHERE a.no_sp='$noSP'";
        $queryData = "$mainQuery $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) AS total FROM($mainQuery $sqlSearch limit $limit offset $offset) a";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function setCetakKe($noSP = '') {
        $this->db->query('UPDATE purchase.t_surat_pesanan set cetak_ke = cetak_ke + 1 where no_sp = ?', array($noSP));
    }

    public function getDataPrint($noSP = '') {
        $sql = "select b.pkp, CASE WHEN a.konsinyasi='1' THEN 'SURAT PESANAN BARANG (KONSINYASI)' ELSE 'SURAT PESANAN BARANG' END title,a.no_sp, a.tgl_sp,a.kd_peruntukan, 
	            a.order_by, a.top,b.nama_supplier, a.alamat_kirim_sp, a.remark,
	            b.nama_supplier, b.fax, b.pic, b.telpon,
                    b.npwp, b.email,a.is_bonus,
                    a.tgl_berlaku_sp2, a.rp_dp, a.kirim_sp, a.cetak_ke, a.cetak_ke_non_harga, a.approval_by
                    from purchase.t_surat_pesanan a, mst.t_supplier b
                    where a.no_sp = '$noSP'
                    and a.kd_suplier = b.kd_supplier";

        $query = $this->db->query($sql);
        // print_r($this->db->last_query());exit;
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select a.*, b.kd_produk_lama, b.nama_produk, c.nm_satuan,b.kd_produk_supp
                       from purchase.t_surat_pesanan_detail a, mst.t_produk b, mst.t_satuan c
                       where a.no_sp = '$noSP'
                       and a.kd_produk = b.kd_produk
                       and b.kd_satuan = c.kd_satuan";

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        return $data;
    }

}
