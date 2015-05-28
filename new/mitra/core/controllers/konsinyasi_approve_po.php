<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_approve_po extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_approve_po_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows_detail($no_po=''){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");

        $hasil = $this->konsinyasi_approve_po_model->get_rows_detail($no_po, $start, $limit);
		$results = array();
		foreach($hasil as $result){
			
				// $obj->disk_persen_supp1_po = $result->disk_persen_supp1_po;
				// $obj->disk_persen_supp2_po = $result->disk_persen_supp2_po;
				// $obj->disk_persen_supp3_po = $result->disk_persen_supp3_po;
				// $obj->disk_persen_supp4_po = $result->disk_persen_supp4_po;
				
				// $obj->disk_amt_supp1_po = $result->disk_amt_supp1_po;
				// $obj->disk_amt_supp2_po = $result->disk_amt_supp2_po;
				// $obj->disk_amt_supp3_po = $result->disk_amt_supp3_po;
				// $obj->disk_amt_supp4_po = $result->disk_amt_supp4_po;
				
				//hitung diskon
				$diskon = 0;
								
				if($result->disk_persen_supp1_po != '' || $result->disk_persen_supp1_po != 0){
					// $diskon_supp1_po = ($result->disk_persen_supp1_po * $result->price_supp_po) /100;
					$diskon_supp1_po = $result->disk_persen_supp1_po;
				}else{
					if($result->disk_amt_supp1_po != ''){
						$diskon_supp1_po = $result->disk_amt_supp1_po;
					}else{
						$diskon_supp1_po = 0;
					}
				}
				
				if($result->disk_persen_supp2_po != '' || $result->disk_persen_supp2_po != 0){
					// $diskon_supp2_po = ($result->disk_persen_supp2_po * $diskon_supp1_po) /100;
					$diskon_supp2_po = $result->disk_persen_supp2_po;
				}else{
					if($result->disk_amt_supp1_po != ''){
						$diskon_supp2_po = $result->disk_amt_supp2_po;
					}else{
						$diskon_supp2_po = 0;
					}
				}
				
				if($result->disk_persen_supp3_po != '' || $result->disk_persen_supp3_po != 0){
					// $diskon_supp3_po = ($result->disk_persen_supp3_po * $diskon_supp2_po) /100;
					$diskon_supp3_po = $result->disk_persen_supp3_po;
				}else{
					if($result->disk_amt_supp1_po != ''){
						$diskon_supp3_po = $result->disk_amt_supp3_po;
					}else{
						$diskon_supp3_po = 0;
					}
				}
				
				if($result->disk_persen_supp4_po != '' || $result->disk_persen_supp4_po != 0){
					// $diskon_supp4_po = ($result->disk_persen_supp4_po * $diskon_supp3_po) /100;
					$diskon_supp4_po = $result->disk_persen_supp4_po;
				}else{
					if($result->disk_amt_supp1_po != ''){
						$diskon_supp4_po = $result->disk_amt_supp1_po;
					}else{
						$diskon_supp4_po = 0;
					}
				}
				
				$diskon_supp5_po = $result->disk_amt_supp5_po;
				
				$diskon = $diskon_supp1_po + $diskon_supp2_po + $diskon_supp3_po + $diskon_supp4_po + $diskon_supp5_po;
				
				//diskon Rp
				$result->disk_supp1_po = $diskon_supp1_po;
				$result->disk_supp2_po = $diskon_supp2_po;
				$result->disk_supp3_po = $diskon_supp3_po;
				$result->disk_supp4_po = $diskon_supp4_po;
				$result->disk_amt_supp5_po = $diskon_supp5_po;
				
				//hitung harga
				$result->harga = $result->hrg_supplier - $diskon;
				
				//hitung jumlah
				$result->jumlah = ($result->qty_adj - $result->qty_po) * $result->harga;			
				
				$result->qty = $result->qty_adj - $result->qty_po;	
				
				$results[] = $result;
			}			
		echo '{success:true,record:'.count($results).',data:'.json_encode($results).'}'; 
	}
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
        $result = $this->konsinyasi_approve_po_model->get_rows($search, $start, $limit);
        
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$postdata = isset($_POST['postdata']) ? $this->db->escape_str($this->input->post('postdata',TRUE)) : FALSE;
		$data = explode('_',$postdata);
		
		$no_po = $data[0];
		$status = $data[1];
		
		$updated_by = $this->session->userdata('username');
		$updated_date = date('Y-m-d H:i:s');
		
		$datau = array(
			'approval_po' => $status,
			'approval_by' => $updated_by,
			'updated_by'	=>	$updated_by,
			'updated_date'	=>	$updated_date,
		);
	   
		if ($this->konsinyasi_approve_po_model->update_row($no_po, $datau)) {
			$result = '{"success":true,"errMsg":""}';
		} else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
                
        echo $result;
	}
	
}