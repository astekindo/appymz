<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_purchase_order extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_purchase_order_model','lpo_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_supplier(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->lpo_model->search_supplier($search, $start, $limit);

        echo $result;
	}

        public function search_member(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->lpo_model->search_member($search, $start, $limit);

        echo $result;
	}

        public function search_produk(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->lpo_model->search_produk($search, $start, $limit);

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_supplier(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		$action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action',TRUE)) : '';

		$results = $this->lpb_model->search_produk_by_supplier($kd_supplier,$search, $start, $limit);

		$result = '{"success":true,"data":'.json_encode($results).'}';

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby bambang
	 * @lastedited 22 jun 2014
	 */
	public function print_form(){
		$data = $this->lpo_model->get_data_po_print();
		if(!$data) show_404('page');

		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanPurchaseOrderPrint.php');
		$pdf = new LaporanPurchaseOrderPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();
		exit;
	}

    public function get_report() {
        $params = array(
            'creator'       => $this->session->userdata('username'),
            'tgl_min'       => $this->form_data('dari_tgl','2014-01-01'),
            'tgl_max'       => $this->form_data('sampai_tgl','2014-01-31'),
            'kategori1'     => $this->form_data('kd_kategori1_sel'),
            'kategori2'     => $this->form_data('kd_kategori2_sel'),
            'kategori3'     => $this->form_data('kd_kategori3_sel'),
            'kategori4'     => $this->form_data('kd_kategori4_sel'),
            'no_po'         => $this->form_data('no_po_sel'),
            'supplier'      => $this->form_data('kd_supplier_sel'),
            'konsinyasi'    => $this->form_data('kd_konsinyasi',1),
            'pkp'           => $this->form_data('kd_status',1)
        );

        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/laporan_purchase_order.rptdesign&__parameterpage=false';

        foreach ($params as $key => $value) {
            if($key === 'tgl_min' || $key === 'tgl_max') $value = date('Y-m-d', strtotime($value));
            if(!is_null($value) || !empty($value)) $reportURL .= "&$key=$value";
        }

        echo '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas A3 (Continuous Form)", "printUrl":"' . $reportURL . '"}';
    }
}
