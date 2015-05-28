<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_receive_ord extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('monitoring_receive_order_model', 'mpro');
    }
	
	public function get_ro($no_ro = ''){
		$no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : '';
		
        $result = $this->mpro->get_ro($no_ro);
        
        echo $result;
	}
	
	public function get_ro_detail(){
		$no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : '';
		
        $result = $this->mpro->get_ro_detail($no_ro);
        
        echo $result;
	}
}