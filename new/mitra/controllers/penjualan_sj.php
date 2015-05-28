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

    public $test = false;

    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_sj_model', 'psj_model');
    }

    public function get_form() {
        $no_do = 'SJ' . date('Ymd') . '-';
        $sequence = $this->psj_model->get_kode_sequence($no_do, 4);
        echo json_encode(array(
            'success' => true,
            'data' => array(
                'no_sj'     => $no_do . $sequence,
                'tgl_sj'    => date('d-M-Y')
            )
        ));
    }

    public function search_do() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->psj_model->get_nodo($search, 0, $start, $limit),$this->test);
    }

    public function search_do_kembali() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->psj_model->get_nodo($search, 1, $start, $limit),$this->test);
    }

    public function search_sj() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->psj_model->get_nosj($search, $start, $limit),$this->test);
    }

    public function search_sj_kembali() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->psj_model->get_nosj_kembali($search, $start, $limit),$this->test);
    }

    public function search_produk_nodo() {
        $no_do  = $this->form_data('no_do','');
        $search = $this->form_data('query','');

        $result = $this->psj_model->get_do_detail($no_do, $search);

        echo $result;
    }

    public function search_produk_nosj($no_sj = '') {
      if(empty($no_sj) && !empty($_POST['no_sj'])) {
          $no_sj  = $this->form_data('no_sj','');
      }

        $this->print_result_json($this->psj_model->get_sj_detail($no_sj),$this->test);
    }

    public function search_ekspedisi() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $result = $this->psj_model->search_ekspedisi($search, $start, $limit);


        echo $result;
    }

    public function get_lokasi_by_produk() {
        $search     = $this->form_data('query','');
        $kd_produk  = $this->form_data('kd_produk','');
        $kd_lokasi  = $this->form_data('kd_lokasi','');

        $this->print_result_json($this->psj_model->get_lokasi_by_produk($kd_produk, $kd_lokasi, $search),$this->test);
    }

    function get_qty_by_lokasi() {
        $lokasi     = $this->form_data('lokasi','');
        $blok       = $this->form_data('blok','');
        $subblok    = $this->form_data('subblok','');
        $kd_produk  = $this->form_data('kd_produk','');

        $this->print_result_json($this->psj_model->get_qty_by_lokasi($kd_produk, $lokasi, $blok, $subblok),$this->test);
    }

    public function update_row() {
        $result = array(
            'success'   => false,
            'errMsg'    => 'Process Failed. Unknown error'
        );
        header('Content-Type: application/json');

        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d');

        $header_do = array(
          'no_sj'             => $this->form_data('no_sj', false),
          'tanggal'           => $this->form_data('tgl_sj', false),
          'no_do'             => $this->form_data('no_do', false),
          'kd_ekspedisi'      => $this->form_data('kd_ekspedisi', false),
          'no_kendaraan'      => $this->form_data('no_kendaraan', false),
          'sopir'             => $this->form_data('sopir', false),
          'pic_penerima'      => $this->form_data('pic_terima', false),
          'alamat_penerima'   => $this->form_data('alm_penerima', false),
          'no_telp_penerima'  => $this->form_data('telp_terima', false),
          'keterangan'        => $this->form_data('keterangan', false),
          'created_by'        => $created_by,
          'created_date'      => $created_date,
          'is_kembali'        => 0
        );

        $tanggal = $header_do['tanggal'];
        $current_date = date('Ymd', strtotime($tanggal));
        $no_sj = 'SJ' . $current_date . '-';
        $sequence = $this->psj_model->get_kode_sequence($no_sj, 3);
        $no_sj = $no_sj . $sequence;
        $header_do['no_sj'] = $no_sj;

        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();

        $tgl_trx = $header_do['tanggal'];

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
                    if(isset($detail_do)) unset($detail_do);

                    $kd_lokasi                  = substr($obj->sub, 0, 2);
                    $kd_blok                    = substr($obj->sub, 2, 2);
                    $kd_sub_blok                = substr($obj->sub, 4, 2);
                    $detail_do = array(
                      'kd_lokasi'     => $kd_lokasi,
                      'kd_blok'       => $kd_blok,
                      'kd_sub_blok'   => $kd_sub_blok,
                      'no_sj'         => $header_do['no_sj'],
                      'kd_produk'     => $obj->kd_produk,
                      'qty'           => $obj->qty,
                      'keterangan'    => $obj->keterangan,
                      'qty_kembali'   => 0
                    );

                    if($this->psj_model->insert_row('sales.t_surat_jalan_detail', $detail_do)) {
                        $qty_sj_indo = $this->psj_model->getdo_qty_sj($header_do['no_do'], $obj->kd_produk);
                    } else {
                        $this->db->trans_rollback();
                        $result['errMsg']    = 'Process Failed.<br/> Error menyimpan data SJ';
                        echo json_encode($result);
                        return;
                    }

                    $qty_sj_indo = $qty_sj_indo + $obj->qty;
                    if(isset($updateDOdet)) unset($updateDOdet);
                    $updateDOdet['qty_sj'] = $qty_sj_indo;
                    $detail_result = $this->psj_model->update_do_detail($header_do['no_do'], $obj->kd_produk, $updateDOdet);
                    if(isset($trxinv)) unset($trxinv);

                    $trxinv = array(
                      'kd_produk'    => $obj->kd_produk,
                      'no_ref'       => $header_do['no_sj'],
                      'kd_lokasi'    => $kd_lokasi,
                      'kd_blok'      => $kd_blok,
                      'kd_sub_blok'  => $kd_sub_blok,
                      'qty_in'       => 0,
                      'qty_out'      => (int) $obj->qty,
                      'type'         => '7',
                      'created_by'   => $created_by,
                      'created_date' => $created_date,
                      'tgl_trx'      => $tgl_trx
                    );

                    $stok = 0;
                    $stokexists = FALSE;
                    $rowstok = $this->psj_model->cek_exists_brg_inv_sj($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);

                    unset($brg_inventory);

                    if (count($rowstok) > 0) {
                        $stokexists = true;
                        foreach ($rowstok as $objstok) {
                            $stok = $objstok->qty_oh;
                        }
                        $brg_inventory = array(
                          'qty_oh'        => $stok - (int) $obj->qty,
                          'updated_by'    => $created_by,
                          'updated_date'  => $created_date
                        );

                    } else {
                        $brg_inventory = array(
                          'kd_produk'     => $obj->kd_produk,
                          'kd_lokasi'     => $kd_lokasi,
                          'kd_blok'       => $kd_blok,
                          'kd_sub_blok'   => $kd_sub_blok,
                          'qty_oh'        => $stok - (int) $obj->qty,
                          'created_by'    => $created_by,
                          'created_date'  => $created_date,
                        );
                    }

                    if ($this->psj_model->insert_row('inv.t_trx_inventory', $trxinv)) {
                        if (!$stokexists && $this->psj_model->insert_row('inv.t_brg_inventory', $brg_inventory)) {
                            $detail_result++;
                        } elseif ($this->psj_model->update_brg_inv($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok, $brg_inventory)) {
                            $detail_result++;
                        }
                    } else {
                        $this->db->trans_rollback();
                        $result['errMsg']    = 'Process Failed.<br/>Error menyimpan data transaksi barang';
                        echo json_encode($result);
                        return;
                    }
                }

                if ($header_result && $detail_result > 0) {
                    $Alldo_detail = $this->psj_model->checkdo_qty_qty_sj($header_do['no_do']);
                    if ($Alldo_detail == 0) {
                        $updatedo = array(
                            'updated_by'    => $created_by,
                            'updated_date'  => $created_date,
                            'status'        => '1',
                        );
                        $result['success'] = $this->psj_model->update_do($header_do['no_do'], $updatedo);
                    } else {
                        $result['success'] = true;
                    }
                }

                if(!$result['success']) {
                    $this->db->trans_rollback();
                    $result['errMsg']   = 'Process Failed.<br/>Error mengupdate data DO';
                    $result['sql']      = $this->db->queries;
                    echo json_encode($result);
                    return;
                }
                $this->db->trans_complete();
            }
        }
        if($result['success']) {
            $result['errMsg']   = '';
            $result['printUrl'] = site_url("penjualan_sj/print_form/" . $header_do['no_sj']);
        }
        echo json_encode($result);
    }

    public function proses_kembali() {
        $result = array(
          'success'   => false,
          'errMsg'    => 'Process Failed. Unknown error'
        );
        header('Content-Type: application/json');

        $updated_by   = $this->session->userdata('username');
        $updated_date = date('Y-m-d');
        $no_sj      = $this->form_data('no_sj',false);
        $tgl_sj     = $this->form_data('tgl_sj',false);
        $header_sj  = array(
          'is_kembali'       => intval($this->form_data('is_kembali',0)),
          'tanggal_kembali'  => $this->form_data('tanggal_kembali',false),
          'penerima'         => $this->form_data('penerima',false),
          'ket_pengembalian' => $this->form_data('ket_pengembalian',false),
          'updated_by'       => $updated_by,
          'updated_date'     => $updated_date
        );

        $detail_sj = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        if($header_sj['is_kembali'] === 1 && count($detail_sj) > 0 ) {
            $this->db->trans_start();
            $status_header = $this->psj_model->update_sj($no_sj, $header_sj);
            if($status_header) {
                foreach($detail_sj as $data) {
                    $data_update = array(
                        'qty_kembali' => $data->qty_kembali,
                        'ket_kembali' => $data->keterangan
                    );
                    $status_detail = $this->psj_model->update_sj_detail($no_sj, $data->kd_produk, $data_update);
                    if(!$status_detail) {
                        $this->db->trans_rollback();
                        $result['errMsg'] = 'Process Failed.<br/>Gagal memperbarui data penjualan untuk produk'. $data->kd_produk;
                        echo json_encode($result);
                        return;
                    }
                }
                $result['errMsg'] = '';
                $result['success'] = true;
            } else {
                $result['errMsg'] = 'Process Failed.<br/>Gagal memperbarui data penjualan.';
            }
            $this->db->trans_complete();
        } else {
            $result['errMsg'] = 'Process Failed.<br/>No detail received';
        }
        echo json_encode($result);
    }

    public function print_form($no_sj = '') {
//		$this->psj_model->setCetakKe($nno_sj);

        $data = $this->psj_model->get_data_print($no_sj);
        //var_dump($data); die();
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PenjualanSJPrint.php');
        $pdf = new PenjualanSJPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}
