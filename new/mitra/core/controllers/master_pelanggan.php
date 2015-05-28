<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_pelanggan extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_pelanggan_model');
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
		
        $result = $this->master_pelanggan_model->get_rows($search, $start, $limit);
        
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
            $result = $this->master_pelanggan_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
		$nama_pelanggan = isset($_POST['nama_pelanggan']) ? $this->db->escape_str($this->input->post('nama_pelanggan',TRUE)) : FALSE;
		$no_telp = isset($_POST['no_telp']) ? $this->db->escape_str($this->input->post('no_telp',TRUE)) : FALSE;
		$kdprop = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi',TRUE)) : FALSE;
		$kota = isset($_POST['kd_kota']) ? $this->db->escape_str($this->input->post('kd_kota',TRUE)) : FALSE;
		$kecamatan = isset($_POST['kd_kecamatan']) ? $this->db->escape_str($this->input->post('kd_kecamatan',TRUE)) : FALSE;
		$alamat_kirim = isset($_POST['alamat_kirim']) ? $this->db->escape_str($this->input->post('alamat_kirim',TRUE)) : FALSE;
		$alamat_tagih = isset($_POST['alamat_tagih']) ? $this->db->escape_str($this->input->post('alamat_tagih',TRUE)) : FALSE;
		$npwp = isset($_POST['npwp']) ? $this->db->escape_str($this->input->post('npwp',TRUE)) : FALSE;
		$alamat_npwp = isset($_POST['alamat_npwp']) ? $this->db->escape_str($this->input->post('alamat_npwp',TRUE)) : FALSE;
		$kodepos = isset($_POST['kodepos']) ? $this->db->escape_str($this->input->post('kodepos',TRUE)) : FALSE;
		$nama_pic = isset($_POST['nama_pic']) ? $this->db->escape_str($this->input->post('nama_pic',TRUE)) : FALSE;
		$no_telp_pic = isset($_POST['no_telp_pic']) ? $this->db->escape_str($this->input->post('no_telp_pic',TRUE)) : FALSE;
		$top_dist = isset($_POST['top_dist']) ? $this->db->escape_str($this->input->post('top_dist',TRUE)) : FALSE;
		$limit_dist = isset($_POST['limit_dist']) ? $this->db->escape_str($this->input->post('limit_dist',TRUE)) : FALSE;
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		
		if ( ! $kd_pelanggan) { //save    
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');        
            
            $data = array(
				'kd_pelanggan' => 'PLG-'.$this->master_pelanggan_model->get_kode_sequence("M",4),
				'nama_pelanggan' => $nama_pelanggan,
				'no_telp' => $no_telp,
                'kd_propinsi' => $kdprop,
				'kd_kota' => $kota,
				'kd_kecamatan' => $kecamatan,
				//'kd_kelurahan' => $kelurahan,
				'alamat_kirim' => $alamat_kirim,
				'alamat_tagih' => $alamat_tagih,
				'npwp' => $npwp,
				'alamat_npwp' => $alamat_npwp,
				'kodepos' => $kodepos,
				'nama_pic' => $nama_pic,
				'no_telp_pic' => $no_telp_pic,
				'top_dist' => $top_dist,
				'limit_dist' => $limit_dist,
				'aktif' => $aktif
            );
			
            if ($this->master_pelanggan_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit        
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			            
           	$datau = array(
				'nama_pelanggan' => $nama_pelanggan,
				'no_telp' => $no_telp,
                'kd_propinsi' => $kdprop,
				'kd_kota' => $kota,
				'kd_kecamatan' => $kecamatan,
				//'kd_kelurahan' => $kelurahan,
				'alamat_kirim' => $alamat_kirim,
				'alamat_tagih' => $alamat_tagih,
				'npwp' => $npwp,
				'alamat_npwp' => $alamat_npwp,
				'kodepos' => $kodepos,
				'nama_pic' => $nama_pic,
				'no_telp_pic' => $no_telp_pic,
				'top_dist' => $top_dist,
				'limit_dist' => $limit_dist,
				'aktif' => $aktif
            );
           
            if ($this->master_pelanggan_model->update_row($kd_pelanggan, $datau)) {
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
	                if ($this->master_pelanggan_model->delete_row($id)) {
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
		$kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : FALSE;
		
		if ($this->master_pelanggan_model->delete_row($kd_pelanggan)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
}