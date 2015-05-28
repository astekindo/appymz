<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_pembelian extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('harga_pembelian_model', 'hp_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_hp = 'HP' . date('Ymd') . '-';
    	$sequence = $this->hp_model->get_kode_sequence($no_hp, 3);
    	echo '{"success":true,
				"data":{
					"no_hp":"' . $no_hp . $sequence . '",
					"tanggal":"' . date('d-m-Y'). '"
				}
			}';
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		$no_hp = isset($_POST['no_hp']) ? $this->db->escape_str($this->input->post('no_hp',TRUE)) : '';
		$no_bukti_filter = isset($_POST['no_bukti_filter']) ? $this->db->escape_str($this->input->post('no_bukti_filter',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$kategori1 = isset($_POST['kategori1']) ? $this->db->escape_str($this->input->post('kategori1',TRUE)) : '';
		$kategori2 = isset($_POST['kategori2']) ? $this->db->escape_str($this->input->post('kategori2',TRUE)) : '';
		$kategori3 = isset($_POST['kategori3']) ? $this->db->escape_str($this->input->post('kategori3',TRUE)) : '';
		$kategori4 = isset($_POST['kategori4']) ? $this->db->escape_str($this->input->post('kategori4',TRUE)) : '';
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';
		$action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action',TRUE)) : FALSE;
		
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$this->db->trans_begin();
		foreach($detail as $obj){
			if($obj->edited == 'Y'){
			
				if($kd_supplier == '')
					$kd_supplier = $obj->kd_supplier;
					
				$kd_produk = $obj->kd_produk;
				$waktu_top = $obj->waktu_top;

				$disk_supp1_op = $obj->disk_supp1_op;
				$disk_supp2_op = $obj->disk_supp2_op;
				$disk_supp3_op = $obj->disk_supp3_op;
				$disk_supp4_op = $obj->disk_supp4_op;

				$disk_supp1 = $obj->disk_supp1;
				$disk_supp2 = $obj->disk_supp2;
				$disk_supp3 = $obj->disk_supp3;
				$disk_supp4 = $obj->disk_supp4;
				
				if($disk_supp1_op === "%"){
					$disk_persen_supp1 = $disk_supp1;
					$disk_amt_supp1 = 0;
				}else{
					$disk_persen_supp1 = 0;
					$disk_amt_supp1 = $disk_supp1;
				}
				if($disk_supp2_op === "%"){
					$disk_persen_supp2 = $disk_supp2;
					$disk_amt_supp2 = 0;
				}else{
					$disk_persen_supp2 = 0;
					$disk_amt_supp2 = $disk_supp2;
				}
				if($disk_supp3_op === "%"){
					$disk_persen_supp3 = $disk_supp3;
					$disk_amt_supp3 = 0;
				}else{
					$disk_persen_supp3 = 0;
					$disk_amt_supp3 = $disk_supp3;
				}
				if($disk_supp4_op === "%"){
					$disk_persen_supp4 = $disk_supp4;
					$disk_amt_supp4 = 0;
				}else{
					$disk_persen_supp4 = 0;
					$disk_amt_supp4 = $disk_supp4;
				}
				
				$disk_supp5 = $obj->disk_amt_supp5;
				
				$disk_dist1_op = $obj->disk_dist1_op;
				$disk_dist2_op = $obj->disk_dist2_op;
				$disk_dist3_op = $obj->disk_dist3_op;
				$disk_dist4_op = $obj->disk_dist4_op;

				$disk_dist1 = $obj->disk_dist1;
				$disk_dist2 = $obj->disk_dist2;
				$disk_dist3 = $obj->disk_dist3;
				$disk_dist4 = $obj->disk_dist4;
				
				if($disk_dist1_op === "%"){
					$disk_persen_dist1 = $disk_dist1;
					$disk_amt_dist1 = 0;
				}else{
					$disk_persen_dist1 = 0;
					$disk_amt_dist1 = $disk_dist1;
				}
				if($disk_dist2_op === "%"){
					$disk_persen_dist2 = $disk_dist2;
					$disk_amt_dist2 = 0;
				}else{
					$disk_persen_dist2 = 0;
					$disk_amt_dist2 = $disk_dist2;
				}
				if($disk_dist3_op === "%"){
					$disk_persen_dist3 = $disk_dist3;
					$disk_amt_dist3 = 0;
				}else{
					$disk_persen_dist3 = 0;
					$disk_amt_dist3 = $disk_dist3;
				}
				if($disk_dist4_op === "%"){
					$disk_persen_dist4 = $disk_dist4;
					$disk_amt_dist4 = 0;
				}else{
					$disk_persen_dist4 = 0;
					$disk_amt_dist4 = $disk_dist4;
				}
				
				$disk_dist5 = $obj->disk_amt_dist5;
				$hrg_supplier = $obj->hrg_supplier;
				$hrg_supplier_dist = $obj->hrg_supplier_dist;
				$net_hrg_supplier_sup = $obj->net_hrg_supplier_sup;
				$net_hrg_supplier_dist = $obj->net_hrg_supplier_dist;
				$rp_het_harga_beli = $obj->rp_het_harga_beli;

				$net_hrg_supplier_sup_inc = $obj->net_hrg_supplier_sup_inc;
				$net_hrg_supplier_dist_inc = $obj->net_hrg_supplier_dist_inc;
				$dpp = $obj->dpp;
				$aktif = '1';

								
				$updated_by = $this->session->userdata('username');
				$updated_date = date('Y-m-d H:i:s');
				
				unset($detail_hp);
				$detail_hp['waktu_top']	=	$waktu_top;
				$detail_hp['disk_persen_supp1']	=	$disk_persen_supp1;
				$detail_hp['disk_persen_supp2']	=	$disk_persen_supp2;
				$detail_hp['disk_persen_supp3']	=	$disk_persen_supp3;
				$detail_hp['disk_persen_supp4']	=	$disk_persen_supp4;
				$detail_hp['disk_amt_supp1']	=	$disk_amt_supp1;
				$detail_hp['disk_amt_supp2']	=	$disk_amt_supp2;
				$detail_hp['disk_amt_supp3']	=	$disk_amt_supp3;
				$detail_hp['disk_amt_supp4']	=	$disk_amt_supp4;
				$detail_hp['disk_amt_supp5']	=	$disk_supp5;
				$detail_hp['disk_persen_dist1']	=	$disk_persen_dist1;
				$detail_hp['disk_persen_dist2']	=	$disk_persen_dist2;
				$detail_hp['disk_persen_dist3']	=	$disk_persen_dist3;
				$detail_hp['disk_persen_dist4']	=	$disk_persen_dist4;
				$detail_hp['disk_amt_dist1']	=	$disk_amt_dist1;
				$detail_hp['disk_amt_dist2']	=	$disk_amt_dist2;
				$detail_hp['disk_amt_dist3']	=	$disk_amt_dist3;
				$detail_hp['disk_amt_dist4']	=	$disk_amt_dist4;
				$detail_hp['disk_amt_dist5']	=	$disk_dist5;
				$detail_hp['hrg_supplier']		=	$hrg_supplier;
				$detail_hp['hrg_supplier_dist']	=	$hrg_supplier;
				$detail_hp['net_hrg_supplier_sup']	=	$net_hrg_supplier_sup;
				$detail_hp['net_hrg_supplier_dist']	=	$net_hrg_supplier_dist;
				$detail_hp['net_hrg_supplier_sup_inc']	=	$net_hrg_supplier_sup_inc;
				$detail_hp['net_hrg_supplier_dist_inc']	=	$net_hrg_supplier_dist_inc;
				$detail_hp['dpp']			=	$dpp;
				$detail_hp['created_by']	=	$updated_by;
				$detail_hp['created_date']	=	$updated_date;
				$detail_hp['aktif']			=	$aktif;
				$detail_hp['keterangan']	=	$keterangan;
				$detail_hp['no_bukti']	=	$no_hp;
				
				if($no_bukti_filter != ''){
					unset($detail_hp['no_bukti']);
					if($this->hp_model->update_temp($kd_supplier, $kd_produk, $waktu_top,$no_bukti_filter,$detail_hp)){
						$results = 'success';
					}else{
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
						exit;
					}
				}else{
					if($this->hp_model->select_temp($kd_supplier,$kd_produk,'0')){
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"Barang dengan Kode Barang: '.$kd_produk.' Belum Diapprove"}';
						exit;
					// }else if($this->hp_model->select_temp($kd_supplier,$kd_produk,'1')){
						
					}else {
						$detail_hp['kd_supplier']	=	$kd_supplier;
						$detail_hp['kd_produk']	=	$kd_produk;
						$detail_hp['waktu_top']	=	$waktu_top;
						$detail_hp['status']	=	0;
						if($this->hp_model->insert_temp($detail_hp)){
							$results = 'success';
						}else{
							$this->db->trans_rollback();
							echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
							exit;
						}
					}
				}	
				$kd_supplier = '';
			}
		}
			if($results == 'success'){
				$this->db->trans_commit();
				$result = '{"success":true,"errMsg":""}';
			}else {
				$result = '{"success":false,"errMsg":"Tidak Ada Data yang Disimpan"}';
			}
			echo $result;
    }
	
	
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hp_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_nobukti(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hp_model->get_nobukti($search, $start, $limit);
				
        echo $result;
	}
				
    public function get_nobukti_all($no_bukti=''){
		$result = $this->hp_model->get_nobukti_all($no_bukti);
        echo $result;
	}
	
	public function search_no_bukti(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hp_model->search_no_bukti($search, $start, $limit);
				
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_supplier(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
                $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran',TRUE)) : '';
                $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan',TRUE)) : '';
		$list = isset($_POST['list']) ? $this->db->escape_str($this->input->post('list',TRUE)) : '';
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
		if($list != ''){
			$list_exp = explode(',',$list);
			$list_imp = implode("','",$list_exp);
			$list = strtoupper("'".$list_imp."'");
		}
		
		$data_result = $this->hp_model->search_produk_by_supplier($kd_supplier,$no_bukti,$kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$kd_ukuran,$kd_satuan,$list,$search,$start,$limit);
		
		$hasil = $data_result['rows'];
		$results = array();
		
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0){
				$diskon_supp1 = $result->disk_persen_supp1;
				$result->disk_supp1_op = "%";
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp1 = $result->disk_amt_supp1;
					$result->disk_supp1_op = "Rp";
				}else{
					$diskon_supp1 = 0;
				}
			}
			
			if($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0){
				$diskon_supp2 = $result->disk_persen_supp2;
				$result->disk_supp2_op = "%";
			}else{
				if($result->disk_amt_supp2 != ''){
					$diskon_supp2 = $result->disk_amt_supp2;
					$result->disk_supp2_op = "Rp";
				}else{
					$diskon_supp2 = 0;
				}
			}
			
			if($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0){
				$diskon_supp3 = $result->disk_persen_supp3;
				$result->disk_supp3_op = "%";
			}else{
				if($result->disk_amt_supp3 != ''){
					$diskon_supp3 = $result->disk_amt_supp3;
					$result->disk_supp3_op = "Rp";
				}else{
					$diskon_supp3 = 0;
				}
			}
			
			if($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0){
				$diskon_supp4 = $result->disk_persen_supp4;
				$result->disk_supp4_op = "%";
			}else{
				if($result->disk_amt_supp4 != ''){
					$diskon_supp4 = $result->disk_amt_supp4;
					$result->disk_supp4_op = "Rp";
				}else{
					$diskon_supp4 = 0;
				}
			}
			
			if($result->diskon_amt_supp5 != ''){
				$diskon_amt_supp5 = $result->diskon_amt_supp5;
			}else{
				$diskon_amt_supp5 = 0;
			}
			
			 
			$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_amt_supp5;
			
			//diskon Rp
			$result->disk_supp1 = $diskon_supp1;
			$result->disk_supp2 = $diskon_supp2;
			$result->disk_supp3 = $diskon_supp3;
			$result->disk_supp4 = $diskon_supp4;
			
			$diskon = 0;
							
			if($result->disk_persen_dist1 != '' && $result->disk_persen_dist1 != 0){
				$diskon_dist1 = $result->disk_persen_dist1;
				$result->disk_dist1_op = "%";
			}else{
				if($result->disk_amt_dist1 != ''){
					$diskon_dist1 = $result->disk_amt_dist1;
					$result->disk_dist1_op = "Rp";
				}else{
					$diskon_dist1 = 0;
				}
			}
			
			if($result->disk_persen_dist2 != '' && $result->disk_persen_dist2 != 0){
				$diskon_dist2 = $result->disk_persen_dist2;
				$result->disk_dist2_op = "%";
			}else{
				if($result->disk_amt_dist2 != ''){
					$diskon_dist2 = $result->disk_amt_dist2;
					$result->disk_dist2_op = "Rp";
				}else{
					$diskon_dist2 = 0;
				}
			}
			
			if($result->disk_persen_dist3 != '' && $result->disk_persen_dist3 != 0){
				$diskon_dist3 = $result->disk_persen_dist3;
				$result->disk_dist3_op = "%";
			}else{
				if($result->disk_amt_dist3 != ''){
					$diskon_dist3 = $result->disk_amt_dist3;
					$result->disk_dist3_op = "Rp";
				}else{
					$diskon_dist3 = 0;
				}
			}
			
			if($result->disk_persen_dist4 != '' && $result->disk_persen_dist4 != 0){
				$diskon_dist4 = $result->disk_persen_dist4;
				$result->disk_dist4_op = "%";
			}else{
				if($result->disk_amt_dist4 != ''){
					$diskon_dist4 = $result->disk_amt_dist4;
					$result->disk_dist4_op = "Rp";
				}else{
					$diskon_dist4 = 0;
				}
			}
			
			if($result->diskon_amt_dist5 != ''){
				$diskon_amt_dist5 = $result->diskon_amt_dist5;
			}else{
				$diskon_amt_dist5 = 0;
			}
			
			 
			$diskon = $diskon_dist1 + $diskon_dist2 + $diskon_dist3 + $diskon_dist4 + $diskon_amt_dist5;
			
			//diskon Rp
			$result->disk_dist1 = $diskon_dist1;
			$result->disk_dist2 = $diskon_dist2;
			$result->disk_dist3 = $diskon_dist3;
			$result->disk_dist4 = $diskon_dist4;
			
			
			$results[] = $result;
		}
		echo '{success:true,record:'.$data_result['total'].',data:'.json_encode($results).'}';
	}
	
	public function search_produk_history(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';

		$hasil = $this->hp_model->search_produk_history($no_bukti,$kd_produk);
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0){
				$diskon_supp1 = $result->disk_persen_supp1;
				$result->disk_supp1_op = "%";
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp1 = $result->disk_amt_supp1;
					$result->disk_supp1_op = "Rp";
				}else{
					$diskon_supp1 = 0;
				}
			}
			
			if($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0){
				$diskon_supp2 = $result->disk_persen_supp2;
				$result->disk_supp2_op = "%";
			}else{
				if($result->disk_amt_supp2 != ''){
					$diskon_supp2 = $result->disk_amt_supp2;
					$result->disk_supp2_op = "Rp";
				}else{
					$diskon_supp2 = 0;
				}
			}
			
			if($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0){
				$diskon_supp3 = $result->disk_persen_supp3;
				$result->disk_supp3_op = "%";
			}else{
				if($result->disk_amt_supp3 != ''){
					$diskon_supp3 = $result->disk_amt_supp3;
					$result->disk_supp3_op = "Rp";
				}else{
					$diskon_supp3 = 0;
				}
			}
			
			if($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0){
				$diskon_supp4 = $result->disk_persen_supp4;
				$result->disk_supp4_op = "%";
			}else{
				if($result->disk_amt_supp4 != ''){
					$diskon_supp4 = $result->disk_amt_supp4;
					$result->disk_supp4_op = "Rp";
				}else{
					$diskon_supp4 = 0;
				}
			}
			
			if($result->diskon_amt_supp5 != ''){
				$diskon_amt_supp5 = $result->diskon_amt_supp5;
			}else{
				$diskon_amt_supp5 = 0;
			}
			
			 
			$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_amt_supp5;
			
			//diskon Rp
			$result->disk_supp1 = $diskon_supp1;
			$result->disk_supp2 = $diskon_supp2;
			$result->disk_supp3 = $diskon_supp3;
			$result->disk_supp4 = $diskon_supp4;
			
			$diskon = 0;
							
			if($result->disk_persen_dist1 != '' && $result->disk_persen_dist1 != 0){
				$diskon_dist1 = $result->disk_persen_dist1;
				$result->disk_dist1_op = "%";
			}else{
				if($result->disk_amt_dist1 != ''){
					$diskon_dist1 = $result->disk_amt_dist1;
					$result->disk_dist1_op = "Rp";
				}else{
					$diskon_dist1 = 0;
				}
			}
			
			if($result->disk_persen_dist2 != '' && $result->disk_persen_dist2 != 0){
				$diskon_dist2 = $result->disk_persen_dist2;
				$result->disk_dist2_op = "%";
			}else{
				if($result->disk_amt_dist2 != ''){
					$diskon_dist2 = $result->disk_amt_dist2;
					$result->disk_dist2_op = "Rp";
				}else{
					$diskon_dist2 = 0;
				}
			}
			
			if($result->disk_persen_dist3 != '' && $result->disk_persen_dist3 != 0){
				$diskon_dist3 = $result->disk_persen_dist3;
				$result->disk_dist3_op = "%";
			}else{
				if($result->disk_amt_dist3 != ''){
					$diskon_dist3 = $result->disk_amt_dist3;
					$result->disk_dist3_op = "Rp";
				}else{
					$diskon_dist3 = 0;
				}
			}
			
			if($result->disk_persen_dist4 != '' && $result->disk_persen_dist4 != 0){
				$diskon_dist4 = $result->disk_persen_dist4;
				$result->disk_dist4_op = "%";
			}else{
				if($result->disk_amt_dist4 != ''){
					$diskon_dist4 = $result->disk_amt_dist4;
					$result->disk_dist4_op = "Rp";
				}else{
					$diskon_dist4 = 0;
				}
			}
			
			if($result->diskon_amt_dist5 != ''){
				$diskon_amt_dist5 = $result->diskon_amt_dist5;
			}else{
				$diskon_amt_dist5 = 0;
			}
			
			 
			$diskon = $diskon_dist1 + $diskon_dist2 + $diskon_dist3 + $diskon_dist4 + $diskon_amt_dist5;
			
			//diskon Rp
			$result->disk_dist1 = $diskon_dist1;
			$result->disk_dist2 = $diskon_dist2;
			$result->disk_dist3 = $diskon_dist3;
			$result->disk_dist4 = $diskon_dist4;
			
			
			$results[] = $result;
		}
		echo '{success:true,data:'.json_encode($results).'}';
	}
	
	public function search_kd_produk($search_by){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
	
		echo $this->hp_model->get_produk($keyword);
	
	}
	
	public function print_form($no_bukti = '', $kd_produk = ''){
		$data = $this->hp_model->get_data_print($no_bukti,$kd_produk);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/HargaPembelianPrint.php');
		$pdf = new HargaPembelianPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'F4', true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['detail']);
		$pdf->Output();	
		exit;
	}

	public function get_no_bukti_filter(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hp_model->get_no_bukti_filter($search, $start, $limit);
				
        echo $result;
	}
	
}
