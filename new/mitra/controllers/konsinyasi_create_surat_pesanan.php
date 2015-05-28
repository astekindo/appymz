<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_create_surat_pesanan extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('konsinyasi_create_surat_pesanan_model', 'kcsp_model');
		$this->load->model('pembelian_create_po_model', 'pcp_model');
		$this->load->model('konsinyasi_create_request_model', 'kcr_model');
    }

    public function get_form(){
    	// $no_po = 'PK' . date('Ymd') . '-';
    	// $sequence = $this->kcsp_model->get_kode_sequence($no_po, 3);
		$jth_tempo_po = $this->kcsp_model->get_jth_tempo_po();
		
    	echo '{"success":true,
				"data":{
					"no_po":"",
					"order_by":"' . $this->session->userdata('username') . '",
					"tanggal_po":"' . date('d-M-Y'). '",
                                        "user_peruntukan":"'. $this->session->userdata('user_peruntukan') .'", 
					"tgl_berlaku_po":"' . date('d-m-Y', strtotime('+'.$jth_tempo_po.' day')). '"
				}
			}';
    }

    public function update_row(){
		// $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po',TRUE)) : '';
		$tanggal_po = isset($_POST['tanggal_po']) ? $this->db->escape_str($this->input->post('tanggal_po',TRUE)) : '';
		$order_by_po = isset($_POST['order_by']) ? $this->db->escape_str($this->input->post('order_by',TRUE)) : '';
		$kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
		$alamat_kirim_po = isset($_POST['alamat_penerima']) ? $this->db->escape_str($this->input->post('alamat_penerima',TRUE)) : '';
		$kirim_po = isset($_POST['pic_penerima']) ? $this->db->escape_str($this->input->post('pic_penerima',TRUE)) : '';
		$kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : '';
		$top = isset($_POST['waktu_top']) ? $this->db->escape_str($this->input->post('waktu_top',TRUE)) : '';
		$tgl_berlaku_po = isset($_POST['tgl_berlaku_po']) ? $this->db->escape_str($this->input->post('tgl_berlaku_po',TRUE)) : '';
		
		$dp = isset($_POST['_dp']) ? $this->db->escape_str($this->input->post('_dp',TRUE)) : 0;
		$remark = isset($_POST['remark']) ? $this->db->escape_str($this->input->post('remark',TRUE)) : FALSE;
		
		$jumlah = isset($_POST['_jumlah']) ? $this->db->escape_str($this->input->post('_jumlah',TRUE)) : 0;
		$diskon_rp = isset($_POST['_diskon_rp']) ? $this->db->escape_str($this->input->post('_diskon_rp',TRUE)) : 0;
		$ppn_persen = isset($_POST['_ppn_persen']) ? $this->db->escape_str($this->input->post('_ppn_persen',TRUE)) : 0;
		$ppn_rp = isset($_POST['_ppn_rp']) ? $this->db->escape_str($this->input->post('_ppn_rp',TRUE)) : 0;
		$total = isset($_POST['_total']) ? $this->db->escape_str($this->input->post('_total',TRUE)) : 0;
		
				
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

		$header_result = 0;
		$detail_result = 0;
		
		if(count($detail) == 0){	
			echo '{"success":false,"errMsg":"Proses gagal"}';
			exit;
		}
		
		if($tanggal_po){
			$tanggal_sp = date('Y-m-d', strtotime($tanggal_po));
		}
		
		if($tgl_berlaku_po){
			$tgl_berlaku_po = date('Y-m-d', strtotime($tgl_berlaku_po));
		}
			
		$this->db->trans_begin();
				
		$no_po = 'SP' . date('Ymd') . '-';
                $sequence = $this->kcsp_model->get_kode_sequence($no_po, 3);
		$no_sp = $no_po.$sequence;
		
		$masa_berlaku = (strtotime($tgl_berlaku_po) - strtotime(date('Y-m-d')))/86400;
		
		$header_po['no_sp'] = $no_sp;
		$header_po['tgl_sp'] = $tanggal_sp;
		$header_po['kd_suplier'] = $kd_supplier;
		$header_po['masa_berlaku'] = $masa_berlaku;
		$header_po['order_by'] = $order_by_po;
		$header_po['created_by'] = $this->session->userdata('username');
		$header_po['created_date'] = date('Y-m-d H:i:s');
		$header_po['kirim_sp'] = $kirim_po;
		$header_po['alamat_kirim_sp'] = $alamat_kirim_po;
		$header_po['remark'] = $remark;
		$header_po['no_ro'] = $no_ro;
		$header_po['top'] = $top;
		$header_po['tgl_berlaku'] = $tgl_berlaku_po;
		$header_po['konsinyasi'] = '1';
                $header_po['kd_peruntukan'] = $kd_peruntukan;
                $header_po['tgl_berlaku_sp2'] = $tgl_berlaku_po;
		
		$header_result = $this->kcsp_model->insert_row('purchase.t_surat_pesanan', $header_po);
		
		foreach($detail as $obj){
			unset($detail_pr);
			
			$detail_pr['no_sp'] = $no_sp;
			$detail_pr['no_ro'] = $obj->no_ro;
			$detail_pr['kd_produk'] = $obj->kd_produk;
			$detail_pr['qty_sp'] = (int) $obj->qty;
			$detail_pr['sp_created_by'] = $this->session->userdata('username');
			$detail_pr['sp_created_date'] = date('Y-m-d H:i:s');
				
			if($this->kcsp_model->insert_row('purchase.t_surat_pesanan_detail', $detail_pr)){
				$this->kcsp_model->update_detail_request_order($obj->no_ro, $obj->kd_produk, (int) $obj->qty);
				$detail_result++;
			}
			
		}
				
		
		if ($header_result && $detail_result > 0) {
			$this->db->trans_commit();
			$result = '{"success":true,"errMsg":"Pembuatan Surat Pesanan berhasil, NO SP: '.$no_sp.'","printUrl":"' . site_url("pembelian_create_po/print_form/" . $no_sp . $sequence) . '"}';
		} else {
			$this->db->trans_rollback();
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
    }

	public function get_row_harga_supplier(){
		$search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by',TRUE)) : "";
		$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : "";
		$qty = isset($_POST['qty']) ? $this->db->escape_str($this->input->post('qty',TRUE)) : '';
       		
		$result = $this->kcsp_model->get_row_harga_supplier($search_by, $id, $kd_produk);
        
		if(count($result) > 0){
			$result->hrg_supplier = (int) $result->hrg_supplier;
			
			$result->disk_persen_supp1_po = $result->disk_persen_supp1;
			$result->disk_persen_supp2_po = $result->disk_persen_supp2;
			$result->disk_persen_supp3_po = $result->disk_persen_supp3;
			$result->disk_persen_supp4_po = $result->disk_persen_supp4;
			
			//hitung diskon
			$diskon = 0;
							
			if($result->disk_persen_supp1 != '' || $result->disk_persen_supp1 != 0){
				$diskon_supp1 = ($result->disk_persen_supp1 * $result->hrg_supplier) /100;
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp1 = $result->disk_amt_supp1;
				}else{
					$diskon_supp1 = 0;
				}
			}
			
			if($result->disk_persen_supp2 != '' || $result->disk_persen_supp2 != 0){
				$diskon_supp2 = ($result->disk_persen_supp2 * ($result->hrg_supplier - $diskon_supp1)) /100;
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp2 = $result->disk_amt_supp2;
				}else{
					$diskon_supp2 = 0;
				}
			}
			
			if($result->disk_persen_supp3 != '' || $result->disk_persen_supp3 != 0){
				$diskon_supp3 = ($result->disk_persen_supp3 * ($result->hrg_supplier - $diskon_supp1 - $diskon_supp2)) /100;
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp3 = $result->disk_amt_supp3;
				}else{
					$diskon_supp3 = 0;
				}
			}
			
			if($result->disk_persen_supp4 != '' || $result->disk_persen_supp4 != 0){
				$diskon_supp4 = ($result->disk_persen_supp4 * ($result->hrg_supplier - $diskon_supp1 - $diskon_supp2 - $diskon_supp3)) /100;
			}else{
				if($result->disk_amt_supp1 != ''){
					$diskon_supp4 = $result->disk_amt_supp1;
				}else{
					$diskon_supp4 = 0;
				}
			}
			
			$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4;
			
			//diskon Rp
			$result->disk_persen_supp1 = $diskon_supp1;
			$result->disk_persen_supp2 = $diskon_supp2;
			$result->disk_persen_supp3 = $diskon_supp3;
			$result->disk_persen_supp4 = $diskon_supp4;
			
			//hitung harga
			$result->harga = $result->hrg_supplier - $diskon;
			
			//hitung jumlah
			$result->jumlah = (int) $qty * $result->harga;
			
		}		    
        echo '{success:true,data:'.json_encode($result).'}';
	}
	
	public function search_supplier(){			
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$result = $this->kcsp_model->search_supplier($search, $start, $limit);
				
        echo $result;
	}

	public function get_all_ro(){
		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->kcsp_model->get_all_ro($keyword);
        
        echo $result;
	}

	public function get_supplier_by_barang(){
		$cmd = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd',TRUE)) : "";		
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : "";
		
		if($cmd == 'get'){
			$result = $this->kcsp_model->get_supplier_by_barang($kd_produk);
		}else{
			$result = '{"success" : true, record : 0, "data" : ""} ';
		}
		
        
        echo $result;
	}

	public function get_ro_detail_by_supplier($kd_supplier = '', $wkt_top = '', $pkp = 1){
		
		$detail = $this->kcsp_model->get_ro_detail_by_supplier($kd_supplier, $wkt_top);
		
		$data = array();
		foreach($detail as $obj){
			
			$result = $this->kcsp_model->get_row_harga_produk_per_supplier($obj->kd_produk, $obj->kd_supplier);
        
			if(count($result) > 0){
				$obj->PICPenerimaPO = $PICPenerimaPO;
				$obj->AlamatPenerimaPO = $AlamatPenerimaPO;
				$obj->hrg_supplier = $result->hrg_supplier;
				
				$obj->disk_persen_supp1_po = $result->disk_persen_supp1;
				$obj->disk_persen_supp2_po = $result->disk_persen_supp2;
				$obj->disk_persen_supp3_po = $result->disk_persen_supp3;
				$obj->disk_persen_supp4_po = $result->disk_persen_supp4;
				
				$obj->disk_amt_supp1_po = $result->disk_amt_supp1;
				$obj->disk_amt_supp2_po = $result->disk_amt_supp2;
				$obj->disk_amt_supp3_po = $result->disk_amt_supp3;
				$obj->disk_amt_supp4_po = $result->disk_amt_supp4;
				$obj->disk_amt_supp5_po = $result->disk_amt_supp5;
				
				//hitung diskon
				$diskon = 0;
								
				if($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0){
					$diskon_supp1 = ($result->disk_persen_supp1 * $result->hrg_supplier) /100;
					$disk_grid_supp1 = $result->disk_persen_supp1.'%';
				}else{
					if($result->disk_amt_supp1 != '' && $result->disk_amt_supp1 != 0){
						$diskon_supp1 = $result->disk_amt_supp1;
						$disk_grid_supp1 = 'Rp. '.number_format($diskon_supp1);
					}else{
						$diskon_supp1 = 0;
						$disk_grid_supp1 = '0%';
					}
				}
				
				if($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0){
					$diskon_supp2 = ($result->disk_persen_supp2 * ($result->hrg_supplier - $diskon_supp1)) /100;
					$disk_grid_supp2 = $result->disk_persen_supp2.'%';
				}else{
					if($result->disk_amt_supp2 != '' && $result->disk_amt_supp2 != 0){
						$diskon_supp2 = $result->disk_amt_supp2;
						$disk_grid_supp2 = 'Rp. '.number_format($diskon_supp2);

					}else{
						$diskon_supp2 = 0;
						$disk_grid_supp2 = '0%';
					}
				}
				
				if($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0){
					$diskon_supp3 = ($result->disk_persen_supp3 * ($result->hrg_supplier - $diskon_supp1 - $diskon_supp2)) /100;
					$disk_grid_supp3 = $result->disk_persen_supp3.'%';
				}else{
					if($result->disk_amt_supp3 != '' && $result->disk_amt_supp3 != 0){
						$diskon_supp3 = $result->disk_amt_supp3;
						$disk_grid_supp3 = 'Rp. '.number_format($diskon_supp3);
					}else{
						$diskon_supp3 = 0;
						$disk_grid_supp3 = '0%';
					}
				}
				
				if($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0){
					$diskon_supp4 = ($result->disk_persen_supp4 * ($result->hrg_supplier - $diskon_supp1 - $diskon_supp2 - $diskon_supp3)) /100;
					$disk_grid_supp4 = $result->disk_persen_supp4.'%';
				}else{
					if($result->disk_amt_supp4 != '' && $result->disk_amt_supp4 != 0){
						$diskon_supp4 = $result->disk_amt_supp1;
						$disk_grid_supp4 = 'Rp. '.number_format($diskon_supp4);
					}else{
						$diskon_supp4 = 0;
						$disk_grid_supp4 = '0%';
					}
				}
				
				$diskon_supp5 = $result->disk_amt_supp5;
				
				$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_supp5;
				
				//diskon Rp
				$obj->disk_grid_supp1 = $disk_grid_supp1;
				$obj->disk_grid_supp2 = $disk_grid_supp2;
				$obj->disk_grid_supp3 = $disk_grid_supp3;
				$obj->disk_grid_supp4 = $disk_grid_supp4;
				$obj->disk_persen_supp1 = $diskon_supp1;
				$obj->disk_persen_supp2 = $diskon_supp2;
				$obj->disk_persen_supp3 = $diskon_supp3;
				$obj->disk_persen_supp4 = $diskon_supp4;
				$obj->disk_persen_supp5 = $diskon_supp5;
				$obj->total_diskon = $diskon;
				// $obj->total_diskon = $result->hrg_supplier-$result->net_hrg_supplier_sup_inc;
				
				//hitung harga
				$obj->harga = $result->hrg_supplier - $diskon;
				// $obj->harga = $result->net_hrg_supplier_sup_inc;
				
				if($pkp == 1){
					$obj->dpp_po = $obj->harga/1.1; 
				}else{
					$obj->dpp_po = $obj->harga;
				}
				//hitung jumlah
				$obj->jumlah = ($obj->qty_adj - $obj->qty_po) * $obj->dpp_po;			
				
				$obj->qty = $obj->qty_adj - $obj->qty_po;	
				
				//validasi
				$obj->validasi_pr = 0;
				$obj->validasi_hj = 0;
				$kd_produk = $obj->kd_produk;
				$kd_peruntukkan = $obj->kd_peruntukkan;
				$this->db->trans_start();
				$validate = $this->kcr_model->validate_pr_by_kd_produk($kd_produk,$kd_peruntukkan);
				if($validate['pr']->sum != 0){
					$obj->validasi_pr = 1;
				}
				if($validate['peruntukan']->harga_jual == ''){
					$obj->validasi_hj = 1;
				}
				$this->db->trans_complete();
				if($obj->qty > 0){
					$data[] = $obj;
				}
			}			
			
		}
		
		echo '{success:true,record:'.count($data).',data:'.json_encode($data).'}';        
	}

	public function get_ro_detail($no_ro = '', $wkt_top = ''){
		$detail = $this->kcsp_model->get_ro_detail($no_ro);
		
		foreach($detail as $obj){
			$obj->is_pilih = FALSE;
			if($wkt_top != ''){
				if($wkt_top == $obj->waktu_top){
					$obj->is_pilih = TRUE;
				}
			}
			$result = $this->kcsp_model->get_row_harga_produk_per_supplier($obj->kd_produk, $obj->kd_supplier);
        
			if(count($result) > 0){
				$obj->hrg_supplier = $result->hrg_supplier;
				
				$obj->disk_persen_supp1_po = $result->disk_persen_supp1;
				$obj->disk_persen_supp2_po = $result->disk_persen_supp2;
				$obj->disk_persen_supp3_po = $result->disk_persen_supp3;
				$obj->disk_persen_supp4_po = $result->disk_persen_supp4;
				
				$obj->disk_amt_supp1_po = $result->disk_amt_supp1;
				$obj->disk_amt_supp2_po = $result->disk_amt_supp2;
				$obj->disk_amt_supp3_po = $result->disk_amt_supp3;
				$obj->disk_amt_supp4_po = $result->disk_amt_supp4;
				$obj->disk_amt_supp5_po = $result->disk_amt_supp5;
				
				//hitung diskon
				$diskon = 0;
								
				if($result->disk_persen_supp1 != '' || $result->disk_persen_supp1 != 0){
					$diskon_supp1 = ($result->disk_persen_supp1 * $result->hrg_supplier) /100;
				}else{
					if($result->disk_amt_supp1 != ''){
						$diskon_supp1 = $result->disk_amt_supp1;
					}else{
						$diskon_supp1 = 0;
					}
				}
				
				if($result->disk_persen_supp2 != '' || $result->disk_persen_supp2 != 0){
					$diskon_supp2 = ($result->disk_persen_supp2 * $diskon_supp1) /100;
				}else{
					if($result->disk_amt_supp1 != ''){
						$diskon_supp2 = $result->disk_amt_supp2;
					}else{
						$diskon_supp2 = 0;
					}
				}
				
				if($result->disk_persen_supp3 != '' || $result->disk_persen_supp3 != 0){
					$diskon_supp3 = ($result->disk_persen_supp3 * $diskon_supp2) /100;
				}else{
					if($result->disk_amt_supp1 != ''){
						$diskon_supp3 = $result->disk_amt_supp3;
					}else{
						$diskon_supp3 = 0;
					}
				}
				
				if($result->disk_persen_supp4 != '' || $result->disk_persen_supp4 != 0){
					$diskon_supp4 = ($result->disk_persen_supp4 * $diskon_supp3) /100;
				}else{
					if($result->disk_amt_supp1 != ''){
						$diskon_supp4 = $result->disk_amt_supp1;
					}else{
						$diskon_supp4 = 0;
					}
				}
				
				$diskon_supp5 = $result->disk_amt_supp5;
				
				$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_supp5;
				
				//diskon Rp
				$obj->disk_persen_supp1 = $diskon_supp1;
				$obj->disk_persen_supp2 = $diskon_supp2;
				$obj->disk_persen_supp3 = $diskon_supp3;
				$obj->disk_persen_supp4 = $diskon_supp4;
				$obj->disk_persen_supp5 = $diskon_supp5;
				$obj->total_diskon = $diskon;
				
				//hitung harga
				$obj->harga = $result->hrg_supplier - $diskon;
				
				//hitung jumlah
				$obj->jumlah = ($obj->qty_adj - $obj->qty_po) * $obj->harga;			
				
				$obj->qty = $obj->qty_adj - $obj->qty_po;		
			}			
			
		}
				
		echo '{success:true,record:'.count($detail).',data:'.json_encode($detail).'}';        
	}

	public function generate_po(){
		$detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();
		
		if(count($detail) > 0){	
			unset($po);		
			foreach($detail as $obj){
				if($obj->kd_supplier == ""){
					echo '{"success" : false, "errMsg":"Lengkapi data supplier"} ';
					die;
				}
				
				$po[$obj->kd_supplier][] = $obj;
			}	

			$i=0;
			foreach($po as $k=>$v){
				$no_po = 'PS' . date('Ymd') . '-';
    			$sequence = $this->kcsp_model->get_kode_sequence($no_po, 3);
				$arr[$i]->no_po = $no_po . $sequence;
				$arr[$i]->kd_supplier = $k;
				$arr[$i]->nama_supplier = $v[0]->nama_supplier;
				$arr[$i]->kirim_po = $v[0]->kirim_po;
				$arr[$i]->alamat_kirim_po = $v[0]->alamat_kirim_po;
				$jumlah = 0;
				foreach($v as $d){
					$jumlah += $d->jumlah;
				}
				$arr[$i]->jumlah = $jumlah;
				$arr[$i]->diskon_persen = 0;
				$arr[$i]->diskon_rp = 0;
				$arr[$i]->sub_jumlah = $jumlah;
				$arr[$i]->ppn_persen = 10;
				$rp_ppn = (10*$jumlah)/100;
				$arr[$i]->ppn_rp = $rp_ppn;
				$arr[$i]->total = $jumlah+$rp_ppn;
				
				$i++; 				
			}		
			
			$result = '{"success" : true, record : ' . count($arr). ', "data" : ' . json_encode($arr) . '} ';
		}else
			$result = '{"success" : true, record : 0, "data" : ""} ';
			
		echo $result;
	}

	public function get_all_produk(){
		$result = $this->kcsp_model->get_all_produk();
        
        echo $result;
	}

	public function get_row_produk(){
		$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
        $result = $this->kcsp_model->get_row_produk($id);
            
        echo $result;
	}

	public function get_term_of_payment_by_supplier($kd_supplier = ''){
		$result = $this->kcsp_model->get_term_of_payment_by_supplier($kd_supplier);
            
        echo $result;
	}
	
	public function get_term_of_payment($no_ro = ''){
		//$no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : '';
		$result = $this->kcsp_model->get_term_of_payment($no_ro);
            
        echo $result;
	}
	
	public function print_form($no_po = ''){
		$data = $this->kcsp_model->get_data_print($no_po);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PembelianCreatePOPrint.php');
		$pdf = new PembelianCreatePOPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
	public function print_form_non_harga($no_po = ''){
		$this->pcp_model->setCetakKeNonHarga($no_po);

		$data = $this->pcp_model->get_data_print($no_po);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		require_once(APPPATH . 'libraries/PembelianCreatePONonHargaPrint.php');
		$pdf = new PembelianCreatePOPrint(PDF_PAGE_ORIENTATION_PORTRAIT, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
	}
	
	public function get_nilai_parameter_pic(){
		$result = $this->kcsp_model->get_nilai_parameter(PIC_PENERIMA_PO);
		return $result;
	}
	
	public function get_nilai_parameter_alamat(){
		$result = $this->kcsp_model->get_nilai_parameter(ALAMAT_PENERIMA_PO);
		return $result; 
	}
	
	public function get_nilai_parameter_remark(){
		$result = $this->kcsp_model->get_nilai_parameter(REMARK_PO);
		return $result; 
	}
}
