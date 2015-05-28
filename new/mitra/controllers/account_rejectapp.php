<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_rejectapp
 *
 * @author miyzan
 */
class account_rejectapp extends MY_Controller{ 
    public function __construct() {
        parent::__construct();
        $this->load->model('account_rejectapp_model','rejectapp_model');
    }
   public function get_rows_reject(){ 
       $kdcabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;        
       $kdvoucher = isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher', TRUE)) : null;
       $result =  $this->rejectapp_model->get_rows_reject($kdvoucher,$kdcabang);
       echo $result;
   }
    public function get_rows_all(){
        $tglawal = isset($_POST['tglawal']) ? $this->db->escape_str($this->input->post('tglawal', TRUE)) : null;
        $tglakhir = isset($_POST['tglakhir']) ? $this->db->escape_str($this->input->post('tglakhir', TRUE)) : null;        
        $kdcabang = isset($_POST['kdcabang']) ? $this->db->escape_str($this->input->post('kdcabang', TRUE)) : null;        
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : null;        
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $user=$this->session->userdata('username');
        $result =  $this->rejectapp_model->get_rows_all($kdcabang,$tglawal,$tglakhir,$search,$start, $limit,$user);
        echo $result;
    }
    //put your code here
    
    public function get_rows_params(){
        $tglawal = isset($_POST['tglawal']) ? $this->db->escape_str($this->input->post('tglawal', TRUE)) : null;
        $tglakhir = isset($_POST['tglakhir']) ? $this->db->escape_str($this->input->post('tglakhir', TRUE)) : null;        
        $kdcabang = isset($_POST['kdcabang']) ? $this->db->escape_str($this->input->post('kdcabang', TRUE)) : null;        
        $sapproval= isset($_POST['sapproval']) ? $this->db->escape_str($this->input->post('sapproval', TRUE)) : null;        
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : null;        
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $user=$this->session->userdata('username');
        $result =  $this->rejectapp_model->get_rows_params($kdcabang,$tglawal,$tglakhir,$sapproval,$search,$start, $limit,$user);
        echo $result;
    }
    public function update_row(){
//        applevel	2
//        approval_by	spvacc
//        approval_date	2014-03-04
//        kd_cabang	001
//        kd_jenis_voucher	JV-04
//        kd_transaksi	TR-016
//        kd_voucher	EV-201403-00004
//        keterangan	3435
//        reason	test
//        tipe	2
        $kd_voucher=isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher', TRUE)) : null; 
        $reason=isset($_POST['reason']) ? $this->db->escape_str($this->input->post('reason', TRUE)) : null; 
        $approval=isset($_POST['applevel']) ? $this->db->escape_str($this->input->post('applevel', TRUE)) : null; 
        $approval_by=isset($_POST['approval_by']) ? $this->db->escape_str($this->input->post('approval_by', TRUE)) : null; 
        $approval_date=isset($_POST['approval_date']) ? $this->db->escape_str($this->input->post('approval_date', TRUE)) : null; 
        $tipe=isset($_POST['tipe']) ? $this->db->escape_str($this->input->post('tipe', TRUE)) : null; 
        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null; 
        $kd_transaksi=isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : null; 
        $kd_jenis_voucher=isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : null; 
        $keterangan=isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : null; 
        $reject_level=isset($_POST['reject_level']) ? $this->db->escape_str($this->input->post('reject_level', TRUE)) : null; 
        $datau=array();        
        $approval_reject='';
        if($approval==1){            
                $datau['aktif'] = 1;
                $datau['approval_by']=NULL;
                $datau['approval_date']=NULL;   
                $approval_reject='Approval 1';
        }
        if($approval==2){            
                $datau['status_apv2'] = NULL;
                $datau['approval2_by']=NULL;
                $datau['approval2_date']=NULL;                
                $approval_reject='Approval 2';
        }
        if($approval==3){            
                $datau['status_apv3'] = NULL;
                $datau['approval3_by']=NULL;
                $datau['approval3_date']=NULL;                
                $approval_reject='Approval 3';
        }
        if(!$approval){
            if($tipe==1){
                $approval_reject='Entry Close';
            }else{
                $approval_reject='Entry Edit';
            }
            
            $datau['aktif'] = 1;
                $datau['approval_by']=NULL;
                $datau['approval_date']=NULL; 
        }
        
        
        $status_close=null;
        if($tipe==1){
            $status_close='t';
            $datau['status_close'] = 't';
            $datau['close_by'] = $this->session->userdata('username');
            $datau['close_date'] = date('Y-m-d');
        }
        $datawhere=array();
        $datawhere=array('kd_voucher'=>$kd_voucher);
        
        $datalog=array();
        $datalog['kd_voucher']=$kd_voucher;
        $datalog['kd_transaksi']=$kd_transaksi;
        $datalog['kd_jenis_voucher']=$kd_jenis_voucher;
        $datalog['keterangan']=$keterangan;
        $datalog['kd_cabang']=$kd_cabang;
        $datalog['approval_reject']=$approval_reject;
        $datalog['reject_by']=$this->session->userdata('username');
        $datalog['reject_date']=date('Y-m-d');
        $datalog['status_close'] = $status_close;
        $datalog['reason'] = $reason;
        $datalog['approval_by'] = $approval_by;
        $datalog['approval_date'] = date('Y-m-d',strtotime($approval_date));
        if($reject_level==1){            
                  
                $datalog['reject_level']='Approval 1';
        }
        if($reject_level==2){            
                           
                $datalog['reject_level']='Approval 2';
        }
        if($reject_level==3){            
                             
                $datalog['reject_level']='Approval 3';
        }
        
        $this->db->trans_start();
            $result = $this->rejectapp_model->update_row('acc.t_voucher', $datau, $datawhere);
            $result = $this->rejectapp_model->insert_row('acc.t_histo_voucher', $datalog);
            //        insert histo reject
        $this->db->trans_complete();
        
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":""}';
        } else {
            $retval = '{"success":false,"errMsg":"Process Failed ' . $result . '"}';
        }
        echo $retval;

        
    }
}

?>
