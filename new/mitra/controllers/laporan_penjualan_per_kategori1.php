<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan_per_kategori1 extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan_per_kategori1_model', 'lppk1');
    }

    public function get_report() {

        $dari_tgl    = $this->form_data('lppk1_dari_tgl', '01-01-2014');
        $sampai_tgl  = $this->form_data('lppk1_sampai_tgl', '31-12-2014');
        $status      = $this->form_data('status', 0);
        $kategori1   = $this->form_data('kd_kategori1_sel', null);
        $supplier    = $this->form_data('kd_supplier_sel', null);
        $data_type   = $this->form_data('data_type', 0);
        $value_type  = $this->form_data('value_type', 1);
        $sort_order  = $this->form_data('sort_order', 1);

        $params = '&__parameterpage=false&creator=' . $this->session->userdata('username');

        $tgl_min = date('Y-m-d', strtotime($dari_tgl));
        $tgl_max = date('Y-m-d', strtotime($sampai_tgl));

        $params = !is_null($tgl_min) ?        $params . '&tgl_min=' . $tgl_min : $params;
        $params = !is_null($tgl_max) ?        $params . '&tgl_max=' . $tgl_max : $params;
        $params = !is_null($status) ?         $params . '&status=' . $status : $params;
        $params = !is_null($kategori1) ?      $params . '&kategori1=' . $kategori1 : $params;
        $params = !is_null($supplier) ?       $params . '&supplier=' . $supplier : $params;
        $params = intval($data_type) === 1 ?  $params . '&isqty=True' : $params . '&isqty=False';
        $params = intval($value_type) === 0 ? $params . '&isdpp=True' : $params . '&isdpp=False';

        $params = !is_null($sort_order) ?   $params . '&sort_order=' . $sort_order : $params;

        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/penjualan_per_cat_1.rptdesign' . $params;
        echo '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas A3 (Continuous Form)", "printUrl":"' . $reportURL . '"}';
    }
}