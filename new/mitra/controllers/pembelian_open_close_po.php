<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_open_close_po extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_open_close_po_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tgl_akhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $tgl_awal_diperpanjang = isset($_POST['tgl_awal_diperpanjang']) ? $this->db->escape_str($this->input->post('tgl_awal_diperpanjang', TRUE)) : '';
        $tgl_akhir_diperpanjang = isset($_POST['tgl_akhir_diperpanjang']) ? $this->db->escape_str($this->input->post('tgl_akhir_diperpanjang', TRUE)) : '';
        $tgl = date('Y-m-d');
        $result = $this->pembelian_open_close_po_model->get_rows($kd_supplier,$tgl_awal,$tgl_akhir,$tgl,$tgl_awal_diperpanjang,$tgl_akhir_diperpanjang, $search,$start,$limit);

        echo $result;
    }

    public function get_rows_detail($no_po = '') {
        $result = $this->pembelian_open_close_po_model->get_rows_detail($no_po);

        echo $result;
    }

    public function update_row() {

        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : '';
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
        foreach($detail as $obj){
            $tgl_perpanjang = $obj->tgl_perpanjangan;
            $sql = "UPDATE purchase.t_purchase SET tgl_berlaku_po2 = '$tgl_perpanjang' WHERE no_po = '" . $obj->no_po . "'";
            $detail_result = $this->pembelian_open_close_po_model->query_update($sql);
        }
           
        if ($detail_result) {
            $result = '{"success":true,"errMsg":"Data Berhasil Diupdate"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
}
