<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_surat_jalan extends MY_Controller {
    /**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('monitoring_sj_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	
	public function get_rows($no_so=''){
	$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
	$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        	
        $result = $this->monitoring_sj_model->get_rows($no_so, $search, $start, $limit);
       
        echo $result;
	}
        
        public function get_sj_rows($no_do=''){
	$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
	$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        	
        $result = $this->monitoring_sj_model->get_sj_rows($no_do,$search, $start, $limit);
       
        echo $result;
	}
        
        public function get_no_so(){
	$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
	$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        
        $result = $this->monitoring_sj_model->get_no_so( $search, $start, $limit);
       
        echo $result;
	}
}
