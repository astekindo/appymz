<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_collection extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_collection_model', 'master_collection');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $data = $this->master_collection->get_rows($search, $start, $limit);
        echo '{"success":true, "record":' . $data['total'] . ', "data":' . json_encode($data['rows']) . '}';
    }

    public function get_row() {
        $kd_collector = isset($_POST['kd_collector']) ? $this->db->escape_str($this->input->post('kd_collector', TRUE)) : '';
        $data = $this->master_collection->get_row($kd_collector);
//        var_dump($data);exit;
        if ($data) {
            echo '{"success":true, "data":' . json_encode($data['rows']) . '}';
        } else {
            echo '{"success":false,"errMsg":"Process Failed.."}';
        }
    }

    public function update_row() {
        $this->db->flush_cache();
        $result = '{"success":false,"errMsg":"Process Failed.."}';

        $data = array(
            'kd_cabang' => isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null,
            'kd_area' => isset($_POST['kd_area']) ? $this->db->escape_str($this->input->post('kd_area', TRUE)) : null,
            'nama_collector' => isset($_POST['nama_collector']) ? $this->db->escape_str($this->input->post('nama_collector', TRUE)) : null,
            'kd_collector' => isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : null,
            'alamat' => isset($_POST['alamat']) ? $this->db->escape_str($this->input->post('alamat', TRUE)) : null,
            'email' => isset($_POST['email']) ? $this->db->escape_str($this->input->post('email', TRUE)) : null,
            'pin_bb' => isset($_POST['pin_bb']) ? $this->db->escape_str($this->input->post('pin_bb', TRUE)) : null,
            'no_telp' => isset($_POST['no_telp']) ? $this->db->escape_str($this->input->post('no_telp', TRUE)) : null,
            'no_telp2' => isset($_POST['no_telp2']) ? $this->db->escape_str($this->input->post('no_telp2', TRUE)) : null,
            'status' => isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : 1
        );

        $kd_collector = isset($_POST['kd_collector']) ? $this->db->escape_str($this->input->post('kd_collector', TRUE)) : FALSE;

        if (isset($kd_collector) && !empty($kd_collector)) {
            //update data
            $success = $this->master_collection->update_row($kd_collector, $data);
        } else {
            //input data baru
            $data['kd_collector'] = $data['kd_cabang'] . 'CLL' . $this->master_collection->get_kode_sequence("SLS", 4);

            $success = $this->master_collection->insert_row($data);
        }
        if ($success) {
            $result = '{"success":true,"errMsg":""}';
        }

        echo $result;
    }

    public function delete_row() {
        $kd_colector = isset($_POST['kd_colector']) ? $this->db->escape_str($this->input->post('kd_colector', TRUE)) : FALSE;

        if ($this->master_collection->delete_row($kd_colector)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_cabang() {
        $data = $this->master_collection->get_cabang();
        if ($data) {
            echo '{"success":true, "data":' . json_encode($data) . '}';
        } else {
            echo '{"success":false,"errMsg":"Process Failed.."}';
        }
    }

    public function get_area() {
        $data = $this->master_collection->get_area();
        if ($data) {
            echo '{"success":true, "errMsg":"", "data":' . json_encode($data) . '}';
        } else {
            echo '{"success":false, "errMsg":"Process Failed.."}';
        }
    }

}
