<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_setting_stock extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('approval_setting_stock_model');
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
		
        $result = $this->approval_setting_stock_model->get_rows($search, $start, $limit);
        
        echo $result;
	}
	
	public function get_rows_detail(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");

        $result = $this->approval_setting_stock_model->get_rows_detail($no_ro, $start, $limit);
        
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row_detail($postdata){
		$postdata = isset($_POST['postdata']) ? $this->db->escape_str($this->input->post('postdata',TRUE)) : FALSE;
		$data = explode('_',$postdata);
		$kd = $data[0];
		$approval_ops = $data[1];
		$updated_by = $this->session->userdata('username');
		$updated_date = date('Y-m-d H:i:s');
		
		$data = array(
			'approval_ops' => $approval_ops,
			'updated_by'	=>	$updated_by,
			'updated_date'	=>	$updated_date,
		);
		if ($this->approval_setting_stock_model->update_row_detail($kd, $data)) {
				$result = '{"success":true,"errMsg":""}';
			} else {
				$result = '{"success":false,"errMsg":"Process Failed.."}';
			}
		     
        echo $result;
	}
	
}