<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori3 extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('kategori3_model');
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
		
        $result = $this->kategori3_model->get_rows($search, $start, $limit);
        
        echo $result;
	}
	
	public function get_nama_kategori3(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
        $result = $this->kategori3_model->get_nama_kategori3($search, $start, $limit);
        
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
			$id2 = isset($_POST['id2']) ? $this->db->escape_str($this->input->post('id2',TRUE)) : NULL;
            $result = $this->kategori3_model->get_row($id1,$id2,$id);
            
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
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : FALSE;
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : FALSE;
		$nama_kategori3 = isset($_POST['nama_kategori3']) ? $this->db->escape_str($this->input->post('nama_kategori3',TRUE)) : FALSE;
		$nama_kategori3 = strtoupper($nama_kategori3);
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		if($aktif=='0')
			$aktif = 'FALSE';
		else $aktif = 'TRUE';
				
		$where = array(
						'nama_kategori3' => $nama_kategori3, 
						'kd_kategori2' => $kd_kategori2,
						'kd_kategori1' => $kd_kategori1
				);
				
		$check_result = $this->kategori3_model->check_data_array($where,'mst.t_kategori3');
		
		if($kd_kategori3){
			$where = array(
					'nama_kategori3' => $nama_kategori3, 
					'kd_kategori3' => $kd_kategori3,
					'kd_kategori2' => $kd_kategori2,
					'kd_kategori1' => $kd_kategori1
			);
			$field_result = $this->kategori3_model->get_data_field_array('nama_kategori3',$where,'mst.t_kategori3');
			if($field_result->nama_kategori3 == $nama_kategori3){
				$check_result = FALSE;
			}
		}
		
		if($check_result){
			$errMsg =  "Data dengan Nama Kategori3: ".$nama_kategori3." Sudah Ada di dalam Database. Silahkan Input Ulang";
			$result = '{"success":false,"errMsg":"'.$errMsg.'"}';
			echo $result;
			exit;
		}
		
		if (!$kd_kategori3) { //save  
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');          
            
            $data = array(
				'kd_kategori3' => $this->kategori3_model->get_kode_sequence('K3'.$kd_kategori1.$kd_kategori2, 3),
                'kd_kategori1' => $kd_kategori1,
				'kd_kategori2' => $kd_kategori2,
				'nama_kategori3' => strtoupper($nama_kategori3),
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );

			
            if ($this->kategori3_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit   			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			          
           	$datau = array(
				'nama_kategori3' => strtoupper($nama_kategori3),
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
                'aktif' => $aktif
            );
           
            if ($this->kategori3_model->update_row($kd_kategori1, $kd_kategori2, $kd_kategori3, $datau)) {
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
	                $kd = explode('-',$id);
	                $this->db->trans_start();
	                if ($this->kategori3_model->delete_row($kd[0],$kd[1],$kd[2],$datau)) {
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
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : FALSE;
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : FALSE;
		
		if ($this->kategori3_model->delete_row($kd_kategori1,$kd_kategori2,$kd_kategori3)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_kategori2($kd_kategori1=NULL){
		
		$result = $this->kategori3_model->get_kategori2($kd_kategori1);
        echo $result;
	}
}
