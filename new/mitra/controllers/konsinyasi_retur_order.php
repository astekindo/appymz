<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_retur_order extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_retur_order_model', 'kretur_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_rb = 'RS' . date('Ym') . '-';
    	$sequence = $this->kretur_model->get_kode_sequence($no_rb, 3);
    	echo '{"success":true,
				"data":{
					"no_rb":"' . $no_rb . $sequence . '",
					"tgl_retur":"' . date('d-m-Y'). '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		
		$tgl_retur = isset($_POST['tgl_retur']) ? $this->db->escape_str($this->input->post('tgl_retur',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
		$remark = isset($_POST['remark']) ? $this->db->escape_str($this->input->post('remark',TRUE)) : FALSE;
		
		$rp_jumlah = isset($_POST['_jumlah']) ? $this->db->escape_str($this->input->post('_jumlah',TRUE)) : 0;
		$pcn_ppn = isset($_POST['_ppn_persen']) ? $this->db->escape_str($this->input->post('_ppn_persen',TRUE)) : 0;
		$rp_ppn = isset($_POST['_ppn_rp']) ? $this->db->escape_str($this->input->post('_ppn_rp',TRUE)) : 0;
		$rp_total = isset($_POST['_total']) ? $this->db->escape_str($this->input->post('_total',TRUE)) : 0;
		
				
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

		$header_result = 0;
		$detail_result = 0;
		
		if(count($detail) == 0){	
			echo '{"success":false,"errMsg":"Proses gagal"}';
			exit;
		}
		
		if($tanggal_po){
			$tanggal_po = date('Y-m-d', strtotime($tanggal_po));
		}
		
		if($tgl_berlaku_po){
			$tgl_berlaku_po = date('Y-m-d', strtotime($tgl_berlaku_po));
		}
			
		$this->db->trans_start();
				
		$no_retur = 'RB' . date('Ym') . '-';
    	$sequence = $this->kretur_model->get_kode_sequence($no_retur, 3);
		
		
		$header_kro['no_retur'] = $no_retur . $sequence;
		$header_kro['tgl_retur'] = $tgl_retur;
		$header_kro['kd_suplier'] = $kd_supplier;
		$header_kro['status'] = '0';
		$header_kro['is_konsinyasi'] = '1';
		$header_kro['rp_jumlah'] = (int) $rp_jumlah;
		$header_kro['pcn_ppn'] = (int) $pcn_ppn;
		$header_kro['rp_ppn'] = (int) $rp_ppn;
		$header_kro['rp_total'] = (int) $rp_total;
		$header_kro['created_by'] = $this->session->userdata('username');
		$header_kro['created_date'] = date('Y-m-d H:i:s');
		$header_kro['updated_by'] = $this->session->userdata('username');
		$header_kro['updated_date'] = date('Y-m-d H:i:s');
		$header_kro['remark'] = $remark;
		
		$header_result = $this->kretur_model->insert_row('purchase.t_retur_purchase', $header_kro);
		
		foreach($detail as $obj){
			unset($detail_kro);
			
			$rp_diskon = $obj->disk_amt_supp1 + $obj->disk_amt_supp2 + $obj->disk_amt_supp3 + $obj->disk_amt_supp4 + $obj->disk_amt_supp5;
			$pricelist = (int) $obj->hrg_supplier;
			$rp_jumlah = $pricelist - $rp_diskon;
			$dpp = $rp_jumlah * $obj->qty;
			
			$detail_kro['no_retur'] = $no_retur . $sequence;
			$detail_kro['kd_produk'] = $obj->kd_produk;
			$detail_kro['qty'] = (int) $obj->qty;
			$detail_kro['price_supp'] = $pricelist;
			$detail_kro['disk_persen_supp1'] = (int) $obj->disk_persen_supp1;
			$detail_kro['disk_persen_supp2'] = (int) $obj->disk_persen_supp2;
			$detail_kro['disk_persen_supp3'] = (int) $obj->disk_persen_supp3;
			$detail_kro['disk_persen_supp4'] = (int) $obj->disk_persen_supp4;
			$detail_kro['disk_amt_supp1'] = (int) $obj->disk_amt_supp1;
			$detail_kro['disk_amt_supp2'] = (int) $obj->disk_amt_supp2;
			$detail_kro['disk_amt_supp3'] = (int) $obj->disk_amt_supp3;
			$detail_kro['disk_amt_supp4'] = (int) $obj->disk_amt_supp4;
			$detail_kro['disk_amt_supp5'] = (int) $obj->disk_amt_supp5;
			$detail_kro['rp_diskon'] = $rp_diskon;
			$detail_kro['net_price'] = (int) $obj->harga;
			$detail_kro['rp_jumlah'] = $rp_jumlah;
			$detail_kro['dpp'] = $dpp;
			$detail_kro['rp_total'] = $dpp;
			$detail_kro['approval'] = '0';
			$detail_kro['created_by'] = $this->session->userdata('username');
			$detail_kro['created_date'] = date('Y-m-d H:i:s');
			$detail_kro['updated_by'] = $this->session->userdata('username');
			$detail_kro['updated_date'] = date('Y-m-d H:i:s');
	
			if($this->kretur_model->insert_row('purchase.t_retur_purchase_detail', $detail_kro)){
				$detail_result++;
			}
			
		}
		$this->db->trans_complete();
		
		
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
	public function search_produk_by_supplier(){			
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
		$result = $this->kretur_model->search_produk_by_supplier($kd_supplier, $search);
		
		foreach($result as $obj){				
			$obj->disk_persen_supp1 = $obj->disk_persen_supp1;
			$obj->disk_persen_supp2 = $obj->disk_persen_supp2;
			$obj->disk_persen_supp3 = $obj->disk_persen_supp3;
			$obj->disk_persen_supp4 = $obj->disk_persen_supp4;
			
			$obj->disk_amt_supp1 = $obj->disk_amt_supp1;
			$obj->disk_amt_supp2 = $obj->disk_amt_supp2;
			$obj->disk_amt_supp3 = $obj->disk_amt_supp3;
			$obj->disk_amt_supp4 = $obj->disk_amt_supp4;
			$obj->disk_amt_supp5 = $obj->disk_amt_supp5;
			
			//hitung diskon
			$diskon = 0;
							
			if($obj->disk_persen_supp1 != '' || $obj->disk_persen_supp1 != 0){
				$diskon_supp1 = ($obj->disk_persen_supp1 * $obj->hrg_supplier) /100;
			}else{
				if($obj->disk_amt_supp1 != ''){
					$diskon_supp1 = $obj->disk_amt_supp1;
				}else{
					$diskon_supp1 = 0;
				}
			}
			
			if($obj->disk_persen_supp2 != '' || $obj->disk_persen_supp2 != 0){
				$diskon_supp2 = ($obj->disk_persen_supp2 * $diskon_supp1) /100;
			}else{
				if($obj->disk_amt_supp1 != ''){
					$diskon_supp2 = $obj->disk_amt_supp2;
				}else{
					$diskon_supp2 = 0;
				}
			}
			
			if($obj->disk_persen_supp3 != '' || $obj->disk_persen_supp3 != 0){
				$diskon_supp3 = ($obj->disk_persen_supp3 * $diskon_supp2) /100;
			}else{
				if($obj->disk_amt_supp1 != ''){
					$diskon_supp3 = $obj->disk_amt_supp3;
				}else{
					$diskon_supp3 = 0;
				}
			}
			
			if($obj->disk_persen_supp4 != '' || $obj->disk_persen_supp4 != 0){
				$diskon_supp4 = ($obj->disk_persen_supp4 * $diskon_supp3) /100;
			}else{
				if($obj->disk_amt_supp1 != ''){
					$diskon_supp4 = $obj->disk_amt_supp1;
				}else{
					$diskon_supp4 = 0;
				}
			}
			
			$diskon_supp5 = $obj->disk_amt_supp5;
			
			$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_supp5;
			
			//diskon Rp
			$obj->disk_persen_supp1 = $diskon_supp1;
			$obj->disk_persen_supp2 = $diskon_supp2;
			$obj->disk_persen_supp3 = $diskon_supp3;
			$obj->disk_persen_supp4 = $diskon_supp4;
			$obj->disk_persen_supp5 = $diskon_supp5;
			
			//hitung harga
			$obj->harga = $obj->hrg_supplier - $diskon;
			
			$obj->jumlah = 0;
			$obj->qty = 0;				
		}
				
		echo '{success:true,record:'.count($result).',data:'.json_encode($result).'}';
	}
	
}