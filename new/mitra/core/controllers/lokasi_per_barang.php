<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lokasi_per_barang extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('lokasi_per_barang_model');
    }
	
	public function get_lokasi_barang(){
            $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
            $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
            $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
            $kdLokasi = isset($_POST['kdLokasi']) ? $this->db->escape_str($this->input->post('kdLokasi',TRUE)) : '';
            $kdBlok = isset($_POST['kdBlok']) ? $this->db->escape_str($this->input->post('kdBlok',TRUE)) : '';
            $kdSubBlok = isset($_POST['kdSubBlok']) ? $this->db->escape_str($this->input->post('kdSubBlok',TRUE)) : '';

            $result = $this->lokasi_per_barang_model->get_lokasi_barang($search, $kdLokasi, $kdBlok, $kdSubBlok, $start, $limit);

            echo $result;   
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_barang_per_lokasi($kdLokasi = "", $kdBlok = "", $kdSubBlok = ""){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $result = $this->lokasi_per_barang_model->get_barang_per_lokasi($kdLokasi, $kdBlok, $kdSubBlok, $search, $start, $limit);
        
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
            $result = $this->lokasi_per_barang_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
        $result = '{"success":true,"errMsg":""}';
    
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_rows(){
		$result = '{"success":true,"errMsg":""}';
    
        echo $result;
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row(){
		$result = '{"success":true,"errMsg":""}';
    
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$result = $this->lokasi_per_barang_model->get_all();
        
        echo $result;
	}
	
}