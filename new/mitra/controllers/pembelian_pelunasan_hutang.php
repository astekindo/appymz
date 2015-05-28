<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_pelunasan_hutang extends MY_Controller {  
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_pelunasan_hutang_model', 'pph_model');
                $this->load->model('cetak_pelunasan_hutang_model', 'cph_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	//$no_kwit = 'PH' . date('Ym') . '-';
    	//$sequence = $this->pph_model->get_kode_sequence($no_kwit, 3);
    	echo '{"success":true,
				"data":{
					"no_bukti":"' . $no_kwit . $sequence . '",
					"user_peruntukan":"' . $this->session->userdata('user_peruntukan') . '",
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		//$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
                $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';	
                $current_date = date('Ymd', strtotime($tanggal));
		$no_ph = 'PH' . $current_date .'-';
                $sequence = $this->pph_model->get_kode_sequence($no_ph, 3);
    	
		$no_bukti = $no_ph . $sequence;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
			
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		
		$total_invoice = isset($_POST['_total_invoice']) ? $this->db->escape_str($this->input->post('_total_invoice',TRUE)) : 0;
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$total_potongan = isset($_POST['_total_potongan']) ? $this->db->escape_str($this->input->post('_total_potongan',TRUE)) : 0;
		$total_dibayar = isset($_POST['_total_dibayar']) ? $this->db->escape_str($this->input->post('_total_dibayar',TRUE)) : 0;
                $total_biaya_lain = isset($_POST['_total_biaya_lain']) ? $this->db->escape_str($this->input->post('_total_biaya_lain',TRUE)) : 0;
                $grand_total = isset($_POST['_grand_total']) ? $this->db->escape_str($this->input->post('_grand_total',TRUE)) : 0;
		$selisih = isset($_POST['_selisih']) ? $this->db->escape_str($this->input->post('_selisih',TRUE)) : 0;
                $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : 0;
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$detailbayar = isset($_POST['detailbayar']) ? json_decode($this->input->post('detailbayar',TRUE)) : array();
		$detailbiayalain = isset($_POST['detailbiayalain']) ? json_decode($this->input->post('detailbiayalain',TRUE)) : array();
                
		if($tanggal != ''){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		
		if(count($detail) == 0 && count($detailbayar) == 0){	
			echo '{"success":false,"errMsg":"Data tidak lengkap"}';
			exit;
		}
                $tgl_bayar = strtotime($tanggal);
                foreach($detail as $obj){
                    $tgl_invoice = strtotime($obj->tgl_terima_invoice);
                    if($tgl_bayar < $tgl_invoice){
                        echo '{"success":false,"errMsg":"Tanggal Bayar Tidak Boleh Lebih Kecil dari Tanggal Terima Invoice"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }
                $header_result = FALSE;
                $detail_result = 0;
		$this->db->trans_begin();
		$header_pph['no_bukti'] = $no_bukti;
		$header_pph['tanggal'] = $tanggal;
		$header_pph['kd_supplier'] = $kd_supplier;
		$header_pph['rp_total_invoice'] = (int) $total_invoice;
		$header_pph['rp_total_potongan'] = (int) $total_potongan;		
		$header_pph['rp_selisih'] = (int) $selisih;		
		$header_pph['rp_total'] = (int) $total_bayar;
		$header_pph['rp_total_dibayar'] = (int) $total_dibayar;
		$header_pph['keterangan'] = $keterangan;
                $header_pph['biaya_lain'] = $total_biaya_lain;
                $header_pph['grand_total'] = $grand_total;
                $header_pph['kd_peruntukan'] = $kd_peruntukan;
                $header_pph['created_by'] = $this->session->userdata('username');
		$header_pph['created_date'] = date('Y-m-d H:i:s');
		
		
                $header_result = $this->pph_model->insert_row('purchase.t_pelunasan_hutang', $header_pph);		 
//		if( ! $this->pph_model->insert_row('purchase.t_pelunasan_hutang', $header_pph)){
//			echo '{"success":false,"errMsg":"Process Failed.."}';
//			$this->db->trans_rollback();
//			exit;
//		}
		
		foreach($detail as $obj){
			$detail_pph['no_bukti'] = $no_bukti;
			$detail_pph['no_invoice'] = $obj->no_invoice;
			$detail_pph['rp_total'] =  $obj->rp_total;
			$detail_pph['rp_diskon'] =  $obj->rp_diskon;
			$detail_pph['tgl_invoice'] = $obj->tgl_invoice;
			$detail_pph['rp_bayar'] = $obj->rp_dibayar;
                        $detail_pph['rp_sisa'] = $obj->rp_sisa_invoice;
			
			if($this->pph_model->insert_row('purchase.t_pelunasan_detail', $detail_pph)){
				$detail_result++;
			}
			
			if( $this->pph_model->update_invoice($obj->no_invoice, $obj->rp_sisa_invoice, $obj->rp_bayar)){
				$detail_result++;
			}
		}
		
		foreach($detailbayar as $obj){
			$detail_bayar['no_bukti'] = $no_bukti;
			$detail_bayar['kd_jns_bayar'] = $obj->kd_jenis_bayar;
			$detail_bayar['nomor_bank'] = $obj->nomor_bank;
			$detail_bayar['nomor_ref'] = $obj->nomor_ref;
			if($obj->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj->tgl_jth_tempo));
			}
			$detail_bayar['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_bayar['rp_bayar'] = (int) $obj->rp_bayar;
			if($detail_bayar['is_validasi_card'] == '1' ){
				if($detail_bayar['nomor_bank'] === '' || $detail_bayar['nomor_ref'] === '' || $detail_bayar['tgl_jth_tempo'] === ''){
					echo '{"success":false,"errMsg":"Pembayaran dengan Cek dan Giro, No Bank, No Ref, dan Tgl Jth Tempo tidak boleh kosong!"}';
					$this->db->trans_rollback();
					exit;
				}
			}
			if( $this->pph_model->insert_row('purchase.t_pelunasan_bayar', $detail_bayar)){
				$detail_result++;
			}else
			$detail_result = 0;
		}
                foreach($detailbiayalain as $obj_lain){
			$detail_biayalain['no_bukti'] = $no_bukti;
			$detail_biayalain['kd_jns_bayar'] = $obj_lain->kd_jenis_bayar;
			$detail_biayalain['nomor_bank'] = $obj_lain->nomor_bank;
			$detail_biayalain['nomor_ref'] = $obj_lain->nomor_ref;
                        $detail_biayalain['keterangan'] = $obj_lain->keterangan;
			if($obj_lain->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj_lain->tgl_jth_tempo));
			}
			$detail_biayalain['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_biayalain['rp_bayar'] = (int) $obj_lain->rp_bayar_lain;
			if($detail_biayalain['is_validasi_card'] == '1' ){
				if($detail_biayalain['nomor_bank'] === '' || $detail_biayalain['nomor_ref'] === '' || $detail_biayalain['tgl_jth_tempo'] === ''){
					echo '{"success":false,"errMsg":"Pembayaran dengan Cek dan Giro, No Bank, No Ref, dan Tgl Jth Tempo tidak boleh kosong!"}';
					$this->db->trans_rollback();
					exit;
				}
			}
			if( $this->pph_model->insert_row('purchase.t_pelunasan_biaya_lain', $detail_biayalain)){
				$detail_result++;
			}else
			$detail_result = 0;
		}
		
		$this->db->trans_commit();
                if ($header_result && $detail_result > 0) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_pelunasan_hutang/print_form/" . $no_bukti) . '"}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }

    public function print_form($no_bukti){
		$data = $this->cph_model->get_data_print($no_bukti);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PelunasanHutangPrint.php');
		$pdf = new PelunasanHutangPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail'],$data['detail_bayar'],$data['detail_biaya_lain']);
		$pdf->Output();	
		exit;
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_invoice(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : "";
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
                $kd_peruntukan = $this->session->userdata('user_peruntukan');
                $result = $this->pph_model->get_all_invoice($kd_supplier,$kd_peruntukan,$search);
                
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->pph_model->get_all_jenis_pembayaran(true);
        
        echo $result;
	}
	
}