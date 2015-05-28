<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_create_invoice extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_create_invoice_model', 'pci_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_in = 'IN' . date('Ymd') . '-';
    	//$sequence = $this->pci_model->get_kode_sequence($no_in, 3);
        $sequence = '';
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
                $tgl_terima_invoice = isset($_POST['tgl_terima_invoice']) ? $this->db->escape_str($this->input->post('tgl_terima_invoice',TRUE)) : FALSE;
		$no_in = 'IN' . date('Ymd') . '-';
                $sequence = $this->pci_model->get_kode_sequence($no_in, 3);
    	
		$no_invoice = $no_in . $sequence;
		$tgl_invoice = isset($_POST['tgl_invoice']) ? $this->db->escape_str($this->input->post('tgl_invoice',TRUE)) : FALSE;
		
		$tgl_jth_tempo = isset($_POST['tgl_jth_tempo']) ? $this->db->escape_str($this->input->post('tgl_jth_tempo',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : FALSE;
		$no_bukti_supplier = isset($_POST['no_bukti_supplier']) ? $this->db->escape_str($this->input->post('no_bukti_supplier',TRUE)) : FALSE;
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : FALSE;
		$rp_jumlah = isset($_POST['rp_jumlah']) ? $this->db->escape_str($this->input->post('rp_jumlah',TRUE)) : FALSE;
		$rp_diskon = isset($_POST['rp_diskon']) ? $this->db->escape_str($this->input->post('rp_diskon',TRUE)) : FALSE;
		$rp_ppn = isset($_POST['rp_ppn']) ? $this->db->escape_str($this->input->post('rp_ppn',TRUE)) : FALSE;
		$rp_total = isset($_POST['rp_total_grand']) ? $this->db->escape_str($this->input->post('rp_total_grand',TRUE)) : FALSE;
		$persen_diskon = isset($_POST['persen_diskon']) ? $this->db->escape_str($this->input->post('persen_diskon',TRUE)) : FALSE;
		$no_faktur_pajak = isset($_POST['no_faktur_pajak']) ? $this->db->escape_str($this->input->post('no_faktur_pajak',TRUE)) : FALSE;
		$tgl_faktur_pajak = isset($_POST['tgl_faktur_pajak']) ? $this->db->escape_str($this->input->post('tgl_faktur_pajak',TRUE)) : FALSE;
		$top = isset($_POST['top']) ? $this->db->escape_str($this->input->post('top',TRUE)) : FALSE;
		//detail invoice
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		// print_r($detail);
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			$this->db->trans_start();
			$header_pr['no_invoice'] = $no_invoice;
			$header_pr['tgl_invoice'] = $tgl_invoice;
			$header_pr['tgl_terima_invoice'] = $tgl_terima_invoice;
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
                        $header_pr['top'] = $top;
			 
			$header_result = $this->pci_model->insert_row('purchase.t_invoice', $header_pr);
			
			foreach($detail as $obj){
				unset($detail_pr);
					//yg diinsert di detail ga boleh kosong
					$detail_pr['no_invoice'] = $no_invoice;
					$detail_pr['kd_produk'] = $obj->kd_produk;
					$detail_pr['no_do'] = $obj->no_do;
					$detail_pr['qty'] = $obj->qty_terima;
					$detail_pr['harga_supplier'] = $obj->pricelist;
					$detail_pr['disk_persen_supp1'] = $obj->disk_persen_supp1_po;
					$detail_pr['disk_persen_supp2'] = $obj->disk_persen_supp2_po;
					$detail_pr['disk_persen_supp3'] = $obj->disk_persen_supp3_po;
					$detail_pr['disk_persen_supp4'] = $obj->disk_persen_supp4_po;
					$detail_pr['disk_amt_supp1'] = $obj->disk_amt_supp1_po;
					$detail_pr['disk_amt_supp2'] = $obj->disk_amt_supp2_po;
					$detail_pr['disk_amt_supp3'] = $obj->disk_amt_supp3_po;
					$detail_pr['disk_amt_supp4'] = $obj->disk_amt_supp4_po;
					$detail_pr['disk_amt_supp5'] = $obj->disk_amt_supp5_po;
					$detail_pr['rp_diskon1'] = $obj->disk_amt_supp1_po;
					$detail_pr['rp_diskon2'] = $obj->disk_amt_supp2_po;
					$detail_pr['rp_diskon3'] = $obj->disk_amt_supp3_po;
					$detail_pr['rp_diskon4'] = $obj->disk_amt_supp4_po;
					$detail_pr['rp_total_diskon'] = $obj->rp_diskon;

					$detail_pr['rp_dpp'] = $obj->dpp_po - ($obj->rp_diskon * $obj->qty_terima);
					$detail_pr['rp_jumlah'] = $obj->rp_total_po;
					$detail_pr['rp_ajd_jumlah'] = $obj->adjust;
					
					if($obj->adjust != '0'){
						if($this->pci_model->update_row_hpp($obj->adjust,$obj->kd_produk,$kd_peruntukan,$no_invoice)){
							$no_bukti = "MB".date('Ym').'-'.$this->pci_model->get_kode_sequence("MB".date('Ym'),5);
							if($this->pci_model->insert_row_histo($no_bukti)){
								$detail_result++;
							}
						}
						$cogs_result = $this->pci_model->get_cogs_on_hpp($obj->kd_produk);
						
						$dataUpdate = array(
										"rp_cogs" => $cogs_result->rp_cogs,
										"rp_het_cogs" => $cogs_result->rp_het
									); 
						// print_r($dataUpdate);
						// exit;
						if($this->pci_model->update_row_produk($obj->kd_produk,$dataUpdate)){
							$detail_result++;
						}else
							$detail_result = 0;
					}
					
					if($this->pci_model->insert_row('purchase.t_invoice_detail', $detail_pr)){
						$detail_result++;
					}
                                        
                                        unset($data_receive);
                                        $data_receive['is_invoice'] = 1; 
                                        $this->pci_model->update_receive_order($obj->no_do, $data_receive);
			}
                        
                        
                        
			$this->db->trans_complete();
		}
		
		if ($header_result && $detail_result > 0) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_create_invoice/print_form/" . $no_invoice) . '"}';
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
		$result = $this->pci_model->get_all_po();
        
        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_po_detail(){
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : "";
		$result = $this->pci_model->get_po_detail($no_po);
        
        echo $result;
	}
	
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->pci_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	
	public function search_no_do_by_supplier(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';

		$result = $this->pci_model->search_no_do_by_supplier($kd_supplier, $no_do);
				
        echo $result;
	}
	
	public function search_no_do_by_supplier_no_do(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';
		
		$hasil = $this->pci_model->search_no_do_by_supplier_no_do($kd_supplier, $no_do);
		// print_r($hasil);
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_supp1_po != '' && $result->disk_persen_supp1_po != 0){
				$diskon_supp1 = ($result->disk_persen_supp1_po * $result->hrg_supplier) /100;
				$disk_grid_supp1 = number_format($result->disk_persen_supp1_po).'%';
			}else{
				if($result->disk_amt_supp1_po != '' && $result->disk_amt_supp1_po != 0){
					$diskon_supp1_po = $result->disk_amt_supp1_po;
					$disk_grid_supp1 = 'Rp. '.number_format($diskon_supp1_po);
				}else{
					$diskon_supp1_po = 0;
					$disk_grid_supp1 = '0%';
				}
			}
			
			if($result->disk_persen_supp2_po != '' && $result->disk_persen_supp2_po != 0){
				$diskon_supp2_po = ($result->disk_persen_supp2_po * $diskon_supp1_po) /100;
				$disk_grid_supp2 = number_format($result->disk_persen_supp2_po).'%';
			}else{
				if($result->disk_amt_supp2_po != '' && $result->disk_amt_supp2_po != 0){
					$diskon_supp2_po = $result->disk_amt_supp2_po;
					$disk_grid_supp2 = 'Rp. '.number_format($diskon_supp2_po);
				}else{
					$diskon_supp2_po = 0;
					$disk_grid_supp2 = '0%';
				}
			}
			
			if($result->disk_persen_supp3_po != '' && $result->disk_persen_supp3_po != 0){
				$diskon_supp3_po = ($result->disk_persen_supp3_po * $diskon_supp2_po) /100;
				$disk_grid_supp3 = number_format($result->disk_persen_supp3_po).'%';
			}else{
				if($result->disk_amt_supp3_po != '' && $result->disk_amt_supp3_po != 0){
					$diskon_supp3_po = $result->disk_amt_supp3_po;
					$disk_grid_supp3 = 'Rp. '.number_format($diskon_supp3_po);
				}else{
					$diskon_supp3_po = 0;
					$disk_grid_supp3 = '0%';
				}
			}
			
			if($result->disk_persen_supp4_po != '' && $result->disk_persen_supp4_po != 0){
				$diskon_supp4_po = ($result->disk_persen_supp4_po * $diskon_supp3_po) /100;
				$disk_grid_supp4 = number_format($result->disk_persen_supp4_po).'%';
			}else{
				if($result->disk_amt_supp4_po != '' && $result->disk_amt_supp4_po != 0){
					$diskon_supp4_po = $result->disk_amt_supp4_po;
					$disk_grid_supp4 = 'Rp. '.number_format($diskon_supp4_po);
				}else{
					$diskon_supp4_po = 0;
					$disk_grid_supp4 = '0%';
				}
			}
			
			if($result->diskon_amt_supp5_po != ''){
				$diskon_amt_supp5_po = $result->diskon_amt_supp5_po;
				$disk_grid_supp5 = 'Rp. '.number_format($diskon_supp5_po);
			}else{
				$diskon_amt_supp5_po = 0;
				$disk_grid_supp5 = '0%';
			}
			
			 
			$diskon = $diskon_supp1_po + $diskon_supp2_po + $diskon_supp3_po + $diskon_supp4_po + $diskon_amt_supp5_po;
			
			//diskon Rp
			$result->disk_grid_supp1 = $disk_grid_supp1;
			$result->disk_grid_supp2 = $disk_grid_supp2;
			$result->disk_grid_supp3 = $disk_grid_supp3;
			$result->disk_grid_supp4 = $disk_grid_supp4;
			$result->disk_grid_supp5 = $disk_grid_supp5;
			$result->disk_supp1_po = $diskon_supp1_po;
			$result->disk_supp2_po = $diskon_supp2_po;
			$result->disk_supp3_po = $diskon_supp3_po;
			$result->disk_supp4_po = $diskon_supp4_po;
			$result->disk_supp5_po = $diskon_supp5_po;
			
			$dpp_po = ($result->dpp_po) * $result->qty_terima;
			$rp_total_po = $dpp_po;
			//($result->dpp_po) - $diskon;
			
			$result->dpp_po = $dpp_po;
			$result->rp_total_po = $rp_total_po;
			$results[] = $result;
		}
		echo '{success:true,data:'.json_encode($results).'}';
	}	
	
	public function get_no_do(){
		$result = $this->pci_model->get_no_do();
				
        echo $result;
	}
	
	
	public function search_produk_by_supplier(){			
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
		$result = $this->pci_model->search_produk_by_supplier($kd_supplier);
				
        echo $result;
	}
	
	public function print_form($no_inv){
		$data = $this->pci_model->get_data_print($no_inv);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PembelianCreateInvoicePrint.php');
		$pdf = new PembelianCreateInvoicePrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
}
