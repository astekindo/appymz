<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjualan_do
 *
 * @author faroq
 */
class Penjualan_do extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_do_model', 'pdo_model');
    }

    //put your code here
    public function get_form() {
        $no_do = 'DO' . date('Ymd') . '-';
        $sequence = $this->pdo_model->get_kode_sequence($no_do, 3);
        echo '{"success":true,
				"data":{
					"no_do":"' . $no_do . $sequence . '",
					"tgl_do":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function search_faktur() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pdo_model->get_nofaktur($search, $start, $limit);


        echo $result;
    } 

    public function search_produk_nofaktur() {
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pdo_model->get_so_detail($no_so, $search);
        

        echo $result;
    }

    public function update_row() {
        $tanggal = isset($_POST['tgl_do']) ? $this->db->escape_str($this->input->post('tgl_do', TRUE)) : false;
        $current_date = date('Ymd', strtotime($tanggal));
        $no_do = 'DO' . $current_date . '-';
        $sequence = $this->pdo_model->get_kode_sequence($no_do, 3);
        $no_do = $no_do . $sequence;
        
        $header_do['no_do'] = $no_do;
        $header_do['tanggal'] = isset($_POST['tgl_do']) ? $this->db->escape_str($this->input->post('tgl_do', TRUE)) : false;
        $header_do['tanggal_kirim'] = isset($_POST['tgl_do_kirim']) ? $this->db->escape_str($this->input->post('tgl_do_kirim', TRUE)) : false;
        $header_do['no_so'] = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : FALSE;
        $header_do['tanggal_so'] = isset($_POST['tgl_faktur']) ? $this->db->escape_str($this->input->post('tgl_faktur', TRUE)) : FALSE;
        $header_do['status'] = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : '0';
        $header_do['created_by'] = $this->session->userdata('username');
        $header_do['created_date'] = date('Y-m-d H:i:s');
        $header_do['updated_by'] = $this->session->userdata('username');
        $header_do['updated_date'] = date('Y-m-d H:i:s');
        $header_do['pic_penerima'] = isset($_POST['pic_terima']) ? $this->db->escape_str($this->input->post('pic_terima', TRUE)) : '';
        $header_do['alamat_penerima'] = isset($_POST['alm_penerima']) ? $this->db->escape_str($this->input->post('alm_penerima', TRUE)) : '';
        $header_do['no_telp_penerima'] = isset($_POST['telp_terima']) ? $this->db->escape_str($this->input->post('telp_terima', TRUE)) : '';
        $header_do['keterangan'] = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : '';
        $nodo = $header_do['no_do'];
        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();

        $header_result = FALSE;
        $detail_result = 0;

        if (count($data_in) > 0) {
            if ($header_do['tanggal']) {
                $header_do['tanggal'] = date('Y-m-d', strtotime($header_do['tanggal']));
            }
            if ($header_do['tanggal_kirim']) {
                $header_do['tanggal_kirim'] = date('Y-m-d', strtotime($header_do['tanggal_kirim']));
            }
            if ($header_do['tanggal_so']) {
                $header_do['tanggal_so'] = date('Y-m-d', strtotime($header_do['tanggal_so']));
            }

            $this->db->trans_start();
            $header_result = $this->pdo_model->insert_row('sales.t_sales_delivery_order', $header_do);
            foreach ($data_in as $obj) {
//				$kd_lokasi = substr($obj->sub, 0, 2);
//				$kd_blok = substr($obj->sub, 2, 2);
//				$kd_sub_blok = substr($obj->sub, 4, 2);
                unset($detail_do);
                $detail_do['no_do'] = $nodo;
                $detail_do['kd_barang'] = $obj->kd_produk;
                $detail_do['qty'] = $obj->qty;
//                                $detail_do['kd_lokasi']=$kd_lokasi;
//                                $detail_do['kd_blok']=$kd_blok;
//                                $detail_do['kd_sub_blok']=$kd_sub_blok;
                $detail_result = $this->pdo_model->insert_row('sales.t_sales_delivery_order_detail', $detail_do);
                if ($detail_result > 0 && ($obj->qty_so === ($obj->qty_do + $obj->qty))) {
                    unset($updateSO);
                    $updateSO['is_do'] = '1';
                    $detail_result = $this->pdo_model->update_SO($header_do['no_so'], $obj->kd_produk, $updateSO);
                }
            }
            $this->db->trans_complete();
        }

        $title = 'DELIVERY ORDER';
        if ($header_result && $detail_result > 0) {
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("penjualan_do/print_form/" . $header_do['no_do']) . '"}';
            //$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_receive_order/print_form/" . $no_do . "/" . $title) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.' . count($data_in) . '."}';
        }
        echo $result;
    }
    
    
    public function print_form($no_do = '') {
//		$this->psj_model->setCetakKe($nno_sj);
        $data = $this->pdo_model->get_data_print($no_do);
        //var_dump($data);exit;
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PenjualanDOPrint.php');
        $pdf = new PenjualanDOprint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}

?>
