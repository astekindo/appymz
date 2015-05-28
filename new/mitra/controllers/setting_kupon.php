<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_kupon extends MY_Controller {

    /**
     * @author bambang
     * @lastedited 3 jul 2014
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('setting_kupon_model','skm');
    }

    public function get_rows() {
        $start          = $this->form_data('start', 0);
        $limit          = $this->form_data('limit', $this->config->item("length_records"));
        $search         = $this->form_data('query');

        $this->print_result_json( $this->skm->get_rows($search, $start, $limit), $this->test);
    }

    public function get_row() {
        $kd_kupon        = $this->form_data('id');

        $this->print_result_json( $this->skm->get_row($kd_kupon), $this->test);
    }

    public function update_row()
    {
        $cmd        = $this->form_data('cmd');
        $kd_kupon   = $this->form_data('kd_kupon');
        $data       = array(
            'rupiah'    => $this->form_data('rupiah'),
            'kupon'     => $this->form_data('kupon'),
            'tgl_awal'  => $this->form_data('tgl_awal'),
            'tgl_akhir' => $this->form_data('tgl_akhir')
        );
        $result = array('success' => false, 'errMsg' => 'Unknown error..');
        $tgl_awal1 = strtotime($data['tgl_awal']);
        $tgl_akhir1 = strtotime($data['tgl_akhir']);
        if ($tgl_awal1 > $tgl_akhir1){
            $result['errMsg'] = 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir';
            echo json_encode($result);
            return;
        }
        $result_prod = $this->skm->select_data($data['rupiah'], $data['tgl_awal'],$data['tgl_akhir']);
            if (!empty($result_prod)) {
                $result['errMsg'] = 'Tanggal Awal Untuk Rupiah '.$data['rupiah'].' Sudah Ada';
                echo json_encode($result);
                return;
            }else {
                $result_data = $this->skm->select_data_end($data['rupiah'], $data['tgl_awal'],$data['tgl_akhir']);
                if (!empty($result_data)) {
                    $result['errMsg'] = 'Tanggal Akhir Untuk Rupiah '.$data['rupiah'].' Sudah Ada';
                    echo json_encode($result);
                    return;
                }
            }
        if($cmd === 'update') {
            $data['update_by']      = $this->session->userdata('username');
            $data['update_date']    = date('Y-m-d');
            if(intval($data['rupiah']) == 0 || intval($data['kupon']) == 0) {
                $result['errMsg'] = 'Nilai rupiah dan/atau jumlah kupon tidak boleh nol!';
                echo json_encode($result);
                return;
            }
            $query = $this->skm->update_row($kd_kupon,$data);
            if($this->test && array_key_exists('lq', $query)) $result['lq'] = $query['lq'];
            if($query['success']) {
                $result['success'] = true;
            } else {
                $result['errMsg'] = 'Kesalahan saat menyimpan data di database!';
            };
        } elseif($cmd === 'save') {
            $kode       = 'KP' . date('Ym', strtotime($data['tgl_awal']));
            $sequence   = $this->skm->get_kode_sequence($kode, 3);
            $kd_kupon   = $kode .'-'. $sequence;

            $data['created_by']    = $this->session->userdata('username');
            $data['created_date']  = date('Y-m-d');
            
            $query = $this->skm->insert_row($kd_kupon, $data);
            if($this->test && array_key_exists('lq', $query)) $result['lq'] = $query['lq'];
            if($query['success']) {
                $result['success'] = true;
            } else {
                $result['errMsg'] = 'Kesalahan saat menyimpan data di database!';
            };
        }
        echo json_encode($result);
        return;
    }
}