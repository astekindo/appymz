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
    
    public function get_rows_twin(){
         $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        //$kdakun = isset($_POST['kdakun']) ? $this->db->escape_str($this->input->post('kdakun', TRUE)) : '';
        

        $result = $this->mcostcenter_model->get_rows_twin($search, $start, $limit);

        echo $result;
        
    }
    
    public function get_rows_twin2($search){
        
    }
    
     public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->mcostcenter_model->get_row($id);

            return $result;
        }
    }
    public function get_rows_akun() {
         $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->mcostcenter_model->get_rows_akun($search,$start, $limit);

        echo $result;
    }
    
    
    public function get_rows_akun_edit() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $res_select = $this->mcostcenter_model->get_rows_akun_select($search);
        $res_all = $this->mcostcenter_model->get_all_akun();
        $resArr = array();
        if(count($res_all)>0){
            foreach ($res_all as $value) {
                $sel=0;
                if(count($res_select)>0){
                    foreach ($res_select as $v) {
                        if($v->kd_akun === $value->kd_akun){
                            $sel=1;                            
                        }
                    }
                }
                $arr= array(
                    'kd_akun'=>$value->kd_akun,
                    'nama_akun'=>$value->nama,
                    'sel'=>$sel
                );
                array_push($resArr, $arr); 
                
            }
        }
        
        $results = '{success:true,record:' . count($resArr) . ',data:' . json_encode($resArr) . '}';
        echo $results ;
        
    }
    
    public function update_row() {
        $kd_cc = isset($_POST['kd_costcenter']) ? $this->db->escape_str($this->input->post('kd_costcenter', TRUE)) : FALSE;                
        $nama = isset($_POST['nama_costcenter']) ? $this->db->escape_str($this->input->post('nama_costcenter', TRUE)) : FALSE;        
        $cmd=isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : FALSE;
        $data_akun = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        
        $aktif = '1';
        
        if($cmd=='update'){
//            $updated_by = $this->session->userdata('username');
//            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_costcenter'=>$nama,                
                'aktif'=>$aktif
            );

            if ($this->mcostcenter_model->update_row($kd_cc, $datau)) {
                $result = '{"success":true,"errMsg":""}';
                $retval = $this->mcostcenter_model->delete_rows('acc.t_costcenter_akun',array('kd_costcenter' => $kd_cc));
                if($retval > 0){
                    if(count($data_akun)>0){
                        foreach ($data_akun as $value) {
                            $child['kd_costcenter']=$kd_cc;
                            $child['kd_akun']=$value->kd_akun;
                            $retval = $this->mcostcenter_model->insert_row_table('acc.t_costcenter_akun',$child);
                        }
                    }
                }
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }  elseif ($cmd=='insert') {
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
                if(count($data_akun)>0){
                        foreach ($data_akun as $value) {
                            $child['kd_costcenter']=$kd_cc;
                            $child['kd_akun']=$value->kd_akun;
                            $retval = $this->mcostcenter_model->insert_row_table('acc.t_costcenter_akun',$child);
                        }
                    }
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
    
    public function delete_row_akun() {
        $kd_akun = isset($_POST['kd_akun']) ? $this->db->escape_str($this->input->post('kd_akun', TRUE)) : FALSE;
        $kd_costcenter = isset($_POST['kd_costcenter']) ? $this->db->escape_str($this->input->post('kd_costcenter', TRUE)) : FALSE;

        if ($this->mcostcenter_model->delete_rows('acc.t_costcenter_akun',array('kd_costcenter'=>$kd_costcenter,'kd_akun'=>$kd_akun))) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
}

?>
