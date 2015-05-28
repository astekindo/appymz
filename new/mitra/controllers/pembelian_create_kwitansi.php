<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_create_kwitansi extends MY_Controller {  
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_create_kwitansi_model', 'pck_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_kwit = 'KWIT' . date('Ym') . '-';
    	$sequence = $this->pck_model->get_kode_sequence($no_kwit, 3);
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
    	$sequence = $this->pck_model->get_kode_sequence($no_kwit, 3);
    	
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
		 
		$header_result = $this->pck_model->insert_row('purchase.t_kwitansi', $header_pph);
		
		
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
		$result = $this->pck_model->get_no_po($no_po);
		
        if($result->rp_total_po !='0'){
			$result->uangsejumlah = strtoupper($this->spellNumberInIndonesian($result->rp_total_po));
		}
		
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	
}