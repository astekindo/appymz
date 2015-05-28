<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_retur extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_retur_model');
        $this->load->model('pembelian_retur_model', 'pret_model');
        $this->load->model('pembelian_receive_order_model', 'pro_model');
    }

    //RBYYYYMM-001
    public function get_form() {
        $no_ret = 'RJ' . date('Ym') . '-';
        $sequence = $this->penjualan_retur_model->get_kode_sequence($no_ret, 3);
        echo '{"success":true,
				"data":{
					"no_retur":"' . $no_ret . $sequence . '",
					"tgl_retur":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function search_salesorder() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->penjualan_retur_model->search_salesorder($search, $start, $limit);


        echo $result;
    }

    public function search_produk_by_salesorder($no_so = '') {
        $detail = $this->penjualan_retur_model->search_produk_by_salesorder($no_so);
        echo '{success:true,data:' . json_encode($detail) . '}';
    }

    public function update_row() {
        $no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : FALSE;
        $tgl_retur = isset($_POST['tgl_retur']) ? $this->db->escape_str($this->input->post('tgl_retur', TRUE)) : FALSE;
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : FALSE;
        $remark = isset($_POST['_remark']) ? $this->db->escape_str($this->input->post('_remark', TRUE)) : FALSE;
        $rp_diskon = isset($_POST['_diskon_returjual']) ? $this->db->escape_str($this->input->post('_diskon_returjual', TRUE)) : FALSE;
        $rp_total = isset($_POST['_jumlah_returjual']) ? $this->db->escape_str($this->input->post('_jumlah_returjual', TRUE)) : FALSE;
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : FALSE;
        $kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok', TRUE)) : FALSE;
        $diskon_ekstra = isset($_POST['_diskon_ekstra_returjual']) ? $this->db->escape_str($this->input->post('_diskon_ekstra_returjual', TRUE)) : FALSE;
        $grand_total = isset($_POST['_grandtotal_returjual']) ? $this->db->escape_str($this->input->post('_grandtotal_returjual', TRUE)) : FALSE;

        $status = '1';
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d');
        // $is_konsinyasi = isset($_POST['pis_konsinyasi']) ? $this->db->escape_str($this->input->post('pis_konsinyasi', TRUE)) : FALSE;

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;
        if ($tgl_retur) {
            $tgl_retur = date('Y-m-d', strtotime($tgl_retur));
        }

        if (count($detail) == 0) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
        }

        $this->db->trans_begin();


        $header_ret['no_retur'] = $no_retur;
        $header_ret['tgl_retur'] = $tgl_retur;
        $header_ret['no_so'] = $no_so;
        $header_ret['pct_potongan'] = '0';
        $header_ret['rp_jumlah'] = $rp_total;
        $header_ret['rp_diskon'] = $rp_diskon;
        $header_ret['rp_potongan'] = $diskon_ekstra;
        $header_ret['rp_total'] = $rp_total - $rp_diskon - $diskon_ekstra;
        $header_ret['remark'] = $remark;
        $header_ret['kd_lokasi'] = $kd_lokasi;
        $header_ret['kd_blok'] = $kd_blok;
        $header_ret['kd_sub_blok'] = $kd_sub_blok;
        $header_ret['status'] = $status;
        $header_ret['created_by'] = $created_by;
        $header_ret['created_date'] = $created_date;
//        $header_ret['updated_by'] = $updated_by;
//        $header_ret['updated_date'] = $updated_date;
        $header_ret['no_so_retur'] = '';



        $header_result = $this->penjualan_retur_model->insert_row('sales.t_retur_sales', $header_ret);

        foreach ($detail as $obj) {
            unset($detail_pr);
            $detail_pr['kd_produk'] = $obj->kd_produk;
            $detail_pr['no_retur'] = $no_retur;
            $detail_pr['qty'] = (int) $obj->qty;
            $detail_pr['rp_disk'] = (int) $obj->rp_diskon;
            $detail_pr['rp_jumlah'] = (int) $obj->rp_harga;
            $detail_pr['rp_potongan'] = $diskon_ekstra;
            $detail_pr['rp_total'] = (int) $obj->rp_total;
            $detail_pr['approval'] = 1;
            $detail_pr['created_by'] = $this->session->userdata('username');
            $detail_pr['created_date'] = date('Y-m-d H:i:s');

            $detailresult = $this->penjualan_retur_model->insert_row('sales.t_retur_sales_detail', $detail_pr);
            if ($detailresult) {
                $detail_result++;
            }
        }
        
        
        
        

        $this->db->trans_commit();
        if ($header_result && $detailresult) {
            $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("penjualan_retur/print_form/" . $header_ret['no_retur']) . '"}';
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
        $data = $this->penjualan_retur_model->get_data_print($no_retur);
        if (!$data)
            show_404('page');

        // print_r($data['header']);
        //print_r($data['detail']);exit;
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/ReturPenjualanPrint.php');
        $pdf = new ReturPenjualanPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}

?>
