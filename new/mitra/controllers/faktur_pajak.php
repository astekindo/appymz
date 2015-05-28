<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Faktur_pajak extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('faktur_pajak_model', 'fp_model');
    }
    
    public function search_faktur_jual(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fp_model->search_faktur_jual($kd_pelanggan, $search);		
                echo $result;
	}
    public function search_pelanggan() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->fp_model->search_pelanggan($search, $start, $limit);

        echo $result;
    }
    public function search_uang_muka(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fp_model->search_uang_muka($kd_pelanggan, $search);		
                echo $result;
	}
   public function search_pelanggan_npwp(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fp_model->search_pelanggan_npwp($kd_pelanggan, $search);		
                echo $result;
	}
    public function search_faktur_jual_detail(){
		$no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fp_model->search_faktur_jual_detail($no_faktur, $search);		
                echo $result;
	}
    public function search_uang_muka_detail(){
		$no_bayar = isset($_POST['no_bayar']) ? $this->db->escape_str($this->input->post('no_bayar',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fp_model->search_uang_muka_detail($no_bayar, $search);		
                echo $result;
	}
   public function update_row(){
		//header invoice
                $tgl_faktur = isset($_POST['tgl_faktur']) ? $this->db->escape_str($this->input->post('tgl_faktur',TRUE)) : FALSE;
                $tgl_faktur = date('Y-m-d', strtotime($tgl_faktur));
                $no_faktur_pajak = isset($_POST['no_faktur_pajak']) ? $this->db->escape_str($this->input->post('no_faktur_pajak',TRUE)) : FALSE;
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
                $no_faktur_jual = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : FALSE;
		$kd_npwp = isset($_POST['kd_npwp']) ? $this->db->escape_str($this->input->post('kd_npwp',TRUE)) : FALSE;
		//detail invoice
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$header_result = FALSE;
		$detail_result = 0;
		$data_faktur = $this->fp_model->search_no_faktur($no_faktur_pajak);
                if($data_faktur > 0){
                    echo '{"success":false,"errMsg":"No Faktur Pajak '.$no_faktur_pajak.' Sudah Ada / Sudah Digunakan"}';
                        $this->db->trans_rollback();
                        exit;//error
                }
		if(count($detail) > 0){	
		
			$this->db->trans_start();
			$header_pr['no_faktur_pajak'] = $no_faktur_pajak;
			$header_pr['kd_pelanggan'] = $kd_pelanggan ;
			$header_pr['tgl_faktur_pajak'] = $tgl_faktur;
			$header_pr['no_faktur'] = $no_faktur_jual;
                        $header_pr['kd_npwp'] = $kd_npwp;
//                        $header_pr['nama_npwp'] = $nama_npwp;
//                        $header_pr['no_npwp'] = $no_npwp;
//                        $header_pr['alamat_npwp'] = $alamat_npwp;
			
			$header_result = $this->fp_model->insert_row('sales.t_faktur_pajak', $header_pr);
			if($header_result){
						$sql = "UPDATE sales.t_faktur_jual SET is_pajak = 1 WHERE no_faktur = '" . $no_faktur_jual . "'";
						$this->fp_model->query_update($sql);
					
					}
			$this->db->trans_complete();
		}
		
		if ($header_result) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("faktur_pajak/print_form/" . $no_faktur_pajak) . '"}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
    public function update_row_uang_muka(){
		//header invoice
                $tgl_bayar = isset($_POST['tgl_bayar']) ? $this->db->escape_str($this->input->post('tgl_bayar',TRUE)) : FALSE;
                $tgl_bayar = date('Y-m-d', strtotime($tgl_bayar));
                $no_faktur_pajak = isset($_POST['no_faktur_pajak']) ? $this->db->escape_str($this->input->post('no_faktur_pajak',TRUE)) : FALSE;
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
                $no_bayar = isset($_POST['no_bayar']) ? $this->db->escape_str($this->input->post('no_bayar',TRUE)) : FALSE;
		$kd_npwp = isset($_POST['kd_npwp']) ? $this->db->escape_str($this->input->post('kd_npwp',TRUE)) : FALSE;
		//detail invoice
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$header_result = FALSE;
		$detail_result = 0;
		$data_faktur = $this->fp_model->search_no_faktur($no_faktur_pajak);
                if($data_faktur > 0){
                    echo '{"success":false,"errMsg":"No Faktur Pajak '.$no_faktur_pajak.' Sudah Ada / Sudah Digunakan"}';
                        $this->db->trans_rollback();
                        exit;//error
                }
		if(count($detail) > 0){	
		
			$this->db->trans_start();
			$header_pr['no_faktur_pajak'] = $no_faktur_pajak;
			$header_pr['kd_pelanggan'] = $kd_pelanggan ;
			$header_pr['tgl_faktur_pajak'] = $tgl_bayar;
			$header_pr['no_bayar_uang_muka'] = $no_bayar;
                        $header_pr['kd_npwp'] = $kd_npwp;
			
			$header_result = $this->fp_model->insert_row('sales.t_faktur_pajak', $header_pr);
			if($header_result){
						$sql = "UPDATE sales.t_uang_muka SET is_pajak = 1 WHERE no_bayar = '" . $no_bayar . "'";
						$this->fp_model->query_update($sql);
					
					}
			$this->db->trans_complete();
		}
		
		if ($header_result) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("faktur_pajak/print_form_uang_muka/" . $no_faktur_pajak) . '"}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
    public function print_form($no_faktur_pajak){
		$data = $this->fp_model->get_data_print($no_faktur_pajak);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/FakturPajakPrint.php');
		$pdf = new FakturPajakPrint(PDF_PAGE_ORIENTATION_PORTRAIT, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}   
   public function print_form_uang_muka($no_faktur_pajak){
		$data = $this->fp_model->get_data_print_uang_muka($no_faktur_pajak);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/FakturPajakUangMukaPrint.php');
		$pdf = new FakturPajakUangMukaPrint(PDF_PAGE_ORIENTATION_PORTRAIT, PDF_UNIT, "F4", true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}   
 }  
?>
