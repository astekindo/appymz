<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_harga_jual extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('setting_harga_jual_model');
    }
	
	
	public function get_row_kode_produk(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : "";
       		
		$result = $this->setting_harga_jual_model->get_row_kode_produk($kd_produk);
		
        if($result->disk_persen_kons1 != 0 AND $result->disk_amt_kons1 == 0){
			$result->disk_kons1_op = 'persen';
			$result->disk_kons1 = $result->disk_persen_kons1;
		}else if($result->disk_persen_kons1 == 0 AND $result->disk_amt_kons1 != 0){
			$result->disk_kons1_op = 'amount';
			$result->disk_kons1 = $result->disk_amt_kons1;
		}else {
			$result->disk_kons1_op = 'persen';
			$result->disk_kons1 = 0;		
		}
		
        if($result->disk_persen_kons2 != 0 AND $result->disk_amt_kons2 == 0){
			$result->disk_kons2_op = 'persen';
			$result->disk_kons2 = $result->disk_persen_kons2;
		}else if($result->disk_persen_kons2 == 0 AND $result->disk_amt_kons2 != 0){
			$result->disk_kons2_op = 'amount';
			$result->disk_kons2 = $result->disk_amt_kons2;
		}else {
			$result->disk_kons2_op = 'persen';
			$result->disk_kons2 = 0;		
		}
		
        if($result->disk_persen_kons3 != 0 AND $result->disk_amt_kons3 == 0){
			$result->disk_kons3_op = 'persen';
			$result->disk_kons3 = $result->disk_persen_kons3;
		}else if($result->disk_persen_kons3 == 0 AND $result->disk_amt_kons3 != 0){
			$result->disk_kons3_op = 'amount';
			$result->disk_kons3 = $result->disk_amt_kons3;
		}else {
			$result->disk_kons3_op = 'persen';
			$result->disk_kons3 = 0;		
		}
		
        if($result->disk_persen_kons4 != 0 AND $result->disk_amt_kons4 == 0){
			$result->disk_kons4_op = 'persen';
			$result->disk_kons4 = $result->disk_persen_kons4;
		}else if($result->disk_persen_kons4 == 0 AND $result->disk_amt_kons4 != 0){
			$result->disk_kons4_op = 'amount';
			$result->disk_kons4 = $result->disk_amt_kons4;
		}else {
			$result->disk_kons4_op = 'persen';
			$result->disk_kons4 = 0;		
		}
		
        if($result->disk_persen_member1 != 0 AND $result->disk_amt_member1 == 0){
			$result->disk_memb1_op = 'persen';
			$result->disk_memb1 = $result->disk_persen_member1;
		}else if($result->disk_persen_member1 == 0 AND $result->disk_amt_member1 != 0){
			$result->disk_memb1_op = 'amount';
			$result->disk_memb1 = $result->disk_amt_member1;
		}else {
			$result->disk_memb1_op = 'persen';
			$result->disk_memb1 = 0;	
		}
		
        if($result->disk_persen_member2 != 0 AND $result->disk_amt_member2 == 0){
			$result->disk_memb2_op = 'persen';
			$result->disk_memb2 = $result->disk_persen_member2;
		}else if($result->disk_persen_member2 == 0 AND $result->disk_amt_member2 != 0){
			$result->disk_memb2_op = 'amount';
			$result->disk_memb2 = $result->disk_amt_member2;
		}else {
			$result->disk_memb2_op = 'persen';
			$result->disk_memb2 = 0;	
		}
		
        if($result->disk_persen_member3 != 0 AND $result->disk_amt_member3 == 0){
			$result->disk_memb3_op = 'persen';
			$result->disk_memb3 = $result->disk_persen_member3;
		}else if($result->disk_persen_member3 == 0 AND $result->disk_amt_member3 != 0){
			$result->disk_memb3_op = 'amount';
			$result->disk_memb3 = $result->disk_amt_member3;
		}else {
			$result->disk_memb3_op = 'persen';
			$result->disk_memb3 = 0;		
		}
		
        if($result->disk_persen_member4 != 0 AND $result->disk_amt_member4 == 0){
			$result->disk_memb4_op = 'persen';
			$result->disk_memb4 = $result->disk_persen_member4;
		}else if($result->disk_persen_member4 == 0 AND $result->disk_amt_member4 != 0){
			$result->disk_memb4_op = 'amount';
			$result->disk_memb4 = $result->disk_amt_member4;
		}else {
			$result->disk_memb4_op = 'persen';
			$result->disk_memb4 = 0;		
		}
		
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$kd_diskon_sales = isset($_POST['kd_diskon_sales']) ? $this->db->escape_str($this->input->post('kd_diskon_sales',TRUE)) : FALSE;
		$koreksi_ke = isset($_POST['koreksi_ke']) ? $this->db->escape_str($this->input->post('koreksi_ke',TRUE)) : FALSE;
		
		$qty_beli_bonus = isset($_POST['qty_beli_bonus']) ? $this->db->escape_str($this->input->post('qty_beli_bonus',TRUE)) : FALSE;
		$kd_produk_bonus = isset($_POST['kd_produk_bonus']) ? $this->db->escape_str($this->input->post('kd_produk_bonus',TRUE)) : FALSE;
		$qty_bonus = isset($_POST['qty_bonus']) ? $this->db->escape_str($this->input->post('qty_bonus',TRUE)) : FALSE;
		$is_bonus_kelipatan = isset($_POST['is_bonus_kelipatan']) ? $this->db->escape_str($this->input->post('is_bonus_kelipatan',TRUE)) : '0'; 
		$qty_beli_member = isset($_POST['qty_beli_member']) ? $this->db->escape_str($this->input->post('qty_beli_member',TRUE)) : FALSE;
		$kd_produk_member = isset($_POST['kd_produk_member']) ? $this->db->escape_str($this->input->post('kd_produk_member',TRUE)) : FALSE;
		$qty_member = isset($_POST['qty_member']) ? $this->db->escape_str($this->input->post('qty_member',TRUE)) : FALSE;
		$is_member_kelipatan = isset($_POST['is_member_kelipatan']) ? $this->db->escape_str($this->input->post('is_member_kelipatan',TRUE)) : FALSE;
		
		if($qty_bonus > 0 || $qty_member > 0){
			$is_bonus = 1;		
		}else $is_bonus = 0;
		
		
		$disk_amt_kons5 = isset($_POST['disk_kons5']) ? $this->db->escape_str($this->input->post('disk_kons5',TRUE)) : FALSE;
		$disk_amt_member5 = isset($_POST['disk_memb5']) ? $this->db->escape_str($this->input->post('disk_memb5',TRUE)) : FALSE;
		
		$disk_kons1_op = isset($_POST['disk_kons1_op']) ? $this->db->escape_str($this->input->post('disk_kons1_op',TRUE)) : FALSE;
		$disk_kons2_op = isset($_POST['disk_kons2_op']) ? $this->db->escape_str($this->input->post('disk_kons2_op',TRUE)) : FALSE;
		$disk_kons3_op = isset($_POST['disk_kons3_op']) ? $this->db->escape_str($this->input->post('disk_kons3_op',TRUE)) : FALSE;
		$disk_kons4_op = isset($_POST['disk_kons4_op']) ? $this->db->escape_str($this->input->post('disk_kons4_op',TRUE)) : FALSE;
		$disk_kons1 = isset($_POST['disk_kons1']) ? $this->db->escape_str($this->input->post('disk_kons1',TRUE)) : FALSE;
		$disk_kons2 = isset($_POST['disk_kons2']) ? $this->db->escape_str($this->input->post('disk_kons2',TRUE)) : FALSE;
		$disk_kons3 = isset($_POST['disk_kons3']) ? $this->db->escape_str($this->input->post('disk_kons3',TRUE)) : FALSE;
		$disk_kons4 = isset($_POST['disk_kons4']) ? $this->db->escape_str($this->input->post('disk_kons4',TRUE)) : FALSE;
		if($disk_kons1_op === "persen"){
			$disk_persen_kons1 = $disk_kons1;
			$disk_amt_kons1 =0;
		}else{
			$disk_persen_kons1 = 0;
			$disk_amt_kons1 = $disk_kons1;			
		}
		if($disk_kons2_op === "persen"){
			$disk_persen_kons2 = $disk_kons2;
			$disk_amt_kons2 =0;
		}else{
			$disk_persen_kons2 = 0;
			$disk_amt_kons2 = $disk_kons2;			
		}
		if($disk_kons3_op === "persen"){
			$disk_persen_kons3 = $disk_kons3;
			$disk_amt_kons3 =0;
		}else{
			$disk_persen_kons3 = 0;
			$disk_amt_kons3 = $disk_kons3;			
		}
		if($disk_kons4_op === "persen"){
			$disk_persen_kons4 = $disk_kons4;
			$disk_amt_kons4 =0;
		}else{
			$disk_persen_kons4 = 0;
			$disk_amt_kons4 = $disk_kons4;			
		}
		
		$disk_memb1_op = isset($_POST['disk_memb1_op']) ? $this->db->escape_str($this->input->post('disk_memb1_op',TRUE)) : FALSE;
		$disk_memb2_op = isset($_POST['disk_memb2_op']) ? $this->db->escape_str($this->input->post('disk_memb2_op',TRUE)) : FALSE;
		$disk_memb3_op = isset($_POST['disk_memb3_op']) ? $this->db->escape_str($this->input->post('disk_memb3_op',TRUE)) : FALSE; 
		$disk_memb4_op = isset($_POST['disk_memb4_op']) ? $this->db->escape_str($this->input->post('disk_memb4_op',TRUE)) : FALSE;
		$disk_memb1 = isset($_POST['disk_memb1']) ? $this->db->escape_str($this->input->post('disk_memb1',TRUE)) : FALSE;
		$disk_memb2 = isset($_POST['disk_memb2']) ? $this->db->escape_str($this->input->post('disk_memb2',TRUE)) : FALSE;
		$disk_memb3 = isset($_POST['disk_memb3']) ? $this->db->escape_str($this->input->post('disk_memb3',TRUE)) : FALSE; 
		$disk_memb4 = isset($_POST['disk_memb4']) ? $this->db->escape_str($this->input->post('disk_memb4',TRUE)) : FALSE;
		if($disk_memb1_op === "persen"){
			$disk_persen_member1 = $disk_memb1;
			$disk_amt_member1 =0;
		}else{
			$disk_persen_member1 = 0;
			$disk_amt_member1 = $disk_memb1;			
		}
		if($disk_memb2_op === "persen"){
			$disk_persen_member2 = $disk_memb2;
			$disk_amt_member2 =0;
		}else{
			$disk_persen_member2 = 0;
			$disk_amt_member2 = $disk_memb2;			
		}
		if($disk_memb3_op === "persen"){
			$disk_persen_member3 = $disk_memb3;
			$disk_amt_member3 =0;
		}else{
			$disk_persen_member3 = 0;
			$disk_amt_member3 = $disk_memb3;			
		}
		if($disk_memb4_op === "persen"){
			$disk_persen_member4 = $disk_memb4;
			$disk_amt_member4 =0;
		}else{
			$disk_persen_member4 = 0;
			$disk_amt_member4 = $disk_memb4;			
		}
		
		
		$no_urut_diskon = $this->setting_harga_jual_model->get_kode_sequence('HJUAL',3);
		
		
		
		if ( ! $kd_diskon_sales) { //save  
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');          
            $kd_diskon_sales = date('Ym').'-'.$no_urut_diskon;
            $data = array(
				'kd_produk' => $kd_produk,
				'kd_diskon_sales' => $kd_diskon_sales,
				'disk_persen_kons1' => $disk_persen_kons1,
				'disk_persen_kons2' => $disk_persen_kons2,
				'disk_persen_kons3' => $disk_persen_kons3,
				'disk_persen_kons4' => $disk_persen_kons4,
				'disk_amt_kons1' => $disk_amt_kons1,
				'disk_amt_kons2' => $disk_amt_kons2,
				'disk_amt_kons3' => $disk_amt_kons3,
				'disk_amt_kons4' => $disk_amt_kons4,
				'disk_amt_kons5' => $disk_amt_kons5,
				'disk_persen_member1'=>	$disk_persen_member1,
				'disk_persen_member2'=>	$disk_persen_member2,
				'disk_persen_member3'=> $disk_persen_member3,
				'disk_persen_member4'=>	$disk_persen_member4,
				'disk_amt_member1'=>	$disk_amt_member1,
				'disk_amt_member2'=>	$disk_amt_member2,
				'disk_amt_member3'=> 	$disk_amt_member3,
				'disk_amt_member4'=>	$disk_amt_member4,
				'disk_amt_member5'=>	$disk_amt_member5,
				'created_by' => $created_by,
				'created_date' => $created_date,
				'tanggal' => $created_date,
				'qty_beli_bonus'=>	$qty_beli_bonus,
				'kd_produk_bonus'=>	$kd_produk_bonus,
				'qty_bonus'=>	$qty_bonus,
				'is_bonus_kelipatan'=> 	$is_bonus_kelipatan,
				'qty_beli_member'=>	$qty_beli_member,
				'kd_produk_member'=>	$kd_produk_member,
				'qty_member'=>	$qty_member,
				'is_member_kelipatan'=>	$is_member_kelipatan,
				'is_bonus' =>	$is_bonus,
				'koreksi_ke'=>	0,
			
            );
			
            if ($this->setting_harga_jual_model->insert_row($data)) {
                if ($this->setting_harga_jual_model->insert_row_sales($kd_produk,$kd_diskon_sales,0)) {
					$result = '{"success":true,"errMsg":""}';
				}
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit            
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			        
           	$datau = array(
				'disk_persen_kons1' => $disk_persen_kons1,
				'disk_persen_kons2' => $disk_persen_kons2,
				'disk_persen_kons3' => $disk_persen_kons3,
				'disk_persen_kons4' => $disk_persen_kons4,
				'disk_amt_kons1' => $disk_amt_kons1,
				'disk_amt_kons2' => $disk_amt_kons2,
				'disk_amt_kons3' => $disk_amt_kons3,
				'disk_amt_kons4' => $disk_amt_kons4,
				'disk_amt_kons5' => $disk_amt_kons5,
				'disk_persen_member1'=>	$disk_persen_member1,
				'disk_persen_member2'=>	$disk_persen_member2,
				'disk_persen_member3'=> $disk_persen_member3,
				'disk_persen_member4'=>	$disk_persen_member4,
				'disk_amt_member1'=>	$disk_amt_member1,
				'disk_amt_member2'=>	$disk_amt_member2,
				'disk_amt_member3'=> 	$disk_amt_member3,
				'disk_amt_member4'=>	$disk_amt_member4,
				'disk_amt_member5'=>	$disk_amt_member5,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
				'qty_beli_bonus'=>	$qty_beli_bonus,
				'kd_produk_bonus'=>	$kd_produk_bonus,
				'qty_bonus'=>	$qty_bonus,
				'is_bonus_kelipatan'=> 	$is_bonus_kelipatan,
				'qty_beli_member'=>	$qty_beli_member,
				'kd_produk_member'=>	$kd_produk_member,
				'qty_member'=>	$qty_member,
				'is_member_kelipatan'=>	$is_member_kelipatan,
				'is_bonus' =>	$is_bonus,
				'koreksi_ke' => $koreksi_ke+1,
            );
           
			
            if ($this->setting_harga_jual_model->update_row($kd_produk, $datau)) {
                if ($this->setting_harga_jual_model->insert_row_sales($kd_produk,$kd_diskon_sales,$koreksi_ke+1)) {
					$result = '{"success":true,"errMsg":""}';
				}
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }       
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_rows(){
		$postdata = isset($_POST['postdata']) ? $this->input->post('postdata',TRUE) : array();
		
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                
	                $this->db->trans_start();
	                if ($this->setting_harga_jual_model->delete_row($id)) {
	                    $i++;
	                }
	                $this->db->trans_complete();
	            }
	        
	        }
	        if ($i > 0) {
	            $result = '{"success":true,"errMsg":""}';
	        } else {
	            $result = '{"success":false,"errMsg":"Process Failed.."}';
	        }
	        echo $result;
		}		
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		
		if ($this->setting_harga_jual_model->delete_row($kd_produk)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}	
	
	public function get_produk(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$result = $this->setting_harga_jual_model->get_produk($search,$start,$limit);
        echo $result;
	}
}