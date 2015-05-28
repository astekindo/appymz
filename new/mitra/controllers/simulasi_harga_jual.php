<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Simulasi_harga_jual extends MY_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model('simulasi_harga_jual_model', 'shj_model');
    }

	public function get_all_produk($search_by = ""){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->shj_model->get_all_produk($search_by, $keyword);
        
        echo $result;
	}

	public function search_produk(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
                $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
                
		$data_result = $this->shj_model->search_produk($kd_supplier,$search, $start, $limit);
		$hasil = $data_result['rows'];
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
			$total_diskon_kons = 0;
			$total_diskon_memb = 0;
							
			if($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0){
				$total_diskon_kons = $result->rp_jual_supermarket-($result->rp_jual_supermarket*($result->disk_persen_kons1/100));
				$diskon_kons1 = $result->disk_persen_kons1;
				$result->diskon1 = $diskon_kons1."%";
			}else{
				if($result->disk_amt_kons1 != ''){
					$total_diskon_kons = $result->rp_jual_supermarket-$result->disk_amt_kons1;
					$diskon_kons1 = $result->disk_amt_kons1;
					$result->diskon1 = "Rp. ".$diskon_kons1;
				}else{
					$diskon_kons1 = 0;
				}
			}
			
			if($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons2/100));
				$diskon_kons2 = $result->disk_persen_kons2;
				$result->diskon2 = $diskon_kons2."%";
			}else{
				if($result->disk_amt_kons2 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons2;
					$diskon_kons2 = $result->disk_amt_kons2;
					$result->diskon2 = "Rp. ".$diskon_kons2;
				}else{
					$diskon_kons2 = 0;
				}
			}
			
			if($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons3/100));
				$diskon_kons3 = $result->disk_persen_kons3;
				$result->diskon3 = $diskon_kons3."%";
			}else{
				if($result->disk_amt_kons3 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons3;
					$diskon_kons3 = $result->disk_amt_kons3;
					$result->diskon3 = "Rp. ".$diskon_kons3;
				}else{
					$diskon_kons3 = 0;
				}
			}
			
			if($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons4/100));
				$diskon_kons4 = $result->disk_persen_kons4;
				$result->diskon4 = $diskon_kons4."%";
			}else{
				if($result->disk_amt_kons4 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons4;
					$diskon_kons4 = $result->disk_amt_kons4;
					$result->diskon4 = "Rp. ".$diskon_kons4;
				}else{
					$diskon_kons4 = 0;
				}
			}
			
			if($result->disk_amt_kons5 != ''){
				$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons5;
				$diskon_amt_kons5 = $result->disk_amt_kons5;
                                $result->diskon5 = "Rp. ".$diskon_amt_kons5;
			}else{
				$diskon_amt_kons5 = 0;
                                $result->diskon5 = '0';
			}
			
			$result->net_price_jual = $total_diskon_kons;
			if ($result->pct_margin != '' && $result->pct_margin != 0){
                            $result->margin = $result->pct_margin."%";
                        }else {
                            $result->margin ="Rp. ".$result->rp_margin;
                        }
                       
			
			$result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
			$margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
			$result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
			$result->rp_het_cogs = 0;
			if(!$result->rp_cogs && $result->rp_cogs != 0){
				$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
			}
			$results[] = $result;
		}
		echo '{success:true,record:'.$data_result['total'].',data:'.json_encode($results).'}';
	}

	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->shj_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}

	public function search_produk_by_supplier(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';			
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';			
		$action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action',TRUE)) : '';
		$sender = isset($_POST['sender']) ? $this->db->escape_str($this->input->post('sender',TRUE)) : '';
                $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : '';
                
		$results = $this->shj_model->search_produk_by_supplier($kd_supplier, $sender, $search, $start, $limit);
		
		$result = '{"success":true,"data":'.json_encode($results).'}';
		
		if($action == 'validate'){
			$validate = $this->shj_model->validate_pr_by_kd_produk($kd_produk,$kd_peruntukan);
			
			if($validate['pr']->sum != 0){
				$result = '{"success":false,"errMsg":"Ada Outstanding PR dengan Kode Produk '.$kd_produk.' sebanyak '.$validate['pr']->sum.'"}';
			}
//			if($validate['peruntukan']->harga_jual == '' or $validate['peruntukan']->harga_jual == 0){
//				$result = '{"success":false,"errMsg":"Harga Jual Untuk Kode Produk '.$kd_produk.' masih kosong"}';				
//			}
			
		}else if($action == 'validate_po'){
			$validate = $this->shj_model->validate_pr_on_po($kd_produk,$kd_peruntukan);
			
			if($validate['po']->sum != 0){
				$result = '{"success":true,"errMsg":"Ada Outstanding PO dengan Kode Produk '.$kd_produk.' sebanyak '.$validate['po']->sum.'"}';
			}
			
		}
		
        echo $result;
	}
	
	public function print_form($no_ro = ''){
		
		$this->shj_model->setCetakKe($no_ro);

		$data = $this->shj_model->get_data_print($no_ro);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PembelianCreateRequestPrint.php');
		$pdf = new PembelianCreateRequestPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
}
