<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan1 extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan1_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_user(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_penjualan1_model->search_user($search, $start, $limit);
				
        echo $result;
	}
        
        public function search_shift(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_penjualan1_model->search_shift($search, $start, $limit);
				
        echo $result;
	}
        
        public function search_member(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_penjualan1_model->search_member($search, $start, $limit);
				
        echo $result;
	}
        
        
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012 
	$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
	
	 */
	public function print_form($kd_user = '',$kd_shift = '',$kd_member = '',$dari_tgl = '',$sampai_tgl = ''){
		
               $data = $this->laporan_penjualan1_model->get_data_penjualan1_print($kd_user,$kd_shift,$kd_member,$dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');
		
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanPenjualan1Print.php');
		$pdf = new LaporanPenjualan1Print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();	
		exit;
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
	
}
