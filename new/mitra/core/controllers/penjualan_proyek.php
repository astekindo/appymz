<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_proyek extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('penjualan_proyek_model', 'pp_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_so = 'SO' . date('Ymd') . '-';
    	$sequence = $this->pp_model->get_kode_sequence($no_so, 3);
    	echo '{"success":true,
				"data":{
					"no_so":"' . $no_so . $sequence . '",
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
	public function get_all_produk($search_by = ""){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->pp_model->get_all_produk($search_by, $keyword);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->pp_model->get_all_jenis_pembayaran();
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_member(){
		$result = $this->pp_model->get_all_member();
        
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
        $member = isset($_POST['member']) ? $this->db->escape_str($this->input->post('member',TRUE)) : '';
		
		$result = $this->pp_model->get_row_produk($search_by, $id);
        
		if(count($result) > 0){
			$result->hrg_jual = (int) $result->hrg_jual;
			
			//hitung diskon
			$diskon = 0;			
			$result->rp_diskon = $diskon;
			
			//hitung jumlah
			$result->rp_jumlah = $result->hrg_jual - $diskon;
			
			//hitung total
			$result->rp_total = (int) $qty * $result->rp_jumlah;
			
			//hitung bonus
			$qty_bonus = 0;
			
			if($member != ''){
				if(($qty != '' || $qty != 0) && $result->qty_beli_member >= $qty){
					if($result->is_member_kelipatan){
						$qty_bonus = (floor($qty/$result->qty_beli_member)) * $result->qty_member;	
					}else{
						$qty_bonus = $result->qty_member;
					}				
				}
			}else{
				if(($qty != '' || $qty != 0) && $result->qty_beli_bonus >= $qty){
					if($result->is_bonus_kelipatan){
						$qty_bonus = (floor($qty/$result->qty_beli_bonus)) * $result->qty_bonus;	
					}else{
						$qty_bonus = $result->qty_bonus;
					}				
				}
			}
			
			$result->qty_bonus = (int) $qty_bonus;
			
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
		$no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : '';
		$kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member',TRUE)) : '';
		$tgl_so = isset($_POST['tgl_so']) ? $this->db->escape_str($this->input->post('tgl_so',TRUE)) : '';
		$kirim_so = isset($_POST['kirim_so']) ? $this->db->escape_str($this->input->post('kirim_so',TRUE)) : '';
		$kirim_alamat_so = isset($_POST['kirim_alamat_so']) ? $this->db->escape_str($this->input->post('kirim_alamat_so',TRUE)) : ''; 
		$kirim_passwd_so = isset($_POST['kirim_passwd_so']) ? $this->db->escape_str($this->input->post('kirim_passwd_so',TRUE)) : '';
		$rp_total = isset($_POST['_rp_total']) ? $this->db->escape_str($this->input->post('_rp_total',TRUE)) : 0; 
		$rp_diskon = isset($_POST['_rp_diskon']) ? $this->db->escape_str($this->input->post('_rp_diskon',TRUE)) : 0; 
		$rp_bank_charge = isset($_POST['_rp_bank_charge']) ? $this->db->escape_str($this->input->post('_rp_bank_charge',TRUE)) : 0;
		$rp_ongkos_kirim = isset($_POST['_rp_ongkos_kirim']) ? $this->db->escape_str($this->input->post('_rp_ongkos_kirim',TRUE)) : 0;
		$rp_ongkos_pasang = isset($_POST['_rp_ongkos_pasang']) ? $this->db->escape_str($this->input->post('_rp_ongkos_pasang',TRUE)) : 0;
		$rp_total_bayar = isset($_POST['_rp_total_bayar']) ? $this->db->escape_str($this->input->post('_rp_total_bayar',TRUE)) : 0;
		$kd_voucher = isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher',TRUE)) : '';
		$qty_voucher = isset($_POST['qty_voucher']) ? $this->db->escape_str($this->input->post('qty_voucher',TRUE)) : 0;
		
		//detail po
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		//po jenis bayar
		$jenis_bayar = isset($_POST['jenis_bayar']) ? json_decode($this->input->post('jenis_bayar',TRUE)) : array();
		
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			if($tgl_so){
				$tgl_so = date('Y-m-d', strtotime($tgl_so));
			}	
			$this->db->trans_start();
			unset($header_so);
			$header_so['no_so'] = $no_so;
			$header_so['kd_member'] = $kd_member;
			$header_so['status'] = $status;
			$header_so['tgl_so'] = $tgl_so;
			$header_so['kirim_so'] = $kirim_so;
			$header_so['kirim_alamat_so'] = $kirim_alamat_so;
			$header_so['kirim_passwd_so'] = $kirim_passwd_so;
			$header_so['rp_total'] = $rp_total;
			$header_so['rp_diskon'] = $rp_diskon;
			$header_so['rp_bank_charge'] = $rp_bank_charge;
			$header_so['rp_ongkos_kirim'] = $rp_ongkos_kirim;
			$header_so['rp_ongkos_pasang'] = $rp_ongkos_pasang;
			$header_so['rp_total_bayar'] = $rp_total_bayar;
			$header_so['kd_voucher'] = $kd_voucher;
			$header_so['qty_voucher'] = $qty_voucher;
			$header_so['created_by'] = $this->session->userdata('username');
			$header_so['created_date'] = date('Y-m-d H:i:s');
			$header_so['updated_by'] = $this->session->userdata('username');
			$header_so['updated_date'] = date('Y-m-d H:i:s');
			$header_so['type_sales'] = 2;
			 
			$header_result = $this->pp_model->insert_row('sales.t_sales_order', $header_so);

			foreach($detail as $obj){
				unset($detail_so);
				unset($detail_bonus);
				if($obj->kd_produk != '' && $obj->qty != ''){ //yg diinsert di detail ga boleh kosong
					$detail_so['no_so'] = $no_so;
					$detail_so['kd_produk'] = $obj->kd_produk;
					$detail_so['qty'] = $obj->qty;
					if($kd_member!=''){
						$detail_so['rp_diskon_member'] = $obj->rp_diskon;
						$detail_so['rp_diskon'] = 0;
					}else{
						$detail_so['rp_diskon_member'] = 0;
						$detail_so['rp_diskon'] = $obj->rp_diskon;
					}					
					
					$detail_so['is_kirim'] = $obj->is_kirim;
					$detail_so['rp_harga'] = $obj->hrg_jual;
					$detail_so['rp_total'] = $obj->rp_jumlah;
					
			
					if($this->pp_model->insert_row('sales.t_sales_order_detail', $detail_so)){
						$detail_result++;
					}
					
					//jika ada bonus
					if($obj->kd_produk_bonus != '' && $obj->qty_bonus > 0){
						$detail_bonus['no_so'] = $no_so;
						$detail_bonus['kd_produk'] = $obj->kd_produk;
						$detail_bonus['kd_produk_bonus'] = $obj->kd_produk_bonus;
						$detail_bonus['qty_bonus'] = $obj->qty_bonus;					
						$this->pp_model->insert_row('sales.t_sales_order_bonus', $detail_bonus);
					}
				}
			}
			
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
						
						$this->pp_model->insert_row('sales.t_sales_order_bayar', $detail_bayar);
					}					
				}
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
}