<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tanda_terima_faktur extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('tanda_terima_faktur_model', 'ttf_model');
    }
   
   public function search_pelanggan(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                
                $result = $this->ttf_model->search_pelanggan($kd_colector,$search, $start, $limit);
                echo $result;
	}
   public function search_no_faktur(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
                $result = $this->ttf_model->search_no_faktur($kd_pelanggan,$search, $start, $limit);
                echo $result;
	}
  public function update_row(){
		//header invoice
                $tgl_ttf = isset($_POST['tgl_ttf']) ? $this->db->escape_str($this->input->post('tgl_ttf',TRUE)) : FALSE;
                $tgl_ttf = date('Y-m-d', strtotime($tgl_ttf));
                $current_date = date('Ym', strtotime($tgl_ttf));
		$no_bukti = 'TTF' . $current_date .'-';
                $sequence = $this->ttf_model->get_kode_sequence($no_bukti, 4);
                $no_ttf = "$no_bukti$sequence";
                
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
                $diserahkan = isset($_POST['diserahkan']) ? $this->db->escape_str($this->input->post('diserahkan',TRUE)) : FALSE;
                $tgl_diserahkan = isset($_POST['tgl_diserahkan']) ? $this->db->escape_str($this->input->post('tgl_diserahkan',TRUE)) : FALSE;
                $diterima = isset($_POST['diterima']) ? $this->db->escape_str($this->input->post('diterima',TRUE)) : FALSE;
                $tgl_diterima = isset($_POST['tgl_diterima']) ? $this->db->escape_str($this->input->post('tgl_diterima',TRUE)) : FALSE;
                $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : FALSE;
                $tgl_diserahkan = date('Y-m-d', strtotime($tgl_diserahkan));
                $tgl_diterima = date('Y-m-d', strtotime($tgl_diterima));
		//detail faktur
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$header_result = FALSE;
		$detail_result = FALSE;
		if($tgl_ttf > $tgl_diserahkan){
                    echo '{"success":false,"errMsg":"Tanggal TTF tidak boleh lebih besar dari Tanggal Diserahkan"}';
                    $this->db->trans_rollback();
                    exit;
                }
                if($tgl_diserahkan > $tgl_diterima){
                    echo '{"success":false,"errMsg":"Tanggal Diserahkan tidak boleh lebih besar dari Tanggal Diterima"}';
                    $this->db->trans_rollback();
                    exit;
                }
		if(count($detail) > 0){	
                $this->db->trans_begin();
		
			$this->db->trans_start();
                        $ttf['no_ttf'] = $no_ttf;
			$ttf['tanggal'] = $tgl_ttf ;
			$ttf['kd_pelanggan'] = $kd_pelanggan;
                        $ttf['diserahkan'] = $diserahkan;
                        $ttf['tgl_diserahkan'] = $tgl_diserahkan;
                        $ttf['diterima'] = $diterima;
                        $ttf['tgl_diterima'] = $tgl_diterima;
                        $ttf['keterangan'] = $keterangan;
                        $ttf['status'] = 1;
                        $ttf['created_by'] = $this->session->userdata('username');
                        $ttf['created_date'] = date('Y-m-d H:i:s');
			//$ttf['total_faktur'] = (int) $rp_total_faktur;
                        $header_result = $this->ttf_model->insert_row('sales.t_ttf', $ttf);
                        if(!$header_result){
                                echo '{"success":false,"errMsg":"Process Failed.."}';
                                $this->db->trans_rollback();
                                exit;
                        }
			
                    foreach($detail as $obj){	
                        $ttf_detail['no_ttf'] = $no_ttf;
                        $ttf_detail['no_faktur'] = $obj->no_faktur;
                        $ttf_detail['tgl_faktur'] = $obj->tgl_faktur;
                        $ttf_detail['rp_faktur'] = (int) $obj->rp_faktur;
                        $ttf_detail['rp_uang_muka'] = (int) $obj->rp_uang_muka;
                        $ttf_detail['rp_cash_diskon'] = (int) $obj->rp_cash_diskon;
                        $ttf_detail['rp_potongan'] = (int) $obj->rp_potongan;
                        $ttf_detail['rp_faktur_net'] = (int) $obj->rp_faktur_net;
                        $ttf_detail['rp_dpp'] = (int) $obj->rp_dpp;
                        $ttf_detail['rp_ppn'] = (int) $obj->rp_ppn;
                        $ttf_detail['rp_total_faktur'] = (int)$obj->rp_total_faktur;
			$ttf_detail['rp_kurang_bayar'] = (int)$obj->rp_kurang_bayar;
                        $ttf_detail['tgl_jatuh_tempo'] =  $obj->tgl_jatuh_tempo;
                        $detail_result = $this->ttf_model->insert_row('sales.t_ttf_detail', $ttf_detail);
                        if($detail_result){
                            $update_faktur = $this->ttf_model->update_row($obj->no_faktur, $no_ttf);
                            if (!$update_faktur){
                                 echo '{"success":false,"errMsg":"update faktur jual Failed.."}';
                                 $this->db->trans_rollback();
                                 exit;
                            }
                        }
                    }
                    $this->db->trans_commit();
                    }
                    if ($header_result && $detail_result && $update_faktur) {
                            $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("bukti_serah_terima_tagihan/print_form/" . $no_bukti_terima) . '"}';
                    } else {
                            $result = '{"success":false,"errMsg":"Process Failed.."}';
                    }
                    echo $result;
    }
  public function print_form($no_bukti = '') {
        $data = $this->ttf_model->get_data_print($no_bukti);
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
