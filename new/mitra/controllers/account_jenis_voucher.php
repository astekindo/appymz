<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_jenis_voucher
 *
 * @author miyzan
 */
class account_jenis_voucher extends MY_Controller {
    
    //put your code here
    
    public function __construct() {
        parent::__construct();
        $this->load->model('account_jenisvoucher_model','acc_jvm');
    }
    
     public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
       
        $result = $this->acc_jvm->get_rows($start, $limit);
        echo $result;
    }
    
    public function get_rows_akun() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->acc_jvm->get_rows_akun($search);

        echo $result;
    }

    public function get_rows_akun_edit() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $res_select = $this->acc_jvm->get_rows_akun_select($search);
        $res_all = $this->acc_jvm->get_all_akun();
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
        $cmd=isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : FALSE;
        $kdvoucher = isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : FALSE;
        $title = isset($_POST['title']) ? $this->db->escape_str($this->input->post('title', TRUE)) : FALSE;        
        $dk= isset($_POST['dk']) ? $this->db->escape_str($this->input->post('dk', TRUE)) : '';
        $autopost = isset($_POST['auto_posting_voucher']) ? $this->db->escape_str($this->input->post('auto_posting_voucher', TRUE)) : 0;
        
        $data_akun = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        
        $retval = 0;
        
        if ($autopost=='on' || $autopost=='true'){
            $autopost=1;
        }else{
            $autopost=0;
        }
        if($cmd=='insert'){
            if(!$this->acc_jvm->check_exists('title',$title)){
                $kdvoucher='JV-' . $this->acc_jvm->get_kode_sequence('JV-', 2);
                $header['kd_jenis_voucher']=$kdvoucher;
                $header['title']=$title;
                $header['dk']=$dk;
                $header['auto_posting_voucher']=$autopost;
                $retval = $this->acc_jvm->insert_row('acc.t_jenis_voucher',$header);
                if($retval > 0){
                    if(count($data_akun)>0){
                        foreach ($data_akun as $value) {
                            $child['kd_jenis_voucher']=$kdvoucher;
                            $child['kd_akun']=$value->kd_akun;
                            $retval = $this->acc_jvm->insert_row('acc.t_jenis_voucher_detail',$child);
                        }
                    }
                }
                
                
            }
        }elseif ($cmd=='update') {
            if($this->acc_jvm->check_exists('kd_jenis_voucher',$kdvoucher)){                
//                $header['kd_jenis_voucher']=$kdvoucher;
                $header['title']=$title;
                $header['dk']=$dk;
                $header['auto_posting_voucher']=$autopost;
                $retval = $this->acc_jvm->update_row($kdvoucher,$header);
                if($retval > 0){
                    $retval = $this->acc_jvm->delete_row('acc.t_jenis_voucher_detail',$kdvoucher);
                    if(count($data_akun)>0){
                        foreach ($data_akun as $value) {
                            $child['kd_jenis_voucher']=$kdvoucher;
                            $child['kd_akun']=$value->kd_akun;
                            $retval = $this->acc_jvm->insert_row('acc.t_jenis_voucher_detail',$child);
                        }
                    }
                }
                
                
            }
        }
        
        if ($retval > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."'.$autopost.'}';
        }


        echo $result;
        
    }
    
    public function delete_header() {        
        $kdvoucher = isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : FALSE;
                
        $retval = 0;        
        $retval = $this->acc_jvm->delete_row('acc.t_jenis_voucher',$kdvoucher);
        if ($retval > 0){
            $retval = $this->acc_jvm->delete_row('acc.t_jenis_voucher_detail',$kdvoucher);
        }
                
        if ($retval > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }


        echo $result;
        
    }
    
    public function delete_detail() {        
        $kdvoucher = isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : FALSE;
        $kdakun        = isset($_POST['kd_akun']) ? $this->db->escape_str($this->input->post('kd_akun', TRUE)) : FALSE;
        $retval = 0;
        $retval = $this->acc_jvm->delete_row_akun(array('kd_jenis_voucher'=>$kdvoucher,'kd_akun'=>$kdakun));
                        
        if ($retval > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }


        echo $result;
        
    }
    
}

?>
