<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier_per_barang extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('supplier_per_barang_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows($kd_supplier=''){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		if($kd_supplier==''){
			$kd_supplier = isset($_POST['fieldId']) ? $this->db->escape_str($this->input->post('fieldId',TRUE)) : $kd_supplier;
		}
		
        $result = $this->supplier_per_barang_model->get_rows($kd_supplier, $search, $start, $limit);
        
        echo $result;
	}
	
	public function supplier_(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
        $result = $this->supplier_per_barang_model->supplier_($search, $start, $limit);
        
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
                        $result = $this->supplier_per_barang_model->get_row($id,$id1);
            
			if($result->disk_persen_supp1 != 0 AND $result->disk_amt_supp1 == 0){
			$result->disk_supp1_op = 'persen';
			$result->disk_supp1 = $result->disk_persen_supp1;
			}else if($result->disk_persen_supp1 == 0 AND $result->disk_amt_supp1 != 0){
				$result->disk_supp1_op = 'amount';
				$result->disk_supp1 = $result->disk_amt_supp1;
			}else {
				$result->disk_supp1_op = 'persen';
				$result->disk_supp1 = 0;		
			}
			
			if($result->disk_persen_supp2 != 0 AND $result->disk_amt_supp2 == 0){
				$result->disk_supp2_op = 'persen';
				$result->disk_supp2 = $result->disk_persen_supp2;
			}else if($result->disk_persen_supp2 == 0 AND $result->disk_amt_supp2 != 0){
				$result->disk_supp2_op = 'amount';
				$result->disk_supp2 = $result->disk_amt_supp2;
			}else {
				$result->disk_supp2_op = 'persen';
				$result->disk_supp2 = 0;		
			}
			
			if($result->disk_persen_supp3 != 0 AND $result->disk_amt_supp3 == 0){
				$result->disk_supp3_op = 'persen';
				$result->disk_supp3 = $result->disk_persen_supp3;
			}else if($result->disk_persen_supp3 == 0 AND $result->disk_amt_supp3 != 0){
				$result->disk_supp3_op = 'amount';
				$result->disk_supp3 = $result->disk_amt_supp3;
			}else {
				$result->disk_supp3_op = 'persen';
				$result->disk_supp3 = 0;		
			}
			
			if($result->disk_persen_supp4 != 0 AND $result->disk_amt_supp4 == 0){
				$result->disk_supp4_op = 'persen';
				$result->disk_supp4 = $result->disk_persen_supp4;
			}else if($result->disk_persen_supp4 == 0 AND $result->disk_amt_supp4 != 0){
				$result->disk_supp4_op = 'amount';
				$result->disk_supp4 = $result->disk_amt_supp4;
			}else {
				$result->disk_supp4_op = 'persen';
				$result->disk_supp4 = 0;		
			}
			
			
            
			if($result->disk_persen_dist1 != 0 AND $result->disk_amt_dist1 == 0){
			$result->disk_dist1_op = 'persen';
			$result->disk_dist1 = $result->disk_persen_dist1;
			}else if($result->disk_persen_dist1 == 0 AND $result->disk_amt_dist1 != 0){
				$result->disk_dist1_op = 'amount';
				$result->disk_dist1 = $result->disk_amt_dist1;
			}else {
				$result->disk_dist1_op = 'persen';
				$result->disk_dist1 = 0;		
			}
			
			if($result->disk_persen_dist2 != 0 AND $result->disk_amt_dist2 == 0){
				$result->disk_dist2_op = 'persen';
				$result->disk_dist2 = $result->disk_persen_dist2;
			}else if($result->disk_persen_dist2 == 0 AND $result->disk_amt_dist2 != 0){
				$result->disk_dist2_op = 'amount';
				$result->disk_dist2 = $result->disk_amt_dist2;
			}else {
				$result->disk_dist2_op = 'persen';
				$result->disk_dist2 = 0;		
			}
			
			if($result->disk_persen_dist3 != 0 AND $result->disk_amt_dist3 == 0){
				$result->disk_dist3_op = 'persen';
				$result->disk_dist3 = $result->disk_persen_dist3;
			}else if($result->disk_persen_dist3 == 0 AND $result->disk_amt_dist3 != 0){
				$result->disk_dist3_op = 'amount';
				$result->disk_dist3 = $result->disk_amt_dist3;
			}else {
				$result->disk_dist3_op = 'persen';
				$result->disk_dist3 = 0;		
			}
			
			if($result->disk_persen_dist4 != 0 AND $result->disk_amt_dist4 == 0){
				$result->disk_dist4_op = 'persen';
				$result->disk_dist4 = $result->disk_persen_dist4;
			}else if($result->disk_persen_dist4 == 0 AND $result->disk_amt_dist4 != 0){
				$result->disk_dist4_op = 'amount';
				$result->disk_dist4 = $result->disk_amt_dist4;
			}else {
				$result->disk_dist4_op = 'persen';
				$result->disk_dist4 = 0;		
			}
			
			
            echo '{"success":true,"data":'.json_encode($result).'}';
        }
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
	                if ($this->supplier_per_barang_model->delete_row($kd[0],$kd[1])) {
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
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : FALSE;
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;

		if ($this->kategori1_model->delete_row($kd_supplier,$kd_produk)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_supplier(){
		$result = $this->supplier_per_barang_model->get_supplier();
        
        echo $result;
	}
	
	// public function get_produk($search_by = ""){
        
		// $keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		// $result = $this->supplier_per_barang_model->get_all_produk($search_by, $keyword);
        // echo $result;
	// }
	
	public function get_produk(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$result = $this->supplier_per_barang_model->get_produk($search,$start,$limit,$kd_supplier);
        echo $result;
	}
	
	public function update_row(){
		$action = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : FALSE;
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$waktu_top = isset($_POST['waktu_top']) ? $this->db->escape_str($this->input->post('waktu_top',TRUE)) : FALSE;
		$by_kategori = isset($_POST['by_kategori-checkbox']) ? $this->db->escape_str($this->input->post('by_kategori-checkbox',TRUE)) : FALSE;
		$by_produk = isset($_POST['by_produk-checkbox']) ? $this->db->escape_str($this->input->post('by_produk-checkbox',TRUE)) : FALSE;
		$aktif = '1';
                $no_bukti ='xxx';
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		if($by_kategori == 'on' ){	
			foreach($detail as $obj){
				$where = array(
							'kd_produk' => $obj->kd_produk,
							'kd_supplier' => $kd_supplier,
						);
				$check_result = $this->supplier_per_barang_model->check_data_array($where,'mst.t_supp_per_brg');
				
				
				if($check_result){
					$field_result = $this->supplier_per_barang_model->get_data_field('nama_produk','kd_produk',$obj->kd_produk,'mst.t_produk');
					
					$obj->add = false;
					
				}
				
				if($obj->add == 1){
					$created_by = $this->session->userdata('username');
					$created_date = date('Y-m-d H:i:s');
					
					$data = array(
						'kd_supplier'	=>	$kd_supplier,
						'kd_produk'	=>	$obj->kd_produk,
						'waktu_top'	=>	$waktu_top,
						'created_by' => $created_by,
						'created_date' => $created_date,
						'aktif'	=>	$aktif,
                                                'no_bukti' => $no_bukti,

					);

					if ($this->supplier_per_barang_model->insert_row($data)) {
						$result = '{"success":true,"errMsg":""}';
					} else {
						$result = '{"success":false,"errMsg":"Process Failed.."}';
					}
				}
			}
		} else if($by_produk == 'on'){ //save 
			$where = array(
							'kd_produk' => $kd_produk,
							'kd_supplier' => $kd_supplier,
						);
			$check_result = $this->supplier_per_barang_model->check_data_array($where,'mst.t_supp_per_brg');
			
			
			if($check_result){
				$field_result = $this->supplier_per_barang_model->get_data_field('nama_produk','kd_produk',$kd_produk,'mst.t_produk');
				$nama_produk =	str_replace('"',"''",$field_result->nama_produk); 
				$errMsg =  "Data Supplier per Barang dengan Nama Produk: ".$nama_produk.". Sudah Ada di dalam Database. Silahkan Input Ulang";
				$result = '{"success":false,"errMsg":"'.$errMsg.'"}';
				echo $result;
				exit;
				
			}
				
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');
			
            $data = array(
				'kd_supplier'	=>	$kd_supplier,
				'kd_produk'	=>	$kd_produk,
				'waktu_top'	=>	$waktu_top,
				'created_by' => $created_by,
				'created_date' => $created_date,
				'aktif'	=>	$aktif,
                                'no_bukti' => $no_bukti,

            );

            if ($this->supplier_per_barang_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
			
		}
		
        echo $result;
	}
	public function search_produk_by_kategori(){
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
		
		$data_result = $this->supplier_per_barang_model->search_produk_by_kategori($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4);
		$results = $data_result;
		
		echo '{success:true,data:'.json_encode($results).'}';
	}
         public function update_aktif(){
		
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		$kd_barang = isset($_POST['kd_barang']) ? $this->db->escape_str($this->input->post('kd_barang',TRUE)) : FALSE;
		$kd_supplier = isset($_POST['kd_supplier_akt']) ? $this->db->escape_str($this->input->post('kd_supplier_akt',TRUE)) : FALSE;
		{ //edit            			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			
           	$datau = array(
				'aktif'	=>	$aktif,
				
				);
           
            if ($this->supplier_per_barang_model->update_aktif($kd_supplier,$kd_barang, $datau)) {
		$result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }       
        
        echo $result;
	}
        
}
