<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_create_invoice extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_create_invoice_model', 'kci_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_in = 'IK' . date('Ymd') . '-';
    	$sequence = $this->kci_model->get_kode_sequence($no_in, 3);
    	echo '{"success":true,
				"data":{
					"no_invoice":"' . $no_in . $sequence . '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		//header invoice
		$no_in = 'IN' . date('Ymd') . '-';
    	$sequence = $this->kci_model->get_kode_sequence($no_in, 3);
    	
		$no_invoice = $no_in . $sequence;
		$tgl_invoice = isset($_POST['tgl_invoice']) ? $this->db->escape_str($this->input->post('tgl_invoice',TRUE)) : FALSE;
		$tgl_terima_invoice = isset($_POST['tgl_terima_invoice']) ? $this->db->escape_str($this->input->post('tgl_terima_invoice',TRUE)) : FALSE;
		$tgl_jth_tempo = isset($_POST['tgl_jth_tempo']) ? $this->db->escape_str($this->input->post('tgl_jth_tempo',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : FALSE;
		$no_bukti_supplier = isset($_POST['no_bukti_supplier']) ? $this->db->escape_str($this->input->post('no_bukti_supplier',TRUE)) : FALSE;
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : FALSE;
		$rp_jumlah = isset($_POST['rp_jumlah']) ? $this->db->escape_str($this->input->post('rp_jumlah',TRUE)) : FALSE;
		$rp_diskon = isset($_POST['rp_diskon']) ? $this->db->escape_str($this->input->post('rp_diskon',TRUE)) : FALSE;
		$rp_ppn = isset($_POST['rp_ppn']) ? $this->db->escape_str($this->input->post('rp_ppn',TRUE)) : FALSE;
		$rp_total = isset($_POST['rp_total']) ? $this->db->escape_str($this->input->post('rp_total',TRUE)) : FALSE;
		$persen_diskon = isset($_POST['persen_diskon']) ? $this->db->escape_str($this->input->post('persen_diskon',TRUE)) : FALSE;
		$no_faktur_pajak = isset($_POST['no_faktur_pajak']) ? $this->db->escape_str($this->input->post('no_faktur_pajak',TRUE)) : FALSE;
		$tgl_faktur_pajak = isset($_POST['tgl_faktur_pajak']) ? $this->db->escape_str($this->input->post('tgl_faktur_pajak',TRUE)) : FALSE;
		
		//detail invoice
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		// print_r($detail);
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			$this->db->trans_start();
			$header_pr['no_invoice'] = $no_invoice;
			$header_pr['tgl_invoice'] = $tgl_invoice;
			$header_pr['tgl_terima_invoice'] = $tgl_invoice;
			$header_pr['tgl_jth_tempo'] = $tgl_jth_tempo;
			$header_pr['kd_supplier'] = $kd_supplier;
			$header_pr['no_bukti_supplier'] = $no_bukti_supplier;
			$header_pr['no_po'] = $no_po;
			$header_pr['no_faktur_pajak'] = $no_faktur_pajak;
			$header_pr['tgl_faktur_pajak'] = $tgl_faktur_pajak;
			$header_pr['rp_jumlah'] = str_replace(',','',$rp_jumlah);
			$header_pr['rp_diskon'] = str_replace(',','',$rp_diskon);
			$header_pr['rp_ppn'] = str_replace(',','',$rp_ppn);
			$header_pr['rp_total'] = str_replace(',','',$rp_total);
			$header_pr['persen_diskon'] = str_replace(',','',$persen_diskon);
			$header_pr['created_by'] = $this->session->userdata('username');
			$header_pr['created_date'] = date('Y-m-d H:i:s');
			 
			$header_result = $this->kci_model->insert_row('purchase.t_invoice', $header_pr);
			
			foreach($detail as $obj){
				unset($detail_pr);
				// if($obj->adjust != '' ){ //yg diinsert di detail ga boleh kosong
					$detail_pr['no_invoice'] = $no_invoice;
					$detail_pr['kd_produk'] = $obj->kd_produk;
					$detail_pr['no_do'] = $obj->no_do;
					$detail_pr['qty'] = $obj->qty_terima;
					$detail_pr['harga_supplier'] = $obj->pricelist;
					$detail_pr['rp_diskon1'] = $obj->disk_amt_supp1_po;
					$detail_pr['rp_diskon2'] = $obj->disk_amt_supp2_po;
					$detail_pr['rp_diskon3'] = $obj->disk_amt_supp3_po;
					$detail_pr['rp_diskon4'] = $obj->disk_amt_supp4_po;
					$detail_pr['rp_total_diskon'] = $obj->rp_diskon;
					$detail_pr['rp_dpp'] = $obj->dpp_po;
					$detail_pr['rp_jumlah'] = $obj->rp_total_po;
					$detail_pr['rp_ajd_jumlah'] = $obj->adjust;
			
					if($this->kci_model->insert_row('purchase.t_invoice_detail', $detail_pr)){
						$detail_result++;
					}
				// }
			}
			$this->db->trans_complete();
		}
		
		if ($header_result && $detail_result > 0) {
			$result = '{"success":true,"errMsg":""}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_po(){
		$result = $this->kci_model->get_all_po();
        
        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_po_detail(){
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : "";
		$result = $this->kci_model->get_po_detail($no_po);
        
        echo $result;
	}
	
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->kci_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	
	public function search_no_do_by_supplier(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';

		$result = $this->kci_model->search_no_do_by_supplier($kd_supplier, $no_do);
				
        echo $result;
	}
	
	public function search_no_do_by_supplier_no_do(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';
		
		$hasil = $this->kci_model->search_no_do_by_supplier_no_do($kd_supplier, $no_do);
		
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_supp1_po != '' || $result->disk_persen_supp1_po != 0){
				$diskon_supp1 = ($result->disk_persen_supp1_po * $result->hrg_supplier) /100;
			}else{
				if($result->disk_amt_supp1_po != ''){
					$diskon_supp1_po = $result->disk_amt_supp1_po;
				}else{
					$diskon_supp1_po = 0;
				}
			}
			
			if($result->disk_persen_supp2_po != '' || $result->disk_persen_supp2_po != 0){
				$diskon_supp2_po = ($result->disk_persen_supp2_po * $diskon_supp1_po) /100;
			}else{
				if($result->disk_amt_supp1_po != ''){
					$diskon_supp2_po = $result->disk_amt_supp2_po;
				}else{
					$diskon_supp2_po = 0;
				}
			}
			
			if($result->disk_persen_supp3_po != '' || $result->disk_persen_supp3_po != 0){
				$diskon_supp3_po = ($result->disk_persen_supp3_po * $diskon_supp2_po) /100;
			}else{
				if($result->disk_amt_supp1_po != ''){
					$diskon_supp3_po = $result->disk_amt_supp3_po;
				}else{
					$diskon_supp3_po = 0;
				}
			}
			
			if($result->disk_persen_supp4_po != '' || $result->disk_persen_supp4_po != 0){
				$diskon_supp4_po = ($result->disk_persen_supp4_po * $diskon_supp3_po) /100;
			}else{
				if($result->disk_amt_supp1_po != ''){
					$diskon_supp4_po = $result->disk_amt_supp1_po;
				}else{
					$diskon_supp4_po = 0;
				}
			}
			
			if($result->disk_amt_supp5_po != ''){
				$disk_amt_supp5_po = $result->disk_amt_supp5_po;
			}else{
				$disk_amt_supp5_po = 0;
			}
			
			$diskon = $diskon_supp1_po + $diskon_supp2_po + $diskon_supp3_po + $diskon_supp4_po + $disk_amt_supp5_po;
			
			//diskon Rp
			$result->disk_supp1_po = $diskon_supp1_po;
			$result->disk_supp2_po = $diskon_supp2_po;
			$result->disk_supp3_po = $diskon_supp3_po;
			$result->disk_supp4_po = $diskon_supp4_po;
			$result->disk_amt_supp5_po = $disk_amt_supp5_po;
			$result->rp_disk_po = $diskon;
			
			$dpp_po = ($result->pricelist - $diskon) * $result->qty_terima;
			$rp_total_po = ($result->pricelist * $result->qty_terima) - $diskon;
			
			$result->dpp_po = $dpp_po;
			$result->rp_total_po = $rp_total_po;
			
		}
		echo '{success:true,data:['.json_encode($result).']}';
       
	}	
	
	public function get_no_do(){
		$result = $this->kci_model->get_no_do();
				
        echo $result;
	}
	
	
	public function search_produk_by_supplier(){			
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
		$result = $this->kci_model->search_produk_by_supplier($kd_supplier);
				
        echo $result;
	}
}