<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Barang_per_lokasi extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('barang_per_lokasi_model', 'bpl_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_bpl = 'BL' . date('Ym') . '-';
    	$sequence = $this->bpl_model->get_kode_sequence($no_bpl, 3);
    	echo '{"success":true,
				"data":{
					"no_bpl":"' . $no_bpl . $sequence . '",
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
		$cmd = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd',TRUE)) : FALSE;
		$no_bpl = isset($_POST['no_bpl']) ? $this->db->escape_str($this->input->post('no_bpl',TRUE)) : FALSE;
		$kd_produk = isset($_POST['kode_prod']) ? $this->db->escape_str($this->input->post('kode_prod',TRUE)) : FALSE;
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : FALSE;
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : FALSE;
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : FALSE;
		$keterangan = isset($_POST['ket']) ? $this->db->escape_str($this->input->post('ket',TRUE)) : FALSE;
		$kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : FALSE;
		$tanggal =  date('Y-m-d H:i:s');  

		if ($cmd=="save") { //save          
            $data = array(
				'kd_produk'	=>	$kd_produk,
				'kd_lokasi'	=>	$kd_lokasi,
				'kd_blok'	=>	$kd_blok,
				'kd_sub_blok'	=>	$kd_sub_blok,
				'keterangan'	=>	strtoupper($keterangan),
				'kd_peruntukan'	=>	$kd_peruntukan,
            );

            if ($this->bpl_model->insert_row($data)) {
					$result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit            			
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			
           	$datau = array(
				'kd_lokasi'	=>	$kd_lokasi,
				'kd_blok'	=>	$kd_blok,
				'kd_sub_blok'	=>	$kd_sub_blok
            );
           
            if ($this->bpl_model->update_row($kd_produk, $datau)) {
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
	public function search_produk_by_kategori(){
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
		$hasil = $this->bpl_model->search_produk_by_kategori($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$search);
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0){
				$diskon_kons1 = $result->disk_persen_kons1;
				$result->disk_kons1_op = "%";
			}else{
				if($result->disk_amt_kons1 != ''){
					$diskon_kons1 = $result->disk_amt_kons1;
					$result->disk_kons1_op = "Rp";
				}else{
					$diskon_kons1 = 0;
				}
			}
			
			if($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0){
				$diskon_kons2 = $result->disk_persen_kons2;
				$result->disk_kons2_op = "%";
			}else{
				if($result->disk_amt_kons2 != ''){
					$diskon_kons2 = $result->disk_amt_kons2;
					$result->disk_kons2_op = "Rp";
				}else{
					$diskon_kons2 = 0;
				}
			}
			
			if($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0){
				$diskon_kons3 = $result->disk_persen_kons3;
				$result->disk_kons3_op = "%";
			}else{
				if($result->disk_amt_kons3 != ''){
					$diskon_kons3 = $result->disk_amt_kons3;
					$result->disk_kons3_op = "Rp";
				}else{
					$diskon_kons3 = 0;
				}
			}
			
			if($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0){
				$diskon_kons4 = $result->disk_persen_kons4;
				$result->disk_kons4_op = "%";
			}else{
				if($result->disk_amt_kons4 != ''){
					$diskon_kons4 = $result->disk_amt_kons4;
					$result->disk_kons4_op = "Rp";
				}else{
					$diskon_kons4 = 0;
				}
			}
			
			if($result->diskon_amt_kons5 != ''){
				$diskon_amt_kons5 = $result->diskon_amt_kons5;
			}else{
				$diskon_amt_kons5 = 0;
			}
			
			 
			$diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;
			
			//diskon Rp
			$result->disk_kons1 = $diskon_kons1;
			$result->disk_kons2 = $diskon_kons2;
			$result->disk_kons3 = $diskon_kons3;
			$result->disk_kons4 = $diskon_kons4;
			
			$diskon = 0;
							
			if($result->disk_persen_member1 != '' && $result->disk_persen_member1 != 0){
				$diskon_member1 = $result->disk_persen_member1;
				$result->disk_member1_op = "%";
			}else{
				if($result->disk_amt_member1 != ''){
					$diskon_member1 = $result->disk_amt_member1;
					$result->disk_member1_op = "Rp";
				}else{
					$diskon_member1 = 0;
				}
			}
			
			if($result->disk_persen_member2 != '' && $result->disk_persen_member2 != 0){
				$diskon_member2 = $result->disk_persen_member2;
				$result->disk_member2_op = "%";
			}else{
				if($result->disk_amt_member2 != ''){
					$diskon_member2 = $result->disk_amt_member2;
					$result->disk_member2_op = "Rp";
				}else{
					$diskon_member2 = 0;
				}
			}
			
			if($result->disk_persen_member3 != '' && $result->disk_persen_member3 != 0){
				$diskon_member3 = $result->disk_persen_member3;
				$result->disk_member3_op = "%";
			}else{
				if($result->disk_amt_member3 != ''){
					$diskon_member3 = $result->disk_amt_member3;
					$result->disk_member3_op = "Rp";
				}else{
					$diskon_member3 = 0;
				}
			}
			
			if($result->disk_persen_member4 != '' && $result->disk_persen_member4 != 0){
				$diskon_member4 = $result->disk_persen_member4;
				$result->disk_member4_op = "%";
			}else{
				if($result->disk_amt_member4 != ''){
					$diskon_member4 = $result->disk_amt_member4;
					$result->disk_member4_op = "Rp";
				}else{
					$diskon_member4 = 0;
				}
			}
			
			if($result->diskon_amt_member5 != ''){
				$diskon_amt_member5 = $result->diskon_amt_member5;
			}else{
				$diskon_amt_member5 = 0;
			}
			
			if($result->is_member_kelipatan == 0){
				$result->is_member_kelipatan = 'Tidak';
			}else{
				$result->is_member_kelipatan = 'Ya';
			}
			
			if($result->is_bonus_kelipatan == 0){
				$result->is_bonus_kelipatan = 'Tidak';
			}else{
				$result->is_bonus_kelipatan = 'Ya';
			}
			
			$result->margin_op = '%';
			$result->margin = $result->pct_margin;
			
			
			 
			$diskon = $diskon_member1 + $diskon_member2 + $diskon_member3 + $diskon_member4 + $diskon_amt_member5;
			
			//diskon Rp
			$result->disk_member1 = $diskon_member1;
			$result->disk_member2 = $diskon_member2;
			$result->disk_member3 = $diskon_member3;
			$result->disk_member4 = $diskon_member4;
			
			
			$results[] = $result;
		}
		echo '{success:true,data:'.json_encode($results).'}';
	}
		
	public function get_detail(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		echo $this->bpl_model->get_detail($kd_produk,$search);
	}
	
	public function get_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : '';
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : '';
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : '';
		
		
		$results =  $this->bpl_model->get_row($kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);		
		echo $results;
	}
	public function delete_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : FALSE;
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : FALSE;
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : FALSE;

		if ($this->bpl_model->delete_row($kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
        
        public function search_all_lokasi() {
            $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

            $result = $this->bpl_model->search_all_lokasi($search);

            echo $result;
        }
        
        public function search_lokasi(){
            $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
            $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

            $result = $this->bpl_model->search_lokasi($search, $kd_produk);


            echo $result;
        }
}
