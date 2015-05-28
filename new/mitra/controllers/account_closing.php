<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_closing
 *
 * @author miyzan
 */
class account_closing extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_closing_model', 'acm_model');
    }

    //put your code here

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->acm_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function add_closing() {
        $th = isset($_POST['mtahun']) ? $this->db->escape_str($this->input->post('mtahun', TRUE)) : NUll;
        $bl = isset($_POST['mbulan']) ? $this->db->escape_str($this->input->post('mbulan', TRUE)) : NULL;
        $kdcabang = isset($_POST['mkdcabang']) ? $this->db->escape_str($this->input->post('mkdcabang', TRUE)) : NULL;
        $resultfalse = '{"success":false,"errMsg":"Process Failed.."}';
        $resulttrue = '{"success":true,"errMsg":""}';
        $result = "";
        if (!$th) {
            echo $resultfalse;
            return;
        }
        if (!$bl) {
            echo $resultfalse;
            return;
        }
        if (!$kdcabang) {
            echo $resultfalse;
            return;
        }
        //=========================
        $status = 1;

        if ($this->acm_model->get_rows_exists_aktif($kdcabang)) {
            $result = '{"success":false,"errMsg":"Process Failed.. Any Record Active"}';
        } elseif ($this->acm_model->get_rows_exist($th . $bl, $kdcabang)) {
            $result = '{"success":false,"errMsg":"Process Failed.. Record is exists"}';
        } else {
            $datai = array(
                'thbl' => $th . $bl,
                'status' => $status,
                'aktif_date' => date("Y-m-d"),
                'aktif_by' => $this->session->userdata('username'),
                'kd_cabang' => $kdcabang
            );
            if ($this->acm_model->insert_row('acc.t_master_closing', $datai)) {
                $result = $resulttrue;
            } else {
                $result = $resultfalse;
            }
        }


//        insert_row
        echo $result;
    }
    public function histo_rugilaba($thbl,$kdcabang){
        $this->load->library('../controllers/account_rugilaba');
        $test=new account_rugilaba();
        $test->histo_rugilaba($thbl,$kdcabang);
    }
    public function histo_neracasaldo($thbl,$kdcabang){
        $this->load->library('../controllers/account_neracalajur');
        $test=new account_neracalajur();
        $test->histo_neracasaldo($thbl,$kdcabang);
    }
    public function histo_neraca($thbl,$kdcabang){
        $this->load->library('../controllers/account_neraca');
        $test=new account_neraca();
        $test->histo_neraca($thbl,$kdcabang);
    }
    
    public function get_periode_thbl($thbl, $v) {
        $dt = substr($thbl, 0, 4) . '-' . substr($thbl, 4, 2) . '-01';
        $current_date = date('Y-m-d', strtotime($dt));
        return date('Ym', strtotime($v . ' month', strtotime($current_date)));
    }

    public function set_closing() {
        $thbl = isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : NUll;
        $kdcabang = isset($_POST['kdcabang']) ? $this->db->escape_str($this->input->post('kdcabang', TRUE)) : NULL;
        $resultfalse = '{"success":false,"errMsg":"Process Failed.."}';
        $resulttrue = '{"success":true,"errMsg":""}';
        $result = "";
        if (!$thbl) {
            echo $resultfalse;
            return;
        }
        if (!$kdcabang) {
            echo $resultfalse;
            return;
        }
        //=========================
        $status = 2;
        
        $validasithbl=substr($thbl, 4, 2);
        if($validasithbl=='12'){
            //closetahun            
            //===> posting tahun jurnal penutup 
        }else{
            $this->histo_rugilaba($thbl, $kdcabang);
            $this->histo_neracasaldo($thbl, $kdcabang);
            $this->histo_neraca($thbl, $kdcabang);
            
            //closebulan
            //====> histo_report
            
        }
//        if ($this->acm_model->get_rows_exist($thbl, $kdcabang)) {
//            $datawhere = array();
//            $datawhere = array(
//                'thbl' => $thbl,
//                'kd_cabang' => $kdcabang
//            );
//            $dataset = array();
//            $dataset = array(
//                'status' => $status,
//                'close_date' => date("Y-m-d"),
//                'close_by' => $this->session->userdata('username')
//            );
//            if ($this->acm_model->update_row('acc.t_master_closing', $dataset, $datawhere)) {
//                $result = $resulttrue;
//                $mthbl=$this->get_periode_thbl($thbl, 1);
//                $datai = array(
//                    'thbl' => $mthbl,
//                    'status' => 1,
//                    'aktif_date' => date("Y-m-d"),
//                    'aktif_by' => $this->session->userdata('username'),
//                    'kd_cabang' => $kdcabang
//                );
//                if ($this->acm_model->insert_row('acc.t_master_closing', $datai)) {
//                    
//                }
//            } else {
//                $result = $resultfalse;
//            }
//        } else {
//            $result = '{"success":false,"errMsg":"Process Failed.. Record Not exists"}';
//        }


//        insert_row
        echo $result;
    }

}

?>
