<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_purchase_order_print extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_purchase_order_print_model','ppop_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
        $result = $this->ppop_model->get_rows($kd_supplier,$search, $start, $limit);
        
        echo $result;
	}
	
	public function get_rows_detail($no_po=''){
		$result = $this->ppop_model->get_rows_detail($no_po);
        
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */

}
