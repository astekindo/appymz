<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori1 extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('kategori1_model');
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

        $result = $this->kategori1_model->get_rows($search, $start, $limit,$fields);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($kd_kategori1 = ''){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
            $result = $this->kategori1_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : FALSE;
		$nama_kategori1 = isset($_POST['nama_kategori1']) ? $this->db->escape_str($this->input->post('nama_kategori1',TRUE)) : FALSE;
		$nama_kategori1 = strtoupper($nama_kategori1);
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		if($aktif=='0')
			$aktif = 'FALSE';
		else $aktif = 'TRUE';
		
		$check_result = $this->kategori1_model->check_data('nama_kategori1',$nama_kategori1,'mst.t_kategori1');
		
		if($kd_kategori1){
			$field_result = $this->kategori1_model->get_data_field('nama_kategori1','kd_kategori1',$kd_kategori1,'mst.t_kategori1');
			if($field_result->nama_kategori1 == $nama_kategori1){
				$check_result = FALSE;
			}
		}
		
		if($check_result){
			$errMsg =  "Data Kategori1 dengan Nama Kategori1: ".$nama_kategori1.". Sudah Ada di dalam Database. Silahkan Input Ulang";
			$result = '{"success":false,"errMsg":"'.$errMsg.'"}';
			echo $result;
			exit;
		}
		if ( ! $kd_kategori1) { //save   
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');   
			      
			$abjad_list = array('01' => 'A', '02' => 'B', '03' => 'C', '04' => 'D', '05' => 'E', '06' => 'F', '07' => 'G', '08' => 'H', '09' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'R', '20' => 'S', '21' => 'T', '22' => 'U', '23' => 'V', '24' => 'W', '25' => 'X', '26' => 'Y', '27' => 'Z');
			$kd_kategori1  = $abjad_list[$this->kategori1_model->get_kode_sequence('K1', 2)];
			
	    		$data = array(
				'kd_kategori1' => $kd_kategori1,
				'nama_kategori1' => $nama_kategori1,
				'created_by' => $created_by,
				'created_date' => $created_date,
				'aktif' => $aktif
		    	);
			
			if ($this->kategori1_model->insert_row($data)) {
				$result = '{"success":true,"errMsg":""}';
			} else {
				$result = '{"success":false,"errMsg":"Process Failed.."}';
			}
            
        	} else { //edit         			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			    
           		$datau = array(
				'nama_kategori1' => $nama_kategori1,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
                		'aktif' => $aktif
            		);
           
			    if ($this->kategori1_model->update_row($kd_kategori1, $datau)) {
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
			'updated_by'	=>	$updated_by,
			'updated_date'	=>	$updated_date,
			'aktif' => '0'
		);
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                
	                $this->db->trans_start();
	                if ($this->kategori1_model->delete_row($id,$datau)) {
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
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : FALSE;
		
		if ($this->kategori1_model->delete_row($kd_kategori1)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
}
