<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_ekspedisi extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_ekspedisi_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows_master(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
		
        $result = $this->master_ekspedisi_model->get_rows_master($search, $start, $limit);
        
        echo $result;
	}
	
	public function get_rows_price($kd_ekspedisi=''){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		if($kd_ekspedisi==''){
			$kd_ekspedisi = isset($_POST['fieldId']) ? $this->db->escape_str($this->input->post('fieldId',TRUE)) : $kd_ekspedisi;
		}
        $result = $this->master_ekspedisi_model->get_rows_price($kd_ekspedisi, $search, $start, $limit);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row_master(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
			
            $result = $this->master_ekspedisi_model->get_row_master($id);
			
            echo '{"success":true,"data":'.json_encode($result).'}';
        }
	}
	public function get_row_price(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
			
            $result = $this->master_ekspedisi_model->get_row_price($id);
			
            echo '{"success":true,"data":'.json_encode($result).'}';
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action',TRUE)) : FALSE;
		$kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi',TRUE)) : FALSE;
		$nama_ekspedisi = isset($_POST['nama_ekspedisi']) ? $this->db->escape_str($this->input->post('nama_ekspedisi',TRUE)) : FALSE;
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		$tujuan = isset($_POST['tujuan']) ? $this->db->escape_str($this->input->post('tujuan',TRUE)) : FALSE;
		$kd_satuan = isset($_POST['nm_satuan']) ? $this->db->escape_str($this->input->post('nm_satuan',TRUE)) : FALSE;
		$rp_harga = isset($_POST['rp_harga']) ? $this->db->escape_str($this->input->post('rp_harga',TRUE)) : FALSE;

		if ($action=="save_master") {         
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');  
			
			$kd_ekspedisi = $this->master_ekspedisi_model->get_kode_sequence("ME",3);
            $data = array(
				'kd_ekspedisi'	=>	$kd_ekspedisi,
				'nama_ekspedisi'	=>	$nama_ekspedisi,
				'aktif'	=>	$aktif,
            );

            if ($this->master_ekspedisi_model->insert_row("mst.t_ekpedisi",$data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else if ($action=="update_master") {             			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			
           	$datau = array(
				'nama_ekspedisi' =>	$nama_ekspedisi,				
				'aktif'	=>	$aktif
            );
           
            if ($this->master_ekspedisi_model->update_row("mst.t_ekpedisi", $kd_ekspedisi, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else if ($action=="save_price") {         
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');  

			
            $data = array(
				'kd_ekspedisi'	=>	$kd_ekspedisi,
				'tujuan'	=>	$tujuan,
				'kd_satuan'	=>	$kd_satuan,
				'rp_harga'	=>	$rp_harga,
            );

            if ($this->master_ekspedisi_model->insert_row("mst.t_ekspedisi_price",$data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else if ($action=="update_price") {             			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			// $where = array(
				// 'kd_ekspedisi' => $kd_ekspedisi
			// );
           	$datau = array(
				'tujuan' =>	$tujuan,
				'kd_satuan'	=>	$kd_satuan,
				'rp_harga'	=>	$rp_harga,
            );
           
            if ($this->master_ekspedisi_model->update_row("mst.t_ekspedisi_price", $kd_ekspedisi, $datau)) {
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
	                $kd = explode('-', $id);
	                $this->db->trans_start();
	                if ($this->master_ekspedisi_model->delete_row($kd[0],$kd[1])) {
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
	
	public function get_ekpedisi(){
		$result = $this->master_ekspedisi_model->get_ekpedisi();
        
        echo $result;
	}
	
	public function get_produk($search_by = ""){
        
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->master_ekspedisi_model->get_all_produk($search_by, $keyword);
        echo $result;
	}	
	
	public function delete_row(){
		$kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi',TRUE)) : FALSE;
		$tujuan = isset($_POST['tujuan']) ? $this->db->escape_str($this->input->post('tujuan',TRUE)) : FALSE;

		if ($this->master_ekspedisi_model->delete_row($kd_ekspedisi, $tujuan)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
}
