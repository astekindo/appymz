<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class index_transaksi extends MY_Controller {

    private $kdIndex;
    private $namaIndex;
    private $keterangan;
    private $command;

    function __construct() {
        parent::__construct();
        $this->load->model('index_transaksi_model');
        $this->kdIndex = isset($_POST['it_txt_kd_index']) ? $this->db->escape_str($this->input->post('it_txt_kd_index', TRUE)) : FALSE;
        $this->namaIndex = isset($_POST['it_txt_nama_index']) ? $this->db->escape_str($this->input->post('it_txt_nama_index', TRUE)) : FALSE;
        $this->keterangan = isset($_POST['it_txt_keterangan']) ? $this->db->escape_str($this->input->post('it_txt_keterangan', TRUE)) : FALSE;
        $this->command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : '';
    }

    /**
     * jika record sudah terdaftdar update jika tidak insert
     */
    public function finalInsertAndUpdate() {
        $data = array(
            'kd_index' => $this->kdIndex,
            'nama_index' => $this->namaIndex,
            'keterangan' => $this->keterangan
        );
        if ($this->command == 'update') {
            $this->index_transaksi_model->update($data, $this->kdIndex);
        } else {
            $this->index_transaksi_model->insert($data);
        }
    }

    public function finalDelete() {
        //$kdIndexDelete = isset($_POST['kd_index_delete']) ? $this->db->escape_str($this->input->post('kd_index_delete', TRUE)) : FALSE;
        echo $this->index_transaksi_model->delete($this->kdIndex);
    }

    public function finalGetRows() {
        //$length = 2;
        //$offset = 0;
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : '';
        echo $this->index_transaksi_model->getAll($limit, $start, $search, $id);
    }

    public function generateKodeIndex() {
        $no_ret = 'IDX' . '-';
        $sequence = $this->index_transaksi_model->get_kode_sequence($no_ret, 3);
        $success = array(
            'success' => true,
            'data' => array(
                'it_txt_kd_index' => $no_ret . $sequence
            )
        );
        echo json_encode($success);
    }

    public function testGet() {
        echo $this->index_transaksi_model->testFunction();
    }

    public function finalSearchRows() {
        $this->index_transaksi_model->get($this->kdIndex);
    }

}
