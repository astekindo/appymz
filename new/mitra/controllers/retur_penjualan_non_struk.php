<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjualan_sj
 *
 * @author faroq
 */
class Retur_penjualan_non_struk extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('retur_penjualan_non_struk_model', 'rpns_model');
    }
    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->rpns_model->search_produk($search, $start, $limit);

        echo $result;
    }
    public function get_row_produk() {
        $search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by', TRUE)) : "";
        $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
        $qty = isset($_POST['qty']) ? $this->db->escape_str($this->input->post('qty', TRUE)) : '';
        $extra_bonus = isset($_POST['extra_bonus']) ? $this->db->escape_str($this->input->post('extra_bonus', TRUE)) : '';
        //$member = isset($_POST['member']) ? $this->db->escape_str($this->input->post('member',TRUE)) : '';

        $result = $this->rpns_model->get_row_produk($search_by, $id);

        if (count($result) > 0) {
           
            if ($result->disk_persen_kons1 != '' && $result->disk_persen_kons1 != 0) {
                $total_diskon_kons = $result->rp_jual_supermarket - ($result->rp_jual_supermarket * ($result->disk_persen_kons1 / 100));
                $diskon_kons1 = $result->disk_persen_kons1;
                $result->disk_kons1 = "".$diskon_kons1."%";
            } else {
                if ($result->disk_amt_kons1 != '') {
                    $total_diskon_kons = $result->rp_jual_supermarket - $result->disk_amt1;
                    $diskon_kons1 = $result->disk_amt_kons1;
                    $result->disk_kons1 = "Rp".$diskon_kons1;
                } else {
                    $diskon_kons1 = 0;
                }
            }

            if ($result->disk_persen_kons2 != '' && $result->disk_persen_kons2 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons2 / 100));
                $diskon_kons2 = $result->disk_persen_kons2;
                $result->disk_kons2 = "".$diskon_kons2."%";
            } else {
                if ($result->disk_amt_kons2 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons2;
                    $diskon_kons2 = $result->disk_amt_kons2;
                    $result->disk_kons2 = "Rp".$diskon_kons2;
                } else {
                    $diskon_kons2 = 0;
                }
            }

            if ($result->disk_persen_kons3 != '' && $result->disk_persen_kons3 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons3 / 100));
                $diskon_kons3 = $result->disk_persen_kons3;
                $result->disk_kons3 = "".$diskon_kons3."%";
            } else {
                if ($result->disk_amt_kons3 != '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons3;
                    $diskon_kons3 = $result->disk_amt_kons3;
                    $result->disk_kons3 = "Rp".$diskon_kons3;
                } else {
                    $diskon_kons3 = 0;
                }
            }

            if ($result->disk_persen_kons4 != '' && $result->disk_persen_kons4 != 0) {
                $total_diskon_kons = $total_diskon_kons - ($total_diskon_kons * ($result->disk_persen_kons4 / 100));
                $diskon_kons4 = $result->disk_persen_kons4;
                $result->disk_kons4 = "".$diskon_kons3."%";
            } else {
                if ($result->disk_amt_kons4!= '') {
                    $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons4;
                    $diskon_kons4 = $result->disk_amt_kons4;
                    $result->disk_kons4 = "Rp".$diskon_kons4;
                } else {
                    $diskon_kons4 = 0;
                }
            }

            if ($result->disk_amt_kons5 != '') {
                $total_diskon_kons = $total_diskon_kons - $result->disk_amt_kons5;
                $diskon_amt_kons5 = $result->disk_amt_kons5;
                $result->disk_kons5 = "Rp".$diskon_amt_kons5;
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
            
            $result->rp_jual_supermarket = $result->rp_jual_supermarket;
            $result->total_diskon = $result->rp_jual_supermarket - $total_diskon_kons;
            $result->harga_jual_nett = $total_diskon_kons;
            $result->kd_produk_supp = $result->kd_produk_supp;
        }
        echo '{success:true,data:' . json_encode($result) . '}';
    }
    public function update_row() {
        $tgl_retur = isset($_POST['tgl_retur']) ? $this->db->escape_str($this->input->post('tgl_retur', TRUE)) : FALSE;
        $current_date = date('Ym', strtotime($tgl_retur));
        $no_ret = 'RJ' . $current_date . '-';
        $sequence = $this->rpns_model->get_kode_sequence($no_ret, 3);
        $no_retur = $no_ret . $sequence;
        //$no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : FALSE;
        
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : FALSE;
        $remark = isset($_POST['_remark']) ? $this->db->escape_str($this->input->post('_remark', TRUE)) : FALSE;
        $rp_diskon = isset($_POST['_diskon_returjual']) ? $this->db->escape_str($this->input->post('_diskon_returjual', TRUE)) : FALSE;
        $rp_total = isset($_POST['_jumlah_returjual']) ? $this->db->escape_str($this->input->post('_jumlah_returjual', TRUE)) : FALSE;
        //$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $ppn = isset($_POST['ppn']) ? $this->db->escape_str($this->input->post('ppn', TRUE)) : FALSE;
        $dpp = isset($_POST['dpp']) ? $this->db->escape_str($this->input->post('dpp', TRUE)) : FALSE;
        $diskon_ekstra = isset($_POST['_diskon_ekstra_returjual']) ? $this->db->escape_str($this->input->post('_diskon_ekstra_returjual', TRUE)) : FALSE;
        $jumlah_returjual= isset($_POST['_jumlah_returjual']) ? $this->db->escape_str($this->input->post('_jumlah_returjual', TRUE)) : FALSE;
        $grand_total = isset($_POST['_grandtotal_returjual']) ? $this->db->escape_str($this->input->post('_grandtotal_returjual', TRUE)) : FALSE;
        $pct_diskon_tambahan= isset($_POST['_pct_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_pct_diskon_tambahan', TRUE)) : FALSE;
        $rp_diskon_tambahan = isset($_POST['_rp_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_rp_diskon_tambahan', TRUE)) : FALSE;
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
        //$rp_total = $rp_total - $rp_diskon - $diskon_ekstra;

        $header_ret['no_retur'] = $no_retur;
        $header_ret['tgl_retur'] = $tgl_retur;
        $header_ret['no_so'] = $no_so;
        $header_ret['pct_potongan'] = $pct_diskon_tambahan;
        $header_ret['rp_potongan'] = $rp_diskon_tambahan;
        $header_ret['rp_jumlah'] = $jumlah_returjual;
        $header_ret['rp_total'] = $grand_total;
        $header_ret['remark'] = $remark;
        $header_ret['status'] = $status;
        $header_ret['created_by'] = $created_by;
        $header_ret['created_date'] = $created_date;
        $header_ret['no_so_retur'] = '';
        $header_ret['dpp'] = $dpp;
        $header_ret['ppn'] = $ppn;
        //$header_ret['rp_potongan'] = $diskon_ekstra;
       
        $header_result = $this->rpns_model->insert_row('sales.t_retur_sales', $header_ret);

        foreach ($detail as $obj) {
        
            $kd_lokasi = substr($obj->sub, 0, 2);
            $kd_blok = substr($obj->sub, 2, 2);
            $kd_sub_blok = substr($obj->sub, 4, 2);
            $kd_produk = $obj->kd_produk;  
            
                     
            unset($detail_pr);
            $detail_pr['kd_produk'] = $obj->kd_produk;
            $detail_pr['no_retur'] = $no_retur;
            $detail_pr['qty'] = (int) $obj->qty_input;
            $detail_pr['rp_disk'] = (int) $obj->rp_diskon;
            $detail_pr['rp_jumlah'] = (int) $obj->rp_harga;
            $detail_pr['rp_potongan'] = (int) $obj->ekstra_diskon;
            $detail_pr['rp_total'] = (int) $obj->rp_total1;
            $detail_pr['approval'] = 1;
            $detail_pr['kd_lokasi'] = $kd_lokasi;
            $detail_pr['kd_blok'] = $kd_blok;
            $detail_pr['kd_sub_blok'] = $kd_sub_blok;
            $detail_pr['created_by'] = $this->session->userdata('username');
            $detail_pr['created_date'] = date('Y-m-d H:i:s');
            //print_r($detail_pr);
            $detailresult = $this->rpns_model->insert_row('sales.t_retur_sales_detail', $detail_pr);
            if ($detailresult) {
//                $sql = "UPDATE sales.t_sales_order_detail SET qty_retur = qty_retur + " . $obj->qty_input . " WHERE kd_produk = '" . $obj->kd_produk . "' AND no_so = '".$no_so."'";
//		$this->rpns_model->query_update($sql);
                
                $lokasi = $this->rpns_model->search_lokasi($kd_lokasi,$kd_blok,$kd_sub_blok,$kd_produk);
                if (count($lokasi) > 0){
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty_input . " WHERE kd_produk = '" . $obj->kd_produk . "' AND kd_lokasi = '".$kd_lokasi."' AND kd_blok = '".$kd_blok."' AND kd_sub_blok = '".$kd_sub_blok."'";
                        $this->rpns_model->query_update($sql);
                     }
                else { 
                    unset($brg_inventory);
						$brg_inventory['kd_produk'] = $obj->kd_produk;
						$brg_inventory['kd_lokasi'] = $kd_lokasi;
						$brg_inventory['kd_blok'] = $kd_blok;
						$brg_inventory['kd_sub_blok'] = $kd_sub_blok;
						$brg_inventory['qty_oh'] = $obj->qty_input;
						$brg_inventory['created_by'] = $this->session->userdata('username');
						$brg_inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->rpns_model->insert_row("inv.t_brg_inventory",$brg_inventory);
                       
                     }
                $detail_result++;
                
            }
        
      }
        $this->db->trans_commit();
        if ($header_result && $detailresult) {
            $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("retur_penjualan_non_struk/print_form/" . $no_retur) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
   public function print_form($no_retur = '') {
        $data = $this->rpns_model->get_data_print($no_retur);
        if (!$data)
            show_404('page');
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/ReturPenjualanNonStrukPrint.php');
        $pdf = new ReturPenjualanNonStrukPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    } 
}
