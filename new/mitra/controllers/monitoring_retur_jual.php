<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_retur_jual extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('monitoring_retur_jual_model');
    }
    public function search_noretur() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->monitoring_retur_jual_model->search_noretur($search, $start, $limit);
        echo $result;
    }
    public function search_salesorder() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->monitoring_retur_jual_model->search_salesorder($search, $start, $limit);
        echo $result;
    }
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        //$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : '';
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member', TRUE)) : '';
        if ($tglAwal) {
            $tglAwal = date('Y-m-d', strtotime($tglAwal));
        }
        if ($tglAkhir) {
            $tglAkhir = date('Y-m-d', strtotime($tglAkhir));
        }

        $result = $this->monitoring_retur_jual_model->get_rows($tglAwal, $tglAkhir, $no_retur, $no_so, $kd_member, $search, $start, $limit);

        echo $result;
    }
    public function get_rows_detail($no_retur = '') {
        $hasil = $this->monitoring_retur_jual_model->get_rows_detail($no_retur);
        echo '{success:true,data:'.json_encode($hasil).'}';
        //echo $result;
    }
}
