<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Pembayaran_uang_muka extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('pembayaran_uang_muka_model', 'pum_model');
    }
public function search_so() {
        $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->pum_model->get_noso($kd_pelanggan,$search, $start, $limit);
        echo $result;
    }
public function update_row(){
		
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
		//$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';		
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		
		$total_uang_muka = isset($_POST['_total_uang_muka']) ? $this->db->escape_str($this->input->post('_total_uang_muka',TRUE)) : 0;
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$selisih = isset($_POST['_selisih']) ? $this->db->escape_str($this->input->post('_selisih',TRUE)) : 0;	
		
                $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$detailbayar = isset($_POST['detailbayar']) ? json_decode($this->input->post('detailbayar',TRUE)) : array();
		
		if($tanggal != ''){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		
		if(count($detail) == 0 && count($detailbayar) == 0){	
			echo '{"success":false,"errMsg":"Data tidak lengkap"}';
			exit;
		}
                $tgl_uangmuka = strtotime($tanggal);
                foreach($detail as $obj){
                    $tgl_so = strtotime($obj->tgl_so);
                    if($tgl_uangmuka < $tgl_so){
                    echo '{"success":false,"errMsg":"Tanggal Uang Muka tidak Boleh Lebih Kecil Dari Tanggal SO"}';
                        $this->db->trans_rollback();
                        exit;//error
            }
                }
                $current_date = date('Ymd', strtotime($tanggal));
		$no_kwit = 'PUM' . $current_date .'-';
                $sequence = $this->pum_model->get_kode_sequence($no_kwit, 3);

                $no_bayar = "$no_kwit$sequence";
		$this->db->trans_begin();
		$header_ppp['no_bayar'] = $no_bayar;
		$header_ppp['tgl_bayar'] = $tanggal;
		$header_ppp['kd_pelanggan'] = $kd_pelanggan;
		$header_ppp['total'] = (int) $total_uang_muka;
		$header_ppp['rp_bayar'] = (int) $total_bayar;		
		$header_ppp['keterangan'] = $keterangan;
                $header_ppp['created_by'] = $this->session->userdata('username');
		$header_ppp['created_date'] = date('Y-m-d H:i:s');
		if( ! $this->pum_model->insert_row('sales.t_uang_muka', $header_ppp)){
			echo '{"success":false,"errMsg":"Process Failed.."}';
			$this->db->trans_rollback();
			exit;
		}
                
		foreach($detail as $obj){
			$detail_ppp['no_bayar'] = $no_bayar;
			$detail_ppp['no_so'] = $obj->no_so;
			$detail_ppp['rp_jumlah'] = (int) $obj->rp_jumlah;
			$detail_ppp['rp_dpp'] = (int) $obj->rp_dpp;
			$detail_ppp['rp_ppn'] = (int) $obj->rp_ppn;
			$detail_ppp['rp_uang_muka'] = (int) $obj->rp_uang_muka;
			
			if($this->pum_model->insert_row('sales.t_uang_muka_detail', $detail_ppp)){
				$detail_result++;
			}
			
			if( $this->pum_model->update_sales_order_dist($obj->no_so, $obj->rp_uang_muka)){
				$detail_result++;
			}
		}
                

			
		foreach($detailbayar as $obj){
			$detail_bayar['no_bayar'] = $no_bayar;
			$detail_bayar['kd_jenis_bayar'] = $obj->kd_jenis_bayar;
			$detail_bayar['no_bank'] = $obj->nomor_bank;
			$detail_bayar['no_ref'] = $obj->nomor_ref;
			if($obj->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj->tgl_jth_tempo));
			}
			$detail_bayar['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_bayar['rp_bayar'] = (int) $obj->rp_bayar;
			
			if(! $this->pum_model->insert_row('sales.t_uang_muka_bayar', $detail_bayar)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
		}
		
		$this->db->trans_commit();
                 $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("pembayaran_uang_muka/print_form/" . $no_bayar) . '"}';
                  echo $result;
                 //echo '{"success":true,"errMsg":""}';
    }
    public function print_form($no_bayar = '') {
        $data = $this->pum_model->get_data_print($no_bayar);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembayaranUangMukaPrint.php');
        $pdf = new PembayaranUangMukaPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail'], $data['detail_bayar']);
        $pdf->Output();
        exit;
    }
}