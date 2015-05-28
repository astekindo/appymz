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
class Penjualan_sj extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_sj_model', 'psj_model');
    }

    public function get_form() {
        $no_do = 'SJ' . date('Ymd') . '-';
        $sequence = $this->psj_model->get_kode_sequence($no_do, 4);
        echo '{"success":true,
				"data":{
					"no_sj":"' . $no_do . $sequence . '",
					"tgl_sj":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function search_do() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->psj_model->get_nodo($search, $start, $limit);
        echo $result;
    }

    public function search_produk_nodo() {
        $no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->psj_model->get_do_detail($no_do, $search);

        echo $result;
    }

    public function search_ekspedisi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->psj_model->search_ekspedisi($search, $start, $limit);


        echo $result;
    }

    public function update_row() {
        $header_do['no_sj'] = isset($_POST['no_sj']) ? $this->db->escape_str($this->input->post('no_sj', TRUE)) : FALSE;
        $header_do['tanggal'] = isset($_POST['tgl_sj']) ? $this->db->escape_str($this->input->post('tgl_sj', TRUE)) : FALSE;
        $header_do['no_do'] = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do', TRUE)) : FALSE;
        $header_do['kd_ekspedisi'] = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi', TRUE)) : FALSE;
        $header_do['no_kendaraan'] = isset($_POST['no_kendaraan']) ? $this->db->escape_str($this->input->post('no_kendaraan', TRUE)) : FALSE;
        $header_do['sopir'] = isset($_POST['sopir']) ? $this->db->escape_str($this->input->post('sopir', TRUE)) : FALSE;
        $header_do['pic_penerima'] = isset($_POST['pic_terima']) ? $this->db->escape_str($this->input->post('pic_terima', TRUE)) : FALSE;
        $header_do['alamat_penerima'] = isset($_POST['alm_penerima']) ? $this->db->escape_str($this->input->post('alm_penerima', TRUE)) : FALSE;
        $header_do['no_telp_penerima'] = isset($_POST['telp_terima']) ? $this->db->escape_str($this->input->post('telp_terima', TRUE)) : FALSE;
        $header_do['keterangan'] = isset($_POST['hketerangan']) ? $this->db->escape_str($this->input->post('hketerangan', TRUE)) : FALSE;

        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();

        $header_result = FALSE;
        $detail_result = 0;
        if ($header_do['no_do']) {
            if (count($data_in) > 0) {
                if ($header_do['tanggal']) {
                    $header_do['tanggal'] = date('Y-m-d', strtotime($header_do['tanggal']));
                }
                $this->db->trans_start();
                $header_result = $this->psj_model->insert_row('sales.t_surat_jalan', $header_do);
                foreach ($data_in as $obj) {
                    unset($detail_do);

                    $kd_lokasi = substr($obj->sub, 0, 2);
                    $kd_blok = substr($obj->sub, 2, 2);
                    $kd_sub_blok = substr($obj->sub, 4, 2);
                    $detail_do['kd_lokasi'] = $kd_lokasi;
                    $detail_do['kd_blok'] = $kd_blok;
                    $detail_do['kd_sub_blok'] = $kd_sub_blok;
                    $detail_do['no_sj'] = $header_do['no_sj'];
                    $detail_do['kd_produk'] = $obj->kd_produk;
                    $detail_do['qty'] = $obj->qty;
                    $detail_do['keterangan'] = $obj->keterangan;

                    $detail_result = $this->psj_model->insert_row('sales.t_surat_jalan_detail', $detail_do);
                    $qty_sj_indo = $this->psj_model->getdo_qty_sj($header_do['no_do'], $obj->kd_produk);
                    
                    $qty_sj_indo = $qty_sj_indo + $obj->qty;
                    unset($updateDOdet);
                    $updateDOdet['qty_sj'] = $qty_sj_indo;
                    $detail_result = $this->psj_model->update_do_detail($header_do['no_do'], $obj->kd_produk, $updateDOdet);
                
                    
                    unset($trxinventory);
                    $trxinventory['kd_produk'] = $obj->kd_produk;
                    $trxinventory['no_ref'] = $header_do['no_sj'];
                    $trxinventory['kd_lokasi'] = $kd_lokasi;
                    $trxinventory['kd_blok'] = $kd_blok;
                    $trxinventory['kd_sub_blok'] = $kd_sub_blok;
                    $trxinventory['qty_in'] = 0;
                    $trxinventory['qty_out'] = (int) $obj->qty;
                    $trxinventory['type'] = '7';
                    $trxinventory['created_by'] = $created_by;
                    $trxinventory['created_date'] = $created_date;
                    $trxinventory['tgl_trx'] = $tgl_retur;

                    $stok = 0;
                    $stokexists = FALSE;
                    $rowstok = $this->psj_model->cek_exists_brg_inv_sj($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);

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
                    
                    if ($this->psj_model->insert_row('inv.t_trx_inventory', $trxinventory)) {
                        if (!$stokexists) {
                            if ($this->psj_model->insert_row('inv.t_brg_inventory', $brg_inventory)) {
                                $detail_result++;
                            }
                        } else {
                            if ($this->psj_model->update_brg_inv($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok, $brg_inventory)) {
                                $detail_result++;
                            }
                        }
                    }
                    
                }
                
                
                
                
                
                $this->db->trans_complete();
                if ($header_result && $detail_result > 0) {
                    $Alldo_detail = $this->psj_model->checkdo_qty_qty_sj($header_do['no_do']);
                    if ($Alldo_detail == 0) {
                        $updatedo['updated_by'] = $this->session->userdata('username');
                        $updatedo['updated_date'] = date('Y-m-d H:i:s');
                        $updatedo['status'] = '1';
                        $this->psj_model->update_do($header_do['no_do'], $updatedo);
                    }
                }
            }
        }
        if ($header_result && $detail_result > 0) {
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("penjualan_sj/print_form/" . $header_do['no_sj']) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.' . count($data_in) . '."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($no_sj = '') {
//		$this->psj_model->setCetakKe($nno_sj);

        $data = $this->psj_model->get_data_print($no_sj);
        //var_dump($data); die();
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PenjualanSJPrint.php');
        $pdf = new PenjualanSJPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "SJ", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}

?>
