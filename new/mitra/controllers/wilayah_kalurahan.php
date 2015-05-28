<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Wilayah_kalurahan extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('wilayah_kalurahan_model');
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
		
        $result = $this->wilayah_kalurahan_model->get_rows($search, $start, $limit);
        
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
			$id3 = isset($_POST['id3']) ? $this->db->escape_str($this->input->post('id3',TRUE)) : NULL;
            $result = $this->wilayah_kalurahan_model->get_row($id1,$id2,$id3,$id);
            
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
		$kd_kalurahan = isset($_POST['kd_kalurahan']) ? $this->db->escape_str($this->input->post('kd_kalurahan',TRUE)) : FALSE;
		$nama_kalurahan = isset($_POST['nama_kalurahan']) ? $this->db->escape_str($this->input->post('nama_kalurahan',TRUE)) : FALSE;
		
		
		if (!$kd_kalurahan) { //save         
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s'); 
            $sequence = $this->wilayah_kalurahan_model->get_kode_sequence('KEL'.$kd_propinsi.$kd_kota.$kd_kecamatan, 2);
            $data = array(
				'kd_kalurahan' => $kd_kecamatan.$sequence,
				'kd_kecamatan' => $kd_kecamatan,
                'nama_kalurahan' => strtoupper($nama_kalurahan)
				
            );
			
            if ($this->wilayah_kalurahan_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit     			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			        
           	$datau = array(
				'nama_kalurahan' => strtoupper($nama_kalurahan),
				
            );
           
            if ($this->wilayah_kalurahan_model->update_row($kd_kecamatan, $kd_kalurahan, $datau)) {
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
	                $kd = explode('-',$id);
	                $this->db->trans_start();
	                if ($this->wilayah_kalurahan_model->delete_row($kd[0],$kd[1])) {
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
		$kd_kalurahan = isset($_POST['kd_kalurahan']) ? $this->db->escape_str($this->input->post('kd_kalurahan',TRUE)) : FALSE;
		
		if ($this->kecamatan_model->delete_row($kd_propinsi,$kd_kota,$kd_kecamatan,$kd_kalurahan)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_kecamatan($kd_propinsi='' , $kd_kota=''){
		$result = $this->wilayah_kalurahan_model->get_kecamatan($kd_propinsi,$kd_kota);
        echo $result;
	}
	
}