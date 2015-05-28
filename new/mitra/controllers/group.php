<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('group_model');
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
		
        $result = $this->group_model->get_rows($search, $start, $limit);
        
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
            $result = $this->group_model->get_row($id,$id1);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group',TRUE)) : FALSE;
		$nama_group = isset($_POST['nama_group']) ? $this->db->escape_str($this->input->post('nama_group',TRUE)) : FALSE;
		$deskripsi = isset($_POST['deskripsi']) ? $this->db->escape_str($this->input->post('deskripsi',TRUE)) : FALSE;
       	$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : '0';
		
		if ( ! $kd_group) { //save  			
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');         
            
			$no='GR';
            $data = array(
				'kd_group' => $this->group_model->get_kode_sequence($no, 2),
                'nama_group' => $nama_group,
                'deskripsi' => $deskripsi,
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );
			
            if ($this->group_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit      			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			       
           	$datau = array(
				'nama_group' => $nama_group,
				'updated_by' => $updated_by,
                'deskripsi' => $deskripsi,
				'updated_date' => $updated_date,
                'aktif' => $aktif
            );
            if ($this->group_model->update_row($kd_group, $datau)) {
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
		$kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group',TRUE)) : FALSE;
		
		$data = array(
			'aktif' => '0'
		);
		
		if ($this->group_model->delete_row($kd_group,$data)) {
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
		$result = $this->group_model->get_all();
        
        echo $result;
	}
}