<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_harga_penjualan_distribusi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('approval_harga_penjualan_distribusi_model', 'ahjd_model');
    }

    public function search_produk_by_no_bukti() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';

        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $data_result = $this->ahjd_model->search_produk_by_no_bukti($no_bukti, $search, $start, $limit);
        $hasil = $data_result['rows'];
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
            $result->disk_amt_toko5 = $diskon_amt_kons5;
            $result->net_price_jual_kons = round($total_diskon_kons);
            $result->rp_jual_toko_net = round($result->rp_jual_toko_net);
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

            if ($result->is_agen_kelipatan == 0) {
                $result->is_agen_kelipatan = 'Tidak';
            } else {
                $result->is_agen_kelipatan = 'Ya';
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
            $result->net_price_jual_agen = round($total_diskon_memb);
            $result->rp_jual_agen_net = round($result->rp_jual_agen_net);

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
            $result->net_price_jual_modern_market = empty($total_diskon_modern_market) ? 0 : round($total_diskon_modern_market);
            $result->rp_jual_modern_market_net = empty($total_diskon_modern_market) ? 0 : round($total_diskon_modern_market);

                $results[] = $result;
        }
        echo '{success:true,record:' . $data_result['total'] . ',data:' . json_encode($results) . '}';
    }

    public function get_no_bukti_filter() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->ahjd_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
    }

    public function approval() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : '';

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $result_prod = 0;
        $result_disk = 0;

        $this->db->trans_begin();
        foreach ($detail as $obj) {
            $results = 'success';
            if ($obj->status == 'Approve') {
                $status = '1';
            } else {
                $status = '9';
            }
            $approve_by = $this->session->userdata('username');
            $approve_date = date('Y-m-d H:i:s');

            if (!($this->ahjd_model->update_temp($no_bukti, $obj->kd_produk, $status, $approve_by, $approve_date))) {
                $this->db->trans_rollback();
                echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                exit;
            }
            if ($obj->status == 'Approve') {
                $kd_produk = $obj->kd_produk;
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
                $NetPJualKons = (int) $obj->rp_jual_toko_net;
                $NetPJualMemb = (int) $obj->rp_jual_agen_net;
                $NetPJualModernMarket = (int) $obj->rp_jual_modern_market_net;
                $HetBeli = (int) $obj->rp_het_harga_beli;
                $cogs = (int) $obj->rp_cogs;
                $tgl_start_diskon = $obj->tgl_start_diskon;
                $tgl_end_diskon = $obj->tgl_end_diskon;
                $tgl_now = date("Y-m-d");

                if ($tgl_end_diskon < $tgl_now ) {
                    echo '{"success":false,"errMsg":"Tanggal Awal dan Tanggal Akhir Diskon Tidak Boleh Lebih Kecil Dari Tanggal Hari ini "}';
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
                    if ($NetPJualKons < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualKons < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)'.$NetPJualKons.'"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                if ($cogs > 0) {
                    if ($NetPJualMemb < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($NetPJualMemb < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Member Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
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

                //diskon
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
                $disk_agen_op = $obj->disk_agen4_op;

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
                $kd_produk_agen = $obj->kd_produk_member;
                $qty_agen = $obj->qty_agen;
                $is_agen_kelipatan = $obj->is_member_kelipatan;
                $qty_beli_modern_market = $obj->qty_beli_modern_market;
                $kd_produk_modern_market = $obj->kd_produk_modern_market;
                $qty_modern_market = $obj->qty_modern_market;
                $is_modern_market_kelipatan = $obj->is_modern_market_kelipatan;

                $is_agen_kelipatan = isset($is_agen_kelipatan) ? $is_agen_kelipatan : 0;
                $is_bonus_kelipatan = isset($is_bonus_kelipatan) ? $is_bonus_kelipatan : 0;
                $is_modern_market_kelipatan = isset($is_modern_market_kelipatan) ? $is_modern_market_kelipatan : 0;
                $kd_produk_bonus = isset($kd_produk_bonus) ? $kd_produk_bonus : '';
                $kd_produk_member = isset($kd_produk_member) ? $kd_produk_member : '';
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
                if ($qty_bonus > 0 || $qty_agen > 0 || $qty_modern_market > 0) {
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


            $diskon_hj['rp_ongkos_kirim'] = $obj->rp_ongkos_kirim;
            $diskon_hj['pct_margin'] = $pct_margin;
            $diskon_hj['rp_margin'] = $rp_margin;

            //$diskon_hj['no_bukti'] = $no_bukti;
            $diskon_hj['kd_produk'] = $kd_produk;
            $diskon_hj['kd_diskon_sales'] = $no_bukti;
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
            $diskon_hj['tgl_start_diskon'] = $obj->tgl_start_diskon;
            $diskon_hj['tgl_end_diskon'] = $obj->tgl_end_diskon;

                if ($this->ahjd_model->update_temp_dist($kd_produk, $no_bukti, $diskon_hj)) {
                    $results = 'success';
                } else {
                    $this->db->trans_rollback();
                    echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                    exit;
                }
            }
        }
        if ($results == "success") {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
            echo '{"success":false,"errMsg":"Insert Data Failed . . ."}';
            exit;
        }
        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        }

        $result = $this->ahjd_model->get_data_temp($no_bukti);


        $this->db->trans_begin();
        foreach ($result as $data) {
            //$koreksi_produk = $data['koreksi_produk'];
            $status_approve = $data['status'];
            if ($status_approve == '1') {

                $produk_dist = $this->ahjd_model->select_data_dist($data['kd_produk'], $data['tgl_start_diskon'],$data['tgl_end_diskon']);
                    if ($produk_dist){

                        $data['updated_by'] = $this->session->userdata('username');
                        $data['updated_date'] = date('Y-m-d H:i:s');
                        if ($this->ahjd_model->update_rows_diskon($data['kd_produk'],$data['tgl_start_diskon'],$data['tgl_end_diskon'],$data)) {
                           // if ($this->ahjd_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)) {
                                 $results = 'success';
                            //}
                        } else {
                            echo '{"success":false,"errMsg":"Update Diskon Failed"}';
                            $this->db->trans_rollback();
                            exit;
                        }

                    }else{

                        $data['created_by'] = $this->session->userdata('username');
                        $data['created_date'] = date('Y-m-d H:i:s');

                        if ($this->ahjd_model->insert_rows_diskon($data)) {
                           // if ($this->ahjd_model->insert_rows_diskon_history($data['kd_produk'], $kd_diskon_sales, $data['koreksi_ke'], $no_bukti, $tgl_approve, $approve_by, $status_approve)) {
                                 $results = 'success';
                            //}
                        } else {
                            echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                    }

          }
//          else {
//                $results = 'success';
//            }
        }

        if ($results == 'success') {
            $result = '{"success":true,"errMsg":""}';
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
            $result = '{"success":false,"errMsg":"Process Failed . . ."}';
        }
        echo $result;
    }

}
