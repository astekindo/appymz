<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_barter_barang_controller
 *
 * @author Yakub
 */
class cetak_delivery_order_dist_controller extends MY_Controller {

    private $limit;
    private $offset;
    private $search;
    private $tanggalAwal;
    private $tanggalAkhir;
    private $noDo;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_delivery_order_dist_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->tanggalAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $this->tanggalAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $this->noDo = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do', TRUE)) : '';
    }

    public function finalGetDataDODist() {
        echo $this->cetak_delivery_order_dist_model->getDataDeliveryOrderDist($this->limit, $this->offset, $this->search, $this->tanggalAwal, $this->tanggalAkhir);
    }

    public function finalGetDataDODistDetail() {
        echo $this->cetak_delivery_order_dist_model->getDataDeliveryOrderDistDetail($this->limit, $this->offset, $this->noDo, $this->search);
    }

}
