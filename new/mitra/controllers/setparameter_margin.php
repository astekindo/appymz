<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setparameter_margin extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('barang_per_lokasi_model', 'bpl_model');
        $this->load->model('setparameter_margin_model', 'spm_model');
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
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

                $this->db->trans_begin();
		foreach($detail as $obj){
                               	$created_by = $this->session->userdata('username');
				$created_date = date('Y-m-d');

			if($obj->koreksi_lokasi == 'Y'){
			unset($detail_set);
                                $detail_set['kd_produk']    =	$obj->kd_produk;
				$detail_set['margin_op']    =	$obj->parameter_margin_rp;
                                $detail_set['margin']       =   $obj->parameter_margin;
				$detail_set['markup_op']    =	$obj->parameter_markup_rp;
				$detail_set['markup']       =	$obj->parameter_markup;
				$detail_set['keterangan']   =	'keterangan';
				$detail_set['created_by']   =	$created_by;
                                $detail_set['created_date'] =	$created_date;

				$detailresult = $this->spm_model->input_data($detail_set);


			}
		}
                $this->db->trans_commit();
			if($detailresult){
				$result = '{"success":true,"errMsg":""}';
			}else {
				$result = '{"success":false,"errMsg":"Tidak Ada Data yang Disimpan!!"}';
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
        if($kd_kategori1 == '' && $kd_kategori2 == '' && $kd_kategori3 == '' && $kd_kategori4 == '' && $search == '') {
            echo '{success:true,data: null}';
            return;
        }

		$hasil = $this->spm_model->search_produk_by_kategori($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$search);
       // var_export($hasil);exit;
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;

			if(!empty($result->disk_persen_kons1) && $result->disk_persen_kons1 != 0){
				$diskon_kons1 = $result->disk_persen_kons1;
				$result->disk_kons1_op = "%";
			} elseif(!empty($result->disk_amt_kons1)){
				$diskon_kons1 = $result->disk_amt_kons1;
				$result->disk_kons1_op = "Rp";
			}else{
				$diskon_kons1 = 0;
			}

			if(!empty($result->disk_persen_kons2) && $result->disk_persen_kons2 != 0){
				$diskon_kons2 = $result->disk_persen_kons2;
				$result->disk_kons2_op = "%";
			} elseif(!empty($result->disk_amt_kons2)){
				$diskon_kons2 = $result->disk_amt_kons2;
				$result->disk_kons2_op = "Rp";
			}else{
				$diskon_kons2 = 0;
			}

			if(!empty($result->disk_persen_kons3) && $result->disk_persen_kons3 != 0){
				$diskon_kons3 = $result->disk_persen_kons3;
				$result->disk_kons3_op = "%";
			} elseif(!empty($result->disk_amt_kons3)){
				$diskon_kons3 = $result->disk_amt_kons3;
				$result->disk_kons3_op = "Rp";
			}else{
				$diskon_kons3 = 0;
			}

			if(!empty($result->disk_persen_kons4) && $result->disk_persen_kons4 != 0){
				$diskon_kons4 = $result->disk_persen_kons4;
				$result->disk_kons4_op = "%";
			} elseif(!empty($result->disk_amt_kons4)){
				$diskon_kons4 = $result->disk_amt_kons4;
				$result->disk_kons4_op = "Rp";
			}else{
				$diskon_kons4 = 0;
			}

			if(!empty($result->diskon_amt_kons5)) {
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

			if(!empty($result->disk_persen_member1) && $result->disk_persen_member1 != 0){
				$diskon_member1 = $result->disk_persen_member1;
				$result->disk_member1_op = "%";
			} elseif(!empty($result->disk_amt_member1)){
				$diskon_member1 = $result->disk_amt_member1;
				$result->disk_member1_op = "Rp";
			}else{
				$diskon_member1 = 0;
			}

			if(!empty($result->disk_persen_member2) && $result->disk_persen_member2 != 0){
				$diskon_member2 = $result->disk_persen_member2;
				$result->disk_member2_op = "%";
			} elseif(!empty($result->disk_amt_member2)){
				$diskon_member2 = $result->disk_amt_member2;
				$result->disk_member2_op = "Rp";
			}else{
				$diskon_member2 = 0;
			}

			if(!empty($result->disk_persen_member3) && $result->disk_persen_member3 != 0){
				$diskon_member3 = $result->disk_persen_member3;
				$result->disk_member3_op = "%";
			} elseif(!empty($result->disk_amt_member3)){
				$diskon_member3 = $result->disk_amt_member3;
				$result->disk_member3_op = "Rp";
			}else{
				$diskon_member3 = 0;
			}

			if(!empty($result->disk_persen_member4) && $result->disk_persen_member4 != 0){
				$diskon_member4 = $result->disk_persen_member4;
				$result->disk_member4_op = "%";
			} elseif(!empty($result->disk_amt_member4)){
				$diskon_member4 = $result->disk_amt_member4;
				$result->disk_member4_op = "Rp";
			}else{
				$diskon_member4 = 0;
			}

			if(!empty($result->diskon_amt_member5)){
				$diskon_amt_member5 = $result->diskon_amt_member5;
			}else{
				$diskon_amt_member5 = 0;
			}

			if(!empty($result->is_member_kelipatan) && $result->is_member_kelipatan == 0){
				$result->is_member_kelipatan = 'Tidak';
			}else{
				$result->is_member_kelipatan = 'Ya';
			}

			if(!empty($result->is_bonus_kelipatan) && $result->is_bonus_kelipatan == 0){
				$result->is_bonus_kelipatan = 'Tidak';
			}else{
				$result->is_bonus_kelipatan = 'Ya';
			}

			$result->margin_op = '%';
			$result->margin = !empty($result->pct_margin) ? $result->pct_margin : 0;



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
