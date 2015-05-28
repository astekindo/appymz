<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of create_kwitansi_penjualan_model
 *
 * @author Yakub
 */
class create_kwitansi_penjualan_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getAll($length, $offset, $search = "", $kd_pelanggan) {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        $sqlSearch = "";
        if (!empty($kd_pelanggan)) {
            $getDataQuery = "SELECT * FROM mst.t_pelanggan_dist WHERE kd_pelanggan = '$kd_pelanggan'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM mst.t_pelanggan_dist WHERE kd_pelanggan = '$kd_pelanggan'";
        } else {
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(mst.t_pelanggan_dist.nama_pelanggan) LIKE '%" . strtolower($search) . "%') OR (mst.t_pelanggan_dist.kd_pelanggan LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "select * from mst.t_pelanggan_dist $sqlSearch limit $length offset $offset";
            $getTotalQuery = "select count(*) as total from mst.t_pelanggan_dist $sqlSearch";
        }
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

    public function getAllUangMuka($length, $offset, $search = "", $noBayar) {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        $sqlSearch = "";
        if (!empty($noBayar)) {
            $getDataQuery = "SELECT a.*,b.no_so FROM sales.t_uang_muka_bayar a,sales.t_uang_muka_detail b  WHERE b.no_bayar=a.no_bayar AND a.no_bayar = '$noBayar'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_uang_muka_bayar a,sales.t_uang_muka_detail b  WHERE b.no_bayar=a.no_bayar AND no_bayar = '$noBayar'";
        } else {
            if ($search != "") {
                $sqlSearch = "AND ((lower(sales.t_uang_muka_bayar.no_bayar) LIKE '%" . strtolower($search) . "%') OR (sales.t_uang_muka_bayar.kd_jenis_bayar LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "SELECT a.*,b.no_so FROM sales.t_uang_muka_bayar a,sales.t_uang_muka_detail b  WHERE b.no_bayar=a.no_bayar $sqlSearch LIMIT $length OFFSET $offset";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_uang_muka_bayar a,sales.t_uang_muka_detail b  WHERE b.no_bayar=a.no_bayar $sqlSearch";
        }
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        $resultArray = null;
        if ($queryGetData->num_rows() > 0) {
            foreach ($queryGetData->result_array() as $data) {
                $data['terbilang_bayar'] = strtoupper($this->spellNumberInIndonesian($data['rp_bayar']));
                $data['keterangan_pembayaran'] = "UNTUK PEMBAYARAN UANG MUKA SO NO. " . $data['no_so'] . " DENGAN NO. BUKTI: " . $data['no_bayar'] . "";
                $resultArray[] = $data;
            }
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $resultArray
        ));
        return $results;
    }

    public function getAllFakturJual($length, $offset, $search = "", $noFaktur) {
        $total = 0;
        $queryResults = '';
        $getDataQuery = '';
        $getTotalQuery = '';
        $sqlSearch = "";
        if (!empty($noFaktur)) {
            $getDataQuery = "SELECT a.*,b.no_faktur as nom_fak FROM sales.t_piutang_pembayaran a,sales.t_piutang_dist_detail b WHERE a.no_pembayaran_piutang = b.no_pembayaran_piutang AND b.no_faktur = '$noFaktur'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_piutang_pembayaran a,sales.t_piutang_dist_detail b WHERE a.no_pembayaran_piutang = b.no_pembayaran_piutang AND b.no_faktur = '$noFaktur'";
        } else {
            if ($search != "") {
                $sqlSearch = "AND ((lower(sales.t_piutang_pembayaran.no_pembayaran_piutang) LIKE '%" . strtolower($search) . "%') OR (t_piutang_pembayaran.no_faktur LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "SELECT a.*,b.no_faktur as nom_fak FROM sales.t_piutang_pembayaran a,sales.t_piutang_dist_detail b WHERE a.no_pembayaran_piutang = b.no_pembayaran_piutang $sqlSearch LIMIT $length OFFSET $offset";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_piutang_pembayaran a,sales.t_piutang_dist_detail b WHERE a.no_pembayaran_piutang = b.no_pembayaran_piutang $sqlSearch";
        }
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        $resultArray = null;
        if ($queryGetData->num_rows() > 0) {
            foreach ($queryGetData->result_array() as $data) {
                $data['terbilang_bayar'] = strtoupper($this->spellNumberInIndonesian($data['rp_bayar']));
                $data['keterangan_bayar'] = "PEMBAYARAN UNTUK FAKTUR JUAL NO. " . $data['nom_fak'] . " DENGAN NO. BUKTI: " . $data['no_pembayaran_piutang'] . "";
                $resultArray[] = $data;
            }
            $total = $queryGetTotal->row()->total;
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $resultArray
        ));
        return $results;
    }

    public function insert($data) {
        $this->db->insert('sales.t_sales_kwitansi', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function update($data, $noKwitansi) {
        $this->db->where('sales.t_sales_kwitansi.no_kwitansi', $noKwitansi);
        $this->db->update('sales.t_sales_kwitansi', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function delete() {
        $this->db->where('sales.t_sales_kwitansi.no_kwitansi', $noKwitansi);
        $this->db->delete('sales.t_sales_kwitansi');
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function isDataExist($noKwitansi) {
        $query = $this->db->query("select * from sales.t_sales_kwitansi where no_kwitansi = '$noKwitansi'");
        if ($query->num_rows() != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Indonesian number speller (PHP 4 or greater)
     *
     * @param string $number a string representing a positive, integral number with 15 digits or less
     * @return string|false the spelled out number in Indonesian, or false if the number is invalid
     * @author {@link http://www.lesantoso.com Lucky E. Santoso} <lesantoso@yahoo.com>
     * @copyright Copyright (c) 2006 Lucky E. Santoso
     * @license http://opensource.org/licenses/gpl-license.php The GNU General Public License (GPL)
     */
    private function spellNumberInIndonesian($number) {
        $number = strval($number);
        if (!ereg("^[0-9]{1,15}$", $number))
            return(false);
        $ones = array("", "satu", "dua", "tiga", "empat",
            "lima", "enam", "tujuh", "delapan", "sembilan");
        $majorUnits = array("", "ribu", "juta", "milyar", "trilyun");
        $minorUnits = array("", "puluh", "ratus");
        $result = "";
        $isAnyMajorUnit = false;
        $length = strlen($number);
        for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
            if ($number{$i} != '0') {
                if ($number{$i} != '1')
                    $result .= $ones[$number{$i}] . ' ' . $minorUnits[$pos % 3] . ' ';
                else if ($pos % 3 == 1 && $number{$i + 1} != '0') {
                    if ($number{$i + 1} == '1')
                        $result .= "sebelas ";
                    else
                        $result .= $ones[$number{$i + 1}] . " belas ";
                    $i++;
                    $pos--;
                } else if ($pos % 3 != 0)
                    $result .= "se" . $minorUnits[$pos % 3] . ' ';
                else if ($pos == 3 && !$isAnyMajorUnit)
                    $result .= "se";
                else
                    $result .= "satu ";
                $isAnyMajorUnit = true;
            }
            if ($pos % 3 == 0 && $isAnyMajorUnit) {
                $result .= $majorUnits[$pos / 3] . ' ';
                $isAnyMajorUnit = false;
            }
        }
        $result = trim($result);
        if ($result == "")
            $result = "nol";
        return($result);
    }

    /**
     * function used to print kwitansi
     */
    public function printKwitansi($noKwitansi = '') {
        $sql = "SELECT 'KWITANSI FORM' title, a.*,b.nama_pelanggan
                FROM sales.t_sales_kwitansi a ,mst.t_pelanggan_dist b WHERE a.kd_pelanggan=b.kd_pelanggan AND a.no_kwitansi = '$noKwitansi'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();
//        $kdPelanggan=$query->row()->kd_pelanggan;
//        $this->db->flush_cache();
//        $sql_detail = "select a.nama_pelanggan from mst.t_pelanggan where kd_pelanggan = '$kdPelanggan'";
//
//        $query_detail = $this->db->query($sql_detail);
//        $data['detail'] = $query_detail->result();

        return $data;
    }

}
