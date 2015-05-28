<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Close_bstt extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('close_bstt_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_collector= isset($_POST['kd_collector']) ? $this->db->escape_str($this->input->post('kd_collector', TRUE)) : '';

        $result = $this->close_bstt_model->get_rows($kd_collector, $search,$start,$limit);

        echo $result;
    }
    public function search_collector() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        
        $result = $this->close_bstt_model->search_collector($search,$start,$limit);

        echo $result;
    }

    public function get_rows_detail($bstt = '') {
        $result = $this->close_bstt_model->get_rows_detail($bstt);

        echo $result;
    }

    public function update_row() {
        $no_bstt = isset($_POST['no_bstt']) ? $this->db->escape_str($this->input->post('no_bstt', TRUE)) : '';
        $updatedb['status'] = 1;
        $detail_result = $this->close_bstt_model->update_close_bstt($no_bstt, $updatedb);

        if ($detail_result) {
            $result = '{"success":true,"errMsg":"Data Berhasil Diupdate"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}