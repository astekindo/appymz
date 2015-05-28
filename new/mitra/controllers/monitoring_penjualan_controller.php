<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_penjualan_controller
 *
 * @author Yakub
 */
class monitoring_penjualan_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $bulan;
    private $hariDari;
    private $hariSampai;
    private $isPetugasKasir;
    private $isNoStruk;
    private $isPicPenerima;
    private $petugasKasir;
    private $noStruk;
    private $picPenerima;
    private $modeKasir;
    private $statusSetoran;
    private $noSo;

    function __construct() {
        parent::__construct();
        $this->load->model('monitoring_penjualan_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $this->bulan = isset($_POST['filterBulan_mp']) ? $this->db->escape_str($this->input->post('filterBulan_mp', TRUE)) : '';
        $this->hariDari = isset($_POST['filterHaridari_mp']) ? $this->db->escape_str($this->input->post('filterHaridari_mp', TRUE)) : '';
        $this->hariSampai = isset($_POST['filterHariSampai_mp']) ? $this->db->escape_str($this->input->post('filterHariSampai_mp', TRUE)) : '';
        $this->isPetugasKasir = isset($_POST['chkPetugasKasir_mp']) ? $this->db->escape_str($this->input->post('chkPetugasKasir_mp', TRUE)) : '';
        $this->isNoStruk = isset($_POST['chkNoStruk']) ? $this->db->escape_str($this->input->post('chkNoStruk', TRUE)) : '';
        $this->isPicPenerima = isset($_POST['chkPicPenerima_mp']) ? $this->db->escape_str($this->input->post('chkPicPenerima_mp', TRUE)) : '';
        $this->petugasKasir = isset($_POST['filterPetugasKasir_mp']) ? $this->db->escape_str($this->input->post('filterPetugasKasir_mp', TRUE)) : '';
        $this->noStruk = isset($_POST['filterNoStruk']) ? $this->db->escape_str($this->input->post('filterNoStruk', TRUE)) : '';
        $this->picPenerima = isset($_POST['filterPicPenerima_mp']) ? $this->db->escape_str($this->input->post('filterPicPenerima_mp', TRUE)) : '';
        $this->modeKasir = isset($_POST['filterModeKasir_mp']) ? $this->db->escape_str($this->input->post('filterModeKasir_mp', TRUE)) : '';
        $this->statusSetoran = isset($_POST['filterStatusSetoran_mp']) ? $this->db->escape_str($this->input->post('filterStatusSetoran_mp', TRUE)) : '';
        $this->noSo = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
    }

    public function finalGetDataSales() {
        echo $this->monitoring_penjualan_model->getDataSales($this->limit, $this->offset, $this->bulan, $this->hariDari, $this->hariSampai, $this->isPetugasKasir, $this->isNoStruk, $this->isPicPenerima, $this->petugasKasir, $this->noStruk, $this->picPenerima, $this->modeKasir, $this->statusSetoran);
    }

    public function finalGetDataDetailSales() {
        echo $this->monitoring_penjualan_model->getDataSalesDetail($this->limit, $this->offset, $this->search, $this->noSo);
    }

    public function finalGetDataBonusSales() {
        echo $this->monitoring_penjualan_model->getDataSalesBonus($this->limit, $this->offset, $this->search, $this->noSo);
    }
    
    public function finalGetDataDetailBayar() {
        echo $this->monitoring_penjualan_model->getDataDetailBayar($this->limit, $this->offset, $this->search, $this->noSo);
    }
    
    public function finalGetDataPengirimanBarang() {
        echo $this->monitoring_penjualan_model->getDataPengirimanBarang($this->limit, $this->offset, $this->search, $this->noSo);
    }
    

}
