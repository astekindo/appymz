<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Faktur_penjualan extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('faktur_penjualan_model', 'fj_model');
    }
    
    public function search_no_sj_by_pelanggan(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
                $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->fj_model->search_no_sj_by_pelanggan($kd_pelanggan, $no_so, $search);		
                echo $result;
	}
    public function search_no_do_by_pelanggan_no_sj(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
		$no_sj = isset($_POST['no_sj']) ? $this->db->escape_str($this->input->post('no_sj',TRUE)) : '';
		
		$hasil = $this->fj_model->search_no_do_by_pelanggan_no_sj($kd_pelanggan, $no_sj);
		 //print_r($hasil);
		$results = array();
		foreach($hasil as $result){
                    $qty_sj = $result->qty_sj;
                    $rp_net_harga_jual = $result->rp_net_harga_jual;
                    $result->rp_jumlah= $rp_net_harga_jual * $qty_sj;
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen1 != '' && $result->disk_persen1 != 0){
				$diskon_supp1 = ($result->disk_persen1 * $result->rp_harga_jual) /100;
				$disk_grid_supp1 = number_format($result->disk_persen1).'%';
			}else{
				if($result->disk_amt1!= '' && $result->disk_amt1 != 0){
					$diskon_supp1_po = $result->disk_amt1;
					$disk_grid_supp1 = 'Rp. '.number_format($diskon_supp1_po);
				}else{
					$diskon_supp1_po = 0;
					$disk_grid_supp1 = '0%';
				}
			}
			
			if($result->disk_persen2 != '' && $result->disk_persen2 != 0){
				$diskon_supp2_po = ($result->disk_persen2 * $diskon_supp1_po) /100;
				$disk_grid_supp2 = number_format($result->disk_persen2).'%';
			}else{
				if($result->disk_amt2 != '' && $result->disk_amt2 != 0){
					$diskon_supp2_po = $result->disk_amt2;
					$disk_grid_supp2 = 'Rp. '.number_format($diskon_supp2_po);
				}else{
					$diskon_supp2_po = 0;
					$disk_grid_supp2 = '0%';
				}
			}
			
			if($result->disk_persen3 != '' && $result->disk_persen3 != 0){
				$diskon_supp3_po = ($result->disk_persen3 * $diskon_supp2_po) /100;
				$disk_grid_supp3 = number_format($result->disk_persen3).'%';
			}else{
				if($result->disk_amt3 != '' && $result->disk_amt3 != 0){
					$diskon_supp3_po = $result->disk_amt3;
					$disk_grid_supp3 = 'Rp. '.number_format($diskon_supp3_po);
				}else{
					$diskon_supp3_po = 0;
					$disk_grid_supp3 = '0%';
				}
			}
			
			if($result->disk_persen4 != '' && $result->disk_persen4!= 0){
				$diskon_supp4_po = ($result->disk_persen4 * $diskon_supp3_po) /100;
				$disk_grid_supp4 = number_format($result->disk_persen4).'%';
			}else{
				if($result->disk_amt4 != '' && $result->disk_amt4 != 0){
					$diskon_supp4_po = $result->disk_amt4;
					$disk_grid_supp4 = 'Rp. '.number_format($diskon_supp4_po);
				}else{
					$diskon_supp4_po = 0;
					$disk_grid_supp4 = '0%';
				}
			}
			
			if($result->disk_amt5 != ''){
				$diskon_amt_supp5_po = $result->disk_amt5;
				$disk_grid_supp5 = 'Rp. '.number_format($diskon_amt_supp5_po);
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
			$harga_net = $result->pricelist - $result->rp_disk_po;
                        $result->harga_net= $harga_net;
                        if ($pkp === 'YA'){
                        $harga_net_ect = $harga_net / 1.1;
                        }else {
                           $harga_net_ect = $harga_net; 
                        }
                        $result->harga_net_ect= $harga_net_ect;
			$result->dpp_po = $result->qty_terima * $harga_net_ect;
			$result->rp_total_po = $result->qty_terima * $harga_net_ect;
			$results[] = $result;
                        //print_r($results[]);
		}
		echo '{success:true,data:'.json_encode($results).'}';
	}
        
   public function update_row(){
		//header invoice
                $tgl_faktur = isset($_POST['tgl_faktur']) ? $this->db->escape_str($this->input->post('tgl_faktur',TRUE)) : FALSE;
                $current_date = date('Ymd', strtotime($tgl_faktur));
		$no_fj = 'FJ' . $current_date .'-';
                $sequence = $this->fj_model->get_kode_sequence($no_fj, 3);
                $no_faktur = $no_fj . $sequence;
                
                $no_urut = 'SOS' . $current_date .'-';
                $generate = $this->fj_model->get_kode_sequence($no_urut, 3);
                $no_urut_so = $no_urut . $generate;
                
                $tgl_faktur = date('Y-m-d', strtotime($tgl_faktur));
                $tgl_jth_tempo = isset($_POST['tgl_jth_tempo']) ? $this->db->escape_str($this->input->post('tgl_jth_tempo',TRUE)) : FALSE;
                $tgl_jth_tempo = date('Y-m-d', strtotime($tgl_jth_tempo));
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
		$no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : FALSE;
		$rp_jumlah = isset($_POST['rp_jumlah']) ? $this->db->escape_str($this->input->post('rp_jumlah',TRUE)) : FALSE;
                $rp_dpp = isset($_POST['rp_dpp']) ? $this->db->escape_str($this->input->post('rp_dpp',TRUE)) : FALSE;
		$rp_faktur_net = isset($_POST['total']) ? $this->db->escape_str($this->input->post('total',TRUE)) : FALSE;
                $tagihan = isset($_POST['tagihan']) ? $this->db->escape_str($this->input->post('tagihan',TRUE)) : FALSE;
		$rp_ppn = isset($_POST['rp_ppn']) ? $this->db->escape_str($this->input->post('rp_ppn',TRUE)) : FALSE;
		$uang_muka = isset($_POST['rp_uang_muka']) ? $this->db->escape_str($this->input->post('rp_uang_muka',TRUE)) : FALSE;
		$cash_diskon = isset($_POST['cash_diskon']) ? $this->db->escape_str($this->input->post('cash_diskon',TRUE)) : FALSE;
		$top = isset($_POST['top']) ? $this->db->escape_str($this->input->post('top',TRUE)) : FALSE;
                $kd_npwp = isset($_POST['kd_npwp']) ? $this->db->escape_str($this->input->post('kd_npwp',TRUE)) : FALSE;
		
                //detail faktur
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
                //detail Uang muka
		$detail_dp = isset($_POST['detail_dp']) ? json_decode($this->input->post('detail_dp',TRUE)) : array();
		$header_result = FALSE;
		$detail_result = 0;
		
                $tgl_fk = strtotime($tgl_faktur);
                foreach($detail as $obj){
                    $tgl_sj = strtotime($obj->tanggal);
                    if($tgl_fk < $tgl_sj){
                        echo '{"success":false,"errMsg":"Tanggal Faktur tidak Boleh Lebih Kecil Dari Tanggal SJ"}';
                        $this->db->trans_rollback();
                        exit;//error
                    }
                }
		if(count($detail) > 0){	
		
			$this->db->trans_start();
			$header_pr['no_faktur'] = $no_faktur;
			$header_pr['kd_pelanggan'] = $kd_pelanggan ;
                        $header_pr['no_so'] = $no_so;
			$header_pr['tgl_faktur'] = $tgl_faktur;
			$header_pr['tgl_jatuh_tempo'] = $tgl_jth_tempo;
			$header_pr['rp_faktur'] = str_replace(',','',$rp_jumlah);
			$header_pr['rp_dpp'] = str_replace(',','',$rp_dpp);
			$header_pr['rp_ppn'] = str_replace(',','',$rp_ppn);
			$header_pr['rp_faktur_net'] = str_replace(',','',$rp_faktur_net);
			$header_pr['rp_uang_muka'] = str_replace(',','',$uang_muka);
                        $header_pr['cash_diskon'] = str_replace(',','',$cash_diskon);
                        $header_pr['rp_total_faktur'] = str_replace(',','',$rp_jumlah);
                        $header_pr['no_urut_so'] = $no_urut_so;
                        $header_pr['rp_bayar'] = 0;
                        $header_pr['rp_kurang_bayar'] = str_replace(',','',$tagihan);
			$header_pr['created_by'] = $this->session->userdata('username');
			$header_pr['created_date'] = date('Y-m-d H:i:s');
                        $header_pr['top'] = $top;
                        $header_pr['kd_npwp'] = $kd_npwp;
			 
			$header_result = $this->fj_model->insert_row('sales.t_faktur_jual', $header_pr);
			
			foreach($detail as $obj){
				unset($detail_pr);
					//yg diinsert di detail ga boleh kosong
					$detail_pr['no_faktur'] = $no_faktur;
					$detail_pr['kd_produk'] = $obj->kd_produk;
					$detail_pr['no_sj'] = $obj->no_sj;
					$detail_pr['qty'] = $obj->qty_sj;
					$detail_pr['no_do'] = $obj->no_do;
					$detail_pr['rp_harga_jual'] = $obj->rp_harga_jual;
					$detail_pr['rp_total_diskon'] = $obj->rp_diskon;
					$detail_pr['rp_harga_net'] = $obj->rp_net_harga_jual;
					$detail_pr['rp_jumlah'] = $obj->rp_jumlah;
					$detail_pr['rp_diskon_satuan'] = $obj->rp_diskon_satuan;
					
										
					
					if($this->fj_model->insert_row('sales.t_faktur_jual_detail', $detail_pr)){
						$detail_result++;
                                                
                                                $sql = "UPDATE sales.t_surat_jalan_dist_detail SET is_faktur = 1 WHERE no_sj = '" . $obj->no_sj . "'";
						$this->fj_model->query_update($sql);
					
					}
                                        
                                        unset($data_sj);
                                        $data_sj['is_faktur'] = 1; 
                                        $this->fj_model->update_surat_jalan_dist($obj->no_sj, $data_sj);
			}
                        foreach($detail_dp as $obj){
				$sql = "UPDATE sales.t_uang_muka_detail 
                                        SET rp_uang_muka_terpakai = coalesce(rp_uang_muka_terpakai,0) + " . $obj->uang_muka_sisa . " 
                                        WHERE no_bayar = '" . $obj->no_bayar . "'";
				$this->fj_model->query_update($sql);
                        }
                        
                        
			$this->db->trans_complete();
		}
		
		if ($header_result && $detail_result > 0) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("faktur_penjualan/print_form/" . $no_faktur) . '"}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
    public function print_form($no_faktur){
		$data = $this->fj_model->get_data_print($no_faktur);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/FakturPenjualanPrint.php');
		$pdf = new FakturPenjualanPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
                $pdf->Output();	
		exit;
	}
     public function search_do() {
        $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->fj_model->search_do($kd_pelanggan,$search, $start, $limit);


        echo $result;
    }
    public function search_uang_muka() {
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->fj_model->search_uang_muka($no_so,$search, $start, $limit);


        echo $result;
    }
}
