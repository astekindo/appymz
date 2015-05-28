<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_bukubesar
 *
 * @author faroq
 */
class account_bukubesar extends MY_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('account_bukubesar_model','bukubesar_model');
    }
    
     public function get_view(){
        $tglawal = isset($_POST['tglawal']) ? $this->db->escape_str($this->input->post('tglawal', TRUE)) : null;
        $tglakhir = isset($_POST['tglakhir']) ? $this->db->escape_str($this->input->post('tglakhir', TRUE)) : null;
        $akun = isset($_POST['akun']) ? $this->db->escape_str($this->input->post('akun', TRUE)) : null;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
       
        $result =  $this->bukubesar_model->get_view($akun,$tglawal,$tglakhir,$kd_cabang);

        echo $result;
    }
    
}

?>
