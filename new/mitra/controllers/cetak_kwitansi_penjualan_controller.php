<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_kwitansi_penjualan_controller
 *
 * @author Yakub
 */
class cetak_kwitansi_penjualan_controller extends MY_Controller {

    private $offset;
    private $limit;
    private $search;
    private $kdPelanggan;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_kwitansi_penjualan_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $this->kdPelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
    }

    public function finalGetRows() {
        echo $this->cetak_kwitansi_penjualan_model->getRows($this->limit, $this->offset, $this->search, $this->kdPelanggan);
    }

}
