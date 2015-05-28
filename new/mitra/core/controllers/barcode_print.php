<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Barcode_print extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('barcode_print_model','bp_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
        $result = $this->bp_model->get_rows($kd_supplier,$search, $start, $limit);
        
        echo $result;
	}
	
	public function get_rows_detail($kd_supplier=''){
		$result = $this->bp_model->get_rows_detail($kd_supplier);
        
        echo $result;
	}
	
	public function search_receive_order() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

        $result = $this->bp_model->search_receive_order($kd_produk, $search, $start, $limit);


        echo $result;
    }
	
	public function search_produk_by_no_do() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do', TRUE)) : '';

        $result = $this->bp_model->search_produk_by_no_do($no_do, $search, $start, $limit);


        echo $result;
    }
	
	public function print_form($no_do = '', $kd_produk = '', $jumlah = '', $title = ''){
		
		$this->bp_model->update_sisa($no_do, $kd_produk, $jumlah);
		
		$title = str_replace('%20', ' ',$title);
		$data = $this->bp_model->get_data_print($no_do, $title);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PembelianReceiveOrderPrint.php');
		$pdf = new PembelianReceiveOrderPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
}
