<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_faktur_pajak extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('cetak_faktur_pajak_model', 'cfpajak_model');
    }
    public function search_uang_muka(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->cfpajak_model->search_uang_muka($kd_pelanggan, $search);		
                echo $result;
	}
   public function search_faktur_jual(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->cfpajak_model->search_faktur_jual($kd_pelanggan, $search);		
                echo $result;
	} 
   public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur', TRUE)) : '';
        $no_bayar_uang_muka = isset($_POST['no_bayar_uang_muka']) ? $this->db->escape_str($this->input->post('no_bayar_uang_muka', TRUE)) : '';
        $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        
        $result = $this->cfpajak_model->get_rows($no_faktur,$no_bayar_uang_muka,$kd_pelanggan, $search, $start, $limit);

        echo $result;
    }
}
