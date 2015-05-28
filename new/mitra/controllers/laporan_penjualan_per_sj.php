<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan_per_sj extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan_per_sj_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012 
	$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
	
	 */
	public function print_form($kd_member = '',$dari_tgl = '',$sampai_tgl = ''){
		$data = $this->laporan_penjualan_per_sj_model->get_penjualan_per_sj_print($kd_member,$dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');
		
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanPenjualanPerSjPrint.php');
		$pdf = new LaporanPenjualanPerSjPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();	
		exit;
	}
        
}
