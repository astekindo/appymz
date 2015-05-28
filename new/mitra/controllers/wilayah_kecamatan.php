<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wilayah_kecamatan extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('wilayah_kecamatan_model');
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
		
        $result = $this->wilayah_kecamatan_model->get_rows($search, $start, $limit);
        
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
            $result = $this->wilayah_kecamatan_model->get_row($id1,$id2,$id);
            
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
		$kd_kecamatan = isset($_POST['kd_kecamatan']) ? $this->db->escape_str($this->input->post('kd_kecamatan',TRUE)) : FALSE;
		$nama_kecamatan = isset($_POST['nama_kecamatan']) ? $this->db->escape_str($this->input->post('nama_kecamatan',TRUE)) : FALSE;
		$sequence = $this->wilayah_kecamatan_model->get_kode_sequence('KEC'.$kd_propinsi.$kd_kota, 2);
		$aktif = '1';
		
		if (!$kd_kecamatan) { //save  
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');          
			
            $kd_kecamatan = $kd_kota.$sequence;
            $data = array(
				'kd_kecamatan' => $kd_kecamatan,
                // 'kd_propinsi' => $kd_propinsi,
				'kd_kota' => $kd_kota,
				'nama_kecamatan' => strtoupper($nama_kecamatan),
				// 'created_by' => $created_by,
				// 'created_date' => $created_date,
                // 'aktif' => $aktif
            );

			
            if ($this->wilayah_kecamatan_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit   			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			          
           	$datau = array(
				'nama_kecamatan' => strtoupper($nama_kecamatan),
				// 'updated_by'	=>	$updated_by,
				// 'updated_date'	=>	$updated_date,
                // 'aktif' => $aktif
            );
           
            if ($this->wilayah_kecamatan_model->update_row($kd_kota, $kd_kecamatan, $datau)) {
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
				  
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                $kd = explode('-',$id);
	                $this->db->trans_start();
	                if ($this->wilayah_kecamatan_model->delete_row($kd[0],$kd[1])) {
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
		$kd_kota = isset($_POST['kd_kota']) ? $this->db->escape_str($this->input->post('kd_kota',TRUE)) : FALSE;
		$kd_kecamatan = isset($_POST['kd_kecamatan']) ? $this->db->escape_str($this->input->post('kd_kecamatan',TRUE)) : FALSE;
		
		if ($this->wilayah_kecamatan_model->delete_row($kd_propinsi,$kd_kota,$kd_kecamatan)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_kota($kd_propinsi=''){
		
		$result = $this->wilayah_kecamatan_model->get_kota($kd_propinsi);
        echo $result;
	}
}