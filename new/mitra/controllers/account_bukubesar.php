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
//        $akun = isset($_POST['akun']) ? $this->db->escape_str($this->input->post('akun', TRUE)) : null;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
        $akun =isset($_POST['akun']) ? json_decode($this->input->post('akun', TRUE)) : array();
        $arrrest=array();
//        $akun=json_decode('[{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"}]');
//        $kd_cabang='001';
//        $tglawal='2014-02-01';
//        $tglakhir='2014-02-28';
        if (count($akun)== 0){
           $akun= $this->bukubesar_model->get_akun_child();
        }
        
        $saldotrx=0;
        $saldod=0;
        $saldok=0;
        $jmld=null;
        $jmlk=null;
        foreach ($akun as $value) {        
            $saldotrx=0;
            $saldod=0;
            $saldok=0;
            $result=array();
           $result= $this->bukubesar_model->get_view2($value->kd_akun,$tglawal,$tglakhir,$kd_cabang);
           
           foreach($result as $v){
               $jmld=null;
                $jmlk=null;
               if($v->keterangan=='Saldo Awal'){
                   $saldotrx += $v->jumlah;
                   $jmld=null;
               }
               if($v->keterangan=='Saldo Akhir'){
                   $jmld = $saldod;
                   $jmlk=$saldok;
               }
               
               if($v->keterangan!='Saldo Akhir' && $v->keterangan!='Saldo Awal'){
                   if($v->dk_transaksi=='D') {
                       $jmld=abs($v->jumlah);
                       $saldod +=abs($v->jumlah);
                       }
                   if($v->dk_transaksi=='K') {
                       $jmlk=abs($v->jumlah);
                       $saldok +=abs($v->jumlah);
                   }                
                   $saldotrx += $v->jumlah;
               }
               
               
               
               array_push($arrrest, array(
                    "nomor"=>$v->nomor,
                    "tgl_transaksi"=>$v->tgl_transaksi,
                    "idjurnal"=>$v->idjurnal,
                    "novoucher"=>$v->novoucher,                   
                    "keterangan"=>$v->keterangan,
                    "keterangan_detail"=>$v->keterangan_detail,
                    "costcenter"=>$v->costcenter,
                    "cabang"=>$v->cabang,
                    "kd_akun"=>$v->kd_akun,
                    "nama"=>$v->nama,
                    "dk_transaksi"=>$v->dk_transaksi,
                    "jumlahd"=>$jmld,
                    "jumlahk"=>$jmlk,
                    "jumlah"=>$saldotrx
                   )
               );
           }
           
        }
        $retval ='{success:true,record:' . count($arrrest) . ',data:' . json_encode($arrrest) . '}';

        echo $retval;
    }
    
    public function print_form($tglawal,$tglakhir,$params) {
//        $akun='[{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"},{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"},{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"},{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"},{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"},{"kd_akun":"110.0002","nama":"KAS KECIL"},{"kd_akun":"330.0001","nama":"BIAYA GAJI"}]';
        $params=explode(':', $params);
        $kd_cabang=$params[0] ? $params[0] : null;
        $nm_cabang=$params[1] ? $params[1] : null;
        $akun=$params[2] ? $params[2] : null;
        
        $akun =$akun ? explode('_', $akun) : array();
        $mnoakun='';
        
//        echo var_dump($akun);
//         echo 'kd:'.$kd_cabang.' nm:'.$nm_cabang.' ak:'.$akun;
//        return;
        for($i=0;$i<count($akun);$i++) {  
            
           $mnoakun .= $akun[$i];
           if($i<count($akun)-1){
               $mnoakun .=', ';
           }
        }
        $arrrest=array();
        
        if (count($akun)== 0){
           $res=$this->bukubesar_model->get_akun_child();
           for($i=0;$i<count($res);$i++){
               $akun[$i]=$res[$i]->kd_akun;
           }
           
            
        }
        
        $saldotrx=0;
        $saldod=0;
        $saldok=0;
        $jmld=null;
        $jmlk=null;
        foreach ($akun as $value) {        
            $saldotrx=0;
            $saldod=0;
            $saldok=0;
            $result=array();
           $result= $this->bukubesar_model->get_view2($value,$tglawal,$tglakhir,$kd_cabang);
           
           foreach($result as $v){
               $jmld=null;
                $jmlk=null;
               if($v->keterangan=='Saldo Awal'){
                   $saldotrx += $v->jumlah;
                   $jmld=null;
                   array_push($arrrest, array(
//                    "nomor"=>$v->nomor,
                    "tgl_transaksi"=>"Account:",
                    "idjurnal"=>NULL,
                    "novoucher"=>NULL,  
                    "keterangan"=>$v->kd_akun." ".$v->nama,
                    "keterangan_detail"=>NULL,
                    "costcenter"=>NULL,
                   "cabang"=>NULL,
//                    "kd_akun"=>$v->kd_akun,
//                    "nama"=>$v->nama,
//                    "dk_transaksi"=>$v->dk_transaksi,
                   "jumlahd"=>$jmld,
                   "jumlahk"=>$jmlk,
                    "jumlah"=>$saldotrx
                   ));
               }
               if($v->keterangan=='Saldo Akhir'){
                   $jmld = $saldod;
                   $jmlk=$saldok;
               }
               
               if($v->keterangan!='Saldo Akhir' && $v->keterangan!='Saldo Awal'){
                   if($v->dk_transaksi=='D') {
                       $jmld=abs($v->jumlah);
                       $saldod +=abs($v->jumlah);
                       }
                   if($v->dk_transaksi=='K') {
                       $jmlk=abs($v->jumlah);
                       $saldok +=abs($v->jumlah);
                   }                
                   $saldotrx += $v->jumlah;
               }
               
               
               
               array_push($arrrest, array(
//                    "nomor"=>$v->nomor,
                    "tgl_transaksi"=>$v->tgl_transaksi,
                   "idjurnal"=>$v->idjurnal,
                    "novoucher"=>$v->novoucher,  
                    "keterangan"=>$v->keterangan,
                    "keterangan_detail"=>$v->keterangan_detail,
                    "costcenter"=>$v->costcenter,
                   "cabang"=>$v->cabang,
//                    "kd_akun"=>$v->kd_akun,
//                    "nama"=>$v->nama,
//                    "dk_transaksi"=>$v->dk_transaksi,
                   "jumlahd"=>$jmld,
                   "jumlahk"=>$jmlk,
                    "jumlah"=>$saldotrx
                   )
               );
           }
           
        }
        
        if (!$arrrest)
            show_404('page');
//        echo $mnoakun;
      
        if ($kd_cabang) {
            $kd_cabang = strtoupper($nm_cabang);
            
        } else {
            $kd_cabang = "Semua Cabang";
        }
        
        if($mnoakun==''){
            $mnoakun='Semua Akun';
        }
        
        $periode=date('d/m/Y',  strtotime($tglawal)). ' - '.date('d/m/Y',  strtotime($tglakhir));
        
        $this->load->library('Bukubesar_pdf');
//        '21',29.7
        $pdf = new Bukubesar_pdf('P','mm',array(215,297));
        $pdf->AliasNbPages();
        $pdf->SetFont('courier', '', 14);
        $pdf->SetMargins(5,5,5);
        $pdf->setNoakun($mnoakun);
        $pdf->setCabang($kd_cabang);
        $pdf->setPeriode($periode);
        $pdf->AddPage('P');    
        $pdf->create_pdf($arrrest);
//        $pdf->create_pdf($data['header'], $data['detail']);
        $pdf->Output("bukubesarprint", "I");
    }
    
}

?>
