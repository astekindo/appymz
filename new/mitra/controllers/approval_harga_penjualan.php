<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_harga_penjualan extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('approval_harga_penjualan_model', 'ahj_model');
		$this->load->model('harga_penjualan_model', 'hj_model');
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

		$data_result = $this->ahj_model->search_produk_by_no_bukti($no_bukti,$search,$start,$limit);
		$hasil = $data_result['rows'];
		$results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
			$total_diskon_kons = 0;
			$total_diskon_memb = 0;

			if($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0){
				$total_diskon_kons = $result->rp_jual_supermarket-($result->rp_jual_supermarket*($result->disk_persen_kons1/100));
				$diskon_kons1 = $result->disk_persen_kons1;
				$result->disk_kons1_op = "%";
			}else{
				if($result->disk_amt_kons1 != ''){
					$total_diskon_kons = $result->rp_jual_supermarket-$result->disk_amt_kons1;
					$diskon_kons1 = $result->disk_amt_kons1;
					$result->disk_kons1_op = "Rp";
				}else{
					$diskon_kons1 = 0;
				}
			}

			if($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons2/100));
				$diskon_kons2 = $result->disk_persen_kons2;
				$result->disk_kons2_op = "%";
			}else{
				if($result->disk_amt_kons2 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons2;
					$diskon_kons2 = $result->disk_amt_kons2;
					$result->disk_kons2_op = "Rp";
				}else{
					$diskon_kons2 = 0;
				}
			}

			if($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons3/100));
				$diskon_kons3 = $result->disk_persen_kons3;
				$result->disk_kons3_op = "%";
			}else{
				if($result->disk_amt_kons3 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons3;
					$diskon_kons3 = $result->disk_amt_kons3;
					$result->disk_kons3_op = "Rp";
				}else{
					$diskon_kons3 = 0;
				}
			}

			if($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0){
				$total_diskon_kons = $total_diskon_kons-($total_diskon_kons*($result->disk_persen_kons4/100));
				$diskon_kons4 = $result->disk_persen_kons4;
				$result->disk_kons4_op = "%";
			}else{
				if($result->disk_amt_kons4 != ''){
					$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons4;
					$diskon_kons4 = $result->disk_amt_kons4;
					$result->disk_kons4_op = "Rp";
				}else{
					$diskon_kons4 = 0;
				}
			}

			if($result->disk_amt_kons5 != ''){
				$total_diskon_kons = $total_diskon_kons-$result->disk_amt_kons5;
				$diskon_amt_kons5 = $result->disk_amt_kons5;
			}else{
				$diskon_amt_kons5 = 0;
			}


			$diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

			//diskon Rp
			$result->disk_kons1 = $diskon_kons1;
			$result->disk_kons2 = $diskon_kons2;
			$result->disk_kons3 = $diskon_kons3;
			$result->disk_kons4 = $diskon_kons4;
			$result->net_price_jual_kons = $total_diskon_kons;
			$diskon = 0;

			if($result->disk_persen_member1 != '' && $result->disk_persen_member1 != 0){
				$total_diskon_memb = $result->rp_jual_supermarket-($result->rp_jual_supermarket*($result->disk_persen_member1/100));
				$diskon_member1 = $result->disk_persen_member1;
				$result->disk_member1_op = "%";
			}else{
				if($result->disk_amt_member1 != ''){
					$total_diskon_memb = $result->rp_jual_supermarket-$result->disk_amt_member1;
					$diskon_member1 = $result->disk_amt_member1;
					$result->disk_member1_op = "Rp";
				}else{
					$diskon_member1 = 0;
				}
			}

			if($result->disk_persen_member2 != '' && $result->disk_persen_member2 != 0){
				$total_diskon_memb = $total_diskon_memb-($total_diskon_memb*($result->disk_persen_member2/100));
				$diskon_member2 = $result->disk_persen_member2;
				$result->disk_member2_op = "%";
			}else{
				if($result->disk_amt_member2 != ''){
					$total_diskon_memb = $total_diskon_memb-$result->disk_amt_member2;
					$diskon_member2 = $result->disk_amt_member2;
					$result->disk_member2_op = "Rp";
				}else{
					$diskon_member2 = 0;
				}
			}

			if($result->disk_persen_member3 != '' && $result->disk_persen_member3 != 0){
				$total_diskon_memb = $total_diskon_memb-($total_diskon_memb*($result->disk_persen_member3/100));
				$diskon_member3 = $result->disk_persen_member3;
				$result->disk_member3_op = "%";
			}else{
				if($result->disk_amt_member3 != ''){
					$total_diskon_memb = $total_diskon_memb-$result->disk_amt_member3;
					$diskon_member3 = $result->disk_amt_member3;
					$result->disk_member3_op = "Rp";
				}else{
					$diskon_member3 = 0;
				}
			}

			if($result->disk_persen_member4 != '' && $result->disk_persen_member4 != 0){
				$total_diskon_memb = $total_diskon_memb-($total_diskon_memb*($result->disk_persen_member4/100));
				$diskon_member4 = $result->disk_persen_member4;
				$result->disk_member4_op = "%";
			}else{
				if($result->disk_amt_member4 != ''){
					$total_diskon_memb = $total_diskon_memb-$result->disk_amt_member4;
					$diskon_member4 = $result->disk_amt_member4;
					$result->disk_member4_op = "Rp";
				}else{
					$diskon_member4 = 0;
				}
			}

			if($result->disk_amt_member5 != ''){
				$total_diskon_memb = $total_diskon_memb-$result->disk_amt_member5;
				$diskon_amt_member5 = $result->disk_amt_member5;
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
			$result->net_price_jual_member = $total_diskon_memb;

			//$result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
			//$margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
			//$result->rp_het_harga_beli = ($result->net_hrg_supplier_sup_inc + $margin + $result->rp_ongkos_kirim) * 1.1;
			//$result->rp_het_cogs = 0;
			//if($result->rp_het_cogs == 0 && $result->rp_cogs > 0){
			//	$result->rp_het_cogs = ($result->rp_cogs + $margin + $result->rp_ongkos_kirim) * 1.1;
			//}
			$results[] = $result;
		}
		echo '{success:true,record:'.$data_result['total'].',data:'.json_encode($results).'}';
	}

	public function get_no_bukti_filter(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->ahj_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
	}

	public function approval(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';

		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

		$result_prod = 0;
		$result_disk = 0;

		$this->db->trans_begin();
		foreach($detail as $obj){
			$results = 'success';
			if($obj->status == 'Approve'){
				$status = '1';
			}else {
				$status = '9';
			}
			if(!($this->ahj_model->update_temp($no_bukti, $obj->kd_produk, $status))){
				$this->db->trans_rollback();
				echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
				exit;
			}
			if($obj->status == 'Approve'){
				$kd_produk = $obj->kd_produk;
				if($obj->margin_op == '%'){
					$pct_margin = $obj->margin;
					$rp_margin = ($obj->margin*$obj->net_hrg_supplier_sup_inc)/100;
				}else{
					$rp_margin = $obj->margin;
					$pct_margin = ($obj->margin*100)/$obj->net_hrg_supplier_sup_inc;
				}

				//produk
				$koreksi_produk = $obj->koreksi_produk+1;

				$RpJualSup = (int) $obj->rp_jual_supermarket;
				$NetPJualKons = (int) $obj->net_price_jual_kons;
				$NetPJualMemb = (int) $obj->net_price_jual_member;
				$HetBeli = (int)  $obj->rp_het_harga_beli;
				$cogs = (int) $obj->rp_cogs;

				if($cogs > 0){
					if($RpJualSup < $cogs){
						echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET COGS"}';
						$this->db->trans_rollback();
						exit;
					}
				}else{
					if($RpJualSup < $HetBeli){
						echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
						$this->db->trans_rollback();
						exit;
					}
				}

				if($cogs > 0){
					if($NetPJualKons < $cogs){
						echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET COGS"}';
						$this->db->trans_rollback();
						exit;
					}
				}else{
					if($NetPJualKons < $HetBeli){
						echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
						$this->db->trans_rollback();
						exit;
					}
				}

				if($cogs > 0){
					if($NetPJualMemb < $cogs){
						echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET COGS"}';
						$this->db->trans_rollback();
						exit;
					}
				}else{
					if($NetPJualMemb < $HetBeli){
						echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
						$this->db->trans_rollback();
						exit;
					}
				}

				//diskon
				$kd_diskon_sales = $obj->kd_diskon_sales;

				$disk_kons1_op = $obj->disk_kons1_op;
				$disk_kons2_op = $obj->disk_kons2_op;
				$disk_kons3_op = $obj->disk_kons3_op;
				$disk_kons4_op = $obj->disk_kons4_op;

				$disk_kons1 = $obj->disk_kons1;
				$disk_kons2 = $obj->disk_kons2;
				$disk_kons3 = $obj->disk_kons3;
				$disk_kons4 = $obj->disk_kons4;

				if($disk_kons1_op === "%"){
					$disk_persen_kons1 = $disk_kons1;
					$disk_amt_kons1 = 0;
				}else{
					$disk_persen_kons1 = 0;
					$disk_amt_kons1 = $disk_kons1;
				}
				if($disk_kons2_op === "%"){
					$disk_persen_kons2 = $disk_kons2;
					$disk_amt_kons2 = 0;
				}else{
					$disk_persen_kons2 = 0;
					$disk_amt_kons2 = $disk_kons2;
				}
				if($disk_kons3_op === "%"){
					$disk_persen_kons3 = $disk_kons3;
					$disk_amt_kons3 = 0;
				}else{
					$disk_persen_kons3 = 0;
					$disk_amt_kons3 = $disk_kons3;
				}
				if($disk_kons4_op === "%"){
					$disk_persen_kons4 = $disk_kons4;
					$disk_amt_kons4 = 0;
				}else{
					$disk_persen_kons4 = 0;
					$disk_amt_kons4 = $disk_kons4;
				}

				$disk_kons5 = $obj->disk_amt_kons5;

				$disk_member1_op = $obj->disk_member1_op;
				$disk_member2_op = $obj->disk_member2_op;
				$disk_member3_op = $obj->disk_member3_op;
				$disk_member4_op = $obj->disk_member4_op;

				$disk_member1 = $obj->disk_member1;
				$disk_member2 = $obj->disk_member2;
				$disk_member3 = $obj->disk_member3;
				$disk_member4 = $obj->disk_member4;

				if($disk_member1_op === "%"){
					$disk_persen_member1 = $disk_member1;
					$disk_amt_member1 = 0;
				}else{
					$disk_persen_member1 = 0;
					$disk_amt_member1 = $disk_member1;
				}
				if($disk_member2_op === "%"){
					$disk_persen_member2 = $disk_member2;
					$disk_amt_member2 = 0;
				}else{
					$disk_persen_member2 = 0;
					$disk_amt_member2 = $disk_member2;
				}
				if($disk_member3_op === "%"){
					$disk_persen_member3 = $disk_member3;
					$disk_amt_member3 = 0;
				}else{
					$disk_persen_member3 = 0;
					$disk_amt_member3 = $disk_member3;
				}
				if($disk_member4_op === "%"){
					$disk_persen_member4 = $disk_member4;
					$disk_amt_member4 = 0;
				}else{
					$disk_persen_member4 = 0;
					$disk_amt_member4 = $disk_member4;
				}

				$disk_member5 = $obj->disk_amt_member5;
				$qty_beli_bonus = $obj->qty_beli_bonus;
				$kd_produk_bonus = $obj->kd_produk_bonus;
				$qty_bonus = $obj->qty_bonus;
				$is_bonus_kelipatan = $obj->is_bonus_kelipatan;
				$qty_beli_member = $obj->qty_beli_member;
				$kd_produk_member = $obj->kd_produk_member;
				$qty_member = $obj->qty_member;
				$is_member_kelipatan = $obj->is_member_kelipatan;
                $tgl_start_diskon = $obj->tgl_start_diskon;
                $tgl_end_diskon = $obj->tgl_end_diskon;

				$is_member_kelipatan = isset($is_member_kelipatan) ? $is_member_kelipatan : 0;
				$is_bonus_kelipatan = isset($is_bonus_kelipatan) ? $is_bonus_kelipatan : 0;
				$kd_produk_bonus = isset($kd_produk_bonus) ? $kd_produk_bonus : '';
				$kd_produk_member = isset($kd_produk_member) ? $kd_produk_member : '';


				if($is_bonus_kelipatan == 'Ya'){
					$is_bonus_kelipatan = 1;
				}else if($is_bonus_kelipatan == 'Tidak'){
					$is_bonus_kelipatan = 0;
				}

				if($is_member_kelipatan == 'Ya'){
					$is_member_kelipatan = 1;
				}else if($is_member_kelipatan == 'Tidak'){
					$is_member_kelipatan = 0;
				}
				if($qty_bonus > 0 || $qty_member > 0){
					$is_bonus = 1;
				}else $is_bonus = 0;

				$koreksi_diskon = $obj->koreksi_ke+1;
				$aktif = '1';

				$created_by = $this->session->userdata('username');
				$created_date = date('Y-m-d H:i:s');


				unset($diskon_hj);

				$diskon_hj['net_hrg_supplier_sup_inc'] = $obj->net_hrg_supplier_sup_inc;
				$diskon_hj['rp_cogs'] = $obj->rp_cogs;
				$diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
				$diskon_hj['pct_margin'] = $pct_margin;
				$diskon_hj['rp_margin'] = $rp_margin;
				$diskon_hj['rp_jual_supermarket'] = $obj->rp_jual_supermarket;
				$diskon_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli;
				$diskon_hj['rp_het_cogs'] = $obj->rp_het_cogs;
				$diskon_hj['tanggal']	=	$created_date;
				$diskon_hj['kd_diskon_sales']	=	$kd_diskon_sales;
				$diskon_hj['no_bukti']	=	$no_bukti ;
				$diskon_hj['disk_persen_kons1']	=	$disk_persen_kons1;
				$diskon_hj['disk_persen_kons2']	=	$disk_persen_kons2;
				$diskon_hj['disk_persen_kons3']	=	$disk_persen_kons3;
				$diskon_hj['disk_persen_kons4']	=	$disk_persen_kons4;
				$diskon_hj['disk_amt_kons1']	=	$disk_amt_kons1;
				$diskon_hj['disk_amt_kons2']	=	$disk_amt_kons2;
				$diskon_hj['disk_amt_kons3']	=	$disk_amt_kons3;
				$diskon_hj['disk_amt_kons4']	=	$disk_amt_kons4;
				$diskon_hj['disk_amt_kons5']	=	$disk_kons5;
				$diskon_hj['disk_persen_member1']	=	$disk_persen_member1;
				$diskon_hj['disk_persen_member2']	=	$disk_persen_member2;
				$diskon_hj['disk_persen_member3']	=	$disk_persen_member3;
				$diskon_hj['disk_persen_member4']	=	$disk_persen_member4;
				$diskon_hj['disk_amt_member1']	=	$disk_amt_member1;
				$diskon_hj['disk_amt_member2']	=	$disk_amt_member2;
				$diskon_hj['disk_amt_member3']	=	$disk_amt_member3;
				$diskon_hj['disk_amt_member4']	=	$disk_amt_member4;
				$diskon_hj['disk_amt_member5']	=	$disk_member5;
				$diskon_hj['qty_beli_bonus']	=	$qty_beli_bonus;
				$diskon_hj['kd_produk_bonus']	=	$kd_produk_bonus;
				$diskon_hj['qty_bonus']			=	$qty_bonus;
				$diskon_hj['is_bonus_kelipatan']	=	$is_bonus_kelipatan;
				$diskon_hj['qty_beli_member']	=	$qty_beli_member;
				$diskon_hj['kd_produk_member']	=	$kd_produk_member;
				$diskon_hj['qty_member']	=	$qty_member;
				$diskon_hj['is_member_kelipatan']	=	$is_member_kelipatan;
				$diskon_hj['is_bonus']		=	$is_bonus;
				$diskon_hj['created_by']	=	$created_by;
				$diskon_hj['created_date']	=	$created_date;
				$diskon_hj['koreksi_ke']	=	$koreksi_diskon;
				$diskon_hj['koreksi_produk']	=	$koreksi_produk;
                $diskon_hj['tgl_start_diskon']	=	$tgl_start_diskon;
                //$diskon_hj['tgl_end_diskon']	=	$tgl_end_diskon;

				if($this->hj_model->update_temp($kd_produk, $no_bukti,$diskon_hj)){
					$results = 'success';
				}else{
					$this->db->trans_rollback();
					echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
					exit;
				}
			}
		}

		if($results == "success"){
			$this->db->trans_commit();
		}else{
			$this->db->trans_rollback();
			echo '{"success":false,"errMsg":"Insert Data Failed . . ."}';
			exit;
		}
		if($tanggal){
			$tanggal = date('Y-m-d', strtotime($tanggal));
		}

		$result_temp = $this->ahj_model->get_data_temp($no_bukti);

		$this->db->trans_begin();
		foreach($result_temp as $data) {
			$koreksi_produk = $data['koreksi_produk'];
            $status_approve = $data['status'];
			unset($data['net_hrg_supplier_sup_inc']);
			unset($data['status']);
			unset($data['koreksi_produk']);
			unset($data['no_bukti']);
			unset($data['tgl_approve']);
			unset($data['approve_by']);

			$kd_diskon_sales = $data['kd_diskon_sales'];

                        if ($status_approve == '1') {

                            $produk = $this->ahj_model->select_data_beli($data['kd_produk'],$data['tgl_start_diskon']);
                            if ($produk){
                                //Update Harga

                                if ($this->ahj_model->update_rows_diskon($data['kd_produk'],$data['tgl_start_diskon'],$data)) {
                                       $results = 'success';
                                }else{
                                    $this->db->trans_rollback();
                                        echo '{"success":false,"errMsg":"Update Failed . . ."}';
                                        exit;
                                }
                            }else{
                                $data['created_by'] = $this->session->userdata('username');
                                    $data['created_date'] = date('Y-m-d H:i:s');

                                    if ($this->ahj_model->insert_rows_diskon($data)) {
                                       // if ($this->ahjd_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)) {
                                            $results = 'success';
                                        //}
                                    } else {
                                        echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
                                        $this->db->trans_rollback();
                                        exit;
                                    }
                            }
                            //Non Aktifkan Harga Masa Depan
                            if ($this->ahj_model->update_harga_jual_non_aktif($data['kd_produk'],$data['tgl_start_diskon'])) {
                                       $results = 'success';
                            }else {
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"Non Aktif Failed . . ."}';
                                    exit;
                            }
                            //Update tgl end diskon
                            if ($this->ahj_model->update_harga_jual_tgl_end($data['kd_produk'],$data['tgl_start_diskon'])) {
                                       $results = 'success';
                            }else {
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"Update Tgl End Failed . . ."}';
                                    exit;
                            }
                        }
//                         if ($status_approve == '1') {
//                                $produk = $this->ahj_model->select_data_jual($data['kd_produk'], $data['tgl_start_diskon'],$data['tgl_end_diskon']);
//                                if ($produk){
//                                    $data['updated_by'] = $this->session->userdata('username');
//                                    $data['updated_date'] = date('Y-m-d H:i:s');
//                                    if ($this->ahj_model->update_rows_diskon($data['kd_produk'],$data['tgl_start_diskon'],$data['tgl_end_diskon'],$data)) {
//                                       // if ($this->ahjd_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)) {
//                                            $result_disk++;
//                                        //}
//                                    } else {
//                                        echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
//                                        $this->db->trans_rollback();
//                                        exit;
//                                    }
//
//                                }else{
//                                    $data['created_by'] = $this->session->userdata('username');
//                                    $data['created_date'] = date('Y-m-d H:i:s');
//
//                                    if ($this->ahj_model->insert_rows_diskon($data)) {
//                                       // if ($this->ahjd_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)) {
//                                            $result_disk++;
//                                        //}
//                                    } else {
//                                        echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
//                                        $this->db->trans_rollback();
//                                        exit;
//                                    }
//                                }
//
//                      }  else {
//                            $results = 'success';
//                        }
//			if($status_approve == '1'){
//				if(!$kd_diskon_sales){
//					$no_urut_diskon = $this->ahj_model->get_kode_sequence('HJUAL',3);
//					$kd_diskon_sales = date('Ym').'-'.$no_urut_diskon;
//					// $diskon_hj['kd_diskon_sales']	=	$kd_diskon_sales;
//					$data['created_by']	=	$updated_by;
//					$data['created_date']	=	$updated_date;
//
//					if($this->ahj_model->insert_rows_diskon($data)){
//						if($this->ahj_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)){
//							$result_disk++;
//						}
//					}else{
//						echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
//						$this->db->trans_rollback();
//						exit;
//					}
//				}else{
//					$data['updated_by']	=	$updated_by;
//					$data['updated_date']	=	$updated_date;
//
//					if($this->ahj_model->update_rows_diskon($data['kd_produk'], $kd_diskon_sales, $data)){
//						if($this->ahj_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)){
//							$result_disk++;
//						}
//					}else{
//						echo '{"success":false,"errMsg":"Update Diskon Failed"}';
//						$this->db->trans_rollback();
//						exit;
//					}
//				}
//
//				// if($this->ahj_model->update_row($kd_konslier, $kd_produk, $waktu_top, $data)){
//					// $data['kd_konslier'] = $kd_konslier;
//					// $data['kd_produk'] = $kd_produk;
//					// $data['waktu_top'] = $waktu_top;
//					// $data['tgl_approve'] = $tanggal;
//					// $data['approve_by'] = $this->session->userdata('username');
//
//					// if($this->ahj_model->insert_history($data)){
//						// $results = 'success';
//					// }else{
//						// $this->db->trans_rollback();
//						// echo '{"success":false,"errMsg":"History Failed . . ."}';
//						// exit;
//					// }
//
//					if($this->ahj_model->update_rows_produk($data['kd_produk'], $produk_hj)){
//						if($this->ahj_model->insert_rows_produk_history($data['kd_produk'], $koreksi_produk)){
//							$results = 'success';
//						}
//					}else{
//						$this->db->trans_rollback();
//						echo '{"success":false,"errMsg":"update_rows_produk Failed . . ."}';
//						exit;
//					}
//			}else if($status_approve == '9'){
//				if($this->ahj_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)){
//					$result_disk++;
//				}else{
//					$this->db->trans_rollback();
//					echo '{"success":false,"errMsg":"History Failed . . ."}';
//					exit;
//				}
//			}else{
//				$results = 'success';
//			}
		}

		if($results == 'success'){
			$result = '{"success":true,"errMsg":""}';
			$this->db->trans_commit();
		}else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed . . ."}';
		}
		echo $result;

	}

   public function update_row(){

		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : '';
		$status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';

		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

		$result_prod = 0;
		$result_disk = 0;

		$this->db->trans_begin();
		foreach($detail as $obj){
			$results = 'success';

			if($obj->edited == 'Y'){
					$kd_produk = $obj->kd_produk;
					if($obj->margin_op == '%'){
						$pct_margin = $obj->margin;
						$rp_margin = ($obj->margin*$obj->net_hrg_supplier_sup_inc)/100;
					}else{
						$rp_margin = $obj->margin;
						$pct_margin = ($obj->margin*100)/$obj->net_hrg_supplier_sup_inc;
					}

					//produk
					$koreksi_produk = $obj->koreksi_produk+1;

					$RpJualSup = (int) $obj->rp_jual_supermarket;
					$NetPJualKons = (int) $obj->net_price_jual_kons;
					$NetPJualMemb = (int) $obj->net_price_jual_member;
					$HetBeli = (int)  $obj->rp_het_harga_beli;
					$cogs = (int) $obj->rp_cogs;

                                        if($obj->is_konsinyasi === '0'){

                                            if($cogs > 0){
                                                    if($RpJualSup < $cogs){
                                                            echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET COGS"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }else{
                                                    if($RpJualSup < $HetBeli){
                                                            echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }

                                            if($cogs > 0){
                                                    if($NetPJualKons < $cogs){
                                                            echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET COGS"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }else{
                                                    if($NetPJualKons < $HetBeli){
                                                            echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }

                                            if($cogs > 0){
                                                    if($NetPJualMemb < $cogs){
                                                            echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET COGS"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }else{
                                                    if($NetPJualMemb < $HetBeli){
                                                            echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                                                            $this->db->trans_rollback();
                                                            exit;
                                                    }
                                            }

                                        }

                                        if($RpJualSup < $HetBeli){
                                                echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
                                                $this->db->trans_rollback();
                                                exit;
                                        }

                                        if($NetPJualKons < $HetBeli){
                                                echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                                                $this->db->trans_rollback();
                                                exit;
                                        }

                                        if($NetPJualMemb < $HetBeli){
                                                echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                                                $this->db->trans_rollback();
                                                exit;
                                        }

					//diskon
					$kd_diskon_sales = $obj->kd_diskon_sales;

					$disk_kons1_op = $obj->disk_kons1_op;
					$disk_kons2_op = $obj->disk_kons2_op;
					$disk_kons3_op = $obj->disk_kons3_op;
					$disk_kons4_op = $obj->disk_kons4_op;

					$disk_kons1 = $obj->disk_kons1;
					$disk_kons2 = $obj->disk_kons2;
					$disk_kons3 = $obj->disk_kons3;
					$disk_kons4 = $obj->disk_kons4;

					if($disk_kons1_op === "%"){
						$disk_persen_kons1 = $disk_kons1;
						$disk_amt_kons1 = 0;
					}else{
						$disk_persen_kons1 = 0;
						$disk_amt_kons1 = $disk_kons1;
					}
					if($disk_kons2_op === "%"){
						$disk_persen_kons2 = $disk_kons2;
						$disk_amt_kons2 = 0;
					}else{
						$disk_persen_kons2 = 0;
						$disk_amt_kons2 = $disk_kons2;
					}
					if($disk_kons3_op === "%"){
						$disk_persen_kons3 = $disk_kons3;
						$disk_amt_kons3 = 0;
					}else{
						$disk_persen_kons3 = 0;
						$disk_amt_kons3 = $disk_kons3;
					}
					if($disk_kons4_op === "%"){
						$disk_persen_kons4 = $disk_kons4;
						$disk_amt_kons4 = 0;
					}else{
						$disk_persen_kons4 = 0;
						$disk_amt_kons4 = $disk_kons4;
					}

					$disk_kons5 = $obj->disk_amt_kons5;

					$disk_member1_op = $obj->disk_member1_op;
					$disk_member2_op = $obj->disk_member2_op;
					$disk_member3_op = $obj->disk_member3_op;
					$disk_member4_op = $obj->disk_member4_op;

					$disk_member1 = $obj->disk_member1;
					$disk_member2 = $obj->disk_member2;
					$disk_member3 = $obj->disk_member3;
					$disk_member4 = $obj->disk_member4;

					if($disk_member1_op === "%"){
						$disk_persen_member1 = $disk_member1;
						$disk_amt_member1 = 0;
					}else{
						$disk_persen_member1 = 0;
						$disk_amt_member1 = $disk_member1;
					}
					if($disk_member2_op === "%"){
						$disk_persen_member2 = $disk_member2;
						$disk_amt_member2 = 0;
					}else{
						$disk_persen_member2 = 0;
						$disk_amt_member2 = $disk_member2;
					}
					if($disk_member3_op === "%"){
						$disk_persen_member3 = $disk_member3;
						$disk_amt_member3 = 0;
					}else{
						$disk_persen_member3 = 0;
						$disk_amt_member3 = $disk_member3;
					}
					if($disk_member4_op === "%"){
						$disk_persen_member4 = $disk_member4;
						$disk_amt_member4 = 0;
					}else{
						$disk_persen_member4 = 0;
						$disk_amt_member4 = $disk_member4;
					}

					$disk_member5 = $obj->disk_amt_member5;
					$qty_beli_bonus = $obj->qty_beli_bonus;
					$kd_produk_bonus = $obj->kd_produk_bonus;
					$qty_bonus = $obj->qty_bonus;
					$is_bonus_kelipatan = $obj->is_bonus_kelipatan;
					$qty_beli_member = $obj->qty_beli_member;
					$kd_produk_member = $obj->kd_produk_member;
					$qty_member = $obj->qty_member;
					$is_member_kelipatan = $obj->is_member_kelipatan;
                                        $tgl_start_diskon = $obj->tgl_start_diskon;
                                        $tgl_end_diskon = $obj->tgl_end_diskon;

					$is_member_kelipatan = isset($is_member_kelipatan) ? $is_member_kelipatan : 0;
					$is_bonus_kelipatan = isset($is_bonus_kelipatan) ? $is_bonus_kelipatan : 0;
					$kd_produk_bonus = isset($kd_produk_bonus) ? $kd_produk_bonus : '';
					$kd_produk_member = isset($kd_produk_member) ? $kd_produk_member : '';


					if($is_bonus_kelipatan == 'Ya'){
						$is_bonus_kelipatan = 1;
					}else if($is_bonus_kelipatan == 'Tidak'){
						$is_bonus_kelipatan = 0;
					}

					if($is_member_kelipatan == 'Ya'){
						$is_member_kelipatan = 1;
					}else if($is_member_kelipatan == 'Tidak'){
						$is_member_kelipatan = 0;
					}
					if($qty_bonus > 0 || $qty_member > 0){
						$is_bonus = 1;
					}else $is_bonus = 0;

					$koreksi_diskon = $obj->koreksi_ke+1;
					$aktif = '1';

					$created_by = $this->session->userdata('username');
					$created_date = date('Y-m-d H:i:s');


					unset($diskon_hj);

					$diskon_hj['net_hrg_supplier_sup_inc'] = $obj->net_hrg_supplier_sup_inc;
					$diskon_hj['rp_cogs'] = $obj->rp_cogs;
					$diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
					$diskon_hj['pct_margin'] = $pct_margin;
					$diskon_hj['rp_margin'] = $rp_margin;
					$diskon_hj['rp_jual_supermarket'] = $obj->rp_jual_supermarket;
					$diskon_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli;
					$diskon_hj['rp_het_cogs'] = $obj->rp_het_cogs;
					$diskon_hj['tanggal']	=	$created_date;
					$diskon_hj['kd_diskon_sales']	=	$kd_diskon_sales;
					$diskon_hj['no_bukti']	=	$no_bukti ;
					$diskon_hj['disk_persen_kons1']	=	$disk_persen_kons1;
					$diskon_hj['disk_persen_kons2']	=	$disk_persen_kons2;
					$diskon_hj['disk_persen_kons3']	=	$disk_persen_kons3;
					$diskon_hj['disk_persen_kons4']	=	$disk_persen_kons4;
					$diskon_hj['disk_amt_kons1']	=	$disk_amt_kons1;
					$diskon_hj['disk_amt_kons2']	=	$disk_amt_kons2;
					$diskon_hj['disk_amt_kons3']	=	$disk_amt_kons3;
					$diskon_hj['disk_amt_kons4']	=	$disk_amt_kons4;
					$diskon_hj['disk_amt_kons5']	=	$disk_kons5;
					$diskon_hj['disk_persen_member1']	=	$disk_persen_member1;
					$diskon_hj['disk_persen_member2']	=	$disk_persen_member2;
					$diskon_hj['disk_persen_member3']	=	$disk_persen_member3;
					$diskon_hj['disk_persen_member4']	=	$disk_persen_member4;
					$diskon_hj['disk_amt_member1']	=	$disk_amt_member1;
					$diskon_hj['disk_amt_member2']	=	$disk_amt_member2;
					$diskon_hj['disk_amt_member3']	=	$disk_amt_member3;
					$diskon_hj['disk_amt_member4']	=	$disk_amt_member4;
					$diskon_hj['disk_amt_member5']	=	$disk_member5;
					$diskon_hj['qty_beli_bonus']	=	$qty_beli_bonus;
					$diskon_hj['kd_produk_bonus']	=	$kd_produk_bonus;
					$diskon_hj['qty_bonus']			=	$qty_bonus;
					$diskon_hj['is_bonus_kelipatan']	=	$is_bonus_kelipatan;
					$diskon_hj['qty_beli_member']	=	$qty_beli_member;
					$diskon_hj['kd_produk_member']	=	$kd_produk_member;
					$diskon_hj['qty_member']	=	$qty_member;
					$diskon_hj['is_member_kelipatan']	=	$is_member_kelipatan;
					$diskon_hj['is_bonus']		=	$is_bonus;
					$diskon_hj['created_by']	=	$created_by;
					$diskon_hj['created_date']	=	$created_date;
					$diskon_hj['koreksi_ke']	=	$koreksi_diskon;
					$diskon_hj['koreksi_produk']	=	$koreksi_produk;
                                        $diskon_hj['tgl_start_diskon']	=	$tgl_start_diskon;
                                        $diskon_hj['tgl_end_diskon']	=	$tgl_end_diskon;

					if($this->hj_model->update_temp($kd_produk, $no_bukti,$diskon_hj)){
						$results = 'success';
					}else{
						$this->db->trans_rollback();
						echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
						exit;
					}
				}

		}

		if($results == 'success'){
			$result = '{"success":true,"errMsg":""}';
			$this->db->trans_commit();
		}else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed . . ."}';
		}
		echo $result;


	}

}
