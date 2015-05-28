<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sub_blok_lokasi extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('sub_blok_lokasi_model');
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
		
        $result = $this->sub_blok_lokasi_model->get_rows($search, $start, $limit);
        
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
            $result = $this->sub_blok_lokasi_model->get_row($id, $id1, $id2);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : FALSE;
		$nama_sub_blok = isset($_POST['nama_sub_blok']) ? $this->db->escape_str($this->input->post('nama_sub_blok',TRUE)) : FALSE;
		$kapasitas = isset($_POST['kapasitas']) ? $this->db->escape_str($this->input->post('kapasitas',TRUE)) : FALSE;
       	$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : FALSE;
       	$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : FALSE;
       	$aktif = '1';
		
		if ( ! $kd_sub_blok) { //save      
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');  
			
            $data = array(
				'kd_sub_blok' => $this->sub_blok_lokasi_model->get_kode_sequence("SB".$kd_blok	,2),
				'kd_blok' => $kd_blok,
				'kd_lokasi' => $kd_lokasi,
				'nama_sub_blok' => strtoupper($nama_sub_blok),
				'kapasitas' => $kapasitas,
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );
			
            if ($this->sub_blok_lokasi_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit        
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			            
           	$datau = array(
				'nama_sub_blok' => strtoupper($nama_sub_blok),
				'kapasitas' => $kapasitas,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
				'aktif' => $aktif
            );
            if ($this->sub_blok_lokasi_model->update_row($kd_lokasi, $kd_blok, $kd_sub_blok, $datau)) {
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
	                if ($this->sub_blok_lokasi_model->delete_row($kd[0],$kd[1],$kd[2])) {
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
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : FALSE;
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : FALSE;
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : FALSE;
		
		if ($this->sub_blok_lokasi_model->delete_row($kd_lokasi, $kd_blok, $kd_sub_blok)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}

	public function get_blok($kd_lokasi){
		$result = $this->sub_blok_lokasi_model->get_blok($kd_lokasi);
        echo $result;
	}
        
	public function get_sub_blok($kd_lokasi,$kd_blok){
		$result = $this->sub_blok_lokasi_model->get_sub_blok($kd_lokasi,$kd_blok);
        echo $result;
	}
	
}