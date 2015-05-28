<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wilayah_propinsi extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('wilayah_propinsi_model');
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
		$fields = isset($_POST['fields']) ? json_decode($this->input->post('fields')) : '';

        $result = $this->wilayah_propinsi_model->get_rows($search, $start, $limit,$fields);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
            $result = $this->wilayah_propinsi_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_propinsi = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi',TRUE)) : FALSE;
		$nama_propinsi = isset($_POST['nama_propinsi']) ? $this->db->escape_str($this->input->post('nama_propinsi',TRUE)) : FALSE;
		
		if ( ! $kd_propinsi) { //save   
            
            $data = array(
				'kd_propinsi' => $this->wilayah_propinsi_model->get_kode_sequence('PROP', 2),
                'nama_propinsi' => strtoupper($nama_propinsi)
            );
			
            if ($this->wilayah_propinsi_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit         			
			    
           	$datau = array(
				'nama_propinsi' => strtoupper($nama_propinsi)
            );
           
            if ($this->wilayah_propinsi_model->update_row($kd_propinsi, $datau)) {
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
	public function delete_rows(){
		$postdata = isset($_POST['postdata']) ? $this->input->post('postdata',TRUE) : array();
			
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                
	                $this->db->trans_start();
	                if ($this->wilayah_propinsi_model->delete_row($id)) {
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
		$kd_propinsi = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi',TRUE)) : FALSE;
		
		if ($this->wilayah_propinsi_model->delete_row($kd_propinsi)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
}