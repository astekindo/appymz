<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_rekap_stok_per_katagori extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('report_model');
		$this->load->model('laporan_rekap_stok_per_katagori_model', 'rspk_model');
    }
	
    public function get_report() {
        echo json_encode($_POST);
    }
        
}
