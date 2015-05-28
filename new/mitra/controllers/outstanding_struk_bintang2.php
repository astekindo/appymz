<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class outstanding_struk_bintang2 extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->model = $this->load->model('outstanding_struk_bintang2_model');
    }

    public function finalGetRows() {
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        echo $this->outstanding_struk_bintang2_model->getRows($limit, $start, $search);
    }

}

//$this->model=$this->load->model('outstanding_struk_bintang2_model');
//        $noSo = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
//        $tglSo = isset($_POST['tgl_so']) ? $this->db->escape_str($this->input->post('tgl_so', TRUE)) : '';
//        $kdProduk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
//        $namaProduk = isset($_POST['nama_produk']) ? $this->db->escape_str($this->input->post('nama_produk', TRUE)) : '';
//        $qtyKirim = isset($_POST['qty_kirim']) ? $this->db->escape_str($this->input->post('qty_kirim', TRUE)) : '';
//        $qtyDikirim = isset($_POST['qty_dikirim']) ? $this->db->escape_str($this->input->post('qty_dikirim', TRUE)) : '';
//        if ($tglSo) {
//            $tglSo = date('Y-m-d', strtotime($tglAwal));
//        }