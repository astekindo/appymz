<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_penjualan_bazar extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('harga_penjualan_bazar_model', 'hjb_model');
        $this->load->model('harga_penjualan_model', 'hj_model');
    }
    public function get_form() {
        $no_hjb = 'HJB' . date('Ymd') . '-';
        $sequence = $this->hjb_model->get_kode_sequence($no_hjb, 3);
        echo '{"success":true,
				"data":{
					"no_hjb":"' . $no_hjb . $sequence . '",
					"tanggal":"' . date('d-m-Y') . '"
				}
			}';
    }
     public function search_no_bukti() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $result = $this->hjb_model->search_no_bukti($kd_supplier,$search, $start, $limit);

        echo $result;
    }
    public function search_no_bukti_filter() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $result = $this->hjb_model->search_no_bukti_filter($kd_supplier,$search, $start, $limit);

        echo $result;
    }
     public function search_produk_by_kategori() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1', TRUE)) : '';
        $kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2', TRUE)) : '';
        $kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3', TRUE)) : '';
        $kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4', TRUE)) : '';
        $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran', TRUE)) : '';
        $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan', TRUE)) : '';
        $list = isset($_POST['list']) ? $this->db->escape_str($this->input->post('list', TRUE)) : '';
        $konsinyasi = isset($_POST['konsinyasi']) ? $this->db->escape_str($this->input->post('konsinyasi', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        if ($konsinyasi === 'false') {
            $konsinyasi = 0;
        } else {
            $konsinyasi = 1;
        }
        if ($list != '') {
            $list_exp = explode(',', $list);
            $list_imp = implode("','", $list_exp);
            $list = strtoupper("'" . $list_imp . "'");
        }

        $data_result = $this->hjb_model->search_produk_by_kategori($kd_supplier, $no_bukti, $konsinyasi, $kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $kd_ukuran, $kd_satuan, $list, $search, $start, $limit);
        $hasil = $data_result['rows'];
        //print_r($hasil);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $total_diskon_kons = 0;
            $total_diskon_memb = 0;

            if ($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0) {
                $total_diskon_kons = $result->rp_jual_toko - ($result->rp_jual_toko * ($result->disk_persen_kons1 / 100));
                $diskon_kons1 = $result->disk_persen_kons1;
                $result->disk_bazar1_op = "%";
            } else {
                if ($result->disk_amt_kons1 != '') {
                    $total_diskon_kons = $result->rp_jual_toko - $result->disk_amt_kons1;
                    $diskon_kons1 = $result->disk_amt_kons1;
                    $result->disk_bazar1_op = "Rp";
                } else {
                    $diskon_kons1 = 0;
                }
            }

            if ($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons2 / 100));
                $diskon_kons2 = $result->disk_persen_kons2;
                $result->disk_bazar2_op = "%";
            } else {
                if ($result->disk_amt_kons2 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons2;
                    $diskon_kons2 = $result->disk_amt_kons2;
                    $result->disk_bazar2_op = "Rp";
                } else {
                    $diskon_kons2 = 0;
                }
            }

            if ($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons3 / 100));
                $diskon_kons3 = $result->disk_persen_kons3;
                $result->disk_bazar3_op = "%";
            } else {
                if ($result->disk_amt_kons3 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons3;
                    $diskon_kons3 = $result->disk_amt_kons3;
                    $result->disk_bazar3_op = "Rp";
                } else {
                    $diskon_kons3 = 0;
                }
            }

            if ($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons4 / 100));
                $diskon_kons4 = $result->disk_persen_kons4;
                $result->disk_bazar4_op = "%";
            } else {
                if ($result->disk_amt_kons4 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons4;
                    $diskon_kons4 = $result->disk_amt_kons4;
                    $result->disk_bazar4_op = "Rp";
                } else {
                    $diskon_kons4 = 0;
                }
            }

            if ($result->disk_amt_kons5 != '') {
                $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons5;
                $diskon_amt_kons5 = $result->disk_amt_kons5;
            } else {
                $diskon_amt_kons5 = 0;
            }

            $diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

            //diskon Rp
            $result->disk_bazar1 = $diskon_kons1;
            $result->disk_bazar2 = $diskon_kons2;
            $result->disk_bazar3 = $diskon_kons3;
            $result->disk_bazar4 = $diskon_kons4;
            $result->net_price_jual_kons = $total_diskon_kons;
            $diskon = 0;

            
            if ($result->is_member_kelipatan == 0) {
                $result->is_member_kelipatan = 'Tidak';
            } else {
                $result->is_member_kelipatan = 'Ya';
            }

            if ($result->is_bonus_kelipatan == 0) {
                $result->is_bonus_kelipatan = 'Tidak';
            } else {
                $result->is_bonus_kelipatan = 'Ya';
            }

            $result->margin_op = '%';
            $result->margin = $result->pct_margin;



            $diskon = $diskon_member1 + $diskon_member2 + $diskon_member3 + $diskon_member4 + $diskon_amt_member5;

            //diskon Rp
            $result->disk_agen1 = $diskon_member1;
            $result->disk_agen2 = $diskon_member2;
            $result->disk_agen3 = $diskon_member3;
            $result->disk_agen4 = $diskon_member4;
            $result->net_price_jual_agen = $total_diskon_memb;

            $result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
            $margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
            if($result->rp_het_harga_beli == 0){
            $result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
            }
            $result->rp_het_cogs = 0;
            if(!$result->rp_cogs && $result->rp_cogs != 0){
            	$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
            }
            $results[] = $result;
        }
        echo '{success:true,record:' . $data_result['total'] . ',data:' . json_encode($results) . '}';
    }
    public function update_row() {
        $no_hjb = isset($_POST['no_hjb']) ? $this->db->escape_str($this->input->post('no_hjb', TRUE)) : '';
        $no_bukti_filter = isset($_POST['no_bukti_filter']) ? $this->db->escape_str($this->input->post('no_bukti_filter', TRUE)) : '';
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : FALSE;

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $result_prod = 0;
        $result_disk = 0;

        $this->db->trans_begin();
        foreach ($detail as $obj) {
            if ($obj->edited == 'Y') {
                
                $kd_produk = $obj->kd_produk_baru;
                if ($obj->margin_op == '%') {
                    $pct_margin = $obj->margin;
                    $rp_margin = ($obj->margin * $obj->net_hrg_supplier_sup_inc) / 100;
                } else {
                    $rp_margin = $obj->margin;
                    $pct_margin = ($obj->margin * 100) / $obj->net_hrg_supplier_sup_inc;
                }

                //produk
                $koreksi_produk = $obj->koreksi_produk + 1;
                
                $RpJualBazar = (int) $obj->rp_jual_bazar;
                $NetPJualBazar = (int) $obj->rp_jual_bazar_net;
                $validasi = $obj->is_validasi;
                //$RpJualDist = (int) $obj->rp_jual_distribusi;
                $HetBeli = (int) $obj->rp_het_harga_beli;
                $cogs = (int) $obj->p_rp_cogs;
                $net_hrg_supplier_sup_inc = (int) $obj->net_hrg_supplier_sup_inc;
                $tgl_start_diskon = $obj->tgl_start_diskon;
                $tgl_end_diskon = $obj->tgl_end_diskon;
                $tgl_now = date("Y-m-d");
                
                if ($tgl_end_diskon < $tgl_now or $tgl_start_diskon < $tgl_now) {
                    echo '{"success":false,"errMsg":"Tanggal Awal dan Tanggal Akhir Diskon Tidak Boleh Lebih Kecil Dari Tanggal Hari ini "}';
                    $this->db->trans_rollback();
                    exit;
                }
                            
                
                if ($net_hrg_supplier_sup_inc <= 0) {
                    echo '{"success":false,"errMsg":"Net Price Pembelian Masih 0"}';
                    $this->db->trans_rollback();
                    exit;
                }

                if ($cogs > 0) {
                    if ($RpJualBazar < $cogs and $validasi === 'N' ) {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($RpJualBazar < $HetBeli and $validasi === 'N') {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                if ($cogs > 0) {
                    if ($NetPJualBazar < $cogs and $validasi === 'N') {
                        echo '{"success":false,"errMsg":"Net Price Jual Bazar Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualBazar < $HetBeli and $validasi === 'N') {
                        echo '{"success":false,"errMsg":"Net Price Jual Bazar Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                $kd_diskon_sales = $obj->kd_diskon_sales;

                $disk_bazar1_op = $obj->disk_bazar1_op;
                $disk_bazar2_op = $obj->disk_bazar2_op;
                $disk_bazar3_op = $obj->disk_bazar3_op;
                $disk_bazar4_op = $obj->disk_bazar4_op;

                $disk_bazar1 = $obj->disk_bazar1;
                $disk_bazar2 = $obj->disk_bazar2;
                $disk_bazar3 = $obj->disk_bazar3;
                $disk_bazar4 = $obj->disk_bazar4;

                if ($disk_bazar1_op === "%") {
                    $disk_persen1 = $disk_bazar1;
                    $disk_amt1 = 0;
                } else {
                    $disk_persen1 = 0;
                    $disk_amt1 = $disk_bazar1;
                }
                if ($disk_bazar2_op === "%") {
                    $disk_persen2 = $disk_bazar2;
                    $disk_amt2 = 0;
                } else {
                    $disk_persen2 = 0;
                    $disk_amt2 = $disk_bazar2;
                }
                if ($disk_bazar3_op === "%") {
                    $disk_persen3 = $disk_bazar3;
                    $disk_amt3 = 0;
                } else {
                    $disk_persen3 = 0;
                    $disk_amt3 = $disk_bazar3;
                }
                if ($disk_bazar4_op === "%") {
                    $disk_persen4 = $disk_bazar4;
                    $disk_amt4 = 0;
                } else {
                    $disk_persen4 = 0;
                    $disk_amt4 = $disk_bazar4;
                }

                $disk_amt5 = $obj->disk_amt_bazar5;

                $qty_beli_bonus = $obj->qty_beli_bonus;
                $kd_produk_bonus = $obj->kd_produk_bonus;
                $qty_bonus = $obj->qty_bonus;
                $is_bonus_kelipatan = $obj->is_bonus_kelipatan;
                
                $is_bonus_kelipatan = isset($is_bonus_kelipatan) ? $is_bonus_kelipatan : 0;
                $kd_produk_bonus = isset($kd_produk_bonus) ? $kd_produk_bonus : '';
               
                if ($is_bonus_kelipatan == 'Ya') {
                    $is_bonus_kelipatan = 1;
                } else if ($is_bonus_kelipatan == 'Tidak') {
                    $is_bonus_kelipatan = 0;
                }
                
                if ($qty_bonus > 0) {
                    $is_bonus = 1;
                }
                else
                    $is_bonus = 0;

                $koreksi_diskon = $obj->koreksi_diskon + 1;
                // $keterangan = $obj->keterangan;
                $aktif = '1';

                $created_by = $this->session->userdata('username');
                $created_date = date('Y-m-d H:i:s');


                unset($diskon_hj);
               $diskon_hj['net_hrg_supplier_inc'] = $obj->net_hrg_supplier_sup_inc;
                $diskon_hj['rp_cogs'] = $obj->p_rp_cogs;
                $diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
                $diskon_hj['pct_margin'] = $pct_margin;
                $diskon_hj['rp_margin'] = $rp_margin;
                
                $diskon_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli;
                $diskon_hj['rp_het_cogs'] = $obj->p_rp_het_cogs;
                
                //$diskon_hj['no_bukti'] = $no_hjb;
                $diskon_hj['kd_produk'] = $kd_produk;
                $diskon_hj['kd_diskon_sales'] = $no_hjb;
                $diskon_hj['tanggal'] = $created_date;
                $diskon_hj['koreksi_ke'] = $koreksi_diskon;
                $diskon_hj['is_bonus'] = $is_bonus;
                $diskon_hj['rp_jual_bazar'] = $obj->rp_jual_bazar;
                $diskon_hj['disk_persen_kons1'] = $disk_persen1;
                $diskon_hj['disk_persen_kons2'] = $disk_persen2;
                $diskon_hj['disk_persen_kons3'] = $disk_persen3;
                $diskon_hj['disk_persen_kons4'] = $disk_persen4;
                $diskon_hj['disk_amt_kons1'] = $disk_amt1;
                $diskon_hj['disk_amt_kons2'] = $disk_amt2;
                $diskon_hj['disk_amt_kons3'] = $disk_amt3;
                $diskon_hj['disk_amt_kons4'] = $disk_amt4;
                $diskon_hj['disk_amt_kons5'] = $disk_amt5;
                $diskon_hj['qty_beli_bonus'] = $qty_beli_bonus;
                $diskon_hj['kd_produk_bonus'] = $kd_produk_bonus;
                $diskon_hj['qty_bonus'] = $qty_bonus;
                $diskon_hj['is_bonus_kelipatan'] = $is_bonus_kelipatan;
                $diskon_hj['keterangan'] = $keterangan;
                $diskon_hj['created_by'] = $created_by;
                $diskon_hj['created_date'] = $created_date;
                //$diskon_hj['koreksi_produk'] = $koreksi_produk;
                $diskon_hj['rp_jual_bazar_net'] = $obj->rp_jual_bazar_net;
                $diskon_hj['tgl_start_diskon'] = $obj->tgl_start_diskon;
                $diskon_hj['tgl_end_diskon'] = $obj->tgl_end_diskon;
                $diskon_hj['is_validasi'] = $obj->is_validasi;
               
                if ($no_bukti_filter != '') {

                    if ($this->hjb_model->update_temp($kd_produk, $no_bukti_filter, $diskon_hj)) {
                        $results = 'success';
                    } else {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                        exit;
                    }
                } else {

                    $result = $this->hjb_model->select_temp($kd_produk, '0');
                    if (!empty($result)) {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"Barang dengan Kode Barang: ' . $kd_produk . ' Belum Diapprove"}';
                        exit;
                        // }else if($this->hjd_model->select_temp($kd_produk,'1')){
                    } else {
                        $result_prod = $this->hjb_model->select_data_temp($kd_produk, $tgl_start_diskon,$tgl_end_diskon);
                        if (!empty($result_prod)) {
                            $this->db->trans_rollback();
                            echo '{"success":false,"errMsg":"Tanggal Awal Diskon udah ada dalam range : ' . $kd_produk . ' "}';
                            exit;
                        }else{
                            $result_data = $this->hjb_model->select_data_temp_end($kd_produk, $tgl_start_diskon,$tgl_end_diskon);
                            if (!empty($result_data)) {
                                $this->db->trans_rollback();
                                echo '{"success":false,"errMsg":"Tanggal Akhir Diskon udah ada dalam range ' . $kd_produk . '"}';
                                exit;
                            }else{
                                $diskon_hj['kd_produk'] = $kd_produk;
                                $diskon_hj['status_approval'] = 0;
                                if ($this->hjb_model->insert_temp($diskon_hj)) {
                                    $results = 'success';
                                } else {
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
                                    exit;
                                }
                            }
                        }
                        
                    }
                }//print_r($diskon_hj);
            }
        }
        if ($results == "success") {
            $this->db->trans_commit();
            echo'{"success":true,"errMsg":"Data Berhasil Di Update"}';
        } else {
            $this->db->trans_rollback();
            echo '{"success":false,"errMsg":"Tidak Ada Data yang Disimpan"}';
        }
    }
    
  public function search_produk_history() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

        $hasil = $this->hjb_model->search_produk_history($no_bukti, $kd_produk);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $total_diskon_kons = 0;
            $total_diskon_memb = 0;

               if ($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0) {
                $total_diskon_kons = $result->rp_jual_toko - ($result->rp_jual_toko * ($result->disk_persen_kons1 / 100));
                $diskon_kons1 = $result->disk_persen_kons1;
                $result->disk_bazar1_op = "%";
            } else {
                if ($result->disk_amt_kons1 != '') {
                    $total_diskon_kons = $result->rp_jual_toko - $result->disk_amt_kons1;
                    $diskon_kons1 = $result->disk_amt_kons1;
                    $result->disk_bazar1_op = "Rp";
                } else {
                    $diskon_kons1 = 0;
                }
            }

            if ($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons2 / 100));
                $diskon_kons2 = $result->disk_persen_kons2;
                $result->disk_bazar2_op = "%";
            } else {
                if ($result->disk_amt_kons2 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons2;
                    $diskon_kons2 = $result->disk_amt_kons2;
                    $result->disk_bazar2_op = "Rp";
                } else {
                    $diskon_kons2 = 0;
                }
            }

            if ($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons3 / 100));
                $diskon_kons3 = $result->disk_persen_kons3;
                $result->disk_bazar3_op = "%";
            } else {
                if ($result->disk_amt_kons3 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons3;
                    $diskon_kons3 = $result->disk_amt_kons3;
                    $result->disk_bazar3_op = "Rp";
                } else {
                    $diskon_kons3 = 0;
                }
            }

            if ($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons4 / 100));
                $diskon_kons4 = $result->disk_persen_kons4;
                $result->disk_bazar4_op = "%";
            } else {
                if ($result->disk_amt_kons4 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons4;
                    $diskon_kons4 = $result->disk_amt_kons4;
                    $result->disk_bazar4_op = "Rp";
                } else {
                    $diskon_kons4 = 0;
                }
            }

            if ($result->disk_amt_kons5 != '') {
                $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons5;
                $diskon_amt_kons5 = $result->disk_amt_kons5;
            } else {
                $diskon_amt_kons5 = 0;
            }

            $diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

            //diskon Rp
            $result->disk_bazar1 = $diskon_kons1;
            $result->disk_bazar2 = $diskon_kons2;
            $result->disk_bazar3 = $diskon_kons3;
            $result->disk_bazar4 = $diskon_kons4;
            $result->disk_bazar5 = $diskon_amt_kons5;
            $result->net_price_jual_bazar = $total_diskon_kons;
            $diskon = 0;
            
           if ($result->is_bonus_kelipatan == 0) {
                $result->is_bonus_kelipatan = 'Tidak';
            } else {
                $result->is_bonus_kelipatan = 'Ya';
            }

            $result->margin_op = '%';
            $result->margin = $result->pct_margin;
            
            $result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
            $margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
            if($result->rp_het_harga_beli == 0)
            $result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
            $result->rp_het_cogs = 0;
            if(!$result->rp_cogs && $result->rp_cogs != 0){
            	$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
            }
            $results[] = $result;
        }
        echo '{success:true,data:' . json_encode($results) . '}';
    }
     public function print_form($no_bukti = '', $kd_produk = '') {
        $data = $this->hjb_model->get_data_print($no_bukti, $kd_produk);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/HargaPenjualanBazarPrint.php');
        $pdf = new HargaPenjualanBazarPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'F4', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['detail']);
        $pdf->Output();
        exit;
    }
}
