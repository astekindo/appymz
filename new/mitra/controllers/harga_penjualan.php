<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_penjualan extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('harga_penjualan_model', 'hj_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_hj = 'HJ' . date('Ymd') . '-';
    	$sequence = $this->hj_model->get_kode_sequence($no_hj, 3);
    	echo '{"success":true,
				"data":{
					"no_hj":"",
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
		//$no_hj = isset($_POST['no_hj']) ? $this->db->escape_str($this->input->post('no_hj',TRUE)) : '';
		$no_bukti_filter = isset($_POST['no_bukti_filter']) ? $this->db->escape_str($this->input->post('no_bukti_filter',TRUE)) : '';
		$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : FALSE;
                $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal',TRUE)) : FALSE;

                $current_date = date('Ymd', strtotime($tanggal));
                $no_ret = 'HJ' . $current_date . '-';
                $sequence = $this->hj_model->get_kode_sequence($no_ret, 3);
                $no_hj = "$no_ret$sequence";

		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

		$result_prod = 0;
		$result_disk = 0;

		$this->db->trans_begin();
		foreach($detail as $obj){
			if($obj->edited == 'Y'){
				// echo $obj->kd_produk.'<br>'.$obj->edited;
				// $edited = $obj->kd_produk.' '.$obj->edited;

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
				// unset($produk_hj);
				// $produk_hj['hrg_beli_satuan'] = $obj->hrg_beli_satuan;
				// $produk_hj['hrg_beli_sup'] = $obj->hrg_supplier;
				// $produk_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
				// $produk_hj['pct_margin'] = $pct_margin;
				// $produk_hj['rp_margin'] = $rp_margin;
				// $produk_hj['rp_jual_supermarket'] = $obj->rp_jual_supermarket;
				// $produk_hj['rp_jual_distribusi'] = $obj->rp_jual_distribusi;
				// $produk_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli;
				// $produk_hj['koreksi_ke'] = $koreksi_produk;

				$RpJualSup = (int) $obj->rp_jual_supermarket;
				$NetPJualKons = (int) $obj->net_price_jual_kons;
				$NetPJualMemb = (int) $obj->net_price_jual_member;
				//$RpJualDist = (int) $obj->rp_jual_distribusi;
				$HetBeli = (int)  $obj->rp_het_harga_beli;
				$cogs = (int) $obj->p_rp_het_cogs;
				$net_hrg_supplier_sup_inc = (int) $obj->net_hrg_supplier_sup_inc;

				if($net_hrg_supplier_sup_inc <= 0){
					echo '{"success":false,"errMsg":"Net Price Pembelian Masih 0"}';
					$this->db->trans_rollback();
					exit;
				}

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
				/*
				if($RpJualDist < $HetBeli){
					echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli dan HET COGS"}';
					$this->db->trans_rollback();
					exit;
				}*/

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
                                //$tgl_end_diskon = $obj->tgl_end_diskon;

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

				$koreksi_diskon = $obj->koreksi_diskon+1;
				// $keterangan = $obj->keterangan;
				$aktif = '1';

				$created_by = $this->session->userdata('username');
				$created_date = date('Y-m-d H:i:s');


				unset($diskon_hj);

				$diskon_hj['net_hrg_supplier_sup_inc'] = $obj->net_hrg_supplier_sup_inc;
				$diskon_hj['rp_cogs'] = $obj->p_rp_cogs;
				$diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
				$diskon_hj['pct_margin'] = $pct_margin;
				$diskon_hj['rp_margin'] = $rp_margin;
				$diskon_hj['rp_jual_supermarket'] = $obj->rp_jual_supermarket;
				//$diskon_hj['rp_jual_distribusi'] = $obj->rp_jual_distribusi;
				$diskon_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli;
				$diskon_hj['rp_het_cogs'] = $obj->p_rp_het_cogs;
				$diskon_hj['tanggal']	=	$created_date;
				//$diskon_hj['kd_diskon_sales']	=	$kd_diskon_sales;
				//$diskon_hj['no_bukti']	=	$no_hj ;
                                $diskon_hj['kd_diskon_sales'] = $no_hj;
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
				$diskon_hj['keterangan']	=	$keterangan;
				$diskon_hj['koreksi_produk']	=	$koreksi_produk;
                $diskon_hj['tgl_start_diskon']	=	$tgl_start_diskon;
                //$diskon_hj['tgl_end_diskon']	=	$tgl_end_diskon;

                $result_data = $this->hj_model->select_data_temp($kd_produk, $tgl_start_diskon);
                if ($result_data) {
                    unset($diskon_hj['kd_diskon_sales']);
                    $diskon_hj['status'] = 0;
                    if ($this->hj_model->update_temp_by_date($kd_produk, $tgl_start_diskon,$diskon_hj)) {
                        $results = 'success';
                    }else {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"Update_temp Failed . . ."}';
                        exit;
                    }
                } else {
                    $diskon_hj['kd_produk']	=	$kd_produk;
                    $diskon_hj['status']	=	0;
                    if($this->hj_model->insert_temp($diskon_hj)){
                            $results = 'success';
                    }else{
                            $this->db->trans_rollback();
                            echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
                            exit;
                    }
                }

			}
		}
		if($results == "success"){
			$this->db->trans_commit();
			echo'{"success":true,"errMsg":""}';
		}else{
			$this->db->trans_rollback();
			echo '{"success":false,"errMsg":"Tidak Ada Data yang Disimpan"}';
		}
	}


	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_kategori(){
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
                $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran',TRUE)) : '';
                $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan',TRUE)) : '';
		$list = isset($_POST['list']) ? $this->db->escape_str($this->input->post('list',TRUE)) : '';
		$konsinyasi = isset($_POST['konsinyasi']) ? $this->db->escape_str($this->input->post('konsinyasi',TRUE)) : '';
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		if($konsinyasi === 'false'){
			$konsinyasi = 0;
		}else{
			$konsinyasi = 1;
		}
		if($list != ''){
			$list_exp = explode(',',$list);
			$list_imp = implode("','",$list_exp);
			$list = strtoupper("'".$list_imp."'");
		}

		$data_result = $this->hj_model->search_produk_by_kategori($kd_supplier,$no_bukti,$konsinyasi,$kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$kd_ukuran,$kd_satuan,$list,$search,$start,$limit);
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

			$result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
			$margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
			$result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
			$result->rp_het_cogs = 0;
			if(!$result->rp_cogs && $result->rp_cogs != 0){
				$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
			}
			$results[] = $result;
		}
		echo '{success:true,record:'.$data_result['total'].',data:'.json_encode($results).', lq: '.json_encode($this->db->last_query()).'}';
	}

	public function search_produk_history(){
		$no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti',TRUE)) : '';
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';

		$hasil = $this->hj_model->search_produk_history($no_bukti,$kd_produk);
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
			//$result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
			//$result->rp_het_cogs = 0;
			//if(!$result->rp_cogs && $result->rp_cogs != 0){
			//	$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
			//}
			$results[] = $result;
		}
		echo '{success:true,data:'.json_encode($results).'}';
	}
	public function print_form($no_bukti = '', $kd_produk = ''){
		$data = $this->hj_model->get_data_print($no_bukti,$kd_produk);
		if(!$data) show_404('page');

		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/HargaPenjualanPrint.php');
		$pdf = new HargaPenjualanPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'F4', true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['detail']);
		$pdf->Output();
		exit;
	}

	public function search_no_bukti(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hj_model->search_no_bukti($search, $start, $limit);

        echo $result;
	}

	public function get_no_bukti_filter(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->hj_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
	}

}
