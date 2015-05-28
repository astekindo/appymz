<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_target_beli extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('setting_target_beli_model', 'stb_model');
    }

    public function update_row() {

        $detail = isset($_POST['detail']) ? $this->db->escape_str($this->input->post('detail', TRUE)) : FALSE;
        $bulan = isset($_POST['bulan']) ? $this->db->escape_str($this->input->post('bulan', TRUE)) : FALSE;
        $tahun = isset($_POST['tahun']) ? $this->db->escape_str($this->input->post('tahun', TRUE)) : FALSE;

        $detail = json_decode($detail, true);
        //belum ada disimpan di history
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d H:i:s');
        $count = 0;

        $no = 'STB' . date('Ym') . '-';
        $no_bukti = $this->stb_model->get_kode_sequence($no, 4);
        $kd_stb = $no . $no_bukti;


        for ($i = 0; $i < count($detail); $i++) {
            if ($detail[$i]['edited'] == 'Y') {

                $old_data = $this->stb_model->get_row($bulan,$tahun,$detail[$i]['kd_kategori1'], $detail[$i]['kd_kategori2'], $detail[$i]['kd_kategori3'], $detail[$i]['kd_kategori4']);
                if ($old_data != NULL || $old_data != '') {
                    //data ada 
                    $old_data = get_object_vars($old_data);
                    $update_data = array(
                        'target_qty'    => $detail[$i]['target_qty'],
                        'target_rupiah' => $detail[$i]['target_rupiah'],
                        'created_by' => $created_by,
                        'created_date' => $created_date,
                    );
                    $this->stb_model->update_row( $update_data,$bulan,$tahun,$detail[$i]['kd_kategori1'], $detail[$i]['kd_kategori2'], $detail[$i]['kd_kategori3'], $detail[$i]['kd_kategori4']);
                   
                } else {
                    //data baru
                    $new_data = array(
                        'bulan'  => $bulan,
                        'tahun'=> $tahun,
                        'kd_setting'=> $kd_stb,
                        'kd_kategori1'  => $detail[$i]['kd_kategori1'],
                        'kd_kategori2'  => $detail[$i]['kd_kategori2'],
                        'kd_kategori3'  => $detail[$i]['kd_kategori3'],
                        'kd_kategori4'  => $detail[$i]['kd_kategori4'],
                        'target_qty'    => $detail[$i]['target_qty'],
                        'target_rupiah' => $detail[$i]['target_rupiah'],
                        'created_by' => $created_by,
                        'created_date' => $created_date,
                    );
                    $this->stb_model->insert_row($new_data);
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
        $bln = isset($_POST['bulan']) ? $this->db->escape_str($this->input->post('bulan', TRUE)) : '';
        $thn = isset($_POST['tahun']) ? $this->db->escape_str($this->input->post('tahun', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        
        $bulan = substr($bln, 5,2);
        $tahun = substr($thn, 0,4);
        $hasil = $this->stb_model->search_kategori($bulan,$tahun,$kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $search);
        //$results = array();
        echo '{success:true,data:' . json_encode($hasil) . '}';
    }

}
