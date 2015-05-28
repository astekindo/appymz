<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Target_pencapaian_pepembelian extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan_per_kategori1_model', 'lppk1');
    }

    public function get_report() {
        echo json_encode($_POST);
    }
}