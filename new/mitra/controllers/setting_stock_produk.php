<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_stock_produk extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('setting_stock_produk_model');
    }
	
	
	public function get_row_kode_produk(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : "";
       		
		$result = $this->setting_stock_produk_model->get_row_kode_produk($kd_produk);
		
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$stok_min = isset($_POST['stok_min']) ? $this->db->escape_str($this->input->post('stok_min',TRUE)) : FALSE;
		$stok_max = isset($_POST['stok_max']) ? $this->db->escape_str($this->input->post('stok_max',TRUE)) : FALSE;
		$max_order = isset($_POST['max_order']) ? $this->db->escape_str($this->input->post('max_order',TRUE)) : FALSE;
		$pct_alert = isset($_POST['pct_alert']) ? $this->db->escape_str($this->input->post('pct_alert',TRUE)) : FALSE;
		$created_by = isset($_POST['created_by']) ? $this->db->escape_str($this->input->post('created_by',TRUE)) : FALSE;
		$created_date = isset($_POST['created_date']) ? $this->db->escape_str($this->input->post('created_date',TRUE)) : FALSE;
		$is_kelipatan = isset($_POST['is_kelipatan']) ? $this->db->escape_str($this->input->post('is_kelipatan',TRUE)) : FALSE;
		if($is_kelipatan == '' or $is_kelipatan == NULL)$is_kelipatan = 0;
		
		$where = "kd_produk = '$kd_produk'";
						
		if ($this->setting_stock_produk_model->select_inventory($where)) { //edit            
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			        
           	$datau = array(
				'stok_min' => $stok_min,
				'stok_max' => $stok_max,
				'max_order' => $max_order,
				'pct_alert' => $pct_alert,
				'approval_ops' => '0',
				'approval_buyer' => '0',
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
				'is_kelipatan'	=>	$is_kelipatan
            );
           
		   if ($this->setting_stock_produk_model->update_row($kd_produk, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }  else { //save  
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');
            $data = array(
				'kd_produk' => $kd_produk,
				'stok_min' => $stok_min,
				'stok_max' => $stok_max,
				'max_order' => $max_order,
				'pct_alert' => $pct_alert,
				'created_by' => $created_by,
				'created_date' => $created_date,
				'is_kelipatan'	=>	$is_kelipatan
            );
			
            if ($this->setting_stock_produk_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
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
	public function delete_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		
		if ($this->setting_stock_produk_model->delete_row($kd_produk)) {
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
		$result = $this->setting_stock_produk_model->get_produk($search,$start,$limit);
        echo $result;
	}
}
