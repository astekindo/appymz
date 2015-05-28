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
	
	public function get_rows(){
	$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
	$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : '';
        $tgl_surat_jalan = isset($_POST['tgl_surat_jalan']) ? $this->db->escape_str($this->input->post('tgl_surat_jalan',TRUE)) : '';
        $tgl_so = isset($_POST['tgl_so']) ? $this->db->escape_str($this->input->post('tgl_so',TRUE)) : '';
        $tgl_do = isset($_POST['tgl_do']) ? $this->db->escape_str($this->input->post('tgl_do',TRUE)) : '';
        
        if($tgl_surat_jalan){
                $tgl_surat_jalan = date('Y-m-d', strtotime($tgl_surat_jalan));
        }
        
        if($tgl_so){
                $tgl_so = date('Y-m-d', strtotime($tgl_so));
        }
        
        if($tgl_do){
                $tgl_do = date('Y-m-d', strtotime($tgl_do));
        }
		
		
        $result = $this->monitoring_sj_model->get_rows($no_so, $tgl_surat_jalan, $tgl_so, $tgl_do, $search, $start, $limit);
       
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
