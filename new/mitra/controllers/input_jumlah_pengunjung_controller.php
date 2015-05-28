<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of input_jumlah_pengunjung_controller
 *
 * @author Yakub
 */
class input_jumlah_pengunjung_controller extends MY_Controller {

    //put your code here

    private $tanggal;
    private $jumlahPengunjung;
    private $kdCabang;
    private $command;
    private $offset;
    private $limit;
    private $tanggalSearch;
    private $namaCabang;

    function __construct() {
        parent::__construct();
        $this->load->model('input_jumlah_pengunjung_model');
        $this->tanggal = isset($_POST['txt_tanggal']) ? $this->db->escape_str($this->input->post('txt_tanggal', TRUE)) : '';
        $this->kdCabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : '';
        $this->jumlahPengunjung = isset($_POST['txt_jumlah_pengunjung']) ? $this->db->escape_str($this->input->post('txt_jumlah_pengunjung', TRUE)) : '';
        $this->command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : '';
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->tanggalSearch = isset($_POST['tanggalInput']) ? $this->db->escape_str($this->input->post('tanggalInput', TRUE)) : '';
        $this->namaCabang = isset($_POST['namaCabang']) ? $this->db->escape_str($this->input->post('namaCabang', TRUE)) : '';
    }

    public function finalGetRows() {
        //$kdPelanggan = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : '';
        echo $this->input_jumlah_pengunjung_model->getAll($this->limit, $this->offset, $this->tanggalSearch, $this->namaCabang);
    }

    public function finalGetCabang() {
        echo $this->input_jumlah_pengunjung_model->getCabang();
    }

    public function finalInsertOrUpdate() {
        $data = array(
            'tanggal' => $this->tanggal,
            'kd_cabang' => $this->kdCabang,
            'jumlah' => $this->jumlahPengunjung
        );
        if ($this->command == 'update') {
            echo $this->input_jumlah_pengunjung_model->update($data, $this->kdCabang, $this->tanggal);
        } else {
            echo $this->input_jumlah_pengunjung_model->insert($data);
        }
    }

    public function finalDelete() {
        echo $this->input_jumlah_pengunjung_model->delete($this->kdCabang, $this->tanggal);
    }

}
