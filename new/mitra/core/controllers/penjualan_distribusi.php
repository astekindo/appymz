<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_distribusi extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('penjualan_distribusi_model', 'pd_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	//$no_so = 'SOD' . date('Ymd') . '-';
    	//$sequence = $this->pd_model->get_kode_sequence($no_so, 3);
    	echo '{"success":true,
				"data":{
					
					"tgl_so":"' . date('d-M-Y'). '",
					"display_grand_total":"0"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_pelanggan(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->pd_model->search_pelanggan($search, $start, $limit);
				
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->pd_model->search_produk_distribusi($search, $start, $limit);
				
        echo $result;
	}
	
	public function search_bonus(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		
		$result_bonus = $this->pd_model->get_bonus($kd_produk);
		
		$result = $this->pd_model->search_bonus_distribusi(
					$result_bonus->kd_produk_bonus,$result_bonus->kd_kategori1_bonus,$result_bonus->kd_kategori2_bonus,
					$result_bonus->kd_kategori3_bonus,$result_bonus->kd_kategori4_bonus, $search, $start, $limit);
				
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_produk($search_by = ""){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->pd_model->get_all_produk($search_by, $keyword);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->pd_model->get_all_jenis_pembayaran();
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_member(){
		$result = $this->pd_model->get_all_member();
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row_produk(){
		$search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by',TRUE)) : "";
		$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
		$qty = isset($_POST['qty']) ? $this->db->escape_str($this->input->post('qty',TRUE)) : '';
		$extra_bonus = isset($_POST['extra_bonus']) ? $this->db->escape_str($this->input->post('extra_bonus',TRUE)) : '';
        //$member = isset($_POST['member']) ? $this->db->escape_str($this->input->post('member',TRUE)) : '';
		
		$result = $this->pd_model->get_row_produk($search_by, $id);
        
		if(count($result) > 0){
			$result->hrg_jual = (int) $result->rp_jual_distribusi;
			
			//hitung diskon
			$diskon = 0;
							
			if(isset($result->disk_persen_kons1) && ($result->disk_persen_kons1 != '' || $result->disk_persen_kons1 != 0)){
				$disk_kons1 = ($result->disk_persen_kons1 * $result->hrg_jual) /100;
			}else{
				if(isset($result->disk_amt_kons1) && $result->disk_amt_kons1 != ''){
					$disk_kons1 = $result->disk_amt_kons1;
				}else{
					$disk_kons1 = 0;
				}
			}
			
			if(isset($result->disk_persen_kons2) && ($result->disk_persen_kons2 != '' || $result->disk_persen_kons2 != 0)){
				$disk_kons2 = ($result->disk_persen_kons2 * $disk_kons1) /100;
			}else{
				if(isset($result->disk_amt_kons2) && $result->disk_amt_kons2 != ''){
					$disk_kons2 = $result->disk_amt_kons2;
				}else{
					$disk_kons2 = 0;
				}
			}
			
			if(isset($result->disk_persen_kons3) && ($result->disk_persen_kons3 != '' || $result->disk_persen_kons3 != 0)){
				$disk_kons3 = ($result->disk_persen_kons3 * $disk_kons2) /100;
			}else{
				if(isset($result->disk_amt_kons3) && $result->disk_amt_kons3 != ''){
					$disk_kons3 = $result->disk_amt_kons3;
				}else{
					$disk_kons3 = 0;
				}
			}
			
			if(isset($result->disk_persen_kons4) && ($result->disk_persen_kons4 != '' || $result->disk_persen_kons4 != 0)){
				$disk_kons4 = ($result->disk_persen_kons4 * $disk_kons3) /100;
			}else{
				if(isset($result->disk_amt_kons4) && $result->disk_amt_kons4 != ''){
					$disk_kons4 = $result->disk_amt_kons4;
				}else{
					$disk_kons4 = 0;
				}
			}
			
			if(isset($result->disk_amt_kons5) && $result->disk_amt_kons5 != ''){
				$disk_kons5 = $result->disk_amt_kons5;
			}else{
				$disk_kons5 = 0;
			}
				
			$diskon = $disk_kons1 + $disk_kons2 + $disk_kons3 + $disk_kons4 + $disk_kons5;
			
			$result->disk_kons1 = $disk_kons1;
			$result->disk_kons2 = $disk_kons2;
			$result->disk_kons3 = $disk_kons3;
			$result->disk_kons4 = $disk_kons4;
			$result->disk_kons5 = $disk_kons5;
			
			$result->rp_diskon = $diskon;
			
			//hitung jumlah
			$result->rp_harga_nett = $result->hrg_jual - $diskon;
			
			//hitung total
			$result->rp_jumlah = (int) $qty * $result->rp_harga_nett;
			if($extra_bonus == '') $extra_bonus = 0;
			$result->extra_bonus = $extra_bonus;
			$result->rp_total = $result->rp_jumlah - $extra_bonus;
			
			//hitung bonus
			$qty_bonus = 0;
			/*
			if(($qty != '' || $qty != 0) && $result->qty_beli_bonus >= $qty){
				if($result->is_bonus_kelipatan){
					$qty_bonus = (floor($qty/$result->qty_beli_bonus)) * $result->qty_bonus;	
				}else{
					$qty_bonus = $result->qty_bonus;
				}				
			}
			
			
			$result->qty_bonus = (int) $qty_bonus;
			*/
		}		    
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
    	//header po
        
		//$no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : '';
		$kd_member = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
		// $kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member',TRUE)) : '';
		$tgl_so = isset($_POST['tgl_so']) ? $this->db->escape_str($this->input->post('tgl_so',TRUE)) : '';
		$kirim_so = isset($_POST['kirim_so']) ? $this->db->escape_str($this->input->post('kirim_so',TRUE)) : '';
		$kirim_alamat_so = isset($_POST['kirim_alamat_so']) ? $this->db->escape_str($this->input->post('kirim_alamat_so',TRUE)) : ''; 
		$kirim_telp_so = isset($_POST['kirim_telp_so']) ? $this->db->escape_str($this->input->post('kirim_telp_so',TRUE)) : '';
		$rp_total = isset($_POST['_rp_total']) ? $this->db->escape_str($this->input->post('_rp_total',TRUE)) : 0; 
		$rp_diskon = isset($_POST['_rp_diskon_total']) ? $this->db->escape_str($this->input->post('_rp_diskon_total',TRUE)) : 0; 
		$rp_diskon_tambahan = isset($_POST['_rp_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_rp_diskon_tambahan',TRUE)) : 0; 
		$rp_ongkos_kirim = isset($_POST['_rp_ongkos_kirim']) ? $this->db->escape_str($this->input->post('_rp_ongkos_kirim',TRUE)) : 0;
		$rp_ongkos_pasang = isset($_POST['_rp_ongkos_pasang']) ? $this->db->escape_str($this->input->post('_rp_ongkos_pasang',TRUE)) : 0;
		$rp_total_bayar = isset($_POST['_rp_total_bayar']) ? $this->db->escape_str($this->input->post('_rp_total_bayar',TRUE)) : 0;
                
                $no_so_generate = 'SOD' . date('Ymd') . '-';
                $sequence = $this->pd_model->get_kode_sequence($no_so_generate, 3);
                $no_so = "$no_so$sequence";
                
		//$rp_bank_charge = isset($_POST['_rp_bank_charge']) ? $this->db->escape_str($this->input->post('_rp_bank_charge',TRUE)) : 0;
		//$kd_voucher = isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher',TRUE)) : '';
		//$qty_voucher = isset($_POST['qty_voucher']) ? $this->db->escape_str($this->input->post('qty_voucher',TRUE)) : 0;
		
		//detail po
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		//po jenis bayar
		//$jenis_bayar = isset($_POST['jenis_bayar']) ? json_decode($this->input->post('jenis_bayar',TRUE)) : array();
		
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			if($tgl_so){
				$tgl_so = date('Y-m-d', strtotime($tgl_so));
			}
				
			if(date('n') != date('n',strtotime($tgl_so))){
				$this->db->trans_rollback();
				echo '{"success":false,"errMsg":"Bulan Pada Tanggal SO Harus Di Bulan '.date('F').'"}';
				exit;
			}
			$this->db->trans_begin();
			unset($header_so);
			$header_so['no_so'] = $no_so;
			$header_so['kd_member'] = $kd_member;
			$header_so['status'] = 0;
			$header_so['tgl_so'] = $tgl_so;
			$header_so['kirim_so'] = $kirim_so;
			$header_so['kirim_alamat_so'] = $kirim_alamat_so;
			$header_so['kirim_telp_so'] = $kirim_telp_so;
			$header_so['rp_total'] = $rp_total;
			$header_so['rp_diskon'] = $rp_diskon;
			$header_so['rp_diskon_tambahan'] = $rp_diskon_tambahan;
			$header_so['rp_ongkos_kirim'] = $rp_ongkos_kirim;
			$header_so['rp_total_bayar'] = $rp_total_bayar;
			// $header_so['rp_bank_charge'] = $rp_bank_charge;
			// $header_so['rp_ongkos_pasang'] = $rp_ongkos_pasang;
			// $header_so['kd_voucher'] = $kd_voucher;
			// $header_so['qty_voucher'] = $qty_voucher;
			$header_so['created_by'] = $this->session->userdata('username');
			$header_so['created_date'] = date('Y-m-d H:i:s');
			$header_so['updated_by'] = $this->session->userdata('username');
			$header_so['updated_date'] = date('Y-m-d H:i:s');
			$header_so['type_sales'] = 1;
			 
			$header_result = $this->pd_model->insert_row('sales.t_sales_order', $header_so);

			foreach($detail as $obj){
				unset($detail_so);
				unset($detail_bonus);
				if($obj->kd_produk != '' && $obj->qty != ''){ //yg diinsert di detail ga boleh kosong
					$detail_so['is_kirim'] = 1;
					$detail_so['is_do'] = 0;
					$detail_so['no_so'] = $no_so;
					$detail_so['kd_produk'] = $obj->kd_produk;
					$detail_so['qty'] = $obj->qty;
					$detail_so['rp_harga'] = $obj->rp_jual_distribusi;
					$detail_so['rp_ekstra_diskon'] = $obj->rp_extra_bonus;
					$detail_so['disk_persen_kons1'] = $obj->disk_persen_kons1;
					$detail_so['disk_persen_kons2'] = $obj->disk_persen_kons2;
					$detail_so['disk_persen_kons3'] = $obj->disk_persen_kons3;
					$detail_so['disk_persen_kons4'] = $obj->disk_persen_kons4;
					$detail_so['disk_amt_kons1'] = $obj->disk_amt_kons1;
					$detail_so['disk_amt_kons2'] = $obj->disk_amt_kons2;
					$detail_so['disk_amt_kons3'] = $obj->disk_amt_kons3;
					$detail_so['disk_amt_kons4'] = $obj->disk_amt_kons4;
					$detail_so['disk_amt_kons5'] = $obj->disk_amt_kons5;
					if($kd_member!=''){
						$detail_so['rp_diskon_member'] = $obj->rp_diskon;
						$detail_so['rp_diskon'] = 0;
					}else{
						$detail_so['rp_diskon_member'] = 0;
						$detail_so['rp_diskon'] = $obj->rp_diskon;
					}					
					
					//$detail_so['is_kirim'] = $obj->is_kirim;
					$detail_so['rp_harga'] = $obj->hrg_jual;
					$detail_so['rp_total'] = $obj->rp_jumlah;
					
			
					if($this->pd_model->insert_row('sales.t_sales_order_detail', $detail_so)){
						$detail_result++;
					}
					
					//jika ada bonus
					if($obj->kd_produk_bonus != '' && $obj->qty_bonus > 0){
						$detail_bonus['no_so'] = $no_so;
						$detail_bonus['kd_produk'] = $obj->kd_produk;
						$detail_bonus['kd_produk_bonus'] = $obj->kd_produk_bonus;
						$detail_bonus['qty_bonus'] = $obj->qty_bonus;					
						$this->pd_model->insert_row('sales.t_sales_order_bonus', $detail_bonus);
					}
				}
			}
			/*
			if(count($jenis_bayar) > 0){	
				foreach($jenis_bayar as $jb){
					if($jb->is_pilih){
						unset($detail_bayar);
					
						$detail_bayar['no_so'] = $no_so;
						$detail_bayar['kd_jns_pembayaran'] = $jb->kd_jns_bayar;
						$detail_bayar['rp_jumlah'] = $jb->rp_jumlah;
						$detail_bayar['rp_charge'] = $jb->rp_charge;
						$detail_bayar['rp_total'] = $jb->rp_total;
						$detail_bayar['no_kartu'] = $jb->no_kartu;
						if($jb->tgl_jatuh_tempo != ''){
							$jb->tgl_jatuh_tempo = date('Y-m-d', strtotime($jb->tgl_jatuh_tempo));
						}
						$detail_bayar['tgl_jth_tempo'] = $jb->tgl_jth_tempo;
						
						$this->pd_model->insert_row('sales.t_sales_order_bayar', $detail_bayar);
					}					
				}
			}
			*/
		}
		
		if ($header_result && $detail_result > 0) {
			$this->db->trans_commit();
			$result = '{"success":true,"errMsg":""}';
		} else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
}