<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_masterclosing
 *
 * @author miyzan
 */
class account_masterclosing extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_master_closing_model', 'mclose_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mclose_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {           
            $kdcm = isset($_POST['kd_cm']) ? $this->db->escape_str($this->input->post('kd_cm', TRUE)) : NULL;

//            $jenis = 'LRBL';
//            $thbltype = '1';
//            $kd_akun_jenis = null;
//            $kd_akun_posting = '440.0001';
            $result = $this->mclose_model->get_row($kdcm);

            echo  $result;
        }
    }

    public function get_kd() {
        $no = 'CM-';        
        $sequence = $this->mclose_model->get_kode_sequence($no, 1);

        return $no . $sequence;
    }
    public function update_row() {
        $cmd = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : FALSE;
        $kd_akun_posting = isset($_POST['kd_akun_posting']) ? $this->db->escape_str($this->input->post('kd_akun_posting', TRUE)) : FALSE;
        $kd_akun_jenis = isset($_POST['kd_akun_jenis']) ? $this->db->escape_str($this->input->post('kd_akun_jenis', TRUE)) : FALSE;
        $thbltype = isset($_POST['thblt']) ? $this->db->escape_str($this->input->post('thblt', TRUE)) : FALSE;
        $jenis = isset($_POST['jenisclose']) ? $this->db->escape_str($this->input->post('jenisclose', TRUE)) : FALSE;
        $kdcm=isset($_POST['kd_cm']) ? $this->db->escape_str($this->input->post('kd_cm', TRUE)) : NULL;
        $sjenis = "";
        if (!$kd_akun_jenis) {
            $kd_akun_jenis = null;
            $sjenis = " is null ";
        } else {
            $sjenis = " ='$kd_akun_jenis' ";
        }

        if (!$kd_akun_posting) {
            $kd_akun_posting = null;
        }

        $result = '';
        $sqlwhere = "a.thbl_type=$thbltype and a.jenis='$jenis' ";
//            and akun_jenis $sjenis";
//        $result = $this->mclose_model->get_row_exists($sqlwhere);
        if ($cmd == 'submit') {
            if (!$this->mclose_model->get_row_exists($sqlwhere)) {
                $datau = array(
                    'kd_cm'=>  $this->get_kd(),
                    'thbl_type' => $thbltype,
                    'jenis' => $jenis,
                    'akun_posting' => $kd_akun_posting,
                    'akun_jenis' => $kd_akun_jenis
                );
                if ($this->mclose_model->insert_row($datau)) {
                    $result = '{"success":true,"errMsg":""}';
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                }
            }
        } elseif ($cmd == 'update') {
            if ($this->mclose_model->get_row_exists("a.kd_cm='$kdcm'")) {
                $datau = array(
                    'thbl_type' => $thbltype,
                    'jenis' => $jenis,
                    'akun_posting' => $kd_akun_posting,
                    'akun_jenis' => $kd_akun_jenis
                );
                $where=$kdcm;
                if ($this->mclose_model->update_row_set('acc.t_closing_akun', $datau, $where)) {
                    $result = '{"success":true,"errMsg":""}';
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                }
            }else{
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }
        echo $result;
//        $arr=array();
//        $arr[0]=$thbltype;
//        $arr[1]=$jenis;
//        $arr[2]=$kd_akun_jenis;
//        $arr[3]=$kd_akun_posting;
//        echo "{success:true,errMsg:''}";
    }
    
    public function delete_row() {
        $kdcm = isset($_POST['kd_cm']) ? $this->db->escape_str($this->input->post('kd_cm', TRUE)) : FALSE;

        if ($this->mclose_model->delete_row($kdcm)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    //put your code here
}

?>
