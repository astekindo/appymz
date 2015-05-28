<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_umur_piutang extends MY_Controller {
	/**
     * @author bambang
     * @lastedited 15 mei 2014
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_umur_piutang_model', 'lppk1');
    }

    /**
     * @author bambang
     * @lastedited 15 mei 2014
     */
    public function get_report() {
        echo json_encode($_POST);
    }
}