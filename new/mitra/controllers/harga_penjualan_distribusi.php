<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_penjualan_distribusi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('harga_penjualan_distribusi_model', 'hjd_model');
        $this->load->model('harga_penjualan_model', 'hj_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        $no_hjd = 'HJD' . date('Ymd') . '-';
        $sequence = $this->hjd_model->get_kode_sequence($no_hjd, 3);
        echo '{"success":true,
				"data":{
					"no_hjd":"' . $no_hjd . $sequence . '",
					"tanggal":"' . date('d-m-Y') . '"
				}
			}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $no_hjd = isset($_POST['no_hjd']) ? $this->db->escape_str($this->input->post('no_hjd', TRUE)) : '';
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
                
                $RpJualSup = (int) $obj->rp_jual_toko;
                $NetPJualToko = (int) $obj->rp_jual_toko_net;
                $NetPJualAgen = (int) $obj->rp_jual_agen_net;
                $NetPJualModernMarket = (int) $obj->rp_jual_modern_market_net;
                //$RpJualDist = (int) $obj->rp_jual_distribusi;
                $HetBeli = (int) $obj->rp_het_harga_beli_dist;
                $cogs = (int) $obj->p_rp_cogs;
                $net_hrg_supplier_sup_inc = (int) $obj->net_hrg_supplier_dist_inc;
                $tgl_start_diskon = $obj->tgl_start_diskon;
                $tgl_end_diskon = $obj->tgl_end_diskon;
                $tgl_now = date("Y-m-d");
                
//                if ($tgl_end_diskon < $tgl_now or $tgl_start_diskon < $tgl_now) {
//                    echo '{"success":false,"errMsg":"Tanggal Awal dan Tanggal Akhir Diskon Tidak Boleh Lebih Kecil Dari Tanggal Hari ini "}';
//                    $this->db->trans_rollback();
//                    exit;
//                }
                
                //print_r($NetPJualToko);
                if ($net_hrg_supplier_sup_inc <= 0) {
                    echo '{"success":false,"errMsg":"Net Price Pembelian Masih 0"}';
                    $this->db->trans_rollback();
                    exit;
                }

                if ($cogs > 0) {
                    if ($RpJualSup < $cogs) {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($RpJualSup < $HetBeli) {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                if ($cogs > 0) {
                    if ($NetPJualToko < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Toko Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualToko < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Toko Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                if ($cogs > 0) {
                    if ($NetPJualAgen < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Agen Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualAgen < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Agen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }
                if ($cogs > 0) {
                    if ($NetPJualModernMarket < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Modern Market Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualModernMarket < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Modern Market Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }


                $kd_diskon_sales = $obj->kd_diskon_sales;

                $disk_toko1_op = $obj->disk_toko1_op;
                $disk_toko2_op = $obj->disk_toko2_op;
                $disk_toko3_op = $obj->disk_toko3_op;
                $disk_toko4_op = $obj->disk_toko4_op;

                $disk_toko1 = $obj->disk_toko1;
                $disk_toko2 = $obj->disk_toko2;
                $disk_toko3 = $obj->disk_toko3;
                $disk_toko4 = $obj->disk_toko4;

                if ($disk_toko1_op === "%") {
                    $disk_persen1 = $disk_toko1;
                    $disk_amt1 = 0;
                } else {
                    $disk_persen1 = 0;
                    $disk_amt1 = $disk_toko1;
                }
                if ($disk_toko2_op === "%") {
                    $disk_persen2 = $disk_toko2;
                    $disk_amt2 = 0;
                } else {
                    $disk_persen2 = 0;
                    $disk_amt2 = $disk_toko2;
                }
                if ($disk_toko3_op === "%") {
                    $disk_persen3 = $disk_toko3;
                    $disk_amt3 = 0;
                } else {
                    $disk_persen3 = 0;
                    $disk_amt3 = $disk_toko3;
                }
                if ($disk_toko4_op === "%") {
                    $disk_persen4 = $disk_toko4;
                    $disk_amt4 = 0;
                } else {
                    $disk_persen4 = 0;
                    $disk_amt4 = $disk_toko4;
                }

                $disk_amt5 = $obj->disk_amt_toko5;

                $disk_agen1_op = $obj->disk_agen1_op;
                $disk_agen2_op = $obj->disk_agen2_op;
                $disk_agen3_op = $obj->disk_agen3_op;
                $disk_agen4_op = $obj->disk_agen4_op;

                $disk_agen1 = $obj->disk_agen1;
                $disk_agen2 = $obj->disk_agen2;
                $disk_agen3 = $obj->disk_agen3;
                $disk_agen4 = $obj->disk_agen4;

                if ($disk_agen1_op === "%") {
                    $disk_persen_agen1 = $disk_agen1;
                    $disk_amt_agen1 = 0;
                } else {
                    $disk_persen_agen1 = 0;
                    $disk_amt_agen1 = $disk_agen1;
                }
                if ($disk_agen2_op === "%") {
                    $disk_persen_agen2 = $disk_agen2;
                    $disk_amt_agen2 = 0;
                } else {
                    $disk_persen_agen2 = 0;
                    $disk_amt_agen2 = $disk_agen2;
                }
                if ($disk_agen3_op === "%") {
                    $disk_persen_agen3 = $disk_agen3;
                    $disk_amt_agen3 = 0;
                } else {
                    $disk_persen_agen3 = 0;
                    $disk_amt_agen3 = $disk_agen3;
                }
                if ($disk_agen4_op === "%") {
                    $disk_persen_agen4 = $disk_agen4;
                    $disk_amt_agen4 = 0;
                } else {
                    $disk_persen_agen4 = 0;
                    $disk_amt_agen4 = $disk_agen4;
                }

                $disk_agen5 = $obj->disk_amt_agen5;
                
                $disk_modern_market1_op = $obj->disk_modern_market1_op;
                $disk_modern_market2_op = $obj->disk_modern_market2_op;
                $disk_modern_market3_op = $obj->disk_modern_market3_op;
                $disk_modern_market4_op = $obj->disk_modern_market4_op;

                $disk_modern_market1 = $obj->disk_modern_market1;
                $disk_modern_market2 = $obj->disk_modern_market2;
                $disk_modern_market3 = $obj->disk_modern_market3;
                $disk_modern_market4 = $obj->disk_modern_market4;

                if ($disk_modern_market1_op === "%") {
                    $disk_persen_modern_market1 = $disk_modern_market1;
                    $disk_amt_modern_market1 = 0;
                } else {
                    $disk_persen_modern_market1 = 0;
                    $disk_amt_modern_market1 = $disk_modern_market1;
                }
                if ($disk_modern_market2_op === "%") {
                    $disk_persen_modern_market2 = $disk_modern_market2;
                    $disk_amt_modern_market2 = 0;
                } else {
                    $disk_persen_modern_market2 = 0;
                    $disk_amt_modern_market2 = $disk_modern_market2;
                }
                if ($disk_modern_market3_op === "%") {
                    $disk_persen_modern_market3 = $disk_modern_market3;
                    $disk_amt_modern_market3 = 0;
                } else {
                    $disk_persen_modern_market3 = 0;
                    $disk_amt_modern_market3 = $disk_modern_market3;
                }
                if ($disk_modern_market4_op === "%") {
                    $disk_persen_modern_market4 = $disk_modern_market4;
                    $disk_amt_modern_market4 = 0;
                } else {
                    $disk_persen_modern_market4 = 0;
                    $disk_amt_modern_market4 = $disk_modern_market4;
                }

                $disk_modern_market5 = $obj->disk_amt_modern_market5;
                
                $qty_beli_bonus = $obj->qty_beli_bonus;
                $kd_produk_bonus = $obj->kd_produk_bonus;
                $qty_bonus = $obj->qty_bonus;
                $is_bonus_kelipatan = $obj->is_bonus_kelipatan;
                $qty_beli_agen = $obj->qty_beli_agen;
                $kd_produk_agen = $obj->kd_produk_agen;
                $qty_agen = $obj->qty_agen;
                $is_agen_kelipatan = $obj->is_member_kelipatan;
                $qty_beli_modern_market = $obj->qty_beli_modern_market;
                $kd_produk_modern_market = $obj->kd_produk_modern_market;
                $qty_modern_market = $obj->qty_modern_market;
                $is_modern_market_kelipatan = $obj->is_modern_market_kelipatan;
//                $tgl_start_diskon = $obj->tgl_start_diskon;
//                $tgl_end_diskon = $obj->tgl_end_diskon;

                $is_agen_kelipatan = isset($is_agen_kelipatan) ? $is_agen_kelipatan : 0;
                $is_bonus_kelipatan = isset($is_bonus_kelipatan) ? $is_bonus_kelipatan : 0;
                $is_modern_market_kelipatan = isset($is_modern_market_kelipatan) ? $is_modern_market_kelipatan : 0;
                $kd_produk_bonus = isset($kd_produk_bonus) ? $kd_produk_bonus : '';
                $kd_produk_agen = isset($kd_produk_agen) ? $kd_produk_agen : '';
                $kd_produk_modern_market = isset($kd_produk_modern_market) ? $kd_produk_modern_market : '';

                if ($is_bonus_kelipatan == 'Ya') {
                    $is_bonus_kelipatan = 1;
                } else if ($is_bonus_kelipatan == 'Tidak') {
                    $is_bonus_kelipatan = 0;
                }

                if ($is_agen_kelipatan == 'Ya') {
                    $is_agen_kelipatan = 1;
                } else if ($is_agen_kelipatan == 'Tidak') {
                    $is_agen_kelipatan = 0;
                }
                if ($is_modern_market_kelipatan == 'Ya') {
                    $is_modern_market_kelipatan = 1;
                } else if ($is_modern_market_kelipatan == 'Tidak') {
                    $is_modern_market_kelipatan = 0;
                }
                if ($qty_bonus > 0 || $qty_agen > 0 || $qty_modern_market > 0 ) {
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
                $diskon_hj['net_hrg_supplier_dist_inc'] = $obj->net_hrg_supplier_dist_inc;
                $diskon_hj['rp_cogs'] = $obj->p_rp_cogs;
                $diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
                $diskon_hj['pct_margin'] = $pct_margin;
                $diskon_hj['rp_margin'] = $rp_margin;
                
                $diskon_hj['rp_het_harga_beli'] = $obj->rp_het_harga_beli_dist;
                $diskon_hj['rp_het_cogs'] = $obj->p_rp_het_cogs;
                
                //$diskon_hj['no_bukti'] = $no_hjd;
                $diskon_hj['kd_produk'] = $kd_produk;
                $diskon_hj['kd_diskon_sales'] = $no_hjd;
                $diskon_hj['tanggal'] = $created_date;
                $diskon_hj['koreksi_ke'] = $koreksi_diskon;
                $diskon_hj['is_bonus'] = $is_bonus;
                $diskon_hj['rp_jual_toko'] = $obj->rp_jual_toko;
                $diskon_hj['disk_persen1'] = $disk_persen1;
                $diskon_hj['disk_persen2'] = $disk_persen2;
                $diskon_hj['disk_persen3'] = $disk_persen3;
                $diskon_hj['disk_persen4'] = $disk_persen4;
                $diskon_hj['disk_amt1'] = $disk_amt1;
                $diskon_hj['disk_amt2'] = $disk_amt2;
                $diskon_hj['disk_amt3'] = $disk_amt3;
                $diskon_hj['disk_amt4'] = $disk_amt4;
                $diskon_hj['disk_amt5'] = $disk_amt5;
                $diskon_hj['qty_beli_bonus'] = $qty_beli_bonus;
                $diskon_hj['kd_produk_bonus'] = $kd_produk_bonus;
                $diskon_hj['qty_bonus'] = $qty_bonus;
                $diskon_hj['is_bonus_kelipatan'] = $is_bonus_kelipatan;
                $diskon_hj['rp_jual_agen'] = $obj->rp_jual_agen;
                $diskon_hj['disk_persen_agen1'] = $disk_persen_agen1;
                $diskon_hj['disk_persen_agen2'] = $disk_persen_agen2;
                $diskon_hj['disk_persen_agen3'] = $disk_persen_agen3;
                $diskon_hj['disk_persen_agen4'] = $disk_persen_agen4;
                $diskon_hj['disk_amt_agen1'] = $disk_amt_agen1;
                $diskon_hj['disk_amt_agen2'] = $disk_amt_agen2;
                $diskon_hj['disk_amt_agen3'] = $disk_amt_agen3;
                $diskon_hj['disk_amt_agen4'] = $disk_amt_agen4;
                $diskon_hj['disk_amt_agen5'] = $disk_agen5;
                $diskon_hj['qty_beli_agen'] = $qty_beli_agen;
                $diskon_hj['kd_produk_agen'] = $kd_produk_agen;
                $diskon_hj['qty_agen'] = $qty_agen;
                $diskon_hj['is_agen_kelipatan'] = $is_agen_kelipatan;
                $diskon_hj['rp_jual_modern_market'] = $obj->rp_jual_modern_market;
                $diskon_hj['disk_persen_modern_market1'] = $disk_persen_modern_market1;
                $diskon_hj['disk_persen_modern_market2'] = $disk_persen_modern_market2;
                $diskon_hj['disk_persen_modern_market3'] = $disk_persen_modern_market3;
                $diskon_hj['disk_persen_modern_market4'] = $disk_persen_modern_market4;
                $diskon_hj['disk_amt_modern_market1'] = $disk_amt_modern_market1;
                $diskon_hj['disk_amt_modern_market2'] = $disk_amt_modern_market2;
                $diskon_hj['disk_amt_modern_market3'] = $disk_amt_modern_market3;
                $diskon_hj['disk_amt_modern_market4'] = $disk_amt_modern_market4;
                $diskon_hj['disk_amt_modern_market5'] = $disk_modern_market5;
                $diskon_hj['qty_beli_modern_market'] = $qty_beli_modern_market;
                $diskon_hj['kd_produk_modern_market'] = $kd_produk_modern_market;
                $diskon_hj['qty_modern_market'] = $qty_modern_market;
                $diskon_hj['is_modern_market_kelipatan'] = $is_modern_market_kelipatan;
                $diskon_hj['keterangan'] = $keterangan;
                $diskon_hj['created_by'] = $created_by;
                $diskon_hj['created_date'] = $created_date;
                $diskon_hj['koreksi_produk'] = $koreksi_produk;
                $diskon_hj['rp_jual_toko_net'] = $obj->rp_jual_toko_net;
                $diskon_hj['rp_jual_agen_net'] = $obj->rp_jual_agen_net;
                $diskon_hj['rp_jual_modern_market_net'] = $obj->rp_jual_modern_market_net;
//                $diskon_hj['tgl_start_diskon'] = $tgl_start_diskon;
//                $diskon_hj['tgl_end_diskon'] = $tgl_end_diskon;
                
                if ($no_bukti_filter != '') {

                    if ($this->hjd_model->update_temp($kd_produk, $no_bukti_filter, $diskon_hj)) {
                        $results = 'success';
                    } else {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                        exit;
                    }
                } else {

                    $result = $this->hjd_model->select_temp($kd_produk, '0');
                    if (!empty($result)) {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"Barang dengan Kode Barang: ' . $kd_produk . ' Belum Diapprove"}';
                        exit;
                        // }else if($this->hjd_model->select_temp($kd_produk,'1')){
                    }else {
//                        $produk_sama = $this->hjd_model->select_data_dist_sama($kd_produk, $tgl_start_diskon,$tgl_end_diskon);
//                        if (!empty($produk_sama)){
//                            $diskon_hj['kd_produk'] = $kd_produk;
//                            $diskon_hj['status'] = 0;
//                            if ($this->hjd_model->insert_temp($diskon_hj)) {
//                                $results = 'success';
//                            } else {
//                                $this->db->trans_rollback();
//                                echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
//                                exit;
//                            }
//                            
//                        }else{
//                            $result_prod = $this->hjd_model->select_data_dist($kd_produk, $tgl_start_diskon,$tgl_end_diskon);
//                                if (!empty($result_prod)) {
//                                    $this->db->trans_rollback();
//                                    echo '{"success":false,"errMsg":"Tanggal Awal Diskon untuk ' . $kd_produk . ' sudah ada"}';
//                                    exit;
//                                }else{
//                                    $result_data = $this->hjd_model->select_data_dist_end($kd_produk, $tgl_start_diskon,$tgl_end_diskon);
//                                    if (!empty($result_data)) {
//                                        $this->db->trans_rollback();
//                                        echo '{"success":false,"errMsg":"Tanggal Akhir Diskon untuk ' . $kd_produk . ' sudah ada"}';
//                                        exit;
//                                    }else{
                                        $diskon_hj['kd_produk'] = $kd_produk;
                                        $diskon_hj['status'] = 0;
                                        if ($this->hjd_model->insert_temp($diskon_hj)) {
                                            $results = 'success';
                                        } else {
                                            $this->db->trans_rollback();
                                            echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
                                            exit;
                                        }
                                    }
//                                }
//                        }
       
                        
                    }
                    
//                    else {
//                        $diskon_hj['kd_produk'] = $kd_produk;
//                        $diskon_hj['status'] = 0;
//                        if ($this->hjd_model->insert_temp($diskon_hj)) {
//                            $results = 'success';
//                        } else {
//                            $this->db->trans_rollback();
//                            echo '{"success":false,"errMsg":"insert_temp Failed . . ."}';
//                            exit;
//                        }
//                    }
        //        }//print_r($diskon_hj);
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

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
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

        $data_result = $this->hjd_model->search_produk_by_kategori($kd_supplier, $no_bukti, $konsinyasi, $kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $kd_ukuran, $kd_satuan, $list, $search, $start, $limit);
        $hasil = $data_result['rows'];
        //print_r($hasil);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $total_diskon_kons = 0;
            $total_diskon_memb = 0;

            if ($result->disk_persen1 != '' && $result->disk_persen1 != 0) {
                $total_diskon_kons = $result->rp_jual_toko - ($result->rp_jual_toko * ($result->disk_persen1 / 100));
                $diskon_kons1 = $result->disk_persen1;
                $result->disk_toko1_op = "%";
            } else {
                if ($result->disk_amt1 != '') {
                    $total_diskon_kons = $result->rp_jual_toko - $result->disk_amt1;
                    $diskon_kons1 = $result->disk_amt1;
                    $result->disk_toko1_op = "Rp";
                } else {
                    $diskon_kons1 = 0;
                }
            }

            if ($result->disk_persen2 != '' && $result->disk_persen2 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen2 / 100));
                $diskon_kons2 = $result->disk_persen2;
                $result->disk_toko2_op = "%";
            } else {
                if ($result->disk_amt2 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt2;
                    $diskon_kons2 = $result->disk_amt2;
                    $result->disk_toko2_op = "Rp";
                } else {
                    $diskon_kons2 = 0;
                }
            }

            if ($result->disk_persen3 != '' && $result->disk_persen3 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen3 / 100));
                $diskon_kons3 = $result->disk_persen3;
                $result->disk_toko3_op = "%";
            } else {
                if ($result->disk_amt3 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt3;
                    $diskon_kons3 = $result->disk_amt3;
                    $result->disk_toko3_op = "Rp";
                } else {
                    $diskon_kons3 = 0;
                }
            }

            if ($result->disk_persen4 != '' && $result->disk_persen4 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen4 / 100));
                $diskon_kons4 = $result->disk_persen4;
                $result->disk_toko4_op = "%";
            } else {
                if ($result->disk_amt4 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt4;
                    $diskon_kons4 = $result->disk_amt4;
                    $result->disk_toko4_op = "Rp";
                } else {
                    $diskon_kons4 = 0;
                }
            }

            if ($result->disk_amt5 != '') {
                $total_diskon_kons = $total_diskon_kons - $result->disk_amt5;
                $diskon_amt_kons5 = $result->disk_amt5;
            } else {
                $diskon_amt_kons5 = 0;
            }

            $diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

            //diskon Rp
            $result->disk_toko1 = $diskon_kons1;
            $result->disk_toko2 = $diskon_kons2;
            $result->disk_toko3 = $diskon_kons3;
            $result->disk_toko4 = $diskon_kons4;
            $result->net_price_jual_kons = $total_diskon_kons;
            $diskon = 0;

            if ($result->disk_persen_agen1 != '' && $result->disk_persen_agen1 != 0) {
                $total_diskon_memb = $result->rp_jual_agen - ($result->rp_jual_agen * ($result->disk_persen_agen1 / 100));
                $diskon_member1 = $result->disk_persen_agen1;
                $result->disk_agen1_op = "%";
            } else {
                if ($result->disk_amt_agen1 != '') {
                    $total_diskon_memb = $result->rp_jual_agen - $result->disk_amt_agen1;
                    $diskon_member1 = $result->disk_amt_agen1;
                    $result->disk_agen1_op = "Rp";
                } else {
                    $diskon_member1 = 0;
                }
            }

            if ($result->disk_persen_agen2 != '' && $result->disk_persen_agen2 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen2 / 100));
                $diskon_member2 = $result->disk_persen_agen2;
                $result->disk_agen2_op = "%";
            } else {
                if ($result->disk_amt_agen2 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen2;
                    $diskon_member2 = $result->disk_amt_agen2;
                    $result->disk_agen2_op = "Rp";
                } else {
                    $diskon_member2 = 0;
                }
            }

            if ($result->disk_persen_agen3 != '' && $result->disk_persen_agen3 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen3 / 100));
                $diskon_member3 = $result->disk_persen_agen3;
                $result->disk_agen3_op = "%";
            } else {
                if ($result->disk_amt_agen3 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen3;
                    $diskon_member3 = $result->disk_amt_agen3;
                    $result->disk_agen3_op = "Rp";
                } else {
                    $diskon_member3 = 0;
                }
            }

            if ($result->disk_persen_agen4 != '' && $result->disk_persen_agen4 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen4 / 100));
                $diskon_member4 = $result->disk_persen_agen4;
                $result->disk_agen4_op = "%";
            } else {
                if ($result->disk_amt_agen4 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen4;
                    $diskon_member4 = $result->disk_amt_agen4;
                    $result->disk_agen4_op = "Rp";
                } else {
                    $diskon_member4 = 0;
                }
            }

            if ($result->disk_amt_agen5 != '') {
                $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen5;
                $diskon_amt_member5 = $result->disk_amt_agen5;
            } else {
                $diskon_amt_member5 = 0;
            }

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
            
            if ($result->disk_persen_modern_market1 != '' && $result->disk_persen_modern_market1 != 0) {
                $total_diskon_modern_market = $result->rp_jual_modern_market - ($result->rp_jual_modern_market * ($result->disk_persen_modern_market1 / 100));
                $diskon_modern_market1 = $result->disk_persen_modern_market1;
                $result->disk_modern_market1_op = "%";
            } else {
                if ($result->disk_amt_modern_market1 != '') {
                    $total_diskon_modern_market = $result->rp_jual_modern_market - $result->disk_amt_modern_market1;
                    $diskon_modern_market1 = $result->disk_amt_modern_market1;
                    $result->disk_modern_market1_op = "Rp";
                } else {
                    $diskon_modern_market1 = 0;
                }
            }

            if ($result->disk_amt_modern_market2 != '' && $result->disk_amt_modern_market2 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market2 / 100));
                $diskon_modern_market2 = $result->disk_persen_modern_market2;
                $result->disk_modern_market2_op = "%";
            } else {
                if ($result->disk_amt_modern_market2 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market2;
                    $diskon_modern_market2 = $result->disk_amt_modern_market2;
                    $result->disk_modern_market2_op = "Rp";
                } else {
                    $diskon_modern_market2 = 0;
                }
            }

            if ($result->disk_persen_modern_market3 != '' && $result->disk_persen_modern_market3 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market3 / 100));
                $diskon_modern_market3 = $result->disk_persen_modern_market3;
                $result->disk_modern_market3_op = "%";
            } else {
                if ($result->disk_amt_modern_market3 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market3;
                    $diskon_modern_market3 = $result->disk_amt_modern_market3;
                    $result->disk_modern_market3_op = "Rp";
                } else {
                    $diskon_modern_market3 = 0;
                }
            }

            if ($result->disk_persen_modern_market4 != '' && $result->disk_persen_modern_market4 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market4 / 100));
                $diskon_modern_market4 = $result->disk_persen_modern_market4;
                $result->disk_modern_market4_op = "%";
            } else {
                if ($result->disk_amt_modern_market4 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market4;
                    $diskon_modern_market4 = $result->disk_amt_modern_market4;
                    $result->disk_modern_market4_op = "Rp";
                } else {
                    $diskon_modern_market4 = 0;
                }
            }

            if ($result->disk_amt_modern_market5 != '') {
                $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market5;
                $diskon_amt_modern_market5 = $result->disk_amt_modern_market5;
            } else {
                $diskon_amt_modern_market5 = 0;
            }

            if ($result->is_modern_market_kelipatan == 0) {
                $result->is_modern_market_kelipatan = 'Tidak';
            } else {
                $result->is_modern_market_kelipatan = 'Ya';
            }

            $diskon = $diskon_modern_market1 + $diskon_modern_market2 + $diskon_modern_market3 + $diskon_modern_market4 + $diskon_amt_modern_market5;

            //diskon Rp
            $result->disk_modern_market1 = $diskon_modern_market1;
            $result->disk_modern_market2 = $diskon_modern_market2;
            $result->disk_modern_market3 = $diskon_modern_market3;
            $result->disk_modern_market4 = $diskon_modern_market4;
            $result->disk_amt_modern_market5 = $diskon_amt_modern_market5;
//            $result->net_price_jual_modern_market = round($total_diskon_modern_market);
//            $result->rp_jual_modern_market_net = round($total_diskon_modern_market);

            $result->rp_ongkos_kirim = $result->rp_ongkos_kirim;
            $margin = ($result->pct_margin * $result->net_hrg_supplier_sup_inc)/100;
            $result->rp_het_harga_beli = $result->net_hrg_supplier_sup_inc + $margin + ($result->rp_ongkos_kirim * 1.1);
            $result->rp_het_cogs = 0;
            if(!$result->rp_cogs && $result->rp_cogs != 0){
            	$result->rp_het_cogs = $result->rp_cogs + $margin + ($result->rp_ongkos_kirim * 1.1);
            }
            $results[] = $result;
        }
        echo '{success:true,record:' . $data_result['total'] . ',data:' . json_encode($results) . '}';
    }

    public function search_produk_history() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

        $hasil = $this->hjd_model->search_produk_history($no_bukti, $kd_produk);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $total_diskon_kons = 0;
            $total_diskon_memb = 0;

               if ($result->disk_persen1 != '' && $result->disk_persen1 != 0) {
                $total_diskon_kons = $result->rp_jual_toko - ($result->rp_jual_toko * ($result->disk_persen1 / 100));
                $diskon_kons1 = $result->disk_persen1;
                $result->disk_toko1_op = "%";
            } else {
                if ($result->disk_amt1 != '') {
                    $total_diskon_kons = $result->rp_jual_toko - $result->disk_amt1;
                    $diskon_kons1 = $result->disk_amt1;
                    $result->disk_toko1_op = "Rp";
                } else {
                    $diskon_kons1 = 0;
                }
            }

            if ($result->disk_persen2 != '' && $result->disk_persen2 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen2 / 100));
                $diskon_kons2 = $result->disk_persen2;
                $result->disk_toko2_op = "%";
            } else {
                if ($result->disk_amt2 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt2;
                    $diskon_kons2 = $result->disk_amt2;
                    $result->disk_toko2_op = "Rp";
                } else {
                    $diskon_kons2 = 0;
                }
            }

            if ($result->disk_persen3 != '' && $result->disk_persen3 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen3 / 100));
                $diskon_kons3 = $result->disk_persen3;
                $result->disk_toko3_op = "%";
            } else {
                if ($result->disk_amt3 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt3;
                    $diskon_kons3 = $result->disk_amt3;
                    $result->disk_toko3_op = "Rp";
                } else {
                    $diskon_kons3 = 0;
                }
            }

            if ($result->disk_persen4 != '' && $result->disk_persen4 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen4 / 100));
                $diskon_kons4 = $result->disk_persen4;
                $result->disk_toko4_op = "%";
            } else {
                if ($result->disk_amt4 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt4;
                    $diskon_kons4 = $result->disk_amt4;
                    $result->disk_toko4_op = "Rp";
                } else {
                    $diskon_kons4 = 0;
                }
            }

            if ($result->disk_amt5 != '') {
                $total_diskon_kons = $total_diskon_kons - $result->disk_amt5;
                $diskon_amt_kons5 = $result->disk_amt5;
            } else {
                $diskon_amt_kons5 = 0;
            }

            $diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

            //diskon Rp
            $result->disk_toko1 = $diskon_kons1;
            $result->disk_toko2 = $diskon_kons2;
            $result->disk_toko3 = $diskon_kons3;
            $result->disk_toko4 = $diskon_kons4;
            $result->net_price_jual_kons = $total_diskon_kons;
            $diskon = 0;

            if ($result->disk_persen_agen1 != '' && $result->disk_persen_agen1 != 0) {
                $total_diskon_memb = $result->rp_jual_agen - ($result->rp_jual_agen * ($result->disk_persen_agen1 / 100));
                $diskon_member1 = $result->disk_persen_agen1;
                $result->disk_agen1_op = "%";
            } else {
                if ($result->disk_amt_agen1 != '') {
                    $total_diskon_memb = $result->rp_jual_agen - $result->disk_amt_agen1;
                    $diskon_member1 = $result->disk_amt_agen1;
                    $result->disk_agen1_op = "Rp";
                } else {
                    $diskon_member1 = 0;
                }
            }

            if ($result->disk_persen_agen2 != '' && $result->disk_persen_agen2 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen2 / 100));
                $diskon_member2 = $result->disk_persen_agen2;
                $result->disk_agen2_op = "%";
            } else {
                if ($result->disk_amt_agen2 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen2;
                    $diskon_member2 = $result->disk_amt_agen2;
                    $result->disk_agen2_op = "Rp";
                } else {
                    $diskon_member2 = 0;
                }
            }

            if ($result->disk_persen_agen3 != '' && $result->disk_persen_agen3 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen3 / 100));
                $diskon_member3 = $result->disk_persen_agen3;
                $result->disk_agen3_op = "%";
            } else {
                if ($result->disk_amt_agen3 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen3;
                    $diskon_member3 = $result->disk_amt_agen3;
                    $result->disk_agen3_op = "Rp";
                } else {
                    $diskon_member3 = 0;
                }
            }

            if ($result->disk_persen_agen4 != '' && $result->disk_persen_agen4 != 0) {
                $total_diskon_memb = $total_diskon_memb - ($total_diskon_memb * ($result->disk_persen_agen4 / 100));
                $diskon_member4 = $result->disk_persen_agen4;
                $result->disk_agen4_op = "%";
            } else {
                if ($result->disk_amt_agen4 != '') {
                    $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen4;
                    $diskon_member4 = $result->disk_amt_agen4;
                    $result->disk_agen4_op = "Rp";
                } else {
                    $diskon_member4 = 0;
                }
            }

            if ($result->disk_amt_agen5 != '') {
                $total_diskon_memb = $total_diskon_memb - $result->disk_amt_agen5;
                $diskon_amt_member5 = $result->disk_amt_agen5;
            } else {
                $diskon_amt_member5 = 0;
            }

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
            $result->disk_amt_agen5 = $diskon_amt_member5;
            $result->net_price_jual_agen = $total_diskon_memb;
            
            if ($result->disk_persen_modern_market1 != '' && $result->disk_persen_modern_market1 != 0) {
                $total_diskon_modern_market = $result->rp_jual_modern_market - ($result->rp_jual_modern_market * ($result->disk_persen_modern_market1 / 100));
                $diskon_modern_market1 = $result->disk_persen_modern_market1;
                $result->disk_modern_market1_op = "%";
            } else {
                if ($result->disk_amt_modern_market1 != '') {
                    $total_diskon_modern_market = $result->rp_jual_modern_market - $result->disk_amt_modern_market1;
                    $diskon_modern_market1 = $result->disk_amt_modern_market1;
                    $result->disk_modern_market1_op = "Rp";
                } else {
                    $diskon_modern_market1 = 0;
                }
            }

            if ($result->disk_amt_modern_market2 != '' && $result->disk_amt_modern_market2 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market2 / 100));
                $diskon_modern_market2 = $result->disk_persen_modern_market2;
                $result->disk_modern_market2_op = "%";
            } else {
                if ($result->disk_amt_modern_market2 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market2;
                    $diskon_modern_market2 = $result->disk_amt_modern_market2;
                    $result->disk_modern_market2_op = "Rp";
                } else {
                    $diskon_modern_market2 = 0;
                }
            }

            if ($result->disk_persen_modern_market3 != '' && $result->disk_persen_modern_market3 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market3 / 100));
                $diskon_modern_market3 = $result->disk_persen_modern_market3;
                $result->disk_modern_market3_op = "%";
            } else {
                if ($result->disk_amt_modern_market3 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market3;
                    $diskon_modern_market3 = $result->disk_amt_modern_market3;
                    $result->disk_modern_market3_op = "Rp";
                } else {
                    $diskon_modern_market3 = 0;
                }
            }

            if ($result->disk_persen_modern_market4 != '' && $result->disk_persen_modern_market4 != 0) {
                $total_diskon_modern_market = $total_diskon_modern_market - ($total_diskon_modern_market * ($result->disk_persen_modern_market4 / 100));
                $diskon_modern_market4 = $result->disk_persen_modern_market4;
                $result->disk_modern_market4_op = "%";
            } else {
                if ($result->disk_amt_modern_market4 != '') {
                    $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market4;
                    $diskon_modern_market4 = $result->disk_amt_modern_market4;
                    $result->disk_modern_market4_op = "Rp";
                } else {
                    $diskon_modern_market4 = 0;
                }
            }

            if ($result->disk_amt_modern_market5 != '') {
                $total_diskon_modern_market = $total_diskon_modern_market - $result->disk_amt_modern_market5;
                $diskon_amt_modern_market5 = $result->disk_amt_modern_market5;
            } else {
                $diskon_amt_modern_market5 = 0;
            }

            if ($result->is_modern_market_kelipatan == 0) {
                $result->is_modern_market_kelipatan = 'Tidak';
            } else {
                $result->is_modern_market_kelipatan = 'Ya';
            }

            $diskon = $diskon_modern_market1 + $diskon_modern_market2 + $diskon_modern_market3 + $diskon_modern_market4 + $diskon_amt_modern_market5;

            //diskon Rp
            $result->disk_modern_market1 = $diskon_modern_market1;
            $result->disk_modern_market2 = $diskon_modern_market2;
            $result->disk_modern_market3 = $diskon_modern_market3;
            $result->disk_modern_market4 = $diskon_modern_market4;
            $result->disk_amt_modern_market5 = $diskon_amt_modern_market5;
            $result->net_price_jual_modern_market = $total_diskon_modern_market;
            $results[] = $result;
        }
        echo '{success:true,data:' . json_encode($results) . '}';
    }

    public function print_form($no_bukti = '', $kd_produk = '') {
        $data = $this->hjd_model->get_data_print($no_bukti, $kd_produk);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/HargaPenjualanDistribusiPrint.php');
        $pdf = new HargaPenjualanDistribusiPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'F4', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['detail']);
        $pdf->Output();
        exit;
    }

    public function search_no_bukti() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $result = $this->hjd_model->search_no_bukti($kd_supplier,$search, $start, $limit);

        echo $result;
    }
    public function search_no_bukti_approve() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $result = $this->hjd_model->search_no_bukti_approve($kd_supplier,$search, $start, $limit);

        echo $result;
    }

    public function get_no_bukti_filter() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->hjd_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
    }

}
