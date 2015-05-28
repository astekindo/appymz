<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_monjurnal
 *
 * @author faroq
 */
class account_monjurnal extends MY_Controller{    
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_monjurnal_model','monjurnal_model');
    }
    
    public function get_akun(){
        $result =  $this->monjurnal_model->get_akun();
        echo $result;
    }
    
//    public function get_cabang() {
//        
//        $result = $this->monjurnal_model->get_cabang();
//        echo $result;
//    }
    
    public function get_view(){
        $tglawal = isset($_POST['tglawal']) ? $this->db->escape_str($this->input->post('tglawal', TRUE)) : null;
        $tglakhir = isset($_POST['tglakhir']) ? $this->db->escape_str($this->input->post('tglakhir', TRUE)) : null;
        $akun = isset($_POST['akun']) ? $this->db->escape_str($this->input->post('akun', TRUE)) : null;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
        $dk = isset($_POST['dk']) ? $this->db->escape_str($this->input->post('dk', TRUE)) : null;
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $tgl='2013-11-16';
        $result =  $this->monjurnal_model->get_view($akun,$tglawal,$tglakhir,$kd_cabang,$dk,$start, $limit);
        echo $result;
    }
    
}

?>
