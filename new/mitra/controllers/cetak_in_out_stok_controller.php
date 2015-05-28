<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_in_out_stok_controller
 *
 * @author Yakub
 */
class cetak_in_out_stok_controller extends MY_Controller {

    //put your code here
    private $limit;
    private $offset;
    private $search;
    private $tanggalAwal;
    private $tanggalAkhir;
    private $lokasi;
    private $noBukti;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_in_out_stok_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->tanggalAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $this->tanggalAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $this->lokasi = isset($_POST['lokasi']) ? $this->db->escape_str($this->input->post('lokasi', TRUE)) : '';
        $this->noBukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
    }

    public function finalGetDataNoBukti() {
        echo $this->cetak_in_out_stok_model->getDataNoBukti($this->limit, $this->offset, $this->search);
    }

    public function finalGetDataInOutStok() {
        echo $this->cetak_in_out_stok_model->getDataInOutStok($this->limit, $this->offset, $this->search, $this->tanggalAwal, $this->tanggalAkhir, $this->lokasi, $this->noBukti);
    }

}
