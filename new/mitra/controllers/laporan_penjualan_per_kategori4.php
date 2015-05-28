<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan_per_kategori4 extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby bambang
	 * @lastedited 15 mei 2014
	 */
    public function __construct() {
        parent::__construct();
//		$this->load->model('laporan_penjualan_per_kategori4_model', 'lppk4');
    }

    /**
     * @author bambang
     * @lastedited 15 mei 2014
     */
    public function get_report() {
        echo json_encode($_POST);
    }
}
