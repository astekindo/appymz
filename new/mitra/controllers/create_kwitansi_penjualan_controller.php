<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class create_kwitansi_penjualan_controller extends MY_Controller {

    private $start;
    private $limit;
    private $search;
    private $noKwitansi;
    private $tglKwitansi;
    private $noRef;
    private $type;
    private $rpTotal;
    private $terbilangTotal;
    private $kdPelanggan;
    private $terimaDari;
    private $keterangan;
    private $createdBy;
    private $createdDate;
    private $updatedBy;
    private $updatedDate;

    function __construct() {
        parent::__construct();
        $this->load->model('create_kwitansi_penjualan_model');
        $this->start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        //$this->noKwitansi = isset($_POST['txt_no_kwitansi']) ? $this->db->escape_str($this->input->post('txt_no_kwitansi', TRUE)) : '';
        $this->tglKwitansi = isset($_POST['txt_tgl_kwitansi']) ? $this->db->escape_str($this->input->post('txt_tgl_kwitansi', TRUE)) : '';
        $current_date = date('Ymd', strtotime($this->tglKwitansi));
        $no_ret = 'KW' . $current_date . '-';
        $sequence = $this->create_kwitansi_penjualan_model->get_kode_sequence($no_ret, 3);
        $this->noKwitansi = $no_ret . $sequence;
        $noFaktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur', TRUE)) : '';
        $noBayar = isset($_POST['no_bayar']) ? $this->db->escape_str($this->input->post('no_bayar', TRUE)) : '';
        if (!empty($noFaktur)) {
            $this->noRef = $noFaktur;
            $this->type = 2;
        } else {
            $this->noRef = $noBayar;
            $this->type = 1;
        }
        $this->rpTotal = isset($_POST['rp_total']) ? $this->db->escape_str($this->input->post('rp_total', TRUE)) : '';
        $this->terbilangTotal = isset($_POST['terbilang_total']) ? $this->db->escape_str($this->input->post('terbilang_total', TRUE)) : '';
        $this->kdPelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        $this->terimaDari = isset($_POST['terima_dari']) ? $this->db->escape_str($this->input->post('terima_dari', TRUE)) : '';
        $this->keterangan = isset($_POST['keterangan_pembayaran']) ? $this->db->escape_str($this->input->post('keterangan_pembayaran', TRUE)) : '';
        $this->createdBy = $this->session->userdata('username');
        $this->createdDate = date('Y-m-d H:i:s');
        $this->updatedBy = $this->session->userdata('username');
        $this->updatedDate = date('Y-m-d H:i:s');
    }

    //unused
    private function generateNoKwitansi() {
        $current_date = date('Ymd');
        $no_ret = 'KW' . $current_date . '-';
        $sequence = $this->create_kwitansi_penjualan_model->get_kode_sequence($no_ret, 3);
        return $no_ret . $sequence;
    }

    private function getNoKwitansi() {
        $current_date = date('Ymd', strtotime($this->tglKwitansi));
        $no_ret = 'KW' . $current_date . '-';
        $sequence = $this->create_kwitansi_penjualan_model->get_kode_sequence($no_ret, 3);
        return $no_ret . $sequence;
    }

    public function finalGetDataPelanggan() {
        $kdPelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : FALSE;
        echo $this->create_kwitansi_penjualan_model->getAll($this->limit, $this->start, $this->search, $kdPelanggan);
    }

    public function finalGetDataUangMuka() {
        $noBayar = isset($_POST['no_bayar']) ? $this->db->escape_str($this->input->post('no_bayar', TRUE)) : FALSE;
        echo $this->create_kwitansi_penjualan_model->getAllUangMuka($this->limit, $this->start, $this->search, $noBayar);
    }

    public function finalGetDataFakturJual() {
        $noFaktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur', TRUE)) : FALSE;
        echo $this->create_kwitansi_penjualan_model->getAllFakturJual($this->limit, $this->start, $this->search, $noFaktur);
    }

    public function finalInsert() {
        $data = array(
            'no_kwitansi' => $this->noKwitansi,
            'no_ref' => $this->noRef,
            'trx_type' => $this->type,
            'rp_total' => str_replace(',', '', $this->rpTotal),
            'terbilang_total' => $this->terbilangTotal,
            'kd_pelanggan' => $this->kdPelanggan,
            'tanggal' => $this->tglKwitansi,
            'terima_dari' => $this->terimaDari,
            'keterangan' => $this->keterangan,
            'created_by' => $this->createdBy,
            'created_date' => $this->createdDate,
            'updated_by' => $this->updatedBy,
            'updated_date' => $this->updatedDate
        );
        $this->create_kwitansi_penjualan_model->insert($data);
        $result = array(
            'success' => true,
            'errMessage' => '',
            'no_kw' => $this->noKwitansi,
            'printUrl' => site_url("create_kwitansi_penjualan_controller/printForm/" . $this->noKwitansi)
        );
        echo json_encode($result);
    }

    public function finalUpdate() {
        
    }

    public function finalDelete() {
        
    }

    //unused
    public function test() {
        $data = $this->create_kwitansi_penjualan_model->printKwitansi('KW20140615-003');
        var_dump($data['header']);
    }

    public function printForm($noKwitansi = '') {
        //$this->psj_model->setCetakKe($nno_sj);
        $data = $this->create_kwitansi_penjualan_model->printKwitansi($noKwitansi);
        //var_dump($data);exit;
        if (!$data)
            show_404('page');
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/kwitansi_penjualan_print.php');
        $pdf = new kwitansi_penjualan_print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "F4_MBS_1/2", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header']);
        $pdf->Output();
        exit;
    }
}
