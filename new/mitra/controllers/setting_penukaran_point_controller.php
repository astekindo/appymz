<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of setting_penukaran_barang_controller
 *
 * @author Yakub
 */
class setting_penukaran_point_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $kdProduk;
    private $jumlahPoint;
    private $aktif;
    private $qty;
    private $command;

    function __construct() {
        parent::__construct();
        $this->load->model('setting_penukaran_point_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->kdProduk = isset($_POST['combo_produk_spp']) ? $this->db->escape_str($this->input->post('combo_produk_spp', TRUE)) : FALSE;
        $this->jumlahPoint = isset($_POST['txt_jumlah_point_spp']) ? $this->db->escape_str($this->input->post('txt_jumlah_point_spp', TRUE)) : FALSE;
        $this->aktif = isset($_POST['check_aktif_jumlah_point_spp']) ? $this->db->escape_str($this->input->post('check_aktif_jumlah_point_spp', TRUE)) : FALSE;
        $this->qty = isset($_POST['txt_quantity_spp']) ? $this->db->escape_str($this->input->post('txt_quantity_spp', TRUE)) : FALSE;
        $this->command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : FALSE;
    }

    public function finalGetDataProduk() {
        echo $this->setting_penukaran_point_model->getDataProduk($this->limit, $this->offset, $this->search, $this->kdProduk);
    }

    public function finalGetDataPenukaranPoint() {
        echo $this->setting_penukaran_point_model->getDataPenukaranPoint($this->limit, $this->offset, $this->search, $this->kdProduk);
    }

    public function functionName() {
        if ($this->aktif == 'true') {
            $this->aktif = 1;
        } else {
            $this->aktif = 0;
        }
        echo $this->aktif;
    }

    private function getThrownValue() {
        if ($this->aktif == 'true') {
            $this->aktif = 1;
        } else {
            $this->aktif = 0;
        }
        $data = array(
            'kd_barang' => $this->kdProduk,
            'jumlah_point' => $this->jumlahPoint,
            'aktif' => $this->aktif,
            'qty' => $this->qty,
        );
        return $data;
    }

    private function finalInsert() {
        echo $this->setting_penukaran_point_model->insert($this->getThrownValue());
    }

    private function finalUpdate() {
        echo $this->setting_penukaran_point_model->update($this->getThrownValue(), $this->kdProduk, $this->qty);
    }

    private function finalDelete() {
        echo $this->setting_penukaran_point_model->delete($this->kdProduk, $this->qty);
    }

    public function finalProcessing() {
        switch ($this->command) {
            case 'save':
                $this->finalInsert();
                break;
            case 'update':
                $this->finalUpdate();
                break;
            case 'delete':
                $this->finalDelete();
                break;
            default :
                $error = array('success' => false);
                echo json_encode($error);
                break;
        }
    }

}
