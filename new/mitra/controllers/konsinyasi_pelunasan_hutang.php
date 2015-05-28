<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_pelunasan_hutang extends MY_Controller { 
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_pelunasan_hutang_model', 'kph_model');
                $this->load->model('cetak_pelunasan_hutang_model', 'cph_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_kwit = 'KWIT' . date('Ym') . '-';
    	$sequence = $this->kph_model->get_kode_sequence($no_kwit, 3);
    	echo '{"success":true,
				"data":{
					"no_kwitansi":"' . $no_kwit . $sequence . '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		
		$no_kwit = 'KWIT' . date('Ym') . '-';
    	$sequence = $this->kph_model->get_kode_sequence($no_kwit, 3);
    	
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : FALSE;
		$no_kwitansi = $no_kwit . $sequence;
		$rp_total = isset($_POST['rp_total']) ? $this->db->escape_str($this->input->post('rp_total',TRUE)) : FALSE;
		$terbilang_total = isset($_POST['terbilang_total']) ? $this->db->escape_str($this->input->post('terbilang_total',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : FALSE;
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : FALSE;
		$terima_dari = isset($_POST['terima_dari']) ? $this->db->escape_str($this->input->post('terima_dari',TRUE)) : FALSE;
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : FALSE;
		
		$header_result = FALSE;
		
	
		$this->db->trans_start();
		$header_pph['no_po'] = $no_po;
		$header_pph['no_kwitansi'] = $no_kwitansi;
		$header_pph['rp_total'] = str_replace(',','',$rp_total);
		$header_pph['terbilang_total'] = $terbilang_total;
		$header_pph['kd_supplier'] = $kd_supplier;
		$header_pph['tanggal'] = $tanggal;
		$header_pph['terima_dari'] = $terima_dari;
		$header_pph['keterangan'] = $keterangan;
		$header_pph['created_by'] = $this->session->userdata('username');
		$header_pph['created_date'] = date('Y-m-d H:i:s');
		 
		$header_result = $this->kph_model->insert_row('purchase.t_pelunasan_hutang', $header_pph);
		
		
		$this->db->trans_complete();
		
		if ($header_result > 0) {
			$result = '{"success":true,"errMsg":""}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }
	
	
	/**
		* Indonesian number speller (PHP 4 or greater)
		*
		* @param string $number a string representing a positive, integral number with 15 digits or less
		* @return string|false the spelled out number in Indonesian, or false if the number is invalid
		* @author {@link http://www.lesantoso.com Lucky E. Santoso} <lesantoso@yahoo.com>
		* @copyright Copyright (c) 2006 Lucky E. Santoso
		* @license http://opensource.org/licenses/gpl-license.php The GNU General Public License (GPL)
	*/ 
	function spellNumberInIndonesian ($number) {
		$number = strval($number);
		if (!ereg("^[0-9]{1,15}$", $number)) 
			return(false); 
		$ones = array("", "satu", "dua", "tiga", "empat", 
			"lima", "enam", "tujuh", "delapan", "sembilan");
		$majorUnits = array("", "ribu", "juta", "milyar", "trilyun");
		$minorUnits = array("", "puluh", "ratus");
		$result = "";
		$isAnyMajorUnit = false;
		$length = strlen($number);
		for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
			if ($number{$i} != '0') {
				if ($number{$i} != '1')
					$result .= $ones[$number{$i}].' '.$minorUnits[$pos % 3].' ';
				else if ($pos % 3 == 1 && $number{$i + 1} != '0') {
					if ($number{$i + 1} == '1') 
						$result .= "sebelas "; 
					else 
						$result .= $ones[$number{$i + 1}]." belas ";
					$i++; $pos--;
				} else if ($pos % 3 != 0)
					$result .= "se".$minorUnits[$pos % 3].' ';
				else if ($pos == 3 && !$isAnyMajorUnit)
					$result .= "se";
				else
					$result .= "satu ";
				$isAnyMajorUnit = true;
			}
			if ($pos % 3 == 0 && $isAnyMajorUnit) {
				$result .= $majorUnits[$pos / 3].' ';
				$isAnyMajorUnit = false;
			}
		}
		$result = trim($result);
		if ($result == "") $result = "nol";
		return($result);
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_no_po(){
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : FALSE;
		$result = $this->kph_model->get_no_po($no_po);
		
        if($result->rp_total_po !='0'){
			$result->uangsejumlah = strtoupper($this->spellNumberInIndonesian($result->rp_total_po));
		}
		
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	public function get_all_invoice(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : "";
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
                $kd_peruntukan = $this->session->userdata('user_peruntukan');
                $result = $this->kph_model->get_all_invoice($kd_supplier,$kd_peruntukan,$search);
                
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->kph_model->get_all_jenis_pembayaran(true);
        
        echo $result;
	}
        
        public function update_row_pelunasan(){
		//$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
                $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';	
                $current_date = date('Ymd', strtotime($tanggal));
		$no_ph = 'PK' . $current_date .'-';
                $sequence = $this->kph_model->get_kode_sequence($no_ph, 3);
    	
		$no_bukti = $no_ph . $sequence;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
			
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		
		$total_invoice = isset($_POST['_total_invoice']) ? $this->db->escape_str($this->input->post('_total_invoice',TRUE)) : 0;
		$total_bayar = isset($_POST['_total_bayar']) ? $this->db->escape_str($this->input->post('_total_bayar',TRUE)) : 0;
		$total_potongan = isset($_POST['_total_potongan']) ? $this->db->escape_str($this->input->post('_total_potongan',TRUE)) : 0;
		$total_dibayar = isset($_POST['_total_dibayar']) ? $this->db->escape_str($this->input->post('_total_dibayar',TRUE)) : 0;
		$selisih = isset($_POST['_selisih']) ? $this->db->escape_str($this->input->post('_selisih',TRUE)) : 0;
                $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : 0;
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$detailbayar = isset($_POST['detailbayar']) ? json_decode($this->input->post('detailbayar',TRUE)) : array();
		
		if($tanggal != ''){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		
		if(count($detail) == 0 && count($detailbayar) == 0){	
			echo '{"success":false,"errMsg":"Data tidak lengkap"}';
			exit;
		}
                $tgl_bayar = strtotime($tanggal);
                foreach($detail as $obj){
                    $tgl_invoice = strtotime($obj->tgl_invoice);
                    if($tgl_bayar < $tgl_invoice){
                        echo '{"success":false,"errMsg":"Tanggal Bayar Tidak Boleh Lebih Kecil dari Tanggal Invoice"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }
                $header_result = FALSE;
                $detail_result = 0;
		$this->db->trans_begin();
		$header_pph['no_bukti'] = $no_bukti;
		$header_pph['tanggal'] = $tanggal;
		$header_pph['kd_supplier'] = $kd_supplier;
		$header_pph['rp_total_invoice'] = (int) $total_invoice;
		$header_pph['rp_total_potongan'] = (int) $total_potongan;		
		$header_pph['rp_selisih'] = (int) $selisih;		
		$header_pph['rp_total'] = (int) $total_bayar;
		$header_pph['rp_total_dibayar'] = (int) $total_dibayar;
		$header_pph['keterangan'] = $keterangan;
                $header_pph['kd_peruntukan'] = $kd_peruntukan;
                $header_pph['created_by'] = $this->session->userdata('username');
		$header_pph['created_date'] = date('Y-m-d H:i:s');
		$header_pph['updated_by'] = $this->session->userdata('username');
		$header_pph['updated_date'] = date('Y-m-d H:i:s');
		
                $header_result = $this->kph_model->insert_row('purchase.t_pelunasan_hutang', $header_pph);		 
//		if( ! $this->pph_model->insert_row('purchase.t_pelunasan_hutang', $header_pph)){
//			echo '{"success":false,"errMsg":"Process Failed.."}';
//			$this->db->trans_rollback();
//			exit;
//		}
		
		foreach($detail as $obj){
			$detail_pph['no_bukti'] = $no_bukti;
			$detail_pph['no_invoice'] = $obj->no_invoice;
			$detail_pph['rp_total'] =  $obj->rp_total;
			$detail_pph['rp_diskon'] =  $obj->rp_diskon;
			$detail_pph['tgl_invoice'] = $obj->tgl_invoice;
			$detail_pph['rp_bayar'] = $obj->rp_dibayar;
                        $detail_pph['rp_sisa'] = $obj->rp_sisa_invoice;
			
			if($this->kph_model->insert_row('purchase.t_pelunasan_detail', $detail_pph)){
				$detail_result++;
			}
			
			if( $this->kph_model->update_invoice($obj->no_invoice, $obj->rp_sisa_invoice, $obj->rp_bayar)){
				$detail_result++;
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
			if($detail_bayar['is_validasi_card'] == '1' ){
				if($detail_bayar['nomor_bank'] === '' || $detail_bayar['nomor_ref'] === '' || $detail_bayar['tgl_jth_tempo'] === ''){
					echo '{"success":false,"errMsg":"Pembayaran dengan Cek dan Giro, No Bank, No Ref, dan Tgl Jth Tempo tidak boleh kosong!"}';
					$this->db->trans_rollback();
					exit;
				}
			}
			if( $this->kph_model->insert_row('purchase.t_pelunasan_bayar', $detail_bayar)){
				$detail_result++;
			}else
			$detail_result = 0;
		}
		
		$this->db->trans_commit();
                if ($header_result && $detail_result > 0) {
			$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_pelunasan_hutang/print_form/" . $no_bukti) . '"}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }

    public function print_form($no_bukti){
		$data = $this->cph_model->get_data_print($no_bukti);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PelunasanHutangPrint.php');
		$pdf = new PelunasanHutangPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail'],$data['detail_bayar']);
		$pdf->Output();	
		exit;
	}
}