<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Input_bstt extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('input_bstt_model');
    }
    public function search_colector(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

                $result = $this->input_bstt_model->search_colector($search, $start, $limit);
                echo $result;
	}
   public function search_bstt(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_colector = isset($_POST['kd_colector']) ? $this->db->escape_str($this->input->post('kd_colector', TRUE)) : '';
                $result = $this->input_bstt_model->search_bstt($kd_colector,$search, $start, $limit);
                echo $result;
	}
   public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $no_bstt = isset($_POST['no_bstt']) ? $this->db->escape_str($this->input->post('no_bstt', TRUE)) : '';
                $result = $this->input_bstt_model->get_rows($no_bstt,$search, $start, $limit);
                echo $result;
	}
   public function search_no_faktur(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
                $result = $this->input_bstt_model->search_no_faktur($kd_pelanggan,$search, $start, $limit);
                echo $result;
	}
  public function update_row(){
		//header invoice
                $tgl_bstt = isset($_POST['tgl_bstt']) ? $this->db->escape_str($this->input->post('tgl_bstt',TRUE)) : FALSE;
                $tgl_bstt = date('Y-m-d', strtotime($tgl_bstt));
                $current_date = date('Ymd', strtotime($tgl_bstt));
		$no_bukti = 'BSTT' . $current_date .'-';
                $sequence = $this->input_bstt_model->get_kode_sequence($no_bukti, 4);
                $no_bukti_terima = "$no_bukti$sequence";
                
                $kd_collector = isset($_POST['kd_collector']) ? $this->db->escape_str($this->input->post('kd_collector',TRUE)) : FALSE;
                $rp_total_faktur = isset($_POST['rp_total_faktur']) ? $this->db->escape_str($this->input->post('rp_total_faktur',TRUE)) : FALSE;		
		//detail faktur
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$header_result = FALSE;
		$detail_result = FALSE;
		
		if(count($detail) > 0){	
                $this->db->trans_begin();
		
			$this->db->trans_start();
			$bstt['no_bstt'] = $no_bukti_terima;
			$bstt['tanggal'] = $tgl_bstt ;
			$bstt['kd_collector'] = $kd_collector;
			$bstt['total_faktur'] = (int) $rp_total_faktur;
                        $header_result = $this->input_bstt_model->insert_row('sales.t_bstt', $bstt);
                        if(!$header_result){
                                echo '{"success":false,"errMsg":"Process Failed.."}';
                                $this->db->trans_rollback();
                                exit;
                        }
			
                    foreach($detail as $obj){	
                        $bstt_detail['no_bstt'] = $no_bukti_terima;
                        $bstt_detail['no_faktur'] = $obj->no_faktur;
                        $bstt_detail['rp_faktur'] = (int) $obj->rp_faktur;
			$bstt_detail['kd_pelanggan'] = $obj->kd_pelanggan;
                        $detail_result = $this->input_bstt_model->insert_row('sales.t_bstt_detail', $bstt_detail);
                        
                    }
                    $this->db->trans_commit();
                    }
                    if ($header_result && $detail_result) {
                            $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("bukti_serah_terima_tagihan/print_form/" . $no_bukti_terima) . '"}';
                    } else {
                            $result = '{"success":false,"errMsg":"Process Failed.."}';
                    }
                    echo $result;
    }
  public function print_form($no_bukti = '') {
        $data = $this->input_bstt_model->get_data_print($no_bukti);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/BsttPrint.php');
        $pdf = new BsttPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "A3_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }
}
