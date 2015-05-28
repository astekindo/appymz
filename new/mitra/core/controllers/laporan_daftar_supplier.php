<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_daftar_supplier extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_purchase_order_model');
                $this->load->model('laporan_daftar_supplier_model');
                
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

		$result = $this->laporan_daftar_supplier_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012 
	$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
	
	 */
	public function print_form($kd_supplier = ''){
                // $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : FALSE;
               
		$data = $this->laporan_daftar_supplier_model->get_data_po_print($kd_supplier);
		if(!$data) show_404('page');
		//  print_r($data['detail']);
                 // print_r($data['header']);
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanDaftarSupplier1Print.php');
		$pdf = new LaporanDaftarSupplier1Print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
}
