<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of input_barang_pecah_controller
 *
 * @author Yakub
 */
class input_barang_pecah_controller extends MY_Controller {

    //put your code here
    private $limit;
    private $offset;
    private $search;
    private $kdLokasi;
    private $kdBlok;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('input_barang_pecah_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->kdLokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : '';
        $this->kdBlok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : '';
    }

    public function finalGetDataProduk() {
        echo $this->input_barang_pecah_model->getDataProduk($this->limit, $this->offset, $this->search);
    }

    public function finalGetDataLokasi() {
        echo $this->input_barang_pecah_model->getDataLokasi($this->limit, $this->offset, $this->search);
    }

    public function finalGetDataBlok() {
        echo $this->input_barang_pecah_model->getDataBlok($this->limit, $this->offset, $this->search, $this->kdLokasi);
    }

    public function finalGetDataSubBlok() {
        echo $this->input_barang_pecah_model->getDataSubBlok($this->limit, $this->offset, $this->search, $this->kdLokasi, $this->kdBlok);
    }

    public function finalGetDatas() {
        $success = json_encode(array(
            'success' => true
        ));
        return $success;
    }

}
