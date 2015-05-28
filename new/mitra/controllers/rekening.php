<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rekening extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('rekening_model');
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
		
        $result = $this->rekening_model->get_rows($search, $start, $limit);
        
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
            $result = $this->rekening_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_rekening = isset($_POST['kd_rekening']) ? $this->db->escape_str($this->input->post('kd_rekening',TRUE)) : FALSE;
		$nm_rekening = isset($_POST['nm_rekening']) ? $this->db->escape_str($this->input->post('nm_rekening',TRUE)) : FALSE;
       	$no_rekening = isset($_POST['no_rekening']) ? $this->db->escape_str($this->input->post('no_rekening',TRUE)) : FALSE;
		$aktif = '1';
		
		if ( ! $kd_rekening ){ //save          
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');  
            
            $data = array(
				'kd_rekening' => $this->rekening_model->get_kode_sequence("REK",2),
                'nm_rekening' => $nm_rekening,
                'no_rekening' => $no_rekening,
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );
			
            if ($this->rekening_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit    
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			                
           	$datau = array(
                'kd_rekening' => $kd_rekening,
                'nm_rekening' => $nm_rekening,
                'no_rekening' => $no_rekening,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
                'aktif' => $aktif
            );
           
            if ($this->rekening_model->update_row($kd_rekening, $datau)) {
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
	                if ($this->rekening_model->delete_row($id)) {
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
		$kd_rekening = isset($_POST['kd_rekening']) ? $this->db->escape_str($this->input->post('kd_rekening',TRUE)) : FALSE;
		
		if ($this->rekening_model->delete_row($kd_rekening)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
}