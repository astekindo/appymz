<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order_asset extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_receive_order_asset_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	// $no_do = 'RB' . date('Ymd') . '-';
    	// $sequence = $this->proa_model->get_kode_sequence($no_do, 3);
    	echo '{"success":true,
				"data":{
					"no_do":"",
					"tanggal":"' . date('d-M-Y'). '",
					"tanggal_terima":"' . date('d-m-Y') . '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
                $tanggal_terima = isset($_POST['tanggal_terima']) ? $this->db->escape_str($this->input->post('tanggal_terima',TRUE)) : '';
                $tanggal_terima = date('Ymd', strtotime($tanggal_terima));
                $no_do = 'RA' . $tanggal_terima . '-';
                $sequence = $this->pembelian_receive_order_asset_model->get_kode_sequence($no_do, 3);
                $no_do = $no_do . $sequence;
		//$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		//$tanggal_terima = isset($_POST['tanggal_terima']) ? $this->db->escape_str($this->input->post('tanggal_terima',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$bukti_supplier = isset($_POST['bukti_supplier']) ? $this->db->escape_str($this->input->post('bukti_supplier',TRUE)) : '';
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		$header_result = FALSE;
		$detail_result = 0;
		
		if(count($detail) > 0){	
		
			if($tanggal_terima){
				$tanggal_terima = date('Y-m-d', strtotime($tanggal_terima));
			}	
			if($tanggal){
				$tanggal = date('Y-m-d', strtotime($tanggal));
			}
			$this->db->trans_start();
			
			//$no_do = 'RA' . date('Ymd') . '-';
			//$sequence = $this->pembelian_receive_order_asset_model->get_kode_sequence($no_do, 3);
			//$no_do = $no_do.$sequence;
			
			$header_do['no_do'] = $no_do;			
			$header_do['kd_supplier'] = $kd_supplier;
			$header_do['tanggal'] = $tanggal;
			$header_do['tanggal_terima'] = $tanggal_terima;
			$header_do['no_bukti_supplier'] = $bukti_supplier;		
			$header_do['created_by'] = $this->session->userdata('username');
			$header_do['created_date'] = date('Y-m-d H:i:s');
			$header_do['updated_by'] = $this->session->userdata('username');
			$header_do['updated_date'] = date('Y-m-d H:i:s');
			
			 
			$header_result = $this->pembelian_receive_order_asset_model->insert_row('purchase.t_receive_order', $header_do);
			
			foreach($detail as $obj){
				$kd_lokasi = substr($obj->sub, 0, 2);
				$kd_blok = substr($obj->sub, 2, 2);
				$kd_sub_blok = substr($obj->sub, 4, 2);
				unset($detail_do);
				$detail_do['no_do'] = $no_do;
				$detail_do['no_po'] = $obj->no_po;
				$detail_do['kd_produk'] = $obj->kd_produk;
				$detail_do['qty_beli'] = $obj->qty_po;
				$detail_do['qty_terima'] = $obj->qty_do;			
				$detail_do['kd_lokasi'] = $kd_lokasi;
				$detail_do['kd_blok'] = $kd_blok;
				$detail_do['kd_sub_blok'] = $kd_sub_blok;						
				$detail_do['created_by'] = $this->session->userdata('username');
				$detail_do['created_date'] = date('Y-m-d H:i:s');
				$detail_do['updated_by'] = $this->session->userdata('username');
				$detail_do['updated_date'] = date('Y-m-d H:i:s');
			
				if($this->pembelian_receive_order_asset_model->insert_row('purchase.t_dtl_receive_order', $detail_do)){
					$detail_result++;
					unset($trx_inventory);
					$trx_inventory['kd_produk'] = $obj->kd_produk;
					$trx_inventory['no_ref'] = $no_do;
					$trx_inventory['kd_lokasi'] = $kd_lokasi;
					$trx_inventory['kd_blok'] = $kd_blok;
					$trx_inventory['kd_sub_blok'] = $kd_sub_blok;
					$trx_inventory['qty_in'] = $obj->qty_do;
					$trx_inventory['qty_out'] = 0;
					$trx_inventory['type'] = 1;
					$trx_inventory['tgl_trx'] = $tanggal_do;
					$trx_inventory['created_by'] = $this->session->userdata('username');
					$trx_inventory['created_date'] = date('Y-m-d H:i:s');
					$this->pembelian_receive_order_asset_model->insert_row('inv.t_trx_inventory', $trx_inventory);
					
                                        if($this->pembelian_receive_order_asset_model->get_stok_inventory($obj->kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)){
						$sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty_do . " WHERE kd_produk = '" . $obj->kd_produk . "' AND kd_lokasi = '".$kd_lokasi."' AND kd_blok = '".$kd_blok."' AND kd_sub_blok = '".$kd_sub_blok."'";
						$this->pembelian_receive_order_asset_model->query_update($sql);
					}
					else{
						unset($brg_inventory);
						$brg_inventory['kd_produk'] = $obj->kd_produk;
						$brg_inventory['kd_lokasi'] = $kd_lokasi;
						$brg_inventory['kd_blok'] = $kd_blok;
						$brg_inventory['kd_sub_blok'] = $kd_sub_blok;
						$brg_inventory['qty_oh'] = $obj->qty_do;
						$brg_inventory['created_by'] = $this->session->userdata('username');
						$brg_inventory['created_date'] = date('Y-m-d H:i:s');
						
						$this->pembelian_receive_order_asset_model->insert_row("inv.t_brg_inventory",$brg_inventory);
					}	
				}
			}
			$this->db->trans_complete();
		}
		$title = 'RECEIVE ORDER ASSET';
		if ($header_result && $detail_result > 0) {		
			$result = '{"success":true,"errMsg":"Pembuatan RO Asset Berhasil, NO RA: '.$no_do.' ","printUrl":"' . site_url("pembelian_receive_order/print_form/" . $no_do . "/" .$title) . '"}';
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
	public function get_all_po(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : "";
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->pembelian_receive_order_asset_model->get_all_po($kd_supplier,$search);
        
        echo $result;
	}


	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_po_detail(){
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : "";
		$result = $this->pembelian_receive_order_asset_model->get_po_detail($no_po);
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_sub_blok(){
		$result = '{"success" : true, record : 0, "data" : ""} ';
				
        
        echo $result;
	}
	public function get_rows_lokasi(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
		
        $result = $this->pembelian_receive_order_asset_model->get_rows_lokasi($kd_produk, $search, $start, $limit);
        
        echo $result;
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

		$result = $this->pembelian_receive_order_asset_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_no_po(){
		$no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : '';
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->pembelian_receive_order_asset_model->get_po_detail($no_po, $search);
				
        echo $result;
	}
	
}
