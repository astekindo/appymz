<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_pembayaran_piutang_distribusi_controller
 *
 * @author Yakub
 */
class cetak_pembayaran_piutang_distribusi_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $tglAwal;
    private $tglAkhir;
    private $noFaktur;
    private $noPembayaran;

    function __construct() {
        parent::__construct();
        $this->load->model('cetak_pembayaran_piutang_distribusi_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $this->tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $this->tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $this->noFaktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur', TRUE)) : '';
        $this->noPembayaran = isset($_POST['no_pembayaran']) ? $this->db->escape_str($this->input->post('no_pembayaran', TRUE)) : '';
    }

    public function finalGetRows() {
        echo $this->cetak_pembayaran_piutang_distribusi_model->getRows($this->offset, $this->limit, $this->tglAwal, $this->tglAkhir, $this->noFaktur, $this->search);
    }

    public function finalGetSODistRows() {
        echo $this->cetak_pembayaran_piutang_distribusi_model->getSalesOrderDistRows($this->offset, $this->limit, $this->search, $this->noFaktur);
    }

    public function finalGetRowDetail() {
        echo $this->cetak_pembayaran_piutang_distribusi_model->getRowsDetail($this->noPembayaran);
    }

    public function finalGetDataPembayaran() {
        echo $this->cetak_pembayaran_piutang_distribusi_model->getDataPembayaran($this->noPembayaran);
    }

    public function finalPrint($noPembayaran) {
        echo $this->cetak_pembayaran_piutang_distribusi_model->printForm($noPembayaran);
    }

}
