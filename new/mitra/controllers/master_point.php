<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_point extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_point_model');
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
		
        $result = $this->master_point_model->get_rows($search, $start, $limit);
        
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
            $result = $this->master_point_model->get_row($id);
            
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
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : FALSE;
		$tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal',TRUE)) : FALSE;
		$point = isset($_POST['point']) ? $this->db->escape_str($this->input->post('point',TRUE)) : FALSE;
		
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		
		if (!$kd_point_setting) { //save         
            
            $data = array(
				'kd_point_setting' => 'P-'.$this->master_point_model->get_kode_sequence('P', 3),
                'kd_kategori1' => $kd_kategori1,
				'kd_kategori2' => $kd_kategori2,
				'kd_kategori3' => $kd_kategori3,
                'kd_kategori4' => $kd_kategori3,
				'point' => $point,
				'tgl_awal' => $tgl_awal,
                'aktif' => $aktif
            );
			
            if ($this->master_point_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit     			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			        
           	$datau = array(
                'kd_kategori1' => $kd_kategori1,
				'kd_kategori2' => $kd_kategori2,
				'kd_kategori3' => $kd_kategori3,
                'kd_kategori4' => $kd_kategori3,
				'point' => $point,
				'tgl_akhir' => $updated_date,
                'aktif' => $aktif
            );
           
            if ($this->master_point_model->update_row($kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $datau)) {
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
			'aktif' => '0'
		);
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                $kd = explode('-',$id);
	                $this->db->trans_start();
	                if ($this->master_point_model->delete_row($kd[0],$kd[1],$kd[2],$kd[3],$datau)) {
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
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : FALSE;
		
		if ($this->kategori3_model->delete_row($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_kategori3($kd_kategori1='' , $kd_kategori2=''){
		$result = $this->master_point_model->get_kategori3($kd_kategori1,$kd_kategori2);
        echo $result;
	}
        public function get_kategori4($kd_kategori1='' , $kd_kategori2='', $kd_kategori3=''){
		$result = $this->master_point_model->get_kategori4($kd_kategori1,$kd_kategori2,$kd_kategori2);
        echo $result;
	}
	
}