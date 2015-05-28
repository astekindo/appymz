<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_monitoring_qty_pr extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_monitoring_qty_pr_model','pmqr_model');
        $this->load->model('account_closing_model', 'acm_model');
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

        $result = $this->pmqr_model->get_rows($kd_supplier,$search, $start, $limit);

//        var_dump($result);
        echo $result;
	}
	
	public function get_rows_detail($kd_supplier=''){
		$result = $this->pmqr_model->get_rows_detail($kd_supplier);
        
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */

}
