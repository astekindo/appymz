<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approval_harga_penjualan_bazar extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('approval_harga_penjualan_bazar_model', 'ahjb_model');
    }

    public function search_produk_by_no_bukti() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';

        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $data_result = $this->ahjb_model->search_produk_by_no_bukti($no_bukti, $search, $start, $limit);
        $hasil = $data_result['rows'];
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
            $result->disk_amt_bazar5 = $diskon_amt_kons5;
            $result->net_price_jual_bazar = $total_diskon_kons;
            $diskon = 0;
            if ($result->is_bonus_kelipatan == 0) {
                $result->is_bonus_kelipatan = 'Tidak';
            } else {
                $result->is_bonus_kelipatan = 'Ya';
            }

            $result->margin_op = '%';
            $result->margin = $result->pct_margin;

            $diskon = $diskon_member1 + $diskon_member2 + $diskon_member3 + $diskon_member4 + $diskon_amt_member5;

            $results[] = $result;
        }
        echo '{success:true,record:' . $data_result['total'] . ',data:' . json_encode($results) . '}';
    }

    public function get_no_bukti_filter() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->ahjb_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
    }

    public function approval() {
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : '';

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $result_prod = 0;
        $result_disk = 0;
        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        }
        $results = null;
        $this->db->trans_begin();
        foreach ($detail as $obj) {
            if ($obj->status == 'Approve') {
                $status = '1';
            } else {
                $status = '9';
            }
            $update_by = $this->session->userdata('username');

            if (!($this->ahjb_model->update_temp($no_bukti, $obj->kd_produk, $status,$tanggal,$update_by))) {
                $this->db->trans_rollback();
                echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                exit;
            }
            if ($obj->status == 'Approve' && $obj->edited == 'Y') {

                $kd_produk = $obj->kd_produk;
                if ($obj->margin_op == '%') {
                    $pct_margin = $obj->margin;
                    $rp_margin = ($obj->margin * $obj->net_hrg_supplier_inc) / 100;
                } else {
                    $rp_margin = $obj->margin;
                    $pct_margin = ($obj->margin * 100) / $obj->net_hrg_supplier_inc;
                }

                //produk
                $koreksi_produk = $obj->koreksi_produk + 1;

                $RpJualBazar = (int) $obj->rp_jual_bazar;
                $NetPJualBazar = (int) $obj->rp_jual_bazar_net;
                $HetBeli = (int) $obj->rp_het_harga_beli;
                $cogs = (int) $obj->rp_cogs;

                if ($cogs > 0) {
                    if ($RpJualBazar < $cogs) {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                    if ($NetPJualBazar < $cogs) {
                        echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET COGS"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                } else {
                    if ($RpJualBazar < $HetBeli) {
                        echo '{"success":false,"errMsg":"Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                    if ($NetPJualBazar < $HetBeli) {
                        echo '{"success":false,"errMsg":"Net Price Jual Konsumen Tidak Boleh Lebih Kecil Dari HET Net Price Beli (Inc. PPN)"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                }

                //diskon
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
                $keterangan = $obj->keterangan;
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
                $diskon_hj['approve_by'] = $created_by;
                $diskon_hj['approve_date'] = $tanggal;
                $diskon_hj['koreksi_produk'] = $koreksi_produk;
                $diskon_hj['rp_jual_bazar_net'] = $obj->rp_jual_bazar_net;
                $diskon_hj['tgl_start_diskon'] = $obj->tgl_start_diskon;
                $diskon_hj['tgl_end_diskon'] = $obj->tgl_end_diskon;
                $diskon_hj['is_validasi'] = $obj->is_validasi;

                if ($this->ahjb_model->update_temp_dist($kd_produk, $no_bukti, $diskon_hj)) {
                    $results = 'success';
                } else {
                    $this->db->trans_rollback();
                    echo '{"success":false,"errMsg":"update_temp Failed . . ."}';
                    exit;
                }
            }
            $results = 'success';
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

        $result = $this->ahjb_model->get_data_temp($no_bukti);


        $this->db->trans_begin();
        foreach ($result as $data) {
            $tgl_approve = $tanggal;
            $approve_by = $this->session->userdata('username');
            //$data['hrg_beli_sup'] = $data['net_hrg_supplier_sup_inc'];
            $status_approve = $data['status_approval'];


            $kd_diskon_sales = $data['kd_diskon_sales'];
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            if ($status_approve == '1') {

                $data['created_by'] = $updated_by;
                $data['created_date'] = $updated_date;
                $data['approve_by'] = $approve_by;
                $data['approve_date'] = $tgl_approve;
                $data['status_approval'] = $status_approve;
                if ($this->ahjb_model->insert_rows_diskon($data)) {
                    $result_disk++;
                } else {
                    echo '{"success":false,"errMsg":"Insert Diskon Failed"}';
                    $this->db->trans_rollback();
                    exit;
                }

            } else {
                $results = 'success';
            }
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
