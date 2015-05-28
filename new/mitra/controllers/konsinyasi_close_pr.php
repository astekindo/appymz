<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_close_pr extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('konsinyasi_close_pr_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';

        $result = $this->konsinyasi_close_pr_model->get_rows($kd_supplier, $search,$start,$limit);

        echo $result;
    }

    public function get_rows_detail($no_ro = '') {
        $result = $this->konsinyasi_close_pr_model->get_rows_detail($no_ro);

        echo $result;
    }

    public function update_row() {

//        die(var_dump($_POST));
        $no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro', TRUE)) : '';
        $updatedb['close_ro'] = 1;
        $detail_result = $this->konsinyasi_close_pr_model->update_close_pr($no_ro, $updatedb);

        if ($detail_result) {
            $result = '{"success":true,"errMsg":"Data Berhasil Diupdate"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}