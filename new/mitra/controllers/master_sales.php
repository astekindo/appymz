<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_sales extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master_sales_model', 'master_sales');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $data = $this->master_sales->get_rows($search, $start, $limit);
        echo '{"success":true, "record":' . $data['total'] . ', "data":' . json_encode($data['rows']) . '}';
    }

    public function get_row() {
        $kd_sales = isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : '';
        $data = $this->master_sales->get_row($kd_sales);
        if ($data) {
            echo '{"success":true, "data":' . json_encode($data['rows']) . '}';
        } else {
            echo '{"success":false,"errMsg":"Process Failed.."}';
        }
    }

    public function update_row() {
        $this->db->flush_cache();
        $result = '{"success":false,"errMsg":"Process Failed.."}';
        $cmd = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
        $data = array(
            'kd_cabang' => isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null,
            'kd_area' => isset($_POST['kd_area']) ? $this->db->escape_str($this->input->post('kd_area', TRUE)) : null,
            'nama_sales' => isset($_POST['nama_sales']) ? $this->db->escape_str($this->input->post('nama_sales', TRUE)) : null,
            'kd_sales' => isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : null,
            'email' => isset($_POST['email']) ? $this->db->escape_str($this->input->post('email', TRUE)) : null,
            'pin_bb' => isset($_POST['pin_bb']) ? $this->db->escape_str($this->input->post('pin_bb', TRUE)) : null,
            'alamat' => isset($_POST['alamat']) ? $this->db->escape_str($this->input->post('alamat', TRUE)) : null,
            'no_telp' => isset($_POST['no_telp']) ? $this->db->escape_str($this->input->post('no_telp', TRUE)) : null,
            'no_telp2' => isset($_POST['no_telp2']) ? $this->db->escape_str($this->input->post('no_telp2', TRUE)) : null,
            'status' => isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : 1
        );

        $kd_sales = isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : FALSE;

        if (!empty($cmd)) {
            if ($cmd == 'update') {
                $success = $this->master_sales->update_row($kd_sales, $data);
            } else {
                $data['kd_sales'] = $data['kd_cabang'] . 'SLS' . $this->master_sales->get_kode_sequence("SLS", 4);
                $success = $this->master_sales->insert_row($data);
            }
        }
//        if(isset($kd_sales) && !empty($kd_sales)) {
//            //update data
//            $success = $this->master_sales->update_row($kd_sales, $data);
//
//        } else {
//            //input data baru
//            $data['kd_sales'] = $data['kd_cabang'].'SLS'.$this->master_sales->get_kode_sequence("SLS",4);
//
//            $success = $this->master_sales->insert_row($data);
//
//        }
        if ($success) {
            $result = '{"success":true,"errMsg":""}';
        }

        echo $result;
    }

    public function delete_row() {
        $kd_sales = isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : FALSE;

        if ($this->master_sales->delete_row($kd_sales)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_cabang() {
        $data = $this->master_sales->get_cabang();
        if ($data) {
            echo '{"success":true, "data":' . json_encode($data) . '}';
        } else {
            echo '{"success":false,"errMsg":"Process Failed.."}';
        }
    }

    public function get_area() {
        $data = $this->master_sales->get_area();
        if ($data) {
            echo '{"success":true, "errMsg":"", "data":' . json_encode($data) . '}';
        } else {
            echo '{"success":false, "errMsg":"Process Failed.."}';
        }
    }

}
