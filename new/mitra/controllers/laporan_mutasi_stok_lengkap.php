<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_mutasi_stok_lengkap extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_mutasi_stok_lengkap_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012 
	$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
	
	 */
        public function search_gudang(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_mutasi_stok_lengkap_model->search_gudang($search, $start, $limit);
				
        echo $result;
	}
    
	public function print_form($dari_tgl = '',$sampai_tgl = ''){
		$data = $this->laporan_mutasi_stok_lengkap_model->get_mutasi_stok_lengkap_print($dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');
		
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanMutasiStokLengkapPrint.php');
		$pdf = new LaporanMutasiStokLengkapPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();	
		exit;
	}
        
}
