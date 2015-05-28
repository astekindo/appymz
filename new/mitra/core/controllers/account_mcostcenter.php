<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_mcostcenter
 *
 * @author faroq
 */
class account_mcostcenter extends MY_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_mcostcenter_model','mcostcenter_model');
    }
    
    public function get_rows(){
         $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mcostcenter_model->get_rows($search, $start, $limit);

        echo $result;
        
    }
    public function get_rows_all(){         
        $result = $this->mcostcenter_model->get_rows_all();

        echo $result;
        
    }
     public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->mcostcenter_model->get_row($id);

            return $result;
        }
    }
    
    public function update_row() {
        $kd_cc = isset($_POST['kd_costcenter']) ? $this->db->escape_str($this->input->post('kd_costcenter', TRUE)) : FALSE;                
        $nama = isset($_POST['nama_costcenter']) ? $this->db->escape_str($this->input->post('nama_costcenter', TRUE)) : FALSE;        
        $aktif = '1';
        
        if($kd_cc){
//            $updated_by = $this->session->userdata('username');
//            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_costcenter'=>$nama,                
                'aktif'=>$aktif
            );

            if ($this->mcostcenter_model->update_row($kd_cc, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }  else {
//            $created_by = $this->session->userdata('username');
//            $created_date = date('Y-m-d H:i:s');
            $no_do = 'CC-';
            $sequence = $this->mcostcenter_model->get_kode_sequence($no_do, 3);   
            $kd_cc=$no_do.$sequence;
            $data = array(
                'kd_costcenter' => $kd_cc,                                
                'nama_costcenter'=>$nama,                
                'aktif'=>$aktif
            );

            if ($this->mcostcenter_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        }

        

        echo $result;
    }
    
    public function delete_row() {
        $kd_akun = isset($_POST['kd_costcenter']) ? $this->db->escape_str($this->input->post('kd_costcenter', TRUE)) : FALSE;

        if ($this->mcostcenter_model->delete_row($kd_akun)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
}

?>
