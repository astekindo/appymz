<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_distribusi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_distribusi_model', 'pd_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        //$no_so = 'SOD' . date('Ymd') . '-';
        //$sequence = $this->pd_model->get_kode_sequence($no_so, 3);
        echo '{"success":true,
				"data":{
					
					"tgl_so":"' . date('d-M-Y') . '",
					"display_grand_total":"0"
				}
			}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_pelanggan() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pd_model->search_pelanggan($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pd_model->search_produk_distribusi($search, $start, $limit);

        echo $result;
    }

    public function search_bonus() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

        $result_bonus = $this->pd_model->get_bonus($kd_produk);

        $result = $this->pd_model->search_bonus_distribusi(
                $result_bonus->kd_produk_bonus, $result_bonus->kd_kategori1_bonus, $result_bonus->kd_kategori2_bonus, $result_bonus->kd_kategori3_bonus, $result_bonus->kd_kategori4_bonus, $search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_produk($search_by = "") {
        $keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : "";
        $result = $this->pd_model->get_all_produk($search_by, $keyword);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_jenis_pembayaran() {
        $result = $this->pd_model->get_all_jenis_pembayaran();

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_member() {
        $result = $this->pd_model->get_all_member();

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row_produk() {
        $search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by', TRUE)) : "";
        $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
        $qty = isset($_POST['qty']) ? $this->db->escape_str($this->input->post('qty', TRUE)) : '';
        $extra_bonus = isset($_POST['extra_bonus']) ? $this->db->escape_str($this->input->post('extra_bonus', TRUE)) : '';
        //$member = isset($_POST['member']) ? $this->db->escape_str($this->input->post('member',TRUE)) : '';

        $result = $this->pd_model->get_row_produk($search_by, $id);

        if (count($result) > 0) {
            $result->hrg_jual_toko = (int) $result->rp_jual_toko;
            $result->hrg_jual_agen = (int) $result->rp_jual_agen;
            $result->hrg_jual_modern_market = (int) $result->rp_jual_modern_market;
            //hitung diskon

            if ($result->disk_persen1 != '' && $result->disk_persen1 != 0) {
                $total_diskon_kons = $result->hrg_jual_toko - ($result->hrg_jual_toko * ($result->disk_persen1 / 100));
                $diskon_kons1 = $result->disk_persen1;
                $result->disk_toko1_op = "%";
            } else {
                if ($result->disk_amt1 != '') {
                    $total_diskon_kons = $result->hrg_jual_toko - $result->disk_amt1;
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
                $result->disk_toko5_op = "Rp";
            } else {
                $diskon_amt_kons5 = 0;
            }

            $diskon_toko = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;

            //diskon Rp
            $result->disk_toko1 = $diskon_kons1;
            $result->disk_toko2 = $diskon_kons2;
            $result->disk_toko3 = $diskon_kons3;
            $result->disk_toko4 = $diskon_kons4;
            $result->disk_toko5 = $diskon_amt_kons5;

            $result->rp_diskon_toko = $result->hrg_jual_toko - $total_diskon_kons;
            
            if ($result->disk_persen_agen1 != '' && $result->disk_persen_agen1 != 0) {
                $total_diskon_memb = $result->hrg_jual_agen - ($result->hrg_jual_agen * ($result->disk_persen_agen1 / 100));
                $diskon_member1 = $result->disk_persen_agen1;
                $result->disk_agen1_op = "%";
            } else {
                if ($result->disk_amt_agen1 != '') {
                    $total_diskon_memb = $result->hrg_jual_agen - $result->disk_amt_agen1;
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
                $result->disk_agen5_op = "Rp";
            } else {
                $diskon_amt_member5 = 0;
            }
            $diskon_agen = $diskon_member1 + $diskon_member2 + $diskon_member3 + $diskon_member4 + $diskon_amt_member5;
            $result->disk_agen1 = $diskon_member1;
            $result->disk_agen2 = $diskon_member2;
            $result->disk_agen3 = $diskon_member3;
            $result->disk_agen4 = $diskon_member4;
            $result->disk_agen5 = $diskon_amt_member5;
            $result->rp_diskon_agen = $result->hrg_jual_agen - $total_diskon_memb;
            
             if ($result->disk_persen_modern_market1 != '' && $result->disk_persen_modern_market1 != 0) {
                $total_diskon_modern_market = $result->hrg_jual_modern_market - ($result->hrg_jual_modern_market * ($result->disk_persen_modern_market1 / 100));
                $diskon_modern_market1 = $result->disk_persen_modern_market1;
                $result->disk_modern_market1_op = "%";
            } else {
                if ($result->disk_amt_modern_market1 != '') {
                    $total_diskon_modern_market = $result->hrg_jual_modern_market - $result->disk_amt_modern_market1;
                    $diskon_modern_market1 = $result->disk_amt_modern_market1;
                    $result->disk_modern_market1_op = "Rp";
                } else {
                    $diskon_modern_market1 = 0;
                }
            }

            if ($result->disk_persen_modern_market2 != '' && $result->disk_persen_modern_market2 != 0) {
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
                $result->disk_modern_market5_op = "Rp";
            } else {
                $diskon_amt_modern_market5 = 0;
            }
            $diskon_modern_market = $diskon_modern_market1 + $diskon_modern_market2 + $diskon_modern_market3 + $diskon_modern_market4 + $diskon_amt_modern_market5;
            $result->disk_modern_market1 = $diskon_modern_market1;
            $result->disk_modern_market2 = $diskon_modern_market2;
            $result->disk_modern_market3 = $diskon_modern_market3;
            $result->disk_modern_market4 = $diskon_modern_market4;
            $result->disk_modern_market5 = $diskon_amt_modern_market5;
            $result->rp_diskon_modern_market = $result->hrg_jual_modern_market - $total_diskon_modern_market;
            
            //hitung jumlah
            $result->rp_harga_nett_toko = $result->hrg_jual_toko - $result->rp_diskon_toko;
            $result->rp_harga_nett_agen = $result->hrg_jual_agen - $result->rp_diskon_agen;
            $result->rp_harga_nett_modern_market = $result->hrg_jual_modern_market - $result->rp_diskon_modern_market;
            //hitung total
            $result->rp_jumlah_toko = (int) $qty * $result->rp_harga_nett_toko;
            $result->rp_jumlah_agen = (int) $qty * $result->rp_harga_nett_agen;
            $result->rp_jumlah_modern_market = (int) $qty * $result->rp_harga_nett_modern_market;
            if ($extra_bonus == '')
                $extra_bonus = 0;
            $result->extra_bonus = $extra_bonus;
            $result->rp_total_toko = $result->rp_jumlah_toko - $extra_bonus;
            $result->rp_jumlah_agen = $result->rp_jumlah_agen - $extra_bonus;
            $result->rp_jumlah_modern_market = $result->rp_jumlah_modern_market - $extra_bonus;
            //hitung bonus
            $qty_bonus = 0;
            /*
              if(($qty != '' || $qty != 0) && $result->qty_beli_bonus >= $qty){
              if($result->is_bonus_kelipatan){
              $qty_bonus = (floor($qty/$result->qty_beli_bonus)) * $result->qty_bonus;
              }else{
              $qty_bonus = $result->qty_bonus;
              }
              }


              $result->qty_bonus = (int) $qty_bonus;
             */
        }
        echo '{success:true,data:' . json_encode($result) . '}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        //header sales distribusi
        $kd_member = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        $kd_sales = isset($_POST['kd_sales']) ? $this->db->escape_str($this->input->post('kd_sales', TRUE)) : '';
        $tgl_so = isset($_POST['tgl_so']) ? $this->db->escape_str($this->input->post('tgl_so', TRUE)) : '';
        $no_ref = isset($_POST['no_ref']) ? $this->db->escape_str($this->input->post('no_ref', TRUE)) : 0;
        $kirim_so = isset($_POST['kirim_so']) ? $this->db->escape_str($this->input->post('kirim_so', TRUE)) : '';
        $kirim_alamat_so = isset($_POST['kirim_alamat_so']) ? $this->db->escape_str($this->input->post('kirim_alamat_so', TRUE)) : '';
        $kirim_telp_so = isset($_POST['kirim_telp_so']) ? $this->db->escape_str($this->input->post('kirim_telp_so', TRUE)) : '';
        $rp_total = isset($_POST['_rp_total']) ? $this->db->escape_str($this->input->post('_rp_total', TRUE)) : 0;
        $uang_muka = isset($_POST['_uang_muka']) ? $this->db->escape_str($this->input->post('_uang_muka', TRUE)) : 0;
        $total = isset($_POST['_total']) ? $this->db->escape_str($this->input->post('_total', TRUE)) : 0;
        $rp_dpp = isset($_POST['_rp_dpp']) ? $this->db->escape_str($this->input->post('_rp_dpp', TRUE)) : 0;
        $pct_ppn = isset($_POST['_pct_ppn']) ? $this->db->escape_str($this->input->post('_pct_ppn', TRUE)) : 0;
        $rp_ppn = isset($_POST['_rp_ppn']) ? $this->db->escape_str($this->input->post('_rp_ppn', TRUE)) : 0;
        //$rp_diskon = isset($_POST['_rp_diskon_total']) ? $this->db->escape_str($this->input->post('_rp_diskon_total', TRUE)) : 0;
        //$rp_diskon_tambahan = isset($_POST['_rp_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_rp_diskon_tambahan', TRUE)) : 0;
        //$rp_ongkos_kirim = isset($_POST['_rp_ongkos_kirim']) ? $this->db->escape_str($this->input->post('_rp_ongkos_kirim', TRUE)) : 0;
        //$pct_diskon_tambahan = isset($_POST['_pct_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_pct_diskon_tambahan', TRUE)) : 0;
        
        //$rp_total_bayar = isset($_POST['_rp_total_bayar']) ? $this->db->escape_str($this->input->post('_rp_total_bayar', TRUE)) : 0;
      
        $current_date = date('Ymd', strtotime($tgl_so));
        $no_ret = 'SOD' . $current_date . '-';
        $sequence = $this->pd_model->get_kode_sequence($no_ret, 4);
        $no_so = "$no_ret$sequence";
        
        
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();
        $header_result = FALSE;
        $detail_result = 0;

        if (count($detail) > 0) {

            if ($tgl_so) {
                $tgl_so = date('Y-m-d', strtotime($tgl_so));
            }

//            if (date('n') != date('n', strtotime($tgl_so))) {
//                $this->db->trans_rollback();
//                echo '{"success":false,"errMsg":"Bulan Pada Tanggal SO Harus Di Bulan ' . date('F') . '"}';
//                exit;
//            }
            $this->db->trans_start();
            unset($header_so);
            $header_so['no_so'] = $no_so;
            $header_so['kd_member'] = $kd_member;
            $header_so['kd_sales'] = $kd_sales;
            $header_so['status'] = 1;
            $header_so['tgl_so'] = $tgl_so;
            $header_so['no_ref'] = $no_ref;
            $header_so['kirim_so'] = $kirim_so;
            $header_so['kirim_alamat_so'] = $kirim_alamat_so;
            $header_so['kirim_telp_so'] = $kirim_telp_so;
            $header_so['rp_total'] = $rp_total;
            $header_so['rp_uang_muka'] = $uang_muka;
            $header_so['rp_kurang_bayar'] = $total;
            $header_so['rp_dpp'] = $rp_dpp;
            $header_so['pct_ppn'] = $pct_ppn;
            $header_so['rp_ppn'] = $rp_ppn;
            $header_so['rp_grand_total'] = $rp_total;
            $header_so['created_by'] = $this->session->userdata('username');
            $header_so['created_date'] = date('Y-m-d H:i:s');
            $header_so['updated_by'] = $this->session->userdata('username');
            $header_so['updated_date'] = date('Y-m-d H:i:s');
            $header_so['type_sales'] = 1;
            
            //$header_so['rp_ongkos_kirim'] = $rp_ongkos_kirim;
            //$header_so['rp_total_bayar'] = $rp_total_bayar;
            //$header_so['pct_diskon_tambahan'] = $pct_diskon_tambahan;
            $header_result = $this->pd_model->insert_row('sales.t_sales_order_dist', $header_so);

            foreach ($detail as $obj) {
                unset($detail_so);
                unset($detail_bonus);
                
                if ($obj->disk_persen1_op == 'Rp'){
                    $disk_amt1 = $obj->disk_persen_kons1;
                    $disk_persen1 = 0;
                }else {
                    $disk_persen1 = $obj->disk_persen_kons1;
                    $disk_amt1 = 0;
                }   
                if ($obj->disk_persen2_op == 'Rp'){
                    $disk_amt2 = $obj->disk_persen_kons2;
                    $disk_persen2 = 0;
                }else {
                    $disk_persen2 = $obj->disk_persen_kons2;
                    $disk_amt2 = 0;
                }
                if ($obj->disk_persen3_op == 'Rp'){
                    $disk_amt3 = $obj->disk_persen_kons3;
                    $disk_persen3 = 0;
                }else {
                    $disk_persen3 = $obj->disk_persen_kons3;
                    $disk_amt3 = 0;
                }
                if ($obj->disk_persen4_op == 'Rp'){
                    $disk_amt4 = $obj->disk_persen_kons4;
                    $disk_persen4 = 0;
                }else {
                    $disk_persen4 = $obj->disk_persen_kons4;
                    $disk_amt4 = 0;
                }
                if ($obj->rp_diskon_satuan == '' || $obj->rp_diskon_satuan == NULL){
                    $diskon_satuan = 0;
                }else {
                    $diskon_satuan = $obj->rp_diskon_satuan;
                }
                if ($obj->kd_produk != '' && $obj->qty != '') { //yg diinsert di detail ga boleh kosong
                    $detail_so['is_kirim'] = 1;
                    $detail_so['is_do'] = 0;
                    $detail_so['no_so'] = $no_so;
                    $detail_so['kd_produk'] = $obj->kd_produk;
                    $detail_so['qty'] = $obj->qty;
                    $detail_so['rp_harga_jual'] = $obj->hrg_jual;
                    //$detail_so['rp_ekstra_diskon'] = $obj->rp_extra_bonus;
                    $detail_so['disk_persen1'] = $disk_persen1;
                    $detail_so['disk_persen2'] = $disk_persen2;
                    $detail_so['disk_persen3'] = $disk_persen3;
                    $detail_so['disk_persen4'] = $disk_persen4;
                    $detail_so['disk_amt1'] = $disk_amt1;
                    $detail_so['disk_amt2'] = $disk_amt2;
                    $detail_so['disk_amt3'] = $disk_amt3;
                    $detail_so['disk_amt4'] = $disk_amt4;
                    $detail_so['disk_amt5'] = $obj->disk_persen_kons5;
                    $detail_so['rp_diskon_satuan'] = $diskon_satuan;
                    $detail_so['rp_diskon'] = $obj->rp_diskon_total;
                    $detail_so['rp_net_harga_jual'] = $obj->rp_harga_nett;
                    $detail_so['rp_jumlah'] = $obj->rp_jumlah;
                    $detail_so['rp_total'] = $obj->rp_jumlah;
 
                    if ($this->pd_model->insert_row('sales.t_sales_order_dist_detail', $detail_so)) {
                        $detail_result++;
                    }

                    //jika ada bonus
                    if ($obj->kd_produk_bonus != '' && $obj->qty_bonus > 0) {
                        $detail_bonus['no_so'] = $no_so;
                        $detail_bonus['kd_produk'] = $obj->kd_produk;
                        $detail_bonus['kd_produk_bonus'] = $obj->kd_produk_bonus;
                        $detail_bonus['qty_bonus'] = $obj->qty_bonus;
                        $this->pd_model->insert_row('sales.t_sales_order_bonus', $detail_bonus);
                    }
                }
            }
            
            $this->db->trans_complete();
        }

        if ($header_result && $detail_result > 0) {
            //$this->db->trans_commit();
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("penjualan_distribusi/print_form/" . $no_so) . '"}';
        } else {
            //$this->db->trans_rollback();
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function print_form($no_so) {
        $data = $this->pd_model->get_data_print($no_so);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PenjualanDistribusiPrint.php');
        $pdf = new PenjualanDistribusiPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function search_sales() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pd_model->search_sales($search, $start, $limit);

        echo $result;
    }

}