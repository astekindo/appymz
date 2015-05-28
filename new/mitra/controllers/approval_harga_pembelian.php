<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_harga_pembelian extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('approval_harga_pembelian_model', 'ahp_model');
		$this->load->model('harga_pembelian_model', 'hp_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_no_bukti(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';

		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$data_result = $this->ahp_model->search_produk_by_no_bukti($no_bukti,$search,$start,$limit);
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

			if(!empty($result->diskon_amt_supp5)){
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

			if(!empty($result->diskon_amt_dist5)){
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

                        $result->net_hrg_supplier_dist_inc = round($result->net_hrg_supplier_dist_inc);

			$results[] = $result;
		}
		echo '{success:true,record:'.$data_result['total'].',data:'.json_encode($results).'}';
	}

	public function get_no_bukti_filter(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->ahp_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
	}
        public function get_no_bukti_filter_dist(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->ahp_model->get_no_bukti_filter_dist($search, $start, $limit);

        echo $result;
	}

	public function approval(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';

		if($tanggal){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$this->db->trans_begin();
		foreach($detail as $obj){
			$results = 'success';
			if($obj->status == 'Approve'){
				$status = '1';
			}else {
				$status = '9';
			}
			if(!($this->ahp_model->update_temp($no_bukti, $obj->kd_produk, $status))){
				$this->db->trans_rollback();
				echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
				exit;
			}
                        $is_konsinyasi = $obj->is_konsinyasi;
                        $hrg_supplier = $obj->hrg_supplier;
                        
			if($obj->status == 'Approve'){
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

				$hrg_supplier = $obj->hrg_supplier;
				$net_hrg_supplier_sup = $obj->net_hrg_supplier_sup;
				$rp_het_harga_beli = $obj->rp_het_harga_beli;
				$net_hrg_supplier_sup_inc = $obj->net_hrg_supplier_sup_inc;
				$dpp = empty($obj->dpp) ? 0 : $obj->dpp;
                                $tgl_start_diskon = $obj->tgl_start_diskon;
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
				$detail_hp['hrg_supplier']		=	$hrg_supplier;
				$detail_hp['net_hrg_supplier_sup']	=	$net_hrg_supplier_sup;
				$detail_hp['net_hrg_supplier_sup_inc']	=	$net_hrg_supplier_sup_inc;
				$detail_hp['dpp']			=	$dpp;
				$detail_hp['created_by']	=	$updated_by;
				$detail_hp['created_date']	=	$updated_date;
				$detail_hp['aktif']			=	$aktif;
				$detail_hp['keterangan']	=	$keterangan;
				$detail_hp['no_bukti']	=	$no_bukti;
                                $detail_hp['konsinyasi']	=	$is_konsinyasi;
                                $detail_hp['tgl_start_diskon'] = $tgl_start_diskon;

				unset($detail_hp['no_bukti']);
				if($this->hp_model->update_temp($kd_supplier, $kd_produk, $waktu_top,$no_bukti,$detail_hp)){
					$results = 'success';
				}else{
					$this->db->trans_rollback();
					echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
					exit;
				}
			}
		}

		if($results == 'success'){
			$this->db->trans_commit();
		}else {
			echo'{"success":false,"errMsg":"Process Failed . . ."}';
			exit;
		}

		$result = $this->ahp_model->get_data_temp($no_bukti);


		$this->db->trans_begin();
		foreach($result as $data)
		{
                $kd_supplier = $data['kd_supplier'];
                $kd_produk = $data['kd_produk'];
                $waktu_top = $data['waktu_top'];
                $status_approve = $data['status'];
                $disk_persen_supp1 = $data['disk_persen_supp1'];
                $disk_persen_supp2 = $data['disk_persen_supp2'];
                $disk_persen_supp3 = $data['disk_persen_supp3'];
                $disk_persen_supp4 = $data['disk_persen_supp4'];
                $disk_amt_supp1 = $data['disk_amt_supp1'];
                $disk_amt_supp2 = $data['disk_amt_supp2'];
                $disk_amt_supp3 = $data['disk_amt_supp3'];
                $disk_amt_supp4 = $data['disk_amt_supp4'];
                $disk_amt_supp5 = $data['disk_amt_supp5'];
                $hrg_supplier = $data['hrg_supplier'];
                $net_hrg_supplier = $data['net_hrg_supplier_sup'];
                $net_hrg_supplier_sup_inc = $data['net_hrg_supplier_sup_inc'];
                $keterangan = $data['keterangan'];
                $tgl_start_diskon = $data['tgl_start_diskon'];
			// print_r($data);
			// exit;
                unset($data_hps);
                $data_hps['kd_supplier']	= $kd_supplier;
                $data_hps['kd_produk']	= $kd_produk;
                $data_hps['waktu_top']	= $waktu_top;
                $data_hps['disk_persen_supp1']	= $disk_persen_supp1;
                $data_hps['disk_persen_supp2']	= $disk_persen_supp2;
                $data_hps['disk_persen_supp3']	= $disk_persen_supp3;
                $data_hps['disk_persen_supp4']	= $disk_persen_supp4;
                $data_hps['disk_amt_supp1']	= $disk_amt_supp1;
                $data_hps['disk_amt_supp2']	= $disk_amt_supp2;
                $data_hps['disk_amt_supp3']	= $disk_amt_supp3;
                $data_hps['disk_amt_supp4']	= $disk_amt_supp4;
                $data_hps['disk_amt_supp5']	= $disk_amt_supp5;
                $data_hps['hrg_supplier']	= $hrg_supplier;
                $data_hps['net_hrg_supplier_sup'] = $net_hrg_supplier;
                $data_hps['net_hrg_supplier_sup_inc']	= $net_hrg_supplier_sup_inc;
                $data_hps['keterangan']	= $keterangan;
                $data_hps['tgl_start_diskon'] = $tgl_start_diskon;
                $data_hps['aktif_diskon'] = 1;
                $data_hps['no_bukti'] = $no_bukti;
			// print_r($data);
			// exit;
			unset($data['kd_supplier']);
			unset($data['kd_produk']);
			// unset($data['waktu_top']);
			unset($data['status']);
			unset($data['tgl_approve']);
			unset($data['approve_by']);

                        if ($status_approve == '1') {
                            $produk = $this->ahp_model->select_data_beli($kd_produk,$tgl_start_diskon);
                             if ($produk > 0){
                                //Update Harga
                                if ($this->ahp_model->update_row($kd_produk,$tgl_start_diskon, $data_hps)) {
                                       $results = 'success';
                                }else{
                                    $this->db->trans_rollback();
                                        echo '{"success":false,"errMsg":"Update Failed . . ."}';
                                        exit;
                                }
                            }else{
                                if ($this->ahp_model->insert_row('mst.t_supp_per_brg', $data_hps)) {
                                        $results = 'success';
                                    }else {
                                        $this->db->trans_rollback();
                                        echo '{"success":false,"errMsg":"Insert Failed . . ."}';
                                        exit;
                                    }
                            }
                            //Non Aktifkan Harga Masa Depan
                            if ($this->ahp_model->update_harga_beli_non_aktif($kd_produk,$tgl_start_diskon)) {
                                       $results = 'success';
                            }else {
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"Non Aktif Failed . . ."}';
                                    exit;
                            }
                            //Update tgl end diskon
                            if ($this->ahp_model->update_harga_beli_tgl_end($kd_produk,$tgl_start_diskon)) {
                                       $results = 'success';
                            }else {
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"Update Tgl End Failed . . ."}';
                                    exit;
                            }
                          }
//			if($status_approve == '1'){
//				if($this->ahp_model->update_row($kd_supplier, $kd_produk, $waktu_top, $data_hps)){
//
//					$data['status_approve'] = $status_approve;
//					$data['kd_supplier'] = $kd_supplier;
//					$data['kd_produk'] = $kd_produk;
//					$data['waktu_top'] = $waktu_top;
//					$data['tgl_approve'] = $tanggal;
//                    $data['kd_peruntukan'] = '0';
//					$data['approve_by'] = $this->session->userdata('username');
//
//					if($this->ahp_model->insert_history($data)){
//						$results = 'success';
//					}else{
//						$this->db->trans_rollback();
//						echo '{"success":false,"errMsg":"History Failed . . ."}';
//						exit;
//					}
//					$produk_result = $this->ahp_model->get_produk_margin($kd_produk);
//					foreach($produk_result as $result){
//						$pct_margin = $result->pct_margin;
//						$rp_ongkos_kirim = $result->rp_ongkos_kirim;
//						$pct_margin_dist = $result->pct_margin_dist;
//						$rp_ongkos_kirim_dist = $result->rp_ongkos_kirim_dist;
//					}
//					$rp_het_harga_beli = ($data['net_hrg_supplier_sup'] + ($data['net_hrg_supplier_sup'] * ($pct_margin/100)) + $rp_ongkos_kirim)*1.1;
//					$rp_het_harga_beli_dist = ($data['net_hrg_supplier_dist'] + ($data['net_hrg_supplier_dist'] * ($pct_margin_dist/100)) + $rp_ongkos_kirim_dist)*1.1;
//
//					if($this->ahp_model->update_net_produk($kd_produk,$rp_het_harga_beli,$data['net_hrg_supplier_sup'])){
//						$results = 'success';
//					}else{
//						$this->db->trans_rollback();
//						echo '{"success":false,"errMsg":"update_net_produk Failed . . ."}';
//						exit;
//					}
//				}else{
//					$this->db->trans_rollback();
//					echo '{"success":false,"errMsg":"update_row Failed . . ."}';
//					exit;
//				}
//			}
                        else if($status_approve == '9'){
				$data['status_approve'] = $status_approve;
				$data['kd_supplier'] = $kd_supplier;
				$data['kd_produk'] = $kd_produk;
				$data['waktu_top'] = $waktu_top;
				$data['tgl_approve'] = $tanggal;
				$data['approve_by'] = $this->session->userdata('username');
				if($this->ahp_model->insert_history($data)){
					$results = 'success';
				}else{
					$this->db->trans_rollback();
					echo '{"success":false,"errMsg":"History Failed . . ."}';
					exit;
				}
			}else{
				$results = 'success';
			}

		}

		if($results == 'success'){
                    //print_r("dddd");
                    //print_r($is_konsinyasi);

                    if($is_konsinyasi == '1'){

                        $updatehj['disk_persen_kons1'] = $disk_persen_supp1;
                        $updatehj['rp_jual_supermarket'] = $hrg_supplier;
                        $result = $this->ahp_model->update_diskon_jual_konsinyasi($kd_produk, $updatehj);


                    }

			$result = '{"success":true,"errMsg":""}';
			$this->db->trans_commit();
		}else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed . . ."}';
		}
		echo $result;

	}

     public function approval_distribusi(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : '';

		if($tanggal){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		$this->db->trans_begin();
		foreach($detail as $obj){
			$results = 'success';
			if($obj->status == 'Approve'){
				$status = '1';
			}else {
				$status = '9';
			}
			if(!($this->ahp_model->update_temp($no_bukti, $obj->kd_produk, $status))){
				$this->db->trans_rollback();
				echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
				exit;
			}
                        $is_konsinyasi = $obj->is_konsinyasi;
                        $hrg_supplier = $obj->hrg_supplier;
			if($obj->status == 'Approve'){
				if($obj->edited == 'Y'){
					$kd_supplier = $obj->kd_supplier;

					$kd_produk = $obj->kd_produk;
					$waktu_top = $obj->waktu_top;

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
					$detail_hp['disk_persen_dist1']	=	$disk_persen_dist1;
					$detail_hp['disk_persen_dist2']	=	$disk_persen_dist2;
					$detail_hp['disk_persen_dist3']	=	$disk_persen_dist3;
					$detail_hp['disk_persen_dist4']	=	$disk_persen_dist4;
					$detail_hp['disk_amt_dist1']	=	$disk_amt_dist1;
					$detail_hp['disk_amt_dist2']	=	$disk_amt_dist2;
					$detail_hp['disk_amt_dist3']	=	$disk_amt_dist3;
					$detail_hp['disk_amt_dist4']	=	$disk_amt_dist4;
					$detail_hp['disk_amt_dist5']	=	$disk_dist5;
					$detail_hp['hrg_supplier_dist']	=	$hrg_supplier_dist;
					$detail_hp['net_hrg_supplier_dist']	=	$net_hrg_supplier_dist;
					$detail_hp['net_hrg_supplier_dist_inc']	=	$net_hrg_supplier_dist_inc;
					$detail_hp['dpp']			=	$dpp;
					$detail_hp['created_by']	=	$updated_by;
					$detail_hp['created_date']	=	$updated_date;
					$detail_hp['aktif']			=	$aktif;
					$detail_hp['keterangan']	=	$keterangan;
					$detail_hp['no_bukti']	=	$no_bukti;
                                        $detail_hp['konsinyasi']	=	$is_konsinyasi;

					unset($detail_hp['no_bukti']);
					if($this->hp_model->update_temp($kd_supplier, $kd_produk, $waktu_top,$no_bukti,$detail_hp)){
						$results = 'success';
					}else{
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
						exit;
					}
				}
			}
		}

		if($results == 'success'){
			$this->db->trans_commit();
		}else {
			echo'{"success":false,"errMsg":"Process Failed . . ."}';
			exit;
		}

		$result = $this->ahp_model->get_data_temp($no_bukti);


		$this->db->trans_begin();
		foreach($result as $data)
		{
			$kd_supplier = $data['kd_supplier'];
			$kd_produk = $data['kd_produk'];
			$waktu_top = $data['waktu_top'];
			$status_approve = $data['status'];
                        $disk_persen_dist1 = $data['disk_persen_dist1'];
                        $disk_persen_dist2 = $data['disk_persen_dist2'];
                        $disk_persen_dist3 = $data['disk_persen_dist3'];
                        $disk_persen_dist4 = $data['disk_persen_dist4'];
                        $disk_amt_dist1 = $data['disk_amt_dist1'];
                        $disk_amt_dist2 = $data['disk_amt_dist2'];
                        $disk_amt_dist3 = $data['disk_amt_dist3'];
                        $disk_amt_dist4 = $data['disk_amt_dist4'];
                        $disk_amt_dist5 = $data['disk_amt_dist5'];
                        $hrg_supplier_dist = $data['hrg_supplier_dist'];
                        $net_hrg_supplier_dist = $data['net_hrg_supplier_dist'];
                        $net_hrg_supplier_dist_inc = $data['net_hrg_supplier_dist_inc'];
                        $keterangan = $data['keterangan'];
			// print_r($data);
			// exit;
                        unset($data_hp);
                        $data_hp['kd_supplier']	= $kd_supplier;
                        $data_hp['kd_produk']	= $kd_produk;
                        $data_hp['waktu_top']	= $waktu_top;
                        $data_hp['disk_persen_dist1']	= $disk_persen_dist1;
                        $data_hp['disk_persen_dist2']	= $disk_persen_dist2;
                        $data_hp['disk_persen_dist3']	= $disk_persen_dist3;
                        $data_hp['disk_persen_dist4']	= $disk_persen_dist4;
                        $data_hp['disk_amt_dist1']	= $disk_amt_dist1;
                        $data_hp['disk_amt_dist2']	= $disk_amt_dist2;
                        $data_hp['disk_amt_dist3']	= $disk_amt_dist3;
                        $data_hp['disk_amt_dist4']	= $disk_amt_dist4;
                        $data_hp['disk_amt_dist5']	= $disk_amt_dist5;
                        $data_hp['hrg_supplier_dist']	= $hrg_supplier_dist;
                        $data_hp['net_hrg_supplier_dist'] = $net_hrg_supplier_dist;
                        $data_hp['net_hrg_supplier_dist_inc']	= $net_hrg_supplier_dist_inc;
                        $data_hp['keterangan']	= $keterangan;

			unset($data['kd_supplier']);
			unset($data['kd_produk']);
			// unset($data['waktu_top']);
			unset($data['status']);
			unset($data['tgl_approve']);
			unset($data['approve_by']);


			if($status_approve == '1'){
				if($this->ahp_model->update_row($kd_supplier, $kd_produk, $waktu_top, $data_hp)){

					$data['status_approve'] = $status_approve;
					$data['kd_supplier'] = $kd_supplier;
					$data['kd_produk'] = $kd_produk;
					$data['waktu_top'] = $waktu_top;
					$data['tgl_approve'] = $tanggal;
                                        $data['kd_peruntukan'] = '1';
					$data['approve_by'] = $this->session->userdata('username');

					if($this->ahp_model->insert_history($data)){
						$results = 'success';
					}else{
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"History Failed . . ."}';
						exit;
					}
					$produk_result = $this->ahp_model->get_produk_margin($kd_produk);
					foreach($produk_result as $result){
						$pct_margin = $result->pct_margin;
						$rp_ongkos_kirim = $result->rp_ongkos_kirim;
						$pct_margin_dist = $result->pct_margin_dist;
						$rp_ongkos_kirim_dist = $result->rp_ongkos_kirim_dist;
					}
					$rp_het_harga_beli = ($data['net_hrg_supplier_sup'] + ($data['net_hrg_supplier_sup'] * ($pct_margin/100)) + $rp_ongkos_kirim)*1.1;
					$rp_het_harga_beli_dist = ($data['net_hrg_supplier_dist'] + ($data['net_hrg_supplier_dist'] * ($pct_margin_dist/100)) + $rp_ongkos_kirim_dist)*1.1;

					if($this->ahp_model->update_net_produk_dist($kd_produk,$rp_het_harga_beli_dist,$data['net_hrg_supplier_dist'])){
						$results = 'success';
					}else{
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"update_net_produk Failed . . ."}';
						exit;
					}

//					$diskon_sales_result = $this->ahp_model->get_hrgJual_produk($kd_produk);
//					if($diskon_sales_result){
//						if($this->ahp_model->update_hrgJual_produk_dist($kd_produk,$rp_het_harga_beli_dist,$data['net_hrg_supplier_dist'])){
//							$results = 'success';
//						}else{
//							$this->db->trans_rollback();
//							echo '{"success":false,"errMsg":"update_net_produk Failed . . ."}';
//							exit;
//						}
//					}
				}else{
					$this->db->trans_rollback();
					echo '{"success":false,"errMsg":"update_row Failed . . ."}';
					exit;
				}
			}else if($status_approve == '9'){
				$data['status_approve'] = $status_approve;
				$data['kd_supplier'] = $kd_supplier;
				$data['kd_produk'] = $kd_produk;
				$data['waktu_top'] = $waktu_top;
				$data['tgl_approve'] = $tanggal;
				$data['approve_by'] = $this->session->userdata('username');
				if($this->ahp_model->insert_history($data)){
					$results = 'success';
				}else{
					$this->db->trans_rollback();
					echo '{"success":false,"errMsg":"History Failed . . ."}';
					exit;
				}
			}else{
				$results = 'success';
			}

		}

		if($results == 'success'){
                    //print_r("dddd");
                    //print_r($is_konsinyasi);

                    if($is_konsinyasi == '1'){

                        $updatehj['disk_persen_kons1'] = $disk_persen_supp1;
                        $updatehj['rp_jual_distribusi'] = $hrg_supplier_dist;
                        $result = $this->ahp_model->update_diskon_jual_konsinyasi($kd_produk, $updatehj);


                    }

			$result = '{"success":true,"errMsg":""}';
			$this->db->trans_commit();
		}else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed . . ."}';
		}
		echo $result;

	}

}
