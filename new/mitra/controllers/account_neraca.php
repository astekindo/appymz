<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_neraca
 *
 * @author miyzan
 */
class account_neraca extends MY_Controller {
    
    //put your code here
    
    public function __construct() {
        parent::__construct();
        $this->load->model('account_neraca_model', 'nr_model');
    }
    
    public function get_child_level2($findhead, $child, $level) {
        $resArr = array();
       
        foreach ($child as $c) {
            if ($c["parent_kd_akun"] == $findhead) {
                $levelt = $level + 1;
                $arrget = $this->get_child_level2($c["kd_akun"], $child, $levelt);
//                echo json_encode($arrget);
                if (count($arrget) > 0) {
//                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $c->jumlah2,'jumlah' => $c->jumlah,'saldo' => $c->saldo,'cls'=>'x-bls-header2' ));
                    array_push($resArr, array(                        
                        "groupname" => $c["groupname"],                       
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "subtotal" => NULL, "total" => NULL                       
                        , 'cls' => 'x-bls-header2'
                    ));
                    
//                    echo json_encode($arrget);
                    
                    $saldo=0;           
                    
                    foreach ($arrget as $ag) {
                       
                        if(is_null($ag['subtotal'])){
                            $ag['subtotal']=0;
                        }

                        $saldo=$saldo+ $ag['subtotal'];
                        array_push($resArr, array(                            
                            "groupname" => NULL,                            
                            "isheader" => $ag['header_status'],
                            "kd_akun" => $ag['kd_akun'],
                            "nama" => $ag['nama'],
                            "subtotal" => $ag['subtotal'], "total" => NULL                           
                            , 'cls' => NULL
                        ));
                    }
                     array_push($resArr, array(                            
                            "groupname" => $c["groupname"],                            
                            "isheader" => $c['header_status'],
                            "kd_akun" => $c['kd_akun'],
                            "nama" => 'TOTAL '.$c['nama'],
                            "subtotal" => $saldo, "total" => NULL                            
                            , 'cls' => 'x-bls-header5'
                        ));
                } else {

                    $saldo=0;

                    if(is_null($c['saldo'])){
                        $saldo=0;
                    }else{
                        $saldo=$c['saldo'];
                    }
                    array_push($resArr, array(                        
                        "groupname" => $c["groupname"],                       
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "subtotal" => $saldo, "total" => NULL                       
                        , 'cls' =>NULL
                    ));
                }
            }
        }
       
        return $resArr;
    }
    public function get_child_level($findhead, $child, $level) {
        $resArr = array();
       
        foreach ($child as $c) {
            if ($c["parent_kd_akun"] == $findhead) {
                $levelt = $level + 1;
                $arrget = $this->get_child_level2($c["kd_akun"], $child, $levelt);
//                echo json_encode($arrget);
                if (count($arrget) > 0) {
//                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $c->jumlah2,'jumlah' => $c->jumlah,'saldo' => $c->saldo,'cls'=>'x-bls-header2' ));
                    array_push($resArr, array(                        
                        "groupname" => $c["groupname"],                       
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "subtotal" => NULL, "total" => NULL                       
                        , 'cls' => 'x-bls-header1'
                    ));
                    
//                    echo json_encode($arrget);
                    
                    $saldo=0;           
                    
                    foreach ($arrget as $ag) {
                       
                        if(is_null($ag['subtotal'])){
                            $ag['subtotal']=0;
                        }

                        $saldo=$saldo+ $ag['subtotal'];
                        array_push($resArr, array(                            
                            "groupname" => NULL,                            
                            "isheader" => $ag['isheader'],
                            "kd_akun" => $ag['kd_akun'],
                            "nama" => $ag['nama'],
                            "subtotal" => $ag['subtotal'], "total" => $ag['total']                           
                            , 'cls' => $ag['cls']
                        ));
                    }
                     array_push($resArr, array(                            
                            "groupname" => $c["groupname"],                            
                            "isheader" => $c['header_status'],
                            "kd_akun" => $c['kd_akun'],
                            "nama" => 'TOTAL '.$c['nama'],
                            "subtotal" => $saldo, "total" => NULL                            
                            , 'cls' => 'x-bls-header4'
                        ));
                } else {

                    $saldo=0;

                    if(is_null($c['saldo'])){
                        $saldo=0;
                    }else{
                        $saldo=$c['saldo'];
                    }
                    array_push($resArr, array(                        
                        "groupname" => $c["groupname"],                       
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "subtotal" => $saldo, "total" => NULL                       
                        , 'cls' =>NULL
                    ));
                }
            }
        }
       
        return $resArr;
    }
    public function get_max_level($head, $child) {
        $resArr = array();
        $level = 0;
        $rec = 0;
        $group_name = "";
        $totalall=0;
//        $tohit=0;
        
        foreach ($head as $h) {
            $rec++;
//             echo $h->kd_akun,',',$h->nama;
            if ($group_name!= $h->nama){
                if($group_name!=""){
                    array_push($resArr, array(               
                "groupname" => 'TOTAL '.$group_name,                
                "isheader" => "1",
                "kd_akun" => NULL,
                "nama" => NULL,
                "subtotal" => NULL, "total" => $totalall
                , 'cls' => 'x-bls-header6'
            ));
                    $totalall=0;
                }
                array_push($resArr, array(               
                "groupname" => $h->nama,                
                "isheader" => $h->header_status,
                "kd_akun" => NULL,
                "nama" => NULL,
                "subtotal" => NULL, "total" => NULL
                , 'cls' => 'x-bls-header'
            ));
                $group_name= $h->nama;
            }
            
            
            $arrch = $this->get_child_level($h->kd_akun, $child, $level);
            
            if (count($arrch) > 0) {              
                foreach ($arrch as $ac) {       
                    if ($ac['isheader']!=1){
                        $totalall +=$ac['subtotal'];
                    }                    
                    array_push($resArr, $ac);
            }

            $level = 0;
        }       
        
    }
     array_push($resArr, array(               
                "groupname" => 'TOTAL '.$group_name,                
                "isheader" => "1",
                "kd_akun" => NULL,
                "nama" => NULL,
                "subtotal" => NULL, "total" => $totalall
                , 'cls' => 'x-bls-header6'
            ));
    return $resArr;
    }
    public function getSa($kdakun, $sa) {
        $retval = array("saldo" => "0");

        foreach ($sa as $v) {
//            echo $v["kd_akun"].$v->saldod.$v->saldok ;
            if ($v["kd_akun"] == $kdakun) {
                $retval = array("saldo" => $v["saldo"]);
                return $retval;
            }
        }
        return $retval;
    }
    
    public function get_max_bls($loop_count, $child_d, $child_k) {
        $resArr = array();
        $level = 0;
        $total_aktiva=0;
        $total_passiva=0;
//        $arrField=array('jenis','rekening','subtotal','total','cls');
        for ($i = 0; $i < $loop_count; $i++) {

//            $arr_k = $child_k[$i];
            if ($i < count($child_d)) {
                $arr_d = $child_d[$i];
                $jenis_d = $arr_d['groupname'];
                $kd_akun_d = $arr_d['kd_akun'];
                $nama_d = $arr_d['nama'];
                $subtotal_d = $arr_d['subtotal'];
                $total_d = $arr_d['total'];
                $cls_d=$arr_d['cls'];
                $isheader_d=$arr_d['isheader'];
            } else {
                $arr_d = NULL;
                $jenis_d = NULL;
                $kd_akun_d = NULL;
                $nama_d = NULL;
                $subtotal_d = NULL;
                $total_d = NULL;
                $cls_d=NULL;
                $isheader_d=Null;
            }

            if ($i < count($child_k)) {
                $arr_k = $child_k[$i];
                $jenis_k = $arr_k['groupname'];
                $kd_akun_k = $arr_k['kd_akun'];
                $nama_k = $arr_k['nama'];
                $subtotal_k = $arr_k['subtotal'];
                $total_k = $arr_k['total'];
                $cls_k=$arr_k['cls'];
                $isheader_k=$arr_k['isheader'];
            } else {
                $arr_k = NULL;
                $jenis_k = NULL;
                $kd_akun_k = NULL;
                $nama_k = NULL;
                $subtotal_k = NULL;
                $total_k = NULL;
                $cls_k=NULL;
                $isheader_k=NULL;
            }
            if($total_d){
                $total_aktiva=$total_aktiva+$total_d;
            }
            if($total_k){               
                        $total_passiva=$total_passiva+$total_k;
            }
//            echo 'Totalpassiva='.$total_passiva,',---\n';

//            $arr = array(
//                'groupname_a' => $jenis_d,
//                'kd_akun_a' => $kd_akun_d,
//                'nama_a' => $nama_d,
//                'subtotal_a' => $subtotal_d,
//                'total_a' => $total_d,
//                'cls_a' => $cls_d,
//                'isheader_a'=>$isheader_d,
//                 'groupname_p' => $jenis_k,
//                'kd_akun_p' => $kd_akun_k,
//                'nama_p' => $nama_k,
//                'subtotal_p' => $subtotal_k,
//                'total_p' => $total_k,
//                'cls_p' => $cls_k,
//                'isheader_p'=>$isheader_k
//                );
            $arr = array(
                'groupname_a' => $jenis_d,
                'kd_akun_a' => $kd_akun_d,
                'nama_a' => $nama_d,
                'subtotal_a' => $total_d ? $total_d : $subtotal_d,
                'total_a' => NULL,
                'cls_a' => $cls_d,
                'isheader_a'=>$isheader_d,
                 'groupname_p' => $jenis_k,
                'kd_akun_p' => $kd_akun_k,
                'nama_p' => $nama_k,
                'subtotal_p' => $total_k ? $total_k : $subtotal_k,
                'total_p' => NULL,
                'cls_p' => $cls_k,
                'isheader_p'=>$isheader_k
                );
            array_push($resArr, $arr);
            
        }
        
        $arr = array(
             'groupname_a' => 'TOTAL AKTIVA',
                'kd_akun_a' => NULL,
                'nama_a' => NULL,
                'subtotal_a' => $total_aktiva,
                'total_a' =>NULL,
                'cls_a' => 'x-bls-header3',
                 'groupname_p' => 'TOTAL PASSIVA',
                'kd_akun_p' => null,
                'nama_p' => NULL,
                'subtotal_p' => $total_passiva,
                'total_p' => NULL,
                'cls_p' => 'x-bls-header3'
            );
        array_push($resArr, $arr);
        return $resArr;
    }
    public function get_periode_thbl($thbl,$v) {       
        $dt=substr($thbl, 0, 4).'-'.substr($thbl, 4, 2).'-01';        
        $current_date = date('Y-m-d',strtotime($dt));
        return date('Ym', strtotime($v.' month', strtotime($current_date)));
    }
    public function get_rows() {
        $thbl=isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
//        $thbl='201312';
//        $kd_cabang=NULL;
        $head_d = $this->nr_model->getHeader('D');
        $head_k = $this->nr_model->getHeader('K');
        
        $child_d = $this->nr_model->getchild('D');
        $child_k = $this->nr_model->getchild('K');
        
        $childdetail_d = $this->nr_model->getchild_detail('D');
        $childdetail_k = $this->nr_model->getchild_detail('K');
        
        $childsaldo_d = $this->nr_model->getSaldo($childdetail_d,$thbl,$kd_cabang);
        $childsaldo_k = $this->nr_model->getSaldo($childdetail_k,$thbl,$kd_cabang);
//        echo json_encode($childsaldo_k);
        
        $child_d_new=array();
        $child_k_new=array();
        
        $childsaldo_lr_thberjalan = $this->nr_model->get_rugilaba_thberjalan($thbl,$kd_cabang);
        $childsaldo_lr_blberjalan = $this->nr_model->get_rugilaba_blberjalan($thbl,$kd_cabang);
//        echo $childsaldo_lr_thberjalan;
//        return;
        foreach ($child_d as $v) {

            $saldo = $this->getSa($v->kd_akun, $childsaldo_d);
            

            array_push($child_d_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
        // saldo th berjalan 430.0001 saldo bl berjalan 440.0001
        foreach ($child_k as $v) {
            $saldo=array();            
            if($v->kd_akun=='430.0001'){
                $saldo=array("saldo" => $childsaldo_lr_thberjalan);
            }elseif ($v->kd_akun=='440.0001') {
                $saldo=array("saldo" => $childsaldo_lr_blberjalan);
            }
            else{
                $saldo = $this->getSa($v->kd_akun, $childsaldo_k);
            }
            
            
            array_push($child_k_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
//        echo json_encode($child_k_new);
//        return;
        $child_arr_d = $this->get_max_level($head_d, $child_d_new);
        $child_arr_k = $this->get_max_level($head_k, $child_k_new);
//        return;
            
//        echo json_encode($child_arr_d);
//        return;
        $loop_count = count($child_arr_d);
        if (count($child_arr_k) > count($child_arr_d)) {
            $loop_count = count($child_arr_k);
        }
//        echo $loop_count;
        $result = array();
        $result = $this->get_max_bls($loop_count, $child_arr_d, $child_arr_k);
        $thbl_t=$this->get_periode_thbl($thbl,'-1');
        $result_t=array();
        $result_t = $this->get_rows_t($thbl_t,$kd_cabang);
        for($i=0;$i<count($result);$i++){
           $kda = $result[$i]['kd_akun_a'];
           $kdp = $result[$i]['kd_akun_p'];
           foreach($result_t as $vt){
               if($vt['kd_akun_a']==$kda && $vt['groupname_a']==$result[$i]['groupname_a'] && $vt['nama_a']==$result[$i]['nama_a']){
                   $result[$i]['total_a']=$vt['subtotal_a'];
               }
           }
           foreach($result_t as $vt){
               if($vt['kd_akun_p']==$kdp && $vt['groupname_p']==$result[$i]['groupname_p'] && $vt['nama_p']==$result[$i]['nama_p']){
                   $result[$i]['total_p']=$vt['subtotal_p'];
               }
           }
        }
        $total = count($result);
        echo '{success:true,record:' . $total . ',data:' . json_encode($result) . '}';
    }
    
    public function get_rows_t($thbl,$kd_cabang) {
//        $thbl=isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
//        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
//        $thbl='201312';
//        $kd_cabang=NULL;
        $head_d = $this->nr_model->getHeader('D');
        $head_k = $this->nr_model->getHeader('K');
        
        $child_d = $this->nr_model->getchild('D');
        $child_k = $this->nr_model->getchild('K');
        
        $childdetail_d = $this->nr_model->getchild_detail('D');
        $childdetail_k = $this->nr_model->getchild_detail('K');
        
        $childsaldo_d = $this->nr_model->getSaldo($childdetail_d,$thbl,$kd_cabang);
        $childsaldo_k = $this->nr_model->getSaldo($childdetail_k,$thbl,$kd_cabang);
//        echo json_encode($childsaldo_k);
        
        $child_d_new=array();
        $child_k_new=array();
        
        $childsaldo_lr_thberjalan = $this->nr_model->get_rugilaba_thberjalan($thbl,$kd_cabang);
        $childsaldo_lr_blberjalan = $this->nr_model->get_rugilaba_blberjalan($thbl,$kd_cabang);
//        echo $childsaldo_lr_thberjalan;
//        return;
        foreach ($child_d as $v) {

            $saldo = $this->getSa($v->kd_akun, $childsaldo_d);
            

            array_push($child_d_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
        // saldo th berjalan 430.0001 saldo bl berjalan 440.0001
        foreach ($child_k as $v) {
            $saldo=array();            
            if($v->kd_akun=='430.0001'){
                $saldo=array("saldo" => $childsaldo_lr_thberjalan);
            }elseif ($v->kd_akun=='440.0001') {
                $saldo=array("saldo" => $childsaldo_lr_blberjalan);
            }
            else{
                $saldo = $this->getSa($v->kd_akun, $childsaldo_k);
            }
            
            
            array_push($child_k_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
//        echo json_encode($child_k_new);
//        return;
        $child_arr_d = $this->get_max_level($head_d, $child_d_new);
        $child_arr_k = $this->get_max_level($head_k, $child_k_new);
//        return;
            
//        echo json_encode($child_arr_d);
//        return;
        $loop_count = count($child_arr_d);
        if (count($child_arr_k) > count($child_arr_d)) {
            $loop_count = count($child_arr_k);
        }
//        echo $loop_count;
        $result = array();
        $result = $this->get_max_bls($loop_count, $child_arr_d, $child_arr_k);
//        for($i=0;$i<count($result);$i++){
//          echo $i.' '. $result[$i]['kd_akun_a'];
//        }
        return $result;
//        $total = count($result);
//        echo '{success:true,record:' . $total . ',data:' . json_encode($result) . '}';
    }
    public function getFormatMY($thbl) {
        $str = date("Y F", strtotime($thbl . '01'));
        return $str;
    }
    
    public function histo_neraca($thbl, $kd_cabang) {
        $head_d = $this->nr_model->getHeader('D');
        $head_k = $this->nr_model->getHeader('K');
        
        $child_d = $this->nr_model->getchild('D');
        $child_k = $this->nr_model->getchild('K');
        
        $childdetail_d = $this->nr_model->getchild_detail('D');
        $childdetail_k = $this->nr_model->getchild_detail('K');
        
        $childsaldo_d = $this->nr_model->getSaldo($childdetail_d,$thbl,$kd_cabang);
        $childsaldo_k = $this->nr_model->getSaldo($childdetail_k,$thbl,$kd_cabang);
        
        $child_d_new=array();
        $child_k_new=array();
        
        $childsaldo_lr_thberjalan = $this->nr_model->get_rugilaba_thberjalan($thbl,$kd_cabang);
        $childsaldo_lr_blberjalan = $this->nr_model->get_rugilaba_blberjalan($thbl,$kd_cabang);

        foreach ($child_d as $v) {

            $saldo = $this->getSa($v->kd_akun, $childsaldo_d);
            

            array_push($child_d_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
        // saldo th berjalan 430.0001 saldo bl berjalan 440.0001
        foreach ($child_k as $v) {
            $saldo=array();            
            if($v->kd_akun=='430.0001'){
                $saldo=array("saldo" => $childsaldo_lr_thberjalan);
            }elseif ($v->kd_akun=='440.0001') {
                $saldo=array("saldo" => $childsaldo_lr_blberjalan);
            }
            else{
                $saldo = $this->getSa($v->kd_akun, $childsaldo_k);
            }
            
            
            array_push($child_k_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }

        $child_arr_d = $this->get_max_level($head_d, $child_d_new);
        $child_arr_k = $this->get_max_level($head_k, $child_k_new);

        $loop_count = count($child_arr_d);
        if (count($child_arr_k) > count($child_arr_d)) {
            $loop_count = count($child_arr_k);
        }


        $result = $this->get_max_bls($loop_count, $child_arr_d, $child_arr_k);
        if ($result) {
            foreach ($result as $v) {
                $datan = array();
                $datan = array(
                'groupname_a' => $v['groupname_a'],
                'kd_akun_a' => $v['kd_akun_a'],
                'nama_a' => $v['nama_a'],
                'subtotal_a' => $v['subtotal_a'],
                'total_a' => $v['total_a'],
                'cls_a' => $v['cls_a'],
                'isheader_a'=>$v['isheader_a'],
                 'groupname_p' => $v['groupname_p'],
                'kd_akun_p' => $v['kd_akun_p'],
                'nama_p' => $v['nama_p'],
                'subtotal_p' => $v['subtotal_p'],
                'total_p' => $v['total_p'],
                'cls_p' => $v['cls_p'],
                'isheader_p'=>$v['isheader_p']
                );
                $this->nr_model->insert_row('acc.t_histo_neraca',$datan);
            }
        }
//        echo var_dump($result);
    }
    
    public function print_form($thbl, $kd_cabang, $nmcabang){
//        $thbl='201312';
//        $kd_cabang=NULL;
//        $nmcabang=NULL;
        
        $head_d = $this->nr_model->getHeader('D');
        $head_k = $this->nr_model->getHeader('K');
        
        $child_d = $this->nr_model->getchild('D');
        $child_k = $this->nr_model->getchild('K');
        
        $childdetail_d = $this->nr_model->getchild_detail('D');
        $childdetail_k = $this->nr_model->getchild_detail('K');
        
        $childsaldo_d = $this->nr_model->getSaldo($childdetail_d,$thbl,$kd_cabang);
        $childsaldo_k = $this->nr_model->getSaldo($childdetail_k,$thbl,$kd_cabang);
        
        $child_d_new=array();
        $child_k_new=array();
        
        $childsaldo_lr_thberjalan = $this->nr_model->get_rugilaba_thberjalan($thbl,$kd_cabang);
        $childsaldo_lr_blberjalan = $this->nr_model->get_rugilaba_blberjalan($thbl,$kd_cabang);

        foreach ($child_d as $v) {

            $saldo = $this->getSa($v->kd_akun, $childsaldo_d);
            

            array_push($child_d_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }
        // saldo th berjalan 430.0001 saldo bl berjalan 440.0001
        foreach ($child_k as $v) {
            $saldo=array();            
            if($v->kd_akun=='430.0001'){
                $saldo=array("saldo" => $childsaldo_lr_thberjalan);
            }elseif ($v->kd_akun=='440.0001') {
                $saldo=array("saldo" => $childsaldo_lr_blberjalan);
            }
            else{
                $saldo = $this->getSa($v->kd_akun, $childsaldo_k);
            }
            
            
            array_push($child_k_new, array(                
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldo" => $saldo["saldo"]                
                    )
            );
        }

        $child_arr_d = $this->get_max_level($head_d, $child_d_new);
        $child_arr_k = $this->get_max_level($head_k, $child_k_new);

        $loop_count = count($child_arr_d);
        if (count($child_arr_k) > count($child_arr_d)) {
            $loop_count = count($child_arr_k);
        }


        $result = $this->get_max_bls($loop_count, $child_arr_d, $child_arr_k);
        $thbl_t=$this->get_periode_thbl($thbl,'-1');
        $result_t=array();
        $result_t = $this->get_rows_t($thbl_t,$kd_cabang);
        for($i=0;$i<count($result);$i++){
           $kda = $result[$i]['kd_akun_a'];
           $kdp = $result[$i]['kd_akun_p'];
           foreach($result_t as $vt){
               if($vt['kd_akun_a']==$kda && $vt['groupname_a']==$result[$i]['groupname_a'] && $vt['nama_a']==$result[$i]['nama_a']){
                   $result[$i]['total_a']=$vt['subtotal_a'];
               }
           }
           foreach($result_t as $vt){
               if($vt['kd_akun_p']==$kdp && $vt['groupname_p']==$result[$i]['groupname_p'] && $vt['nama_p']==$result[$i]['nama_p']){
                   $result[$i]['total_p']=$vt['subtotal_p'];
               }
           }
        }
        $thblconvert = $this->getFormatMY($thbl);
        if (!$result)
            show_404('page');
        if (!$kd_cabang) {
            $kd_cabang = "Semua Cabang";
        } else {
            $kd_cabang = "cabang " . $nmcabang;
        }
        $filter=array();
        $filter[0]=array('0'=>'NERACA','1'=>'B');
        $filter[1]=array('0'=>$thblconvert,'1'=>'');
        $filter[2]=array('0'=>$kd_cabang,'1'=>'');
        $this->load->library('neraca_pdf');
        $pdf= new neraca_pdf();     
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial','',14);
        $pdf->AddPage('L');
        $pdf->create_pdf($filter,$result);
        $pdf->Output("nrcprint","I");               
        
    }
}

?>
