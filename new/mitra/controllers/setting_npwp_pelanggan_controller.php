<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class setting_npwp_pelanggan_controller extends MY_Controller {

    private $kdPelanggan;
    private $kdNpwp;
    private $namaNpwp;
    private $alamatNpwp;
    private $noNpwp;
    private $aktif;

    function __construct() {
        parent::__construct();
        $this->load->model('setting_npwp_pelanggan_model');
        $this->kdPelanggan = isset($_POST['txt_kd_pelanggan']) ? $this->db->escape_str($this->input->post('txt_kd_pelanggan', TRUE)) : FALSE;
        //$this->kdNpwp = isset($_POST['txt_kd_npwp']) ? $this->db->escape_str($this->input->post('txt_kd_npwp', TRUE)) : FALSE;
        $this->kdNpwp = isset($_POST['txt_kd_npwp']) ? $this->db->escape_str($this->input->post('txt_kd_npwp', TRUE)) : FALSE;
        $this->namaNpwp = isset($_POST['txt_nama_npwp']) ? $this->db->escape_str($this->input->post('txt_nama_npwp', TRUE)) : FALSE;
        $this->alamatNpwp = isset($_POST['txt_alamat_npwp']) ? $this->db->escape_str($this->input->post('txt_alamat_npwp', TRUE)) : FALSE;
        $this->noNpwp = isset($_POST['txt_no_npwp']) ? $this->db->escape_str($this->input->post('txt_no_npwp', TRUE)) : FALSE;
        $this->aktif = isset($_POST['check_aktif_npwp']) ? $this->db->escape_str($this->input->post('check_aktif_npwp', TRUE)) : FALSE;
    }

    //unused
    private function generateKodeNpwp() {
        $no_ret = 'NPWP' . '-';
        $sequence = $this->setting_npwp_pelanggan_model->get_kode_sequence($no_ret, 3);
        return $no_ret . $sequence;
        $success = array(
            'success' => true,
            'data' => array(
                'txt_no_kwitansi' => $no_ret . $sequence
            )
        );
        echo json_encode($success);
    }

    public function getKodeNpwp() {
        $no_ret = 'NPWP' . '-';
        $sequence = $this->setting_npwp_pelanggan_model->get_kode_sequence($no_ret, 3);
        $success = array(
            'success' => true,
            'data' => array(
                'txt_kd_npwp' => $no_ret . $sequence
            )
        );
        echo json_encode($success);
    }

    public function finalGetRows() {
        $length = 2;
        $offset = 0;
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kdPelanggan = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : '';
        echo $this->setting_npwp_pelanggan_model->getAll($limit, $start, $search, $kdPelanggan);
    }

    public function finalGetNpwpRows($kdPelanggan = '') {
        //$length = 2;
        //$offset = 0;
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kdPelanggan = isset($_POST['kodePelanggan']) ? $this->db->escape_str($this->input->post('kodePelanggan', TRUE)) : '';
        echo $this->setting_npwp_pelanggan_model->getAllNpwp($limit, $start, $search, $kdPelanggan);
    }

    public function finalUpdateOrInsert() {
        if ($this->aktif == 'on') {
            $this->aktif = 1;
        } else {
            $this->aktif = 0;
        }
        $data = array(
            'kd_pelanggan' => $this->kdPelanggan,
            'kd_npwp' => $this->kdNpwp,
            'nama_npwp' => $this->namaNpwp,
            'alamat_npwp' => $this->alamatNpwp,
            'no_npwp' => $this->noNpwp,
            'aktif' => $this->aktif,
        );
        $command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : '';

        if ($command == 'update') {
            echo $this->setting_npwp_pelanggan_model->update($data, $this->kdPelanggan, $this->kdNpwp);
        } else {
            echo $this->setting_npwp_pelanggan_model->insert($data);
        }
    }

    public function finalDelete() {
        $this->kdPelanggan = isset($_POST['kdPelanggan']) ? $this->db->escape_str($this->input->post('kdPelanggan', TRUE)) : FALSE;
        $this->kdNpwp = isset($_POST['kdNpwp']) ? $this->db->escape_str($this->input->post('kdNpwp', TRUE)) : FALSE;
        $this->setting_npwp_pelanggan_model->delete($this->kdPelanggan, $this->kdNpwp);
        $success = array(
            'success' => true
        );
        echo json_encode($success);
    }

}

//    public function finalGetNpwpRows($kdPelanggan = '') {
//        //$length = 2;
//        //$offset = 0;
//        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
//        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
//        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
//        //$kdPelanggan = isset($_POST['kodePelanggan']) ? $this->db->escape_str($this->input->post('kodePelanggan', TRUE)) : '';
//        //$kdPelanggan = 'PLG-0002';
//        if (empty($kdPelanggan)) {
//            echo $this->setting_npwp_pelanggan_model->getAllPelangganNPWP($limit, $start, $search);
//        } else {
//            echo $this->setting_npwp_pelanggan_model->getDetailPelangganNPWP($kdPelanggan);
//        }
//    }


    //    public function finalGetDetailNpwp() {
//        $kdPelanggan = isset($_POST['kodePelanggan']) ? $this->db->escape_str($this->input->post('kodePelanggan', TRUE)) : '';
//        echo $this->setting_npwp_pelanggan_model->getDetailPelangganNPWP($kdPelanggan);
//    }
//    public function finalUpdateOrInsert(){
//        if($this->aktif!=''||$this->aktif!=null){
//            $this->aktif=1;
//        }else{
//            $this->aktif=0;
//        }
//        $data=array(
//            'kd_pelanggan'=>  $this->kdPelanggan,
//            'kd_npwp'=>  $this->kdNpwp,
//            'nama_npwp'=>  $this->namaNpwp,
//            'alamat_npwp'=>  $this->alamatNpwp,
//            'no_npwp'=>  $this->noNpwp,
//            'aktif'=>  $this->aktif,
//        );
//        
//        echo $this->setting_npwp_pelanggan_model->updateOrInsert($data,$this->kdPelanggan);
//    }