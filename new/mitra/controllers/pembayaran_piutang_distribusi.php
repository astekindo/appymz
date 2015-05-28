<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembayaran_piutang_distribusi extends MY_Controller {  
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembayaran_piutang_distribusi_model', 'pp_dist_model');
    }
    public function get_all_faktur(){
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : "";
                $no_bstt = isset($_POST['bstt']) ? $this->db->escape_str($this->input->post('bstt',TRUE)) : "";
		$result = $this->pp_dist_model->get_all_faktur($kd_pelanggan,$no_bstt,$search);
        
        echo $result;
    }
    public function get_rows(){
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
                $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : "";
                $no_bstt = isset($_POST['no_bstt']) ? $this->db->escape_str($this->input->post('no_bstt',TRUE)) : "";
		$result = $this->pp_dist_model->get_rows($kd_pelanggan,$no_bstt,$search);
        
        echo $result;
    }
    public function search_bstt(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
                $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_colector = isset($_POST['kd_colector']) ? $this->db->escape_str($this->input->post('kd_colector', TRUE)) : '';
                $result = $this->pp_dist_model->search_bstt($kd_colector,$search, $start, $limit);
                echo $result;
	}
    public function update_row(){
		
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';		
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
                $no_bstt = isset($_POST['no_bstt']) ? $this->db->escape_str($this->input->post('no_bstt',TRUE)) : '';
		
		$total_faktur = isset($_POST['_total_faktur']) ? $this->db->escape_str($this->input->post('_total_faktur',TRUE)) : 0;
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$total_potongan = isset($_POST['_total_potongan']) ? $this->db->escape_str($this->input->post('_total_potongan',TRUE)) : 0;
		$total_dibayar = isset($_POST['_rp_bayar']) ? $this->db->escape_str($this->input->post('_rp_bayar',TRUE)) : 0;
		$kurang_bayar = isset($_POST['_kurang_bayar']) ? $this->db->escape_str($this->input->post('_kurang_bayar',TRUE)) : 0;
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
                
                $current_date = date('Ymd', strtotime($tanggal));
		$no_kwit = 'PPD' . $current_date .'-';
                $sequence = $this->pp_dist_model->get_kode_sequence($no_kwit, 3);
                $no_bukti = "$no_kwit$sequence";
                
		$this->db->trans_begin();
		$header_ppp['no_pembayaran_piutang'] = $no_bukti;
		$header_ppp['tgl_bayar'] = $tanggal;
		$header_ppp['total_potongan'] = (int) $total_potongan;
		$header_ppp['rp_faktur'] = (int) $total_faktur;
		$header_ppp['rp_kurang_bayar'] = (int) $kurang_bayar;
                $header_ppp['rp_bayar'] = (int) $total_dibayar;
		$header_ppp['keterangan'] = $keterangan;
                $header_ppp['bstt'] = $no_bstt;
                $header_ppp['created_by'] = $this->session->userdata('username');
		$header_ppp['created_date'] = date('Y-m-d H:i:s');
		if( ! $this->pp_dist_model->insert_row('sales.t_piutang_pembayaran', $header_ppp)){
			echo '{"success":false,"errMsg":"Process Failed.."}';
			$this->db->trans_rollback();
			exit;
		}
                $tgl_bayar = strtotime($tanggal);
                foreach($detail as $obj){
                    $tgl_faktur = strtotime($obj->tgl_so);
                    if($tgl_bayar < $tgl_faktur){
                        echo '{"success":false,"errMsg":"Tanggal Pembayaran Tidak Boleh Lebih Kecil dari Tanggal Faktur"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }
                
		foreach($detail as $obj){
                        
			$detail_ppp['no_pembayaran_piutang'] = $no_bukti;
			$detail_ppp['no_faktur'] = $obj->no_faktur;
			$detail_ppp['rp_faktur'] = (int) $obj->rp_grand_total;
			$detail_ppp['tgl_faktur'] = $obj->tgl_so;
			$detail_ppp['rp_bayar'] = (int) $obj->rp_bayar;
			$detail_ppp['rp_piutang'] = (int) $obj->rp_kurang_bayar;
                        $detail_ppp['rp_sisa_piutang'] = (int) $obj->sisa_bayar;
                        $detail_ppp['rp_potongan'] = (int) $obj->rp_potongan;
                        $detail_ppp['rp_dibayar'] = (int) $obj->rp_dibayar;
                        $detail_ppp['rp_sisa'] = (int) $obj->sisa_bayar;
			if($this->pp_dist_model->insert_row('sales.t_piutang_dist_detail', $detail_ppp)){
				$detail_result++;
			}
			
			if( $this->pp_dist_model->update_faktur_jual($obj->no_faktur, $obj->sisa_bayar, $obj->rp_bayar)){
				$detail_result++;
			}
		}
                

			
		foreach($detailbayar as $obj){
			$detail_bayar['no_pembayaran_piutang'] = $no_bukti;
			$detail_bayar['kd_jns_bayar'] = $obj->kd_jenis_bayar;
			$detail_bayar['nomor_bank'] = $obj->nomor_bank;
			$detail_bayar['nomor_ref'] = $obj->nomor_ref;
			if($obj->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj->tgl_jth_tempo));
			}
			$detail_bayar['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_bayar['rp_bayar'] = (int) $obj->rp_bayar_piutang;
			
			if(! $this->pp_dist_model->insert_row('sales.t_piutang_dist_bayar', $detail_bayar)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
		}
		
		$this->db->trans_commit();
                 $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("pembayaran_piutang_distribusi/print_form/" . $no_bukti) . '"}';
                  echo $result;
                 //echo '{"success":true,"errMsg":""}';
    }

    public function print_form($no_bukti = '') {
        $data = $this->pp_dist_model->get_data_print($no_bukti);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembayaranPiutangDistribusiPrint.php');
        $pdf = new PembayaranPiutangDistribusiPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail'], $data['detail_bayar']);
        $pdf->Output();
        exit;
    }
}
?>
