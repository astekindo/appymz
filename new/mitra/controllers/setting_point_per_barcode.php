<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_point_per_barcode extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting_point_per_barcode_model', 'sppb_model');
    }

    public function update_row() {

        $detail = isset($_POST['detail']) ? $this->db->escape_str($this->input->post('detail', TRUE)) : FALSE;
        
        $detail = json_decode($detail, true);
        //belum ada disimpan di history
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d H:i:s');
        $count = 0;
        
        for ($i = 0; $i < count($detail); $i++) {
            if ($detail[$i]['edited'] == 'Y') {
                $tgl_awal1 = strtotime($detail[$i]['tgl_awal']);
                $tgl_akhir1 = strtotime($detail[$i]['tgl_akhir']);
                $kd_produk  = $detail[$i]['kd_produk'];
                $tgl_awal  = $detail[$i]['tgl_awal'];
                $tgl_akhir  = $detail[$i]['tgl_akhir'];
                $kd_point_setting  = $detail[$i]['kd_point_setting'];
                if ($tgl_awal1 > $tgl_akhir1){
                    echo '{"success":false,"errMsg":"Tanggal Awal Tidak Boleh Lebih Besar dari Tanggal Akhir"}';
                    $this->db->trans_rollback();
                    exit;
                }
                
                if ($kd_point_setting){ //update
                    $result_prod = $this->sppb_model->select_data($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                    if (!empty($result_prod)) {
                        $this->db->trans_rollback();
                        echo '{"success":false,"errMsg":"Tanggal Awal untuk Kode Produk ' .$kd_produk. ' sudah ada"}';
                        exit;
                    }else {
                        $result_data = $this->sppb_model->select_data_end($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                        if (!empty($result_data)) {
                            $this->db->trans_rollback();
                            echo '{"success":false,"errMsg":"Tanggal Akhir untuk Kode Produk ' . $kd_produk .' sudah ada"}';
                            exit;
                        }else{
                            $data_point = $this->sppb_model->select_data_point($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                            if ($data_point){
//                                $end_data = $this->sppb_model->select_data_end($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
//                                if ($end_data){
//                                    $this->db->trans_rollback();
//                                    echo '{"success":false,"errMsg":"Tanggal Akhir untuk Kode Produk ' . $kd_produk .' sudah ada"}';
//                                    exit;
//                                }else {
                                    $update_data = array(
                                    'point'    => $detail[$i]['point'],
                                    'tgl_awal' => $detail[$i]['tgl_awal'],
                                    'tgl_akhir' => $detail[$i]['tgl_akhir'],
                                    'update_by' => $created_by,
                                    'update_date' => $created_date,
                                    );
                                    $this->sppb_model->update_row( $update_data,$kd_point_setting,$kd_produk,$detail[$i]['tgl_awal'],$detail[$i]['tgl_akhir']);
                               // }
                                
                            }else {
                                $start_data = $this->sppb_model->select_data_start_not($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                                if ($start_data){
                                    $this->db->trans_rollback();
                                    echo '{"success":false,"errMsg":"Tanggal Awal untuk Kode Produk ' .$kd_produk. ' sudah ada"}';
                                    exit;
                                }else {
                                    $end_data = $this->sppb_model->select_data_end_not($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                                    if ($end_data){
                                        $this->db->trans_rollback();
                                        echo '{"success":false,"errMsg":"Tanggal Akhir untuk Kode Produk ' . $kd_produk .' sudah ada"}';
                                        exit;
                                    }else {
                                         //data baru
                                        $no = 'P-';
                                        $no_bukti = $this->sppb_model->get_kode_sequence($no, 4);
                                        $kd_stb = $no . $no_bukti;
                                        $new_data = array(
                                            'kd_point_setting'=> $kd_stb,
                                            'kd_produk'  => $detail[$i]['kd_produk'],
                                            'point'    => $detail[$i]['point'],
                                            'tgl_awal' => $detail[$i]['tgl_awal'],
                                            'tgl_akhir' => $detail[$i]['tgl_akhir'],
                                            'created_by' => $created_by,
                                            'created_date' => $created_date,
                                        );
                                        $this->sppb_model->insert_row($new_data);
                                    }
                                }
                               
                           }
                            
                        }
                    }
                }else {//save
                     $result_prod = $this->sppb_model->select_data($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                    if (!empty($result_prod)) {
                        $this->db->trans_rollback();
                       echo '{"success":false,"errMsg":"Tanggal Awal untuk Kode Produk ' .$kd_produk. ' sudah ada"}';
                        exit;
                    }else {
                        $result_data = $this->sppb_model->select_data_end($kd_produk, $tgl_awal,$tgl_akhir,$kd_point_setting);
                        if (!empty($result_data)) {
                            $this->db->trans_rollback();
                            echo '{"success":false,"errMsg":"Tanggal Akhir untuk Kode Produk ' . $kd_produk .' sudah ada"}';
                            exit;
                        }else {
                            //data baru
                            $no = 'P-';
                            $no_bukti = $this->sppb_model->get_kode_sequence($no, 4);
                            $kd_stb = $no . $no_bukti;
                            $new_data = array(
                                'kd_point_setting'=> $kd_stb,
                                'kd_produk'  => $detail[$i]['kd_produk'],
                                'point'    => $detail[$i]['point'],
                                'tgl_awal' => $detail[$i]['tgl_awal'],
                                'tgl_akhir' => $detail[$i]['tgl_akhir'],
                                'created_by' => $created_by,
                                'created_date' => $created_date,
                            );
                            $this->sppb_model->insert_row($new_data);
                       }
                    }
                }
                 $count++;
            }
        }

        if ($count = count($detail)) {
            $result = '{"success":true,"errMsg":""}';
        } elseif ($count < count($detail)) {
            $result = '{"success":true,"errMsg":"Sebagian data tidak ter-update.."}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }

        echo $result;
    }

    public function search_kategori() {
        $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1', TRUE)) : '';
        $kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2', TRUE)) : '';
        $kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3', TRUE)) : '';
        $kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        
        $hasil = $this->sppb_model->search_kategori($kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $search);
        //$results = array();
        echo '{success:true,data:' . json_encode($hasil) . '}';
    }

}
