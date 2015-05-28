<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_retur extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_retur_model', 'pret_model');
        $this->load->model('pembelian_receive_order_model', 'pro_model');
    }

    //RBYYYYMM-001
    public function get_form() {
        $no_ret = 'RP' . date('Ym') . '-';
        $sequence = $this->pret_model->get_kode_sequence($no_ret, 3);
        echo '{"success":true,
				"data":{
					"no_retur":"' . $no_ret . $sequence . '",
					"tgl_retur":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function search_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pret_model->search_supplier($search, $start, $limit);


        echo $result;
    }

    public function search_produk_by_supplier() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $hasil = $this->pret_model->search_produk_by_supplier($kd_supplier, $kd_produk, $search);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $diskon_supp1_hitung = 0;
            $diskon_supp2_hitung = 0;
            $diskon_supp3_hitung = 0;
            $diskon_supp4_hitung = 0;


            if ($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0) {
                $diskon_supp1 = $result->disk_persen_supp1;
                $diskon_supp1_hitung = ($result->disk_persen_supp1 * $result->hrg_supplier) / 100;
                $result->disk_supp1_op = "%";
            } else {
                if ($result->disk_amt_supp1 != '' && $result->disk_amt_supp1 != 0) {
                    $diskon_supp1 = $result->disk_amt_supp1;
                    $diskon_supp1_hitung = $diskon_supp1;
                    $result->disk_supp1_op = "Rp";
                } else {
                    $diskon_supp1 = 0;
                    $result->disk_supp1_op = "%";
                }
            }

            if ($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0) {
                $diskon_supp2 = $result->disk_persen_supp2;
                $diskon_supp2_hitung = ($diskon_supp2 * ($result->hrg_supplier - $diskon_supp1_hitung)) / 100;
                $result->disk_supp2_op = "%";
            } else {
                if ($result->disk_amt_supp2 != '' && $result->disk_amt_supp2 != 0) {
                    $diskon_supp2 = $result->disk_amt_supp2;
                    $diskon_supp2_hitung = $diskon_supp2;
                    $result->disk_supp2_op = "Rp";
                } else {
                    $diskon_supp2 = 0;
                    $result->disk_supp2_op = "%";
                }
            }

            if ($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0) {
                $diskon_supp3 = $result->disk_persen_supp3;
                $diskon_supp3_hitung = ($diskon_supp3 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung)) / 100;
                $result->disk_supp3_op = "%";
            } else {
                if ($result->disk_amt_supp3 != '' && $result->disk_amt_supp3 != 0) {
                    $diskon_supp3 = $result->disk_amt_supp3;
                    $diskon_supp3_hitung = $diskon_supp3;
                    $result->disk_supp3_op = "Rp";
                } else {
                    $diskon_supp3 = 0;
                    $result->disk_supp3_op = "%";
                }
            }

            if ($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0) {
                $diskon_supp4 = $result->disk_persen_supp4;
                $diskon_supp4_hitung = ($diskon_supp4 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung - $diskon_supp3_hitung)) / 100;
                $result->disk_supp4_op = "%";
            } else {
                if ($result->disk_amt_supp4 != '' && $result->disk_amt_supp4 != 0) {
                    $diskon_supp4 = $result->disk_amt_supp4;
                    $diskon_supp4_hitung = $diskon_supp4;
                    $result->disk_supp4_op = "Rp";
                } else {
                    $diskon_supp4 = 0;
                    $result->disk_supp4_op = "%";
                }
            }

            if ($result->diskon_amt_supp5 != '') {
                $diskon_amt_supp5 = $result->diskon_amt_supp5;
            } else {
                $diskon_amt_supp5 = 0;
            }

            //diskon Rp
            $result->disk_supp1 = $diskon_supp1;
            $result->disk_supp2 = $diskon_supp2;
            $result->disk_supp3 = $diskon_supp3;
            $result->disk_supp4 = $diskon_supp4;

//              $result->disk_supp1 = $diskon_supp1_hitung;
//              $result->disk_supp2 = $diskon_supp2_hitung;
//              $result->disk_supp3 = $diskon_supp3_hitung;
//              $result->disk_supp4 = $diskon_supp4_hitung;

            $diskon = $diskon_supp1_hitung + $diskon_supp2_hitung + $diskon_supp3_hitung + $diskon_supp4_hitung + $diskon_amt_supp5;

            //hitung harga  
            $result->harga = $result->hrg_supplier - $diskon;
            
            if($result->pkp === '1'){
                $result->harga_exc = $result->harga / 1.1;
            }else{
                $result->harga_exc = $result->harga;
            }

            $result->jumlah = 0;
            $result->qty = 0;

            $results[] = $result;
        }
        echo '{success:true,data:' . json_encode($results) . '}';
    }

    public function search_produk_by_no_invoice() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $no_invoice = isset($_POST['no_invoice']) ? $this->db->escape_str($this->input->post('no_invoice', TRUE)) : '';

        $hasil = $this->pret_model->search_produk_by_no_invoice($kd_supplier, $kd_produk, $no_invoice);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $diskon_supp1_hitung = 0;
            $diskon_supp2_hitung = 0;
            $diskon_supp3_hitung = 0;
            $diskon_supp4_hitung = 0;


            if ($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0) {
                $diskon_supp1 = $result->disk_persen_supp1;
                $diskon_supp1_hitung = ($result->disk_persen_supp1 * $result->hrg_supplier) / 100;
                $result->disk_supp1_op = "%";
            } else {
                if ($result->disk_amt_supp1 != '' && $result->disk_amt_supp1 != 0) {
                    $diskon_supp1 = $result->disk_amt_supp1;
                    $diskon_supp1_hitung = $diskon_supp1;
                    $result->disk_supp1_op = "Rp";
                } else {
                    $diskon_supp1 = 0;
                    $result->disk_supp1_op = "%";
                }
            }

            if ($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0) {
                $diskon_supp2 = $result->disk_persen_supp2;
                $diskon_supp2_hitung = ($diskon_supp2 * ($result->hrg_supplier - $diskon_supp1_hitung)) / 100;
                $result->disk_supp2_op = "%";
            } else {
                if ($result->disk_amt_supp2 != '' && $result->disk_amt_supp2 != 0) {
                    $diskon_supp2 = $result->disk_amt_supp2;
                    $diskon_supp2_hitung = $diskon_supp2;
                    $result->disk_supp2_op = "Rp";
                } else {
                    $diskon_supp2 = 0;
                    $result->disk_supp2_op = "%";
                }
            }

            if ($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0) {
                $diskon_supp3 = $result->disk_persen_supp3;
                $diskon_supp3_hitung = ($diskon_supp3 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung)) / 100;
                $result->disk_supp3_op = "%";
            } else {
                if ($result->disk_amt_supp3 != '' && $result->disk_amt_supp3 != 0) {
                    $diskon_supp3 = $result->disk_amt_supp3;
                    $diskon_supp3_hitung = $diskon_supp3;
                    $result->disk_supp3_op = "Rp";
                } else {
                    $diskon_supp3 = 0;
                    $result->disk_supp3_op = "%";
                }
            }

            if ($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0) {
                $diskon_supp4 = $result->disk_persen_supp4;
                $diskon_supp4_hitung = ($diskon_supp4 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung - $diskon_supp3_hitung)) / 100;
                $result->disk_supp4_op = "%";
            } else {
                if ($result->disk_amt_supp4 != '' && $result->disk_amt_supp4 != 0) {
                    $diskon_supp4 = $result->disk_amt_supp4;
                    $diskon_supp4_hitung = $diskon_supp4;
                    $result->disk_supp4_op = "Rp";
                } else {
                    $diskon_supp4 = 0;
                    $result->disk_supp4_op = "%";
                }
            }

            if ($result->diskon_amt_supp5 != '') {
                $diskon_amt_supp5 = $result->diskon_amt_supp5;
            } else {
                $diskon_amt_supp5 = 0;
            }

            //diskon Rp
            $result->disk_supp1 = $diskon_supp1;
            $result->disk_supp2 = $diskon_supp2;
            $result->disk_supp3 = $diskon_supp3;
            $result->disk_supp4 = $diskon_supp4;

            /*             * $result->disk_supp1 = $diskon_supp1_hitung;
              $result->disk_supp2 = $diskon_supp2_hitung;
              $result->disk_supp3 = $diskon_supp3_hitung;
              $result->disk_supp4 = $diskon_supp4_hitung;* */

            $diskon = $diskon_supp1_hitung + $diskon_supp2_hitung + $diskon_supp3_hitung + $diskon_supp4_hitung + $diskon_amt_supp5;

            //hitung harga  
            $result->harga = $result->hrg_supplier - $diskon;
            
            if($result->pkp === '1'){
                $result->harga_exc = $result->harga / 1.1;
            }else{
                $result->harga_exc = $result->harga;
            }

            $result->jumlah = 0;
            $result->qty = 0;

            $results[] = $result;
        }
        echo '{success:true,data:' . json_encode($results) . '}';
    }

    public function search_produk_by_no_po() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : '';

        $hasil = $this->pret_model->search_produk_by_no_po($kd_supplier, $kd_produk, $no_po);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $diskon_supp1_hitung = 0;
            $diskon_supp2_hitung = 0;
            $diskon_supp3_hitung = 0;
            $diskon_supp4_hitung = 0;


            if ($result->disk_persen_supp1 != '' && $result->disk_persen_supp1 != 0) {
                $diskon_supp1 = $result->disk_persen_supp1;
                $diskon_supp1_hitung = ($result->disk_persen_supp1 * $result->hrg_supplier) / 100;
                $result->disk_supp1_op = "%";
            } else {
                if ($result->disk_amt_supp1 != '' && $result->disk_amt_supp1 != 0) {
                    $diskon_supp1 = $result->disk_amt_supp1;
                    $diskon_supp1_hitung = $diskon_supp1;
                    $result->disk_supp1_op = "Rp";
                } else {
                    $diskon_supp1 = 0;
                    $result->disk_supp1_op = "%";
                }
            }

            if ($result->disk_persen_supp2 != '' && $result->disk_persen_supp2 != 0) {
                $diskon_supp2 = $result->disk_persen_supp2;
                $diskon_supp2_hitung = ($diskon_supp2 * ($result->hrg_supplier - $diskon_supp1_hitung)) / 100;
                $result->disk_supp2_op = "%";
            } else {
                if ($result->disk_amt_supp2 != '' && $result->disk_amt_supp2 != 0) {
                    $diskon_supp2 = $result->disk_amt_supp2;
                    $diskon_supp2_hitung = $diskon_supp2;
                    $result->disk_supp2_op = "Rp";
                } else {
                    $diskon_supp2 = 0;
                    $result->disk_supp2_op = "%";
                }
            }

            if ($result->disk_persen_supp3 != '' && $result->disk_persen_supp3 != 0) {
                $diskon_supp3 = $result->disk_persen_supp3;
                $diskon_supp3_hitung = ($diskon_supp3 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung)) / 100;
                $result->disk_supp3_op = "%";
            } else {
                if ($result->disk_amt_supp3 != '' && $result->disk_amt_supp3 != 0) {
                    $diskon_supp3 = $result->disk_amt_supp3;
                    $diskon_supp3_hitung = $diskon_supp3;
                    $result->disk_supp3_op = "Rp";
                } else {
                    $diskon_supp3 = 0;
                    $result->disk_supp3_op = "%";
                }
            }

            if ($result->disk_persen_supp4 != '' && $result->disk_persen_supp4 != 0) {
                $diskon_supp4 = $result->disk_persen_supp4;
                $diskon_supp4_hitung = ($diskon_supp4 * ($result->hrg_supplier - $diskon_supp1_hitung - $diskon_supp2_hitung - $diskon_supp3_hitung)) / 100;
                $result->disk_supp4_op = "%";
            } else {
                if ($result->disk_amt_supp4 != '' && $result->disk_amt_supp4 != 0) {
                    $diskon_supp4 = $result->disk_amt_supp4;
                    $diskon_supp4_hitung = $diskon_supp4;
                    $result->disk_supp4_op = "Rp";
                } else {
                    $diskon_supp4 = 0;
                    $result->disk_supp4_op = "%";
                }
            }

            if ($result->diskon_amt_supp5 != '') {
                $diskon_amt_supp5 = $result->diskon_amt_supp5;
            } else {
                $diskon_amt_supp5 = 0;
            }

            //diskon Rp
            $result->disk_supp1 = $diskon_supp1;
            $result->disk_supp2 = $diskon_supp2;
            $result->disk_supp3 = $diskon_supp3;
            $result->disk_supp4 = $diskon_supp4;

            /*             * $result->disk_supp1 = $diskon_supp1_hitung;
              $result->disk_supp2 = $diskon_supp2_hitung;
              $result->disk_supp3 = $diskon_supp3_hitung;
              $result->disk_supp4 = $diskon_supp4_hitung;* */

            $diskon = $diskon_supp1_hitung + $diskon_supp2_hitung + $diskon_supp3_hitung + $diskon_supp4_hitung + $diskon_amt_supp5;

            //hitung harga  
            $result->harga = $result->hrg_supplier - $diskon;
            
            if($result->pkp === '1'){
                $result->harga_exc = $result->harga / 1.1;
            }else{
                $result->harga_exc = $result->harga;
            }

            $result->jumlah = 0;
            $result->qty = 0;

            $results[] = $result;
        }
        echo '{success:true,data:' . json_encode($results) . '}';
    }

    public function update_row() {
        $no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : FALSE;
        $tgl_retur = isset($_POST['tgl_retur']) ? $this->db->escape_str($this->input->post('tgl_retur', TRUE)) : FALSE;
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : FALSE;
        $pcn_ppn = isset($_POST['_ppn_persen']) ? $this->db->escape_str($this->input->post('_ppn_persen', TRUE)) : FALSE;
        $rp_jumlah = isset($_POST['_jumlah']) ? $this->db->escape_str($this->input->post('_jumlah', TRUE)) : FALSE;
        $rp_ppn = isset($_POST['_ppn_rp']) ? $this->db->escape_str($this->input->post('_ppn_rp', TRUE)) : FALSE;
        $rp_total = isset($_POST['_total']) ? $this->db->escape_str($this->input->post('_total', TRUE)) : FALSE;
//                $status=	isset($_POST[	'status'	]) ? $this->db->escape_str($this->input->post(	'status'	, TRUE)) : FALSE;   
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : FALSE;
        $kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok', TRUE)) : FALSE;

        $status = '0';
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d H:i:s');
        // $is_konsinyasi = isset($_POST['pis_konsinyasi']) ? $this->db->escape_str($this->input->post('pis_konsinyasi', TRUE)) : FALSE;

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if (count($detail) == 0) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        $this->db->trans_start();


        $header_ret['no_retur'] = $no_retur;
        $header_ret['tgl_retur'] = $tgl_retur;
        $header_ret['kd_suplier'] = $kd_supplier;
        $header_ret['pcn_ppn'] = $pcn_ppn;
        $header_ret['rp_jumlah'] = $rp_jumlah;
        $header_ret['rp_ppn'] = $rp_ppn;
        $header_ret['rp_total'] = $rp_total;
        $header_ret['status'] = $status;
        $header_ret['created_by'] = $created_by;
        $header_ret['created_date'] = $created_date;
//        $header_ret['updated_by'] = $updated_by;
//        $header_ret['updated_date'] = $updated_date;
        $header_ret['is_konsinyasi'] = '0';



        $header_result = $this->pret_model->insert_row('purchase.t_retur_purchase', $header_ret);

        foreach ($detail as $obj) {

            $sub = $obj->sub;
            $kd_lokasi = substr($sub, 0, 2);
            $kd_blok = substr($sub, 2, 2);
            $kd_sub_blok = substr($sub, -2, 2);

            if ($obj->disk_supp1_op == '%') {
                $disk_persen_supp1 = $obj->disk_supp1;
                $disk_amt_supp1 = 0;
            } else if ($obj->disk_supp1_op == 'Rp') {
                $disk_persen_supp1 = 0;
                $disk_amt_supp1 = $obj->disk_supp1;
            }
            if ($obj->disk_supp2_op == '%') {
                $disk_persen_supp2 = $obj->disk_supp2;
                $disk_amt_supp1 = 0;
            } else if ($obj->disk_supp2_op == 'Rp') {
                $disk_persen_supp2 = 0;
                $disk_amt_supp2 = $obj->disk_supp2;
            }
            if ($obj->disk_supp3_op == '%') {
                $disk_persen_supp3 = $obj->disk_supp3;
                $disk_amt_supp3 = 0;
            } else if ($obj->disk_supp3_op == 'Rp') {
                $disk_persen_supp3 = 0;
                $disk_amt_supp3 = $obj->disk_supp3;
            }
            if ($obj->disk_supp4_op == '%') {
                $disk_persen_supp4 = $obj->disk_supp4;
                $disk_amt_supp4 = 0;
            } else if ($obj->disk_supp4_op == 'Rp') {
                $disk_persen_supp4 = 0;
                $disk_amt_supp4 = $obj->disk_supp4;
            }

            $is_pkp = $this->pret_model->get_pkp($kd_supplier);

            unset($detail_ret);
            $detail_ret['kd_produk'] = $obj->kd_produk;
            $detail_ret['no_retur'] = $no_retur;
            $detail_ret['qty'] = (int) $obj->qty;
            $detail_ret['price_supp'] = (int) $obj->hrg_supplier;
            $detail_ret['disk_persen_supp1'] = (int) $disk_persen_supp1;
            $detail_ret['disk_persen_supp2'] = (int) $disk_persen_supp2;
            $detail_ret['disk_persen_supp3'] = (int) $disk_persen_supp3;
            $detail_ret['disk_persen_supp4'] = (int) $disk_persen_supp4;
            $detail_ret['disk_amt_supp1'] = (int) $disk_amt_supp1;
            $detail_ret['disk_amt_supp2'] = (int) $disk_amt_supp2;
            $detail_ret['disk_amt_supp3'] = (int) $disk_amt_supp3;
            $detail_ret['disk_amt_supp4'] = (int) $disk_amt_supp4;
            $detail_ret['disk_amt_supp5'] = (int) $disk_amt_supp5;
            $detail_ret['rp_diskon'] = $disk_persen_supp1 + $disk_persen_supp2 + $disk_persen_supp3 + $disk_persen_supp4;
            $detail_ret['net_price'] = $obj->harga;

            if ($is_pkp->pkp == '1') {
                $hpp_pkp = 1.1;
                $detail_ret['dpp'] = $obj->harga / 1.1;
                $hpp_pct_ppn = 10;
            } else if ($is_pkp->pkp == '0') {
                $hpp_pkp = 1;
                $detail_ret['dpp'] = $obj->harga;
                $hpp_pct_ppn = 0;
            }

            $detail_ret['rp_disk'] = $disk_amt_supp1 + $disk_amt_supp2 + $disk_amt_supp3 + $disk_amt_supp4 + $disk_amt_supp5;
            $detail_ret['rp_jumlah'] = (int) $obj->jumlah;
            $detail_ret['rp_total'] = (int) $obj->jumlah;
            $detail_ret['approval'] = '0';
            $detail_ret['created_by'] = $created_by;
            $detail_ret['created_date'] = $created_date;
            $detail_ret['kd_lokasi'] = $kd_lokasi;
            $detail_ret['kd_blok'] = $kd_blok;
            $detail_ret['kd_sub_blok'] = $kd_sub_blok;

            unset($trxinventory);
            $trxinventory['kd_produk'] = $obj->kd_produk;
            $trxinventory['no_ref'] = $no_retur;
            $trxinventory['kd_lokasi'] = $kd_lokasi;
            $trxinventory['kd_blok'] = $kd_blok;
            $trxinventory['kd_sub_blok'] = $kd_sub_blok;
            $trxinventory['qty_in'] = 0;
            $trxinventory['qty_out'] = (int) $obj->qty;
            $trxinventory['type'] = '2';
            $trxinventory['created_by'] = $created_by;
            $trxinventory['created_date'] = $created_date;
            $trxinventory['tgl_trx'] = $tgl_retur;

            $stok = 0;
            $stokexists = FALSE;
            $rowstok = $this->pret_model->cek_exists_brg_inv($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);

            unset($brg_inventory);
            if (count($rowstok) > 0) {
                $stokexists = true;
                foreach ($rowstok as $objstok) {
                    $stok = $objstok->qty_oh;
                }
                $brg_inventory['qty_oh'] = $stok - (int) $obj->qty;
                $brg_inventory['updated_by'] = $created_by;
                $brg_inventory['updated_date'] = $created_date;
            } else {
                $brg_inventory['kd_produk'] = $obj->kd_produk;
                $brg_inventory['kd_lokasi'] = $kd_lokasi;
                $brg_inventory['kd_blok'] = $kd_blok;
                $brg_inventory['kd_sub_blok'] = $kd_sub_blok;
                $brg_inventory['qty_oh'] = $stok - (int) $obj->qty;
                $brg_inventory['created_by'] = $created_by;
                $brg_inventory['created_date'] = $created_date;
            }

            $hpp_result = $this->pret_model->get_hpp_by_kd_produk($obj->kd_produk);

            $hpp_rp_angkut = $hpp_result->rp_ongkos_kirim;
            $hpp_cogs = (($hpp_result->rp_cogs * ($stok)) - ($obj->qty * $detail_ret['dpp'])) / ($stok - (int) $obj->qty);
            $hpp_rp_margin = ($hpp_result->pct_margin / 100) * $hpp_cogs;
            $hpp_hrg_beli_satuan = $detail_ret['dpp'];
            $rp_nilai_stok = $hpp_cogs * ($stok - (int) $obj->qty);
            $hpp_het_hrg_beli = ($hpp_hrg_beli_satuan + $hpp_rp_angkut + (($hpp_result->pct_margin / 100) * $hpp_hrg_beli_satuan)) * $hpp_pkp;
            $hpp_het = ($hpp_cogs + $hpp_rp_angkut + $hpp_rp_margin) * $hpp_pkp;

            if ($is_pkp->pkp == '1') {
                $hpp_rp_ppn = ($hpp_pct_ppn / 100) * ($hpp_cogs + $hpp_rp_margin + $hpp_rp_angkut);
            } else if ($is_pkp->pkp == '0') {
                $hpp_rp_ppn = 0;
            }

            unset($hpp_inventory);
            $hpp_inventory['no_ref'] = $no_retur;
            $hpp_inventory['type'] = '2';
            $hpp_inventory['qty_in'] = 0;
            $hpp_inventory['qty_out'] = $obj->qty;
            $hpp_inventory['qty_stok'] = $stok - (int) $obj->qty;
            $hpp_inventory['hrg_beli_satuan'] = $detail_ret['dpp'];
            $hpp_inventory['rp_cogs'] = $hpp_cogs;
            $hpp_inventory['rp_nilai_stok'] = $rp_nilai_stok;
            $hpp_inventory['rp_angkut'] = $hpp_rp_angkut;
            $hpp_inventory['pct_margin'] = $hpp_result->pct_margin;
            $hpp_inventory['rp_margin'] = $hpp_rp_margin;
            $hpp_inventory['pct_ppn'] = $hpp_pct_ppn;
            $hpp_inventory['rp_ppn'] = $hpp_rp_ppn;
            $hpp_inventory['rp_het'] = $hpp_het;
            $hpp_inventory['rp_het_hrg_beli'] = $hpp_het_hrg_beli;
            $hpp_inventory['tanggal'] = date('Y-m-d H:i:s');

            if ($this->pret_model->update_row_hpp('0', $obj->kd_produk, $hpp_inventory)) {
                $no_bukti = "MB" . date('Ym') . '-' . $this->pret_model->get_kode_sequence("MB" . date('Ym'), 5);
                if ($this->pret_model->insert_row_histo($no_bukti)) {
                    $detail_result++;
                }
            }

            if ($this->pret_model->insert_row('purchase.t_retur_purchase_detail', $detail_ret)) {
                if ($this->pret_model->insert_row('inv.t_trx_inventory', $trxinventory)) {
                    if (!$stokexists) {
                        if ($this->pret_model->insert_row('inv.t_brg_inventory', $brg_inventory)) {
                            $detail_result++;
                        }
                    } else {
                        if ($this->pret_model->update_brg_inv($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok, $brg_inventory)) {
                            $detail_result++;
                        }
                    }
                }
            }
        }
        $this->db->trans_complete();


        if ($header_result && $detail_result > 0) {
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_retur/print_form/" . $no_retur) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($no_retur = '') {
        $data = $this->pret_model->get_data_print($no_retur);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianReturPrint.php');
        $pdf = new PembelianReturPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}

?>
