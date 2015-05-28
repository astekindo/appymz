<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jenis_pembayaran extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('jenis_pembayaran_model');
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
		
        $result = $this->jenis_pembayaran_model->get_rows($search, $start, $limit);
        
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
            $result = $this->jenis_pembayaran_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_jenis_bayar = isset($_POST['kd_jenis_bayar']) ? $this->db->escape_str($this->input->post('kd_jenis_bayar',TRUE)) : FALSE;
		$nm_pembayaran = isset($_POST['nm_pembayaran']) ? $this->db->escape_str($this->input->post('nm_pembayaran',TRUE)) : FALSE;
		$charge = isset($_POST['charge']) ? $this->db->escape_str($this->input->post('charge',TRUE)) : FALSE;
		$jenis = isset($_POST['jenis']) ? $this->db->escape_str($this->input->post('jenis',TRUE)) : FALSE;
		$status_aktif = isset($_POST['status_aktif']) ? $this->db->escape_str($this->input->post('status_aktif',TRUE)) : FALSE;
		$aktif = '1';
		
		if ( ! $kd_jenis_bayar) { //save   
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');  
			
            $data = array(
				'kd_jenis_bayar' => $this->jenis_pembayaran_model->get_kode_sequence('JP', 2),
                'nm_pembayaran' => $nm_pembayaran,
                'charge' => $charge,
                'jenis' => $jenis,
                'status_aktif' => $status_aktif,
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );
			
            if ($this->jenis_pembayaran_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit           			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			  
           	$datau = array(
				'nm_pembayaran' => $nm_pembayaran,
                'charge' => $charge,
                'jenis' => $jenis,
                'status_aktif' => $status_aktif,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
                'aktif' => $aktif
            );
           
            if ($this->jenis_pembayaran_model->update_row($kd_jenis_bayar, $datau)) {
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
	                if ($this->jenis_pembayaran_model->delete_row($id)) {
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
		$kd_jenis_bayar = isset($_POST['kd_jenis_bayar']) ? $this->db->escape_str($this->input->post('kd_jenis_bayar',TRUE)) : FALSE;
		
		if ($this->jenis_pembayaran_model->delete_row($kd_jenis_bayar)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
}