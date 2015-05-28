<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class create_request_bonus_controller extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_create_request_model', 'pcr_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        //$no_ro = 'PR' . date('Ymd') . '-';
        //$sequence = $this->pcr_model->get_kode_sequence($no_ro, 3);
        echo '{"success":true,
				"data":{
					"no_ro":"",
                                        "user_peruntukan":"' . $this->session->userdata('user_peruntukan') . '",
					"date_tanggal_crb":"' . date('d-m-Y') . '"
				}
			}';
    }

    function record_sort($records, $field, $reverse = false) {
        $hash = array();

        foreach ($records as $record) {
            $hash[$record[$field]] = $record;
        }

        ($reverse) ? krsort($hash) : ksort($hash);

        $records = array();

        foreach ($hash as $record) {
            $records [] = $record;
        }

        return $records;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $tgl_ro = isset($_POST['date_tanggal_crb']) ? $this->db->escape_str($this->input->post('date_tanggal_crb', TRUE)) : FALSE;
        $current_date = date('Ymd', strtotime($tgl_ro));
//                $no_ret = 'PR' . $current_date . '-';
//                $sequence = $this->pcr_model->get_kode_sequence($no_ret, 3);
//                $no_ro = $no_ret . $sequence;
        //$no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : FALSE;
        $subject = isset($_POST['txt_subject_crb']) ? $this->db->escape_str($this->input->post('txt_subject_crb', TRUE)) : FALSE;
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan', TRUE)) : FALSE;
        $header_result = FALSE;
        $detail_result = 0;
        unset($search_array);

        if (count($detail) > 0) {

            if ($tgl_ro) {
                $tgl_ro = date('Y-m-d', strtotime($tgl_ro));
            }
            $this->db->trans_begin();

            $top_temp = -1;
            $no_ro_success = '';

            foreach ($detail as $obj) {
                unset($detail_pr);
                if ($obj->kd_produk != '' && $obj->qty != '') { //yg diinsert di detail ga boleh kosong
                    $detail_pr['kd_produk'] = $obj->kd_produk;
                    $detail_pr['qty'] = $obj->qty;
                    $detail_pr['qty_adj'] = $obj->qty;
                    $detail_pr['status'] = '0';
                    $detail_pr['qty_po'] = 0;
                    $detail_pr['created_by'] = $this->session->userdata('username');
                    $detail_pr['created_date'] = date('Y-m-d H:i:s');
                    $detail_pr['updated_by'] = $this->session->userdata('username');
                    $detail_pr['updated_date'] = date('Y-m-d H:i:s');
                    if ($obj->qty + $obj->jml_stock_pos_so > $obj->max_stock) {
                        echo '{"success":false,"errMsg":"Qty Order + Jml Stok tidak boleh lebih besar dari Max. Stok"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                    if ($obj->qty + $obj->jml_stock_pos_so < $obj->min_stock) {
                        echo '{"success":false,"errMsg":"Qty Order + Jml Stok tidak boleh lebih kecil dari Min. Stok"}';
                        $this->db->trans_rollback();
                        exit;
                    }

                    if ($obj->kelipatan_order == 'YA') {
                        if ($obj->qty < $obj->min_order) {
                            echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                        if (($obj->qty % $obj->min_order) != 0) {
                            echo '{"success":false,"errMsg":"Qty Order harus kelipatan dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                    } else {
                        if ($obj->qty < $obj->min_order) {
                            echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                    }

                    if ($obj->waktu_top != $top_temp) {

                        if (array_key_exists($obj->waktu_top, $search_array)) {
                            $no_ro = $search_array[$obj->waktu_top];
                        } else {

                            $no_ret = 'RX' . $current_date . '-';
                            $sequence = $this->pcr_model->get_kode_sequence($no_ret, 3);
                            $no_ro = $no_ret . $sequence;

                            $header_pr['no_ro'] = $no_ro;
                            $header_pr['subject'] = $subject;
                            $header_pr['status'] = '0';
                            $header_pr['tgl_ro'] = $tgl_ro;
                            $header_pr['close_ro'] = 0;
                            $header_pr['kd_supplier'] = $kd_supplier;
                            $header_pr['konsinyasi'] = 0;
                            $header_pr['created_by'] = $this->session->userdata('username');
                            $header_pr['created_date'] = date('Y-m-d H:i:s');
                            $header_pr['updated_by'] = $this->session->userdata('username');
                            $header_pr['updated_date'] = date('Y-m-d H:i:s');
                            $header_pr['waktu_top'] = $obj->waktu_top;
                            $header_pr['kd_peruntukan'] = $kd_peruntukan;

                            $header_result = $this->pcr_model->insert_row('purchase.t_purchase_request', $header_pr);
//                                                        var_dump($header_result); exit;

                            $search_array[$obj->waktu_top] = $no_ro;
                            $no_ro_success = $no_ro_success . ' , ' . $no_ro;
                        }
                        $top_temp = $obj->waktu_top;
                    }

                    $detail_pr['no_ro'] = $no_ro;
                    if ($this->pcr_model->insert_row('purchase.t_dtl_purchase_request', $detail_pr)) {
                        $detail_result++;
                    }
                }
            }
            $this->db->trans_commit();
            unset($search_array);
        }

        if ($header_result && $detail_result > 0) {
            $result = '{"success":true,"errMsg":"Pembuatan PR berhasil, Listing PR : ' . $no_ro_success . '" ,"printUrl":"' . site_url("pembelian_create_request/print_form/" . $no_ro) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed..' . $header_result . '-' . $detail_result . '"}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_produk($search_by = "") {
        $keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : "";
        $result = $this->pcr_model->get_all_produk($search_by, $keyword);

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
        $result = $this->pcr_model->get_row_produk($search_by, $id);

        echo '{success:true,data:' . json_encode($result) . '}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pcr_model->search_supplier($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk_by_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action', TRUE)) : '';
        $sender = isset($_POST['sender']) ? $this->db->escape_str($this->input->post('sender', TRUE)) : '';
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan', TRUE)) : '';

        $results = $this->pcr_model->search_produk_by_supplier($kd_supplier, $sender, $search, $start, $limit);

        $result = '{"success":true,"data":' . json_encode($results) . '}';

        if ($action == 'validate') {
            $validate = $this->pcr_model->validate_pr_by_kd_produk($kd_produk, $kd_peruntukan);

            if ($validate['pr']->sum != 0) {
                $result = '{"success":false,"errMsg":"Ada Outstanding PR dengan Kode Produk ' . $kd_produk . ' sebanyak ' . $validate['pr']->sum . '"}';
            }
//			if($validate['peruntukan']->harga_jual == '' or $validate['peruntukan']->harga_jual == 0){
//				$result = '{"success":false,"errMsg":"Harga Jual Untuk Kode Produk '.$kd_produk.' masih kosong"}';				
//			}
        } else if ($action == 'validate_po') {
            $validate = $this->pcr_model->validate_pr_on_po($kd_produk, $kd_peruntukan);

            if ($validate['po']->sum != 0) {
                $result = '{"success":true,"errMsg":"Ada Outstanding PO dengan Kode Produk ' . $kd_produk . ' sebanyak ' . $validate['po']->sum . '"}';
            }
        }

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($no_ro = '') {

        $this->pcr_model->setCetakKe($no_ro);

        $data = $this->pcr_model->get_data_print($no_ro);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianCreateRequestPrint.php');
        $pdf = new PembelianCreateRequestPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}
