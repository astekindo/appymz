<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_approval_manager extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_approval_manager_model');
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
		
        $result = $this->konsinyasi_approval_manager_model->get_rows($search, $start, $limit);
        
        echo $result;
	}
	
	public function get_rows_detail($no_ro=''){
		$result = $this->konsinyasi_approval_manager_model->get_rows_detail($no_ro);
        
        echo $result;
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		// $no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : FALSE;
		// $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : FALSE;
		$postdata = isset($_POST['postdata']) ? $this->db->escape_str($this->input->post('postdata',TRUE)) : FALSE;
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		
		$data = explode('_',$postdata);
		$no_ro = $data[0];
		$keterangan = $data[1];
		$status = $data[2];
		$updated_by = $this->session->userdata('username');
		$updated_date = date('Y-m-d H:i:s');
		
		$datau = array(			
			'status' => $status,
			'updated_by'	=>	$updated_by,
			'updated_date'	=>	$updated_date,
			'tgl_ro'	=>	$updated_date,
		);
		
	    $this->db->trans_begin();
		$this->konsinyasi_approval_manager_model->update_row($no_ro, $datau);
			
		foreach($detail as $obj){
			unset($detail_app);
			
			$keterangan2 = ($obj->keterangan2 == 'null') ? "" : $obj->keterangan2;
			
			$no_ro_det = $obj->no_ro;
			$kd_prod_det = $obj->kd_produk;
			$detail_app['qty'] = $obj->qty;
			$detail_app['qty_adj'] = $obj->qty_adj;
			$detail_app['keterangan2'] = $keterangan2;
			$detail_app['status'] = $status;
			$detail_app['approval2'] = $updated_by;
			$detail_app['updated_by'] = $updated_by;
			$detail_app['updated_date'] = $updated_date;
			
			if($status == 9 && $keterangan2 == ''){
				echo '{"success":false,"errMsg":"Keterangan Harus Diisi"}';
				$this->db->trans_rollback();
				exit;
			}
			
			if($status == 2 && $obj->qty_adj <= 0){
				echo '{"success":false,"errMsg":"Untuk Approve RO Qty Adj harus lebih dari 0"}';
				$this->db->trans_rollback();
				exit;
			}
			
			if($obj->is_kelipatan_order == 'YA'){
				if($obj->qty_adj < $obj->min_order){
					echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
					$this->db->trans_rollback();
					exit;
				}
				if(($obj->qty_adj % $obj->min_order) != 0){
					echo '{"success":false,"errMsg":"Qty Order harus kelipatan dari Min. Order"}';
					$this->db->trans_rollback();
					exit;
				}
			}else{
				if($obj->qty_adj < $obj->min_order){
					echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
					$this->db->trans_rollback();
					exit;
				}
			
			}
			
			if ($obj->qty_adj != $obj->qty && $keterangan2 == ''){
				echo '{"success":false,"errMsg":"Keterangan Harus Diisi"}';
				$this->db->trans_rollback();
				exit;
			}else{
				if ( ! $this->konsinyasi_approval_manager_model->update_row_detail($no_ro_det, $kd_prod_det, $detail_app)) {
					echo '{"success":false,"errMsg":"Process Failed.."}';
					$this->db->trans_rollback();
					exit;
				}
			}		
		}
		
        $this->db->trans_commit();
        echo '{"success":true,"errMsg":""}';
	}
	
	public function update_row_detail(){
		$postdata = isset($_POST['postdata']) ? $this->db->escape_str($this->input->post('postdata',TRUE)) : FALSE;
		$data = explode('_',$postdata);
		$no_ro_det = $data[0];
		$kd_prod_det = $data[1];
		$qty = $data[2];
		$qty_adj = $data[3];
		$keterangan2 = $data[4];
		$status = $data[5];
		$updated_by = $this->session->userdata('username');
		$updated_date = date('Y-m-d H:i:s');
		
		$keterangan2= ($keterangan2 == 'null') ? "" : $keterangan2;
	    $this->db->trans_begin();

		$datau = array(
			'qty_adj' => $qty_adj,
			'status' => $status,
			'keterangan2' => $keterangan2,
			'approval2'	=>	$updated_by,
			'updated_by'	=>	$updated_by,
			'updated_date'	=>	$updated_date,
		);
		
		if($status == 9 && $keterangan2 == ''){
			echo '{"success":false,"errMsg":"Keterangan Harus Diisi"}';
			exit;
		}
		
		if($status == 2 && $qty_adj <= 0){
			echo '{"success":false,"errMsg":"Untuk Approve RO Qty Adj harus lebih dari 0"}';
			exit;
		}
		if($obj->is_kelipatan_order == 'YA'){
			if($obj->qty_adj < $obj->min_order){
				echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
				exit;
			}
			if(($obj->qty_adj % $obj->min_order) != 0){
				echo '{"success":false,"errMsg":"Qty Order harus kelipatan dari Min. Order"}';
				exit;
			}
		}else{
			if($obj->qty_adj < $obj->min_order){
				echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
				exit;
			}
		
		}
	    if ($qty_adj != $qty && $keterangan2 == ''){
				$result = '{"success":false,"errMsg":"Keterangan Harus Diisi"}';				
		}else{
			if ($this->konsinyasi_approval_manager_model->update_row_detail($no_ro_det, $kd_prod_det, $datau)) {
				$result = '{"success":true,"errMsg":""}';
				$this->db->trans_commit();
			} else {
				$result = '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
			}
		}
                
        echo $result;
	}
	
}
