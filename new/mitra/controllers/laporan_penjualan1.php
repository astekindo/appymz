<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan1 extends MY_Controller {

	/**
	 * @author dhamarsu
     * @editedby bambang
     * @lastedited 15 mei 2014
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan1_model', 'lp1');
    }

    /**
     * @author bambang
     * @lastedited 15 mei 2014
     */
    public function get_report() {
        $dari_tgl       = $this->form_data('dari_tgl');
        $sampai_tgl     = $this->form_data('sampai_tgl');
        $userid         = $this->form_data('id_user_sel');
        $no_open_saldo  = $this->form_data('shift_sel');
        $no_member      = $this->form_data('kd_member_sel');
        $kd_kategori1   = $this->form_data('kd_kategori1_sel');
        $kd_kategori2   = $this->form_data('kd_kategori2_sel');
        $kd_kategori3   = $this->form_data('kd_kategori3_sel');
        $kd_kategori4   = $this->form_data('kd_kategori4_sel');
        $kd_ukuran      = $this->form_data('kd_ukuran_sel');
        $kd_satuan      = $this->form_data('kd_satuan_sel');
        $kd_produk      = $this->form_data('kd_produk_sel');
        $kd_jns_byr     = $this->form_data('kd_jns_byr_sel');

        $params = '&__parameterpage=false&creator=' . $this->session->userdata('username');

        $dari_tgl = date('Y-m-d', strtotime($dari_tgl));
        $sampai_tgl = date('Y-m-d', strtotime($sampai_tgl));

        if(!is_null($dari_tgl)) $params      .=  '&dari_tgl=' . $dari_tgl;
        if(!is_null($sampai_tgl)) $params    .=  '&sampai_tgl=' . $sampai_tgl;
        if(!is_null($userid)) $params        .=  '&userid=' . $userid;
        if(!is_null($no_open_saldo)) $params .=  '&no_open_saldo=' . $no_open_saldo;
        if(!is_null($no_member)) $params     .=  '&no_member=' . $no_member;
        if(!is_null($kd_kategori1)) $params  .=  '&kd_kategori1=' . $kd_kategori1;
        if(!is_null($kd_kategori2)) $params  .=  '&kd_kategori2=' . $kd_kategori2;
        if(!is_null($kd_kategori3)) $params  .=  '&kd_kategori3=' . $kd_kategori3;
        if(!is_null($kd_kategori4)) $params  .=  '&kd_kategori4=' . $kd_kategori4;
        if(!is_null($kd_ukuran)) $params     .=  '&kd_ukuran=' . $kd_ukuran;
        if(!is_null($kd_satuan)) $params     .=  '&kd_satuan=' . $kd_satuan;
        if(!is_null($kd_produk)) $params     .=  '&kd_produk=' . $kd_produk;
        if(!is_null($kd_jns_byr)) $params    .=  '&kd_jns_byr=' . $kd_jns_byr;

//        echo json_encode($_POST);
        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/penjualan_1.rptdesign' . $params;

        echo '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas A3 (Continuous Form)", "printUrl":"' . $reportURL . '"}';

    }
}
