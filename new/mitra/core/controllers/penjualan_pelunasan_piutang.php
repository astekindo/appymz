<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_pelunasan_piutang extends MY_Controller {  
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('penjualan_pelunasan_piutang_model', 'ppp_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_kwit = 'PP' . date('Ym') . '-';
    	$sequence = $this->ppp_model->get_kode_sequence($no_kwit, 3);
    	echo '{"success":true,
				"data":{
					
					"tanggal":"' . date('d-M-Y'). '"
				}
			}';
    }
	
	public function get_rows(){
		$no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : '';
		
		$result = $this->ppp_model->get_rows($no_faktur);
		
		echo $result;
		
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		//$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';		
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		
		$total_faktur = isset($_POST['_total_faktur']) ? $this->db->escape_str($this->input->post('_total_faktur',TRUE)) : 0;
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$total_potongan = isset($_POST['_total_potongan']) ? $this->db->escape_str($this->input->post('_total_potongan',TRUE)) : 0;
		$total_dibayar = isset($_POST['_total_dibayar']) ? $this->db->escape_str($this->input->post('_total_dibayar',TRUE)) : 0;
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
                $no_kwit = 'PP' . date('Ym') . '-';
                $sequence = $this->ppp_model->get_kode_sequence($no_kwit, 3);
                
                $no_bukti = "$no_kwit$sequence";
		$this->db->trans_begin();
		$header_ppp['no_pelunasan_piutang'] = $no_bukti;
		$header_ppp['tanggal'] = $tanggal;
		$header_ppp['no_faktur'] = $no_faktur;
		$header_ppp['rp_faktur'] = (int) $total_faktur;
		$header_ppp['rp_extra_diskon'] = (int) $total_potongan;		
		$header_ppp['rp_pelunasan'] = (int) $total_bayar;		
		$header_ppp['rp_sisa_piutang'] = (int) $selisih;
		//$header_ppp['rp_sisa_piutang'] = 0;
		$header_ppp['keterangan'] = $keterangan;
				 
		if( ! $this->ppp_model->insert_row('sales.t_piutang_pelunasan', $header_ppp)){
			echo '{"success":false,"errMsg":"Process Failed.."}';
			$this->db->trans_rollback();
			exit;
		}
		
		$sisa_faktur = 0;
		foreach($detail as $obj){
                        $rp_piutang = (int) $obj->rp_piutang;
			$detail_ppp['rp_piutang'] = (int) $obj->sisa_faktur;
			if((int) $obj->sisa_faktur < 0){
				echo '{"success":false,"errMsg":"Rp Bayar Tidak Boleh Lebih Besar dari Rp Piutang"}';
				$this->db->trans_rollback();
				exit;
			}
			if($this->ppp_model->select_detail($no_faktur,$obj->kd_produk)){
				if(! $this->ppp_model->update_detail($no_faktur,$obj->kd_produk,$detail_ppp)){
					echo '{"success":false,"errMsg":"Process Failed.."}';
					$this->db->trans_rollback();
					exit;
				}
			}else{
				$detail_ppp['no_pelunasan_piutang'] = $no_bukti;
				$detail_ppp['no_faktur'] = $no_faktur;
				$detail_ppp['kd_produk'] = $obj->kd_produk;
                                $detail_ppp['rp_piutang'] = $rp_piutang;
				if(! $this->ppp_model->insert_row('sales.t_piutang_detail', $detail_ppp)){
					echo '{"success":false,"errMsg":"Process Failed.."}';
					$this->db->trans_rollback();
					exit;
				}
			}
		
			$sisa_faktur = $sisa_faktur + $obj->sisa_faktur;
		
		}
		if(! $this->ppp_model->sisa_faktur($no_faktur,$sisa_faktur)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
			
		if(! $this->ppp_model->update_pelunasan($no_faktur,$total_bayar)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
                if(! $this->ppp_model->update_sales_order($no_faktur,$selisih)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
			
		foreach($detailbayar as $obj){
			$detail_bayar['no_pelunasan_piutang'] = $no_bukti;
			$detail_bayar['kd_jns_bayar'] = $obj->kd_jenis_bayar;
			$detail_bayar['nomor_bank'] = $obj->nomor_bank;
			$detail_bayar['nomor_ref'] = $obj->nomor_ref;
			if($obj->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj->tgl_jth_tempo));
			}
			$detail_bayar['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_bayar['rp_bayar'] = (int) $obj->rp_bayar;
			
			if(! $this->ppp_model->insert_row('sales.t_piutang_bayar', $detail_bayar)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
		}
		
		$this->db->trans_commit();

		echo '{"success":true,"errMsg":""}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_faktur(){
		// $kd_member = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : "";
		$result = $this->ppp_model->get_all_faktur();
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->ppp_model->get_all_jenis_pembayaran(true);
        
        echo $result;
	}
	
	public function search_pelanggan(){
		echo $this->ppp_model->search_pelanggan();
		
	}
	
}