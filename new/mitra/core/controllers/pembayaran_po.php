<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembayaran_po extends MY_Controller {  
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		//$this->load->model('pembelian_pelunasan_hutang_model', 'ppo_model');
                $this->load->model('pembayaran_po_model','ppo_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_kwit = 'PPO' . date('Ym') . '-';
    	$sequence = $this->ppo_model->get_kode_sequence($no_kwit, 3);
    	echo '{"success":true,
				"data":{
					"no_bukti":"' . $no_kwit . $sequence . '",
					"tanggal":"' . date('d-M-Y'). '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';		
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$detailbayar = isset($_POST['detailbayar']) ? json_decode($this->input->post('detailbayar',TRUE)) : array();
		
		if($tanggal != ''){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		
		if(count($detail) == 0 && count($detailbayar) == 0){	
			echo '{"success":false,"errMsg":"Data tidak lengkap"}';
			exit;
		}

		$this->db->trans_begin();
		$header_ppo['no_bukti'] = $no_bukti;
		$header_ppo['tanggal'] = $tanggal;
		$header_ppo['kd_supplier'] = $kd_supplier;
                $header_ppo['rp_bayar'] = (int) $total_bayar;
		$header_ppo['keterangan'] = $keterangan;
                $header_ppo['rp_total_bayar'] = (int) $total_bayar;
		
				 
		if( ! $this->ppo_model->insert_row('purchase.t_pembayaran_po', $header_ppo)){
			echo '{"success":false,"errMsg":"Process Failed.."}';
			$this->db->trans_rollback();
			exit;
		}
		
		foreach($detail as $obj){
			$detail_ppo['no_bukti'] = $no_bukti;
			$detail_ppo['no_po'] = $obj->no_po;
			$detail_ppo['rp_total'] = (int) $obj->rp_total_po;
			$detail_ppo['rp_bayar'] = (int) $obj->rp_pembayaran_po;
			
			
			if(! $this->ppo_model->insert_row('purchase.t_pembayaran_po_detail', $detail_ppo)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
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
			if($detail_bayar['kd_jns_bayar'] == '6' || $detail_bayar['kd_jns_bayar'] == '13'){
				if($detail_bayar['nomor_bank'] === '' || $detail_bayar['nomor_ref'] === '' || $detail_bayar['tgl_jth_tempo'] === ''){
					echo '{"success":false,"errMsg":"Pembayaran dengan Cek dan Giro, No Bank, No Ref, dan Tgl Jth Tempo tidak boleh kosong!"}';
					$this->db->trans_rollback();
					exit;
				}
			}
			if(! $this->ppo_model->insert_row('purchase.t_pembayaran_po_bayar   ', $detail_bayar)){
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
	public function get_all_invoice(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : "";
		$result = $this->ppo_model->get_all_invoice($kd_supplier);
        
        echo $result;
	}
        public function get_no_po_bysupplier(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : "";
		$result = $this->ppo_model->get_no_po_bysupplier($kd_supplier);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->ppo_model->get_all_jenis_pembayaran(true);
        
        echo $result;
	}
	
}