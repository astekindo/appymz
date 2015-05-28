<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_ukuran extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_ukuran_model');
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

        $result = $this->master_ukuran_model->get_rows($search, $start, $limit,$fields);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($kd_ukuran = ''){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
            $result = $this->master_ukuran_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran',TRUE)) : FALSE;
		$nama_ukuran = isset($_POST['nama_ukuran']) ? $this->db->escape_str($this->input->post('nama_ukuran',TRUE)) : FALSE;
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		
		$check_result = $this->master_ukuran_model->check_data('nama_ukuran',$nama_ukuran,'mst.t_ukuran');
		
		if($kd_ukuran){
			$field_result = $this->master_ukuran_model->get_data_field('nama_ukuran','kd_ukuran',$kd_ukuran,'mst.t_ukuran');
			if($field_result->nama_ukuran == $nama_ukuran){
				$check_result = FALSE;
			}
		}
		
		if($check_result){
			$errMsg =  "Data dengan Nama Ukuran: ".$nama_ukuran." Sudah Ada di dalam Database. Silahkan Input Ulang";
			$result = '{"success":false,"errMsg":"'.$errMsg.'"}';
			echo $result;
			exit;
		}
		
		if ( ! $kd_ukuran) { //save   
            
            $data = array(
				'kd_ukuran' => $this->master_ukuran_model->get_kode_sequence('MU', 4),
                'nama_ukuran' => strtoupper($nama_ukuran),
                'aktif' => $aktif
            );
			
            if ($this->master_ukuran_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit         			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			    
           	$datau = array(
				'nama_ukuran' => strtoupper($nama_ukuran),
                'aktif' => $aktif
            );
           
            if ($this->master_ukuran_model->update_row($kd_ukuran, $datau)) {
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
		$updated_by = $this->session->userdata('username');
		$updated_date = date('Y-m-d H:i:s');
			
		$datau = array(
			'aktif' => '0'
		);
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                
	                $this->db->trans_start();
	                if ($this->master_ukuran_model->delete_row($id,$datau)) {
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
		$kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran',TRUE)) : FALSE;
		
		if ($this->master_ukuran_model->delete_row($kd_ukuran)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
}
