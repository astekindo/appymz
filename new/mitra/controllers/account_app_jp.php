<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_app_jp
 *
 * @author faroq
 */
class account_app_jp extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('account_app_jp_model','apjp_acc_model');
    }
    
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->apjp_acc_model->get_rows($search, $start, $limit);

        echo $result;
    }
    
    public function get_rows_akun() {
       
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->apjp_acc_model->get_rows_akun($search);

        echo $result;
    }
    
    public function update_row(){
        $data_in=isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)): array();   
        
        $nd= 'JR-';
        $seqq = $this->apjp_acc_model->get_kode_sequence($nd, 3);  
        $idjurnal=$nd.$seqq;
         $result=0;         
         $this->db->trans_start();
        foreach ($data_in as $v) {
        
            unset($hvoucher);
            $hvoucher['approval_by']=$this->session->userdata('username');
            $hvoucher['approval_date']=date('Y-m-d');
            $hvoucher['aktif']=2;
            $result=$this->apjp_acc_model->update_row($v->kd_postingjp,$hvoucher);
                
            
            $hjurnal['idjurnal']=$idjurnal;
            $hjurnal['tgl_transaksi']=$v->tgl_posting;
            $hjurnal['kd_transaksi']=$v->kd_transaksi;
            $hjurnal['referensi']=$v->referensi;
            $hjurnal['keterangan']=$v->keterangan;
            $hjurnal['created_by']=$hvoucher['approval_by'];
            $hjurnal['created_date']=$hvoucher['approval_date'];
            $hjurnal['typepost']='penutup';
            $hjurnal['idpost']=$v->kd_postingjp;
            
            if($this->apjp_acc_model->insert_row('acc.t_jurnal',$hjurnal)){
                $result++;
            }
            
            $thbltrx=  explode("-", $v->tgl_posting);
            $thbl=$thbltrx[0].$thbltrx[1];
            
                
            $arrrec=$this->apjp_acc_model->get_rows_akun_loop($v->kd_postingjp);
            foreach ($arrrec as $obj) {
                $fak=1;                
                if ($obj->dk_akun==$obj->dk_transaksi){
                    $fak=1;
                }else{
                    $fak=-1;
                }
                    
                $jumlah= ($obj->debet+$obj->kredit)*$fak;
                
                $djurnal['idjurnal']=$idjurnal;
                $djurnal['kd_akun']=$obj->kd_akun;
                $djurnal['dk_akun']=$obj->dk_akun;
                $djurnal['dk_transaksi']=$obj->dk_transaksi;
                $djurnal['faktor']=$fak;
                $djurnal['jumlah']=$jumlah;
                $djurnal['debet']=$obj->debet;
                $djurnal['kredit']=$obj->kredit;
                
                if($this->apjp_acc_model->insert_row('acc.t_jurnal_detail',$djurnal)){
                    $result++;
                }
                if ($this->apjp_acc_model->get_saldo_bb_exists($obj->kd_akun,$thbl)){
                    $saldobb=$this->apjp_acc_model->get_saldo_bb($obj->kd_akun,$thbl);
                    $saldobb=$saldobb+$jumlah;         
                    $bbsaldo['saldo']=$saldobb;
                    
                    $this->apjp_acc_model->update_row_bb('acc.t_bukubesar_saldo',$obj->kd_akun,$thbl,$bbsaldo);
                    $result++;
                }else{
                    $bbsaldo['thbl']=$thbl;
                    $bbsaldo['kd_akun']=$obj->kd_akun;
                    $bbsaldo['saldo']=$jumlah;                    
                    $this->apjp_acc_model->insert_row('acc.t_bukubesar_saldo',$bbsaldo);
                    $result++;
                }
                
            }
      }
        $this->db->trans_complete();
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":""}';
        }else{
            $retval = '{"success":false,"errMsg":"Process Failed '.$result.'"}';
        }
        echo $retval;
    }
    
    
}

?>
