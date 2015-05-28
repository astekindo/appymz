<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_create_po_bonus extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_create_po_bonus_model', 'pcpb_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        // $no_po = 'PB' . date('Ymd') . '-';
        // $sequence = $this->pcpb_model->get_kode_sequence($no_po, 3);

        echo '{"success":true,
				"data":{
					"no_po":"",
					"order_by":"' . $this->session->userdata('username') . '",
					"tanggal_po":"' . date('d-m-Y') . '",
					"tgl_berlaku_po":"' . date('d-m-Y', strtotime('+30 day')) . '"
				}
			}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : '';
        $tanggal_po = isset($_POST['tanggal_po']) ? $this->db->escape_str($this->input->post('tanggal_po', TRUE)) : '';
        $order_by_po = isset($_POST['order_by']) ? $this->db->escape_str($this->input->post('order_by', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $alamat_kirim_po = isset($_POST['alamat_penerima']) ? $this->db->escape_str($this->input->post('alamat_penerima', TRUE)) : '';
        $kirim_po = isset($_POST['pic_penerima']) ? $this->db->escape_str($this->input->post('pic_penerima', TRUE)) : '';
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan', TRUE)) : '';
        $top = isset($_POST['waktu_top']) ? $this->db->escape_str($this->input->post('waktu_top', TRUE)) : '';
        $tgl_berlaku_po = isset($_POST['tgl_berlaku_po']) ? $this->db->escape_str($this->input->post('tgl_berlaku_po', TRUE)) : '';

        $dp = isset($_POST['_dp']) ? $this->db->escape_str($this->input->post('_dp', TRUE)) : 0;
        $remark = isset($_POST['remark']) ? $this->db->escape_str($this->input->post('remark', TRUE)) : FALSE;

        $jumlah = isset($_POST['_jumlah']) ? $this->db->escape_str($this->input->post('_jumlah', TRUE)) : 0;
        $diskon_rp = isset($_POST['_diskon_rp']) ? $this->db->escape_str($this->input->post('_diskon_rp', TRUE)) : 0;
        $ppn_persen = isset($_POST['_ppn_persen']) ? $this->db->escape_str($this->input->post('_ppn_persen', TRUE)) : 0;
        $ppn_rp = isset($_POST['_ppn_rp']) ? $this->db->escape_str($this->input->post('_ppn_rp', TRUE)) : 0;
        $total = isset($_POST['_total']) ? $this->db->escape_str($this->input->post('_total', TRUE)) : 0;


        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if (count($detail) == 0) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tanggal_po) {
            $tanggal_po = date('Y-m-d', strtotime($tanggal_po));
        }

        if ($tgl_berlaku_po) {
            $tgl_berlaku_po = date('Y-m-d', strtotime($tgl_berlaku_po));
        }

        $this->db->trans_begin();

        $no_po = 'PB' . date('Ymd') . '-';
        $sequence = $this->pcpb_model->get_kode_sequence($no_po, 3);
        $no_po = $no_po . $sequence;

        $masa_berlaku = (strtotime($tgl_berlaku_po) - strtotime(date('Y-m-d'))) / 86400;

        $header_po['no_po'] = $no_po;
        $header_po['tanggal_po'] = $tanggal_po;
        $header_po['kd_suplier_po'] = $kd_supplier;
        $header_po['masa_berlaku_po'] = $masa_berlaku;
        $header_po['rp_jumlah_po'] = (int) $jumlah;
        $header_po['ppn_percent_po'] = (int) $ppn_persen;
        $header_po['rp_ppn_po'] = (int) $ppn_rp;
        $header_po['order_by_po'] = $order_by_po;
        $header_po['rp_total_po'] = (int) $total;
        $header_po['created_by'] = $this->session->userdata('username');
        $header_po['created_date'] = date('Y-m-d H:i:s');
        $header_po['updated_by'] = $this->session->userdata('username');
        $header_po['updated_date'] = date('Y-m-d H:i:s');
        $header_po['kirim_po'] = $kirim_po;
        $header_po['alamat_kirim_po'] = $alamat_kirim_po;
        $header_po['remark'] = $remark;
        $header_po['rp_diskon_po'] = (int) $diskon_rp;
        $header_po['no_ro'] = '';
        $header_po['top'] = $top;
        $header_po['tgl_berlaku_po'] = $tgl_berlaku_po;
        $header_po['is_bonus'] = '1';

        $header_result = $this->pcpb_model->insert_row('purchase.t_purchase', $header_po);

        foreach ($detail as $obj) {
            unset($detail_pr);

            $detail_pr['no_po'] = $no_po;
            $detail_pr['kd_produk'] = $obj->kd_produk;
            $detail_pr['qty_po'] = (int) $obj->qty;
            $detail_pr['disk_persen_supp1_po'] = $obj->disk_persen_supp1_po;
            $detail_pr['disk_persen_supp2_po'] = $obj->disk_persen_supp2_po;
            $detail_pr['disk_persen_supp3_po'] = $obj->disk_persen_supp3_po;
            $detail_pr['disk_persen_supp4_po'] = $obj->disk_persen_supp4_po;
            $detail_pr['disk_amt_supp1_po'] = (int) $obj->disk_amt_supp1_po;
            $detail_pr['disk_amt_supp2_po'] = (int) $obj->disk_amt_supp2_po;
            $detail_pr['disk_amt_supp3_po'] = (int) $obj->disk_amt_supp3_po;
            $detail_pr['disk_amt_supp4_po'] = (int) $obj->disk_amt_supp4_po;
            $detail_pr['disk_amt_supp5_po'] = (int) $obj->disk_amt_supp5_po;
            $detail_pr['price_supp_po'] = (int) $obj->hrg_supplier;
            $detail_pr['net_price_po'] = (int) $obj->harga;
            $detail_pr['dpp_po'] = (int) $obj->dpp_po;
            $detail_pr['rp_disk_po'] = $obj->total_diskon;
            $detail_pr['rp_total_po'] = (int) $obj->jumlah;
            $detail_pr['po_created_by'] = $this->session->userdata('username');
            $detail_pr['po_created_date'] = date('Y-m-d H:i:s');
            $detail_pr['po_updated_by'] = $this->session->userdata('username');
            $detail_pr['po_updated_date'] = date('Y-m-d H:i:s');

            if ($this->pcpb_model->insert_row('purchase.t_purchase_detail', $detail_pr)) {
                $detail_result++;
            }
        }

        if ($header_result && $detail_result > 0) {
            $this->db->trans_commit();
            $result = '{"success":true,"errMsg":"Pembuatan PO Bonus berhasil, NO PB: ' . $no_po . ' ","printUrl":"' . site_url("pembelian_create_po_bonus/print_form/" . $no_po . $sequence) . '"}';
        } else {
            $this->db->trans_rollback();
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk_by_supplier() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $waktu_top = isset($_POST['waktu_top']) ? $this->db->escape_str($this->input->post('waktu_top', TRUE)) : '';
        $pkp = isset($_POST['pkp']) ? $this->db->escape_str($this->input->post('pkp', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pcpb_model->search_produk_by_supplier($kd_supplier, $waktu_top, $search);

        foreach ($result as $obj) {
            $obj->disk_persen_supp1_po = $obj->disk_persen_supp1;
            $obj->disk_persen_supp2_po = $obj->disk_persen_supp2;
            $obj->disk_persen_supp3_po = $obj->disk_persen_supp3;
            $obj->disk_persen_supp4_po = $obj->disk_persen_supp4;

            $obj->disk_amt_supp1_po = $obj->disk_amt_supp1;
            $obj->disk_amt_supp2_po = $obj->disk_amt_supp2;
            $obj->disk_amt_supp3_po = $obj->disk_amt_supp3;
            $obj->disk_amt_supp4_po = $obj->disk_amt_supp4;
            $obj->disk_amt_supp5_po = $obj->disk_amt_supp5;

            //hitung diskon
            $diskon = 0;

            if ($obj->disk_persen_supp1 != '' || $obj->disk_persen_supp1 != 0) {
                $diskon_supp1 = ($obj->disk_persen_supp1 * $obj->hrg_supplier) / 100;
                $diskon_supp1_view = $obj->disk_persen_supp1 . " %";
            } else {
                if ($obj->disk_amt_supp1 != '' || $obj->disk_amt_supp1 != 0) {
                    $diskon_supp1 = $obj->disk_amt_supp1;
                    $diskon_supp1_view = "Rp " . $obj->disk_amt_supp1;
                } else {
                    $diskon_supp1 = 0;
                    $diskon_supp1_view = "0 %";
                }
            }

            if ($obj->disk_persen_supp2 != '' || $obj->disk_persen_supp2 != 0) {
                $diskon_supp2 = ($obj->disk_persen_supp2 * ($obj->hrg_supplier - $diskon_supp1)) / 100;
                $diskon_supp2_view = $obj->disk_persen_supp2 . " %";
            } else {
                if ($obj->disk_amt_supp2 != '' || $obj->disk_amt_supp2 != 0) {
                    $diskon_supp2 = $obj->disk_amt_supp2;
                    $diskon_supp2_view = "Rp " . $obj->disk_amt_supp2;
                } else {
                    $diskon_supp2 = 0;
                    $diskon_supp2_view = "0 %";
                }
            }

            if ($obj->disk_persen_supp3 != '' || $obj->disk_persen_supp3 != 0) {
                $diskon_supp3 = ($obj->disk_persen_supp3 * ($obj->hrg_supplier - $diskon_supp1 - $diskon_supp2)) / 100;
                $diskon_supp3_view = $obj->disk_persen_supp3 . " %";
            } else {
                if ($obj->disk_amt_supp3 != '' || $obj->disk_amt_supp3 != 0) {
                    $diskon_supp3 = $obj->disk_amt_supp3;
                    $diskon_supp3_view = "Rp " . $obj->disk_amt_supp3;
                } else {
                    $diskon_supp3 = 0;
                    $diskon_supp3_view = "0 %";
                }
            }

            if ($obj->disk_persen_supp4 != '' || $obj->disk_persen_supp4 != 0) {
                $diskon_supp4 = ($obj->disk_persen_supp4 * ($obj->hrg_supplier - $diskon_supp1 - $diskon_supp2 - $diskon_supp3)) / 100;
                $diskon_supp4_view = $obj->disk_persen_supp4 . " %";
            } else {
                if ($obj->disk_amt_supp4 != '' || $obj->disk_amt_supp4 != 0) {
                    $diskon_supp4 = $obj->disk_amt_supp4;
                    $diskon_supp4_view = "Rp " . $obj->disk_amt_supp4;
                } else {
                    $diskon_supp4 = 0;
                    $diskon_supp4_view = "0 %";
                }
            }

            $diskon_supp5 = $obj->disk_amt_supp5;
            $diskon_supp5_view = "Rp " . $obj->disk_amt_supp5;

            $diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_supp5;

            //diskon Rp
            $obj->disk_persen_supp1 = $diskon_supp1_view;
            $obj->disk_persen_supp2 = $diskon_supp2_view;
            $obj->disk_persen_supp3 = $diskon_supp3_view;
            $obj->disk_persen_supp4 = $diskon_supp4_view;
            $obj->disk_persen_supp5 = $diskon_supp5_view;

            $obj->total_diskon = $diskon;

            //hitung harga
            $obj->harga = $obj->hrg_supplier - $diskon;

            if ($pkp == 1) {
                $obj->dpp_po = $obj->harga / 1.1;
            } else {
                $obj->dpp_po = $obj->harga;
            }

            $obj->jumlah = 0;
            $obj->qty = 0;
        }

        echo '{success:true,record:' . count($result) . ',data:' . json_encode($result) . '}';
    }

    public function search_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pcpb_model->search_supplier($search, $start, $limit);

        echo $result;
    }

    public function print_form($no_po = '') {
        $data = $this->pcpb_model->get_data_print($no_po);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianCreatePOPrint.php');
        $pdf = new PembelianCreatePOPrint(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_nilai_parameter_pic() {
        $result = $this->pcpb_model->get_nilai_parameter(PIC_PENERIMA_PO);
        return $result;
    }

    public function get_nilai_parameter_alamat() {
        $result = $this->pcpb_model->get_nilai_parameter(ALAMAT_PENERIMA_PO);
        return $result;
    }

    public function get_nilai_parameter_remark() {
        $result = $this->pcpb_model->get_nilai_parameter(REMARK_PO);
        return $result;
    }

}