<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_sales_order extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_sales_order_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function print_form($dari_tgl = '',$sampai_tgl = ''){
		$data = $this->laporan_sales_order_model->get_sales_order_print($dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');

		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanSalesOrderPrint.php');
		$pdf = new LaporanSalesOrderPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();
		exit;
	}

    public function print_pdf() {
        $url = BIRT_BASE_URL . '/frameset?__report=report/penjualan_so.rptdesign&__parameterpage=false';

        $creator = '&creator='.$this->session->userdata('username');
        $hari_gtg_min = isset($_POST['gantung_min']) && !empty($_POST['gantung_min']) ? '&hari_gtg_min=' .$this->db->escape_str($this->input->post('gantung_min', TRUE)) : '';
        $hari_gtg_max = isset($_POST['gantung_max']) && !empty($_POST['gantung_max']) ? '&hari_gtg_max=' .$this->db->escape_str($this->input->post('gantung_max', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) && !empty($_POST['kd_produk']) ? '&kd_produk=' .$this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $kd_member = isset($_POST['kd_member']) && !empty($_POST['kd_member']) ? '&kd_member=' .$this->db->escape_str($this->input->post('kd_member', TRUE)) : '';
        $no_so = isset($_POST['no_so']) && !empty($_POST['no_so']) ? '&no_so=' .$this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $so_blm_kirim = isset($_POST['status_kirim']) && $_POST['status_kirim'] === 1 ? '&so_blm_kirim=' .$this->db->escape_str($this->input->post('kd_member', TRUE)) : '';

        $tgl_dari = isset($_POST['tanggal_dari']) && !empty($_POST['tanggal_dari']) ? $this->db->escape_str($this->input->post('tanggal_dari', TRUE)) : '';
        $tgl_sampai = isset($_POST['tanggal_sampai']) && !empty($_POST['tanggal_sampai']) ? $this->db->escape_str($this->input->post('tanggal_sampai', TRUE)) : '';

        if($tgl_dari !== '') $tgl_dari = '&tgl_dari=' . substr($tgl_dari,6,4) . '-' . substr($tgl_dari,3,2) . '-' . substr($tgl_dari,0,2);
        if($tgl_sampai !== '') $tgl_sampai = '&tgl_sampai=' . substr($tgl_sampai,6,4) . '-' . substr($tgl_sampai,3,2) . '-' . substr($tgl_sampai,0,2);

        $url = $url . $creator . $tgl_dari . $tgl_sampai . $no_so . $hari_gtg_min . $hari_gtg_max . $kd_produk . $kd_member . $so_blm_kirim;
        $result = '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas A3 (Continuous Form)", "printUrl":"' . $url . '"}';

        echo $result;
    }

}
