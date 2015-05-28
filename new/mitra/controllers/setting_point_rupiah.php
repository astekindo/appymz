<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class setting_point_rupiah extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting_point_rupiah_model');
    }
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->setting_point_rupiah_model->get_rows($search, $start, $limit);

        echo $result;
    }
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->setting_point_rupiah_model->get_row($id);

            return $result;
        }
    }
    public function update_row() {
        $tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : FALSE;
        $tgl_akhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : FALSE;
        $point = isset($_POST['point']) ? $this->db->escape_str($this->input->post('point', TRUE)) : FALSE;
        $rupiah = isset($_POST['rupiah']) ? $this->db->escape_str($this->input->post('rupiah', TRUE)) : FALSE;
        $kd_point_setting = isset($_POST['kd_point_setting']) ? $this->db->escape_str($this->input->post('kd_point_setting', TRUE)) : FALSE;

        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : FALSE;
        $tgl_awal1 = strtotime($tgl_awal);
        $tgl_akhir1 = strtotime($tgl_akhir);
        if ($tgl_awal1 > $tgl_akhir1){
            echo '{"success":false,"errMsg":"Tanggal Awal Tidak Boleh Lebih Besar dari Tanggal Akhir"}';
            $this->db->trans_rollback();
            exit;
        }
        if (!$kd_point_setting) { //save         
            $result_prod = $this->setting_point_rupiah_model->select_data($rupiah, $tgl_awal,$tgl_akhir);
            if (!empty($result_prod)) {
                $this->db->trans_rollback();
                echo '{"success":false,"errMsg":"Tanggal Awal untuk Rupiah ' . $rupiah . ' sudah ada"}';
                exit;
            }else {
                $result_data = $this->setting_point_rupiah_model->select_data_end($rupiah, $tgl_awal,$tgl_akhir);
                if (!empty($result_data)) {
                    $this->db->trans_rollback();
                    echo '{"success":false,"errMsg":"Tanggal Akhir untuk Rupiah ' . $rupiah . ' sudah ada"}';
                    exit;
                }
            }
            $data = array(
                'kd_point_setting' => 'RP-' . $this->setting_point_rupiah_model->get_kode_sequence('RP', 4),
                'point' => $point,
                'rupiah' => $rupiah,
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir,
                'created_by' => $this->session->userdata('username'),
                'created_date' => date('Y-m-d H:i:s')
                
            );

            if ($this->setting_point_rupiah_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit     			
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');
            $result_prod = $this->setting_point_rupiah_model->select_data($rupiah, $tgl_awal,$tgl_akhir,$kd_point_setting);
            if (!empty($result_prod)) {
                $this->db->trans_rollback();
                echo '{"success":false,"errMsg":"Tanggal Awal untuk Rupiah ' . $rupiah . ' sudah ada"}';
                exit;
            }else {
                $result_data = $this->setting_point_rupiah_model->select_data_end($rupiah, $tgl_awal,$tgl_akhir,$kd_point_setting);
                if (!empty($result_data)) {
                    $this->db->trans_rollback();
                    echo '{"success":false,"errMsg":"Tanggal Akhir untuk Rupiah ' . $rupiah . ' sudah ada"}';
                    exit;
                }else {
                    $datau = array(
                    'rupiah' => $rupiah,
                    'point' => $point,
                    'tgl_awal' => $tgl_awal,
                    'tgl_akhir' => $tgl_akhir,
                    'update_by' => $updated_by,
                    'update_date' => $updated_date,
                );

                if ($this->setting_point_rupiah_model->update_row($kd_point_setting,$datau)) {
                    $result = '{"success":true,"errMsg":""}';
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                }
                }
            }
            
            
        }

        echo $result;
    }

    public function delete_rows() {
        $postdata = isset($_POST['postdata']) ? $this->input->post('postdata', TRUE) : array();
        $updated_by = $this->session->userdata('username');
        $updated_date = date('Y-m-d H:i:s');

        $datau = array(
            'updated_by' => $updated_by,
            'aktif' => '0'
        );
        if (count($postdata) > 0) {
            $records = explode(';', $this->input->post('postdata'));
            $i = 0;
            foreach ($records as $id) {
                if ($id != '') {
                    $kd = explode('-', $id);
                    $this->db->trans_start();
                    if ($this->setting_point_rupiah_model->delete_row($kd[0], $kd[1], $kd[2], $kd[3], $datau)) {
                        $i++;
                    }
                    $this->db->trans_complete();
                }
            }
            if ($i > 0) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            echo $result;
        }
    }
    public function delete_row() {
        $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1', TRUE)) : FALSE;
        $kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2', TRUE)) : FALSE;
        $kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3', TRUE)) : FALSE;
        $kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4', TRUE)) : FALSE;

        if ($this->kategori3_model->delete_row($kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_kategori3($kd_kategori1 = '', $kd_kategori2 = '') {
        $result = $this->setting_point_rupiah_model->get_kategori3($kd_kategori1, $kd_kategori2);
        echo $result;
    }

    public function get_kategori4($kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '') {
        $result = $this->setting_point_rupiah_model->get_kategori4($kd_kategori1, $kd_kategori2, $kd_kategori2);
        echo $result;
    }

}