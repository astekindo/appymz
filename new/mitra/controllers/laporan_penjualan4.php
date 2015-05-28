<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_penjualan4 extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('laporan_penjualan4_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
        public function search_produk(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_penjualan3_model->search_produk($search, $start, $limit);
				
        echo $result;
	}
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->laporan_penjualan3_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
        
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012 
	$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
	
	 */
	public function print_form($kd_user = '',$kd_shift = '',$kd_member = '',$dari_tgl = '',$sampai_tgl = ''){
		$data = $this->laporan_penjualan4_model->get_data_penjualan4_print($kd_user,$kd_shift,$kd_member,$dari_tgl,$sampai_tgl);
		if(!$data) show_404('page');
		
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/LaporanPenjualan4Print.php');
		$pdf = new LaporanPenjualan4Print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'], $data['detail']);
		$pdf->Output();	
		exit;
	}

    public function print_pdf() {
        $url = 'http://localhost:8080/birt/frameset?__report=Report%5Cpenjualan_4.rptdesign&__toolbar=false&__parameterpage=false&__format=pdf';

        $creator = '&creator='.$this->session->userdata('username');
        $tgl_dari = isset($_POST['dari_tgl']) && !empty($_POST['dari_tgl']) ? $this->db->escape_str($this->input->post('dari_tgl', TRUE)) : '';
        $tgl_sampai = isset($_POST['sampai_tgl']) && !empty($_POST['sampai_tgl']) ? $this->db->escape_str($this->input->post('sampai_tgl', TRUE)) : '';

        $kategori1 = isset($_POST['nama_kategori1']) && !empty($_POST['nama_kategori1']) ? '&kategori1=' . $this->db->escape_str($this->input->post('nama_kategori1', TRUE)) : '';
        $kategori2 = isset($_POST['nama_kategori2']) && !empty($_POST['nama_kategori2']) ? '&kategori2=' . $this->db->escape_str($this->input->post('nama_kategori2', TRUE)) : '';
        $kategori3 = isset($_POST['nama_kategori3']) && !empty($_POST['nama_kategori3']) ? '&kategori3=' . $this->db->escape_str($this->input->post('nama_kategori3', TRUE)) : '';
        $kategori4 = isset($_POST['nama_kategori4']) && !empty($_POST['nama_kategori4']) ? '&kategori4=' . $this->db->escape_str($this->input->post('nama_kategori4', TRUE)) : '';

        $kd_produk = isset($_POST['kd_produk']) && !empty($_POST['kd_produk']) ? '&kd_produk=' . $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) && !empty($_POST['kd_supplier']) ? '&kd_supplier=' . $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_ukuran = isset($_POST['kd_ukuran']) && !empty($_POST['kd_ukuran']) ? '&kd_ukuran=' . $this->db->escape_str($this->input->post('kd_ukuran', TRUE)) : '';
        $kd_satuan = isset($_POST['kd_satuan']) && !empty($_POST['kd_satuan']) ? '&kd_satuan=' . $this->db->escape_str($this->input->post('kd_satuan', TRUE)) : '';

        if($tgl_dari !== '') $tgl_dari = '&tgl_dari=' . substr($tgl_dari,6,4) . '-' . substr($tgl_dari,3,2) . '-' . substr($tgl_dari,0,2);
        if($tgl_sampai !== '') $tgl_sampai = '&tgl_sampai=' . substr($tgl_sampai,6,4) . '-' . substr($tgl_sampai,3,2) . '-' . substr($tgl_sampai,0,2);

        $url = $url . $creator . $tgl_dari . $tgl_sampai . $kategori1 . $kategori2 . $kategori3 . $kategori4 . $kd_produk . $kd_ukuran . $kd_satuan . $kd_supplier;
        $result = '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas Folio(Inkjet/Laserjet)", "printUrl":"' . $url . '"}';

        echo $result;
    }
        
}
