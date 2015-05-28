<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buku extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('buku_model');
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
		
        $result = $this->buku_model->get_rows($search, $start, $limit);
        
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
            $result = $this->buku_model->get_row($id,$id1);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_buku = isset($_POST['kd_buku']) ? $this->db->escape_str($this->input->post('kd_buku',TRUE)) : FALSE;
		$nama_buku = isset($_POST['nama_buku']) ? $this->db->escape_str($this->input->post('nama_buku',TRUE)) : FALSE;
       	$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : '0';
		
		if ( ! $kd_buku) { //save           
            
            $data = array(
				'kd_buku' => $this->buku_model->get_kode_sequence('B', 001),
                'nama_buku' => $nama_buku,
                'aktif' => '1'
            );
			
            if ($this->buku_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit      			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			       
           	$datau = array(
				'nama_buku' => $nama_buku,
                'aktif' => '1'
            );
            if ($this->buku_model->update_row($kd_buku, $datau)) {
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
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row(){
		$kd_buku = isset($_POST['kd_buku']) ? $this->db->escape_str($this->input->post('kd_buku',TRUE)) : FALSE;
		
		$data = array(
			'aktif' => '0'
		);
		
		if ($this->buku_model->delete_row($kd_buku,$data)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$result = $this->buku_model->get_all();
        
        echo $result;
	}
}