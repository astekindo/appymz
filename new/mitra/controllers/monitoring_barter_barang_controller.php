<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_barter_barang_controller
 *
 * @author Yakub
 */
class monitoring_barter_barang_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $kdSupplier;
    private $tglAwal;
    private $tglAkhir;
    private $status;
    private $jenisTransfer;
    private $noTransfer;

    function __construct() {
        parent::__construct();
        $this->load->model('monitoring_barter_barang_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $this->kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $this->tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $this->tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $this->status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : '';
        $this->jenisTransfer = isset($_POST['jenis_transfer']) ? $this->db->escape_str($this->input->post('jenis_transfer', TRUE)) : '';
        $this->noTransfer = isset($_POST['no_transfer']) ? $this->db->escape_str($this->input->post('no_transfer', TRUE)) : '';
    }

    public function finalGetDataBarter() {
        echo $this->monitoring_barter_barang_model->getDataBarter($this->limit, $this->offset, $this->search, $this->kdSupplier, $this->tglAwal, $this->tglAkhir, $this->status, $this->jenisTransfer);
    }

    public function finalGetDataBarterDetail() {
        echo $this->monitoring_barter_barang_model->getDataBarangDetail($this->limit, $this->offset, $this->noTransfer, $this->search);
    }

    public function finalGetDataSupplier() {
        echo $this->monitoring_barter_barang_model->getDataSupplier($this->limit, $this->offset, $this->search);
    }

}
