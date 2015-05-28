<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_create_request_asset extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_create_request_asset_model', 'pcra_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	// $no_ro = 'AR' . date('Ymd') . '-';
    	// $sequence = $this->pcra_model->get_kode_sequence($no_ro, 3);
    	echo '{"success":true,
				"data":{
					"no_ro":"",
					"tgl_ro":"' . date('d-m-Y'). '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		$no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : FALSE;
		$subject = isset($_POST['subject']) ? $this->db->escape_str($this->input->post('subject',TRUE)) : FALSE;
		$tgl_ro = isset($_POST['tgl_ro']) ? $this->db->escape_str($this->input->post('tgl_ro',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			if($tgl_ro){
				$tgl_ro = date('Y-m-d', strtotime($tgl_ro));
			}	
			$this->db->trans_begin();
			
			$no_ro = 'AR' . date('Ymd') . '-';
			$sequence = $this->pcra_model->get_kode_sequence($no_ro, 3);
			
			$no_ro = $no_ro.$sequence;
			
			$header_ra['no_ro'] = $no_ro;
			$header_ra['subject'] = $subject;
			//approval by kepala bagian
			$header_ra['status'] = '0';
			$header_ra['tgl_ro'] = $tgl_ro;
			$header_ra['close_ro'] = 0;
			$header_ra['kd_supplier'] = $kd_supplier;
			$header_ra['konsinyasi'] = 0;
			$header_ra['created_by'] = $this->session->userdata('username');
			$header_ra['created_date'] = date('Y-m-d H:i:s');
			$header_ra['updated_by'] = $this->session->userdata('username');
			$header_ra['updated_date'] = date('Y-m-d H:i:s');
			 
			$header_result = $this->pcra_model->insert_row('purchase.t_purchase_request', $header_ra);
			
			foreach($detail as $obj){
				unset($detail_pr);
				if($obj->kd_produk != '' && $obj->qty != ''){ //yg diinsert di detail ga boleh kosong
					$detail_ra['no_ro'] = $no_ro;
					$detail_ra['kd_produk'] = $obj->kd_produk;
					$detail_ra['qty'] = $obj->qty;
					$detail_ra['qty_adj'] = $obj->qty;
					$detail_ra['status'] = '0';
					$detail_ra['qty_po'] = 0;
					$detail_ra['created_by'] = $this->session->userdata('username');
					$detail_ra['created_date'] = date('Y-m-d H:i:s');
					$detail_ra['updated_by'] = $this->session->userdata('username');
					$detail_ra['updated_date'] = date('Y-m-d H:i:s');
			
					if($obj->qty+$obj->jml_stok > $obj->max_stok){
						echo '{"success":false,"errMsg":"Qty + Jml Stok tidak boleh lebih besar dari Max. Stok"}';
						$this->db->trans_rollback();
						exit;
					}
					if($this->pcra_model->insert_row('purchase.t_dtl_purchase_request', $detail_ra)){
						$detail_result++;
					}
				}
			}
			$this->db->trans_commit();
		}
		
		if ($header_result && $detail_result > 0) {
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
	public function get_all_produk($search_by = ""){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->pcra_model->get_all_produk($search_by, $keyword);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row_produk(){
		$search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by',TRUE)) : "";
		$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
        $result = $this->pcra_model->get_row_produk($search_by, $id);
        		    
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->pcra_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		
		$result = $this->pcra_model->search_produk_by_supplier($kd_supplier,$search, $start, $limit);
				
        echo $result;
	}
}
