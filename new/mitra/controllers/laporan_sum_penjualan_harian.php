<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_sum_penjualan_harian extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_sum_penjualan_harian_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function print_form($dari_tgl = '',$sampai_tgl = ''){
		$data = $this->laporan_sum_penjualan_harian_model->get_sum_penjualan_harian_print($dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');

		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanSumPenjualanHarianPrint.php');
		$pdf = new LaporanSumPenjualanHarianPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();
		exit;
	}

    public function get_report() {
        $dari_tgl       = $this->form_data('dari_tgl');
        $sampai_tgl     = $this->form_data('sampai_tgl');
        $params = '&__parameterpage=false&creator=' . $this->session->userdata('username');

        $dari_tgl       = date('Y-m-d', strtotime($dari_tgl));
        $sampai_tgl     = date('Y-m-d', strtotime($sampai_tgl));

        if(!is_null($dari_tgl)) $params      .= "&dari_tgl=$dari_tgl";
        if(!is_null($sampai_tgl)) $params    .= "&sampai_tgl=$sampai_tgl";

//        echo json_encode($_POST);
        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/summary_penjualan_harian.rptdesign' . $params;
        echo json_encode(array(
            'success'       => true,
            'successMsg'    => 'Siapkan kertas A3 (Continuous Form)',
            'printUrl'      => $reportURL
        ));
    }

}
