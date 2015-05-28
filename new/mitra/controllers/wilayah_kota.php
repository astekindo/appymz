<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wilayah_kota extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('wilayah_kota_model');
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
		
        $result = $this->wilayah_kota_model->get_rows($search, $start, $limit);
        
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
			$id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1',TRUE)) : NULL;
            $result = $this->wilayah_kota_model->get_row($id,$id1);
            
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
		$kd_kota = isset($_POST['kd_kota']) ? $this->db->escape_str($this->input->post('kd_kota',TRUE)) : FALSE;
		$nama_kota = isset($_POST['nama_kota']) ? $this->db->escape_str($this->input->post('nama_kota',TRUE)) : FALSE;
		$sequence = $this->wilayah_kota_model->get_kode_sequence('KOTA'.$kd_propinsi, 2);
		
		if (!$kd_kota) { //save    
			
            $data = array(
				'kd_kota' => $kd_propinsi.$sequence,
				'kd_propinsi' => $kd_propinsi,
				'nama_kota' => strtoupper($nama_kota),
				
            );

            if ($this->wilayah_kota_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit          			
			   
           	$datau = array(
				'nama_kota' => strtoupper($nama_kota),
            );
           
            if ($this->wilayah_kota_model->update_row($kd_kota, $kd_propinsi, $datau)) {
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
	                $kd = explode('-', $id);
	                $this->db->trans_start();
	                if ($this->wilayah_kota_model->delete_row($kd[0],$kd[1],$datau)) {
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
		$kd_kota = isset($_POST['kd_kota']) ? $this->db->escape_str($this->input->post('kd_kota',TRUE)) : FALSE;
		$kd_propinsi = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi',TRUE)) : FALSE;
		// print_r($kd_propinsi);
		if ($this->propinsi_model->delete_row($kd_kota,$kd_propinsi)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_propinsi(){
		$result = $this->wilayah_kota_model->get_propinsi();
        
        echo $result;
	}
}