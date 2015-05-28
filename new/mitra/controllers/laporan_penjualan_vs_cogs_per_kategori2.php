<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class laporan_penjualan_vs_cogs_per_kategori2 extends MY_Controller {

    public function __construct() {
        parent::__construct();
//		$this->load->model('laporan_penjualan_per_kategori2_model', 'lppk2');
    }

    public function get_report() {
        echo json_encode($_POST);
    }
}