<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_neracalajur
 *
 * @author faroq
 */
class account_neracalajur extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_neracalajur_model', 'nl_model');
//        $this->load->library('trialbalance_pdf');
    }

    public function get_child_level2($findhead, $child, $level) {
        $resArr = array();
//        print 'find '.$findhead."\n";

        foreach ($child as $c) {
//            echo $c->parent_kd_akun;
            if ($c["parent_kd_akun"] == $findhead) {
                $levelt = $level + 1;
                $arrget=array();
                $arrget = $this->get_child_level2($c["kd_akun"], $child, $levelt);
//                echo 'parent '.$findhead.'->'. $c["kd_akun"];
//                echo var_dump($arrget);
                if (count($arrget) > 0) {
//                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $c->jumlah2,'jumlah' => $c->jumlah,'saldo' => $c->saldo,'cls'=>'x-bls-header2' ));
                    array_push($resArr, array(
                        'jenis' => NULL,
                        "groupname" => $c["groupname"],
                        "groupakun" => $c["groupakun"],
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "saldoawald" => NULL, "saldoawalk" => NULL,
                        "mutasid" => NULL, "mutasik" => NULL,
                        "saldoakhird" => NULL, "saldoakhirk" => NULL,
                        "labarugid" => NULL, "labarugik" => NULL,
                        "neracad" => NULL, "neracak" => NULL
                        , 'cls' => 'x-bls-header2'
                    ));
                    foreach ($arrget as $ag) {
//                        if(is_null($ag['jumlah'])){
//                            $ag['jumlah']=0;
//                        }
//                        if(is_null($ag['jumlah2'])){
//                            $ag['jumlah2']=0;
//                        }
//                        if(is_null($ag['saldo'])){
//                            $ag['saldo']=0;
//                        }
//                        $jumlah=$jumlah+ $ag['jumlah'];
//                        $jumlah2=$jumlah2+ $ag['jumlah2'];
//                        $saldo=$saldo+ $ag['saldo'];
//                        array_push($resArr, array('jenis'=>NULL,'groupname'=>NULL,'groupakun'=>$ag['groupakun'],'isheader'=>$ag['header_status'],'kd_akun' => $ag['kd_akun'], 'nama' => $ag['nama'], 'jumlah2' =>$ag['jumlah2'],'jumlah' => $ag['jumlah'],'saldo' => $ag['saldo'],'cls'=>NULL ));
                        if($ag['header_status']==1){
                            array_push($resArr, array(
                            'jenis' => NULL,
                            "groupname" => NULL,
                            "groupakun" => $ag['groupakun'],
                            "isheader" => $ag['header_status'],
                            "kd_akun" => $ag['kd_akun'],
                            "nama" => $ag['nama'],
                            "saldoawald" => $ag['saldoawald'], "saldoawalk" => $ag['saldoawalk'],
                            "mutasid" => $ag['mutasid'], "mutasik" => $ag['mutasik'],
                            "saldoakhird" => $ag['saldoakhird'], "saldoakhirk" => $ag['saldoakhirk'],
                            "labarugid" => $ag['labarugid'], "labarugik" => $ag['labarugik'],
                            "neracad" => $ag['neracad'], "neracak" => $ag['neracak']
                            , 'cls' => 'x-bls-header2'
                        ));
                        }else{
                        array_push($resArr, array(
                            'jenis' => NULL,
                            "groupname" => NULL,
                            "groupakun" => $ag['groupakun'],
                            "isheader" => $ag['header_status'],
                            "kd_akun" => $ag['kd_akun'],
                            "nama" => $ag['nama'],
                            "saldoawald" => $ag['saldoawald'], "saldoawalk" => $ag['saldoawalk'],
                            "mutasid" => $ag['mutasid'], "mutasik" => $ag['mutasik'],
                            "saldoakhird" => $ag['saldoakhird'], "saldoakhirk" => $ag['saldoakhirk'],
                            "labarugid" => $ag['labarugid'], "labarugik" => $ag['labarugik'],
                            "neracad" => $ag['neracad'], "neracak" => $ag['neracak']
                            , 'cls' => NULL
                        ));
                        
                        }
                    }
//                     array_push($resArr, array('jenis'=>NULL,'groupname'=>NULL,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah2' =>$jumlah2,'jumlah' => $jumlah,'saldo' => $saldo,'cls'=>'x-bls-header2' ));
                } else {
//                    $jumlah=0;                    
//                    $jumlah2=0;
//                    $saldo=0;
//                    if(is_null($c->jumlah2)){
//                        $jumlah2=0;
//                    }else{
//                        $jumlah2=$c->jumlah2;
//                    }
//                    if(is_null($c->jumlah)){
//                        $jumlah=0;
//                    }else{
//                        $jumlah=$c->jumlah;
//                    }
//                    if(is_null($c->saldo)){
//                        $saldo=0;
//                    }else{
//                        $saldo=$c->saldo;
//                    }
//                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $jumlah2,'jumlah' => $jumlah,'saldo' => $saldo,'cls'=>NULL ));
                    array_push($resArr, array(
                        'jenis' => NULL,
                        "groupname" => $c["groupname"],
                        "groupakun" => $c["groupakun"],
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "saldoawald" => $c["saldoawald"], "saldoawalk" => $c["saldoawalk"],
                        "mutasid" => $c["mutasid"], "mutasik" => $c["mutasik"],
                        "saldoakhird" => $c["saldoakhird"], "saldoakhirk" => $c["saldoakhirk"],
                        "labarugid" => $c["labarugid"], "labarugik" => $c["labarugik"],
                        "neracad" => $c["neracad"], "neracak" => $c["neracak"]
                        , 'cls' => NULL
                    ));
                }
            }
        }

        return $resArr;
    }
    
    public function get_child_level($findhead, $child, $level) {
        $resArr = array();
//        print 'find '.$findhead."\n";

        foreach ($child as $c) {
//            echo $c->parent_kd_akun;
            if ($c["parent_kd_akun"] == $findhead) {
                $levelt = $level + 1;
                $arrget=array();
                $arrget = $this->get_child_level2($c["kd_akun"], $child, $levelt);
//                echo 'parent '.$findhead.'->'. $c["kd_akun"];
//                echo var_dump($arrget);
                if (count($arrget) > 0) {
//                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $c->jumlah2,'jumlah' => $c->jumlah,'saldo' => $c->saldo,'cls'=>'x-bls-header2' ));
                    array_push($resArr, array(
                        'jenis' => NULL,
                        "groupname" => $c["groupname"],
                        "groupakun" => $c["groupakun"],
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "saldoawald" => NULL, "saldoawalk" => NULL,
                        "mutasid" => NULL, "mutasik" => NULL,
                        "saldoakhird" => NULL, "saldoakhirk" => NULL,
                        "labarugid" => NULL, "labarugik" => NULL,
                        "neracad" => NULL, "neracak" => NULL
                        , 'cls' => 'x-bls-header2'
                    ));
                    foreach ($arrget as $ag) {
//                        if($ag['kd_akun']=='121.0000'){
//                            echo var_dump($ag);
//                        }
                         array_push($resArr, array(
                            'jenis' => $ag['jenis'],
                            "groupname" => $ag['groupname'],
                            "groupakun" => $ag['groupakun'],
                            "isheader" => $ag['isheader'],
                            "kd_akun" => $ag['kd_akun'],
                            "nama" => $ag['nama'],
                            "saldoawald" => $ag['saldoawald'], "saldoawalk" => $ag['saldoawalk'],
                            "mutasid" => $ag['mutasid'], "mutasik" => $ag['mutasik'],
                            "saldoakhird" => $ag['saldoakhird'], "saldoakhirk" => $ag['saldoakhirk'],
                            "labarugid" => $ag['labarugid'], "labarugik" => $ag['labarugik'],
                            "neracad" => $ag['neracad'], "neracak" => $ag['neracak']
                            , 'cls' => $ag['cls']
                        ));
                         
//                        if($ag['header_status']==1){
//                            array_push($resArr, array(
//                            'jenis' => NULL,
//                            "groupname" => NULL,
//                            "groupakun" => $ag['groupakun'],
//                            "isheader" => $ag['header_status'],
//                            "kd_akun" => $ag['kd_akun'],
//                            "nama" => $ag['nama'],
//                            "saldoawald" => $ag['saldoawald'], "saldoawalk" => $ag['saldoawalk'],
//                            "mutasid" => $ag['mutasid'], "mutasik" => $ag['mutasik'],
//                            "saldoakhird" => $ag['saldoakhird'], "saldoakhirk" => $ag['saldoakhirk'],
//                            "labarugid" => $ag['labarugid'], "labarugik" => $ag['labarugik'],
//                            "neracad" => $ag['neracad'], "neracak" => $ag['neracak']
//                            , 'cls' => 'x-bls-header2'
//                        ));
//                        }else{
//                        array_push($resArr, array(
//                            'jenis' => NULL,
//                            "groupname" => NULL,
//                            "groupakun" => $ag['groupakun'],
//                            "isheader" => $ag['header_status'],
//                            "kd_akun" => $ag['kd_akun'],
//                            "nama" => $ag['nama'],
//                            "saldoawald" => $ag['saldoawald'], "saldoawalk" => $ag['saldoawalk'],
//                            "mutasid" => $ag['mutasid'], "mutasik" => $ag['mutasik'],
//                            "saldoakhird" => $ag['saldoakhird'], "saldoakhirk" => $ag['saldoakhirk'],
//                            "labarugid" => $ag['labarugid'], "labarugik" => $ag['labarugik'],
//                            "neracad" => $ag['neracad'], "neracak" => $ag['neracak']
//                            , 'cls' => NULL
//                        ));
//                        
//                        }
                    }
//                     array_push($resArr, array('jenis'=>NULL,'groupname'=>NULL,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah2' =>$jumlah2,'jumlah' => $jumlah,'saldo' => $saldo,'cls'=>'x-bls-header2' ));
                } else {
                    array_push($resArr, array(
                        'jenis' => NULL,
                        "groupname" => $c["groupname"],
                        "groupakun" => $c["groupakun"],
                        "isheader" => $c["header_status"],
                        "kd_akun" => $c["kd_akun"],
                        "nama" => $c["nama"],
                        "saldoawald" => $c["saldoawald"], "saldoawalk" => $c["saldoawalk"],
                        "mutasid" => $c["mutasid"], "mutasik" => $c["mutasik"],
                        "saldoakhird" => $c["saldoakhird"], "saldoakhirk" => $c["saldoakhirk"],
                        "labarugid" => $c["labarugid"], "labarugik" => $c["labarugik"],
                        "neracad" => $c["neracad"], "neracak" => $c["neracak"]
                        , 'cls' => NULL
                    ));
                }
            }
        }

        return $resArr;
    }

    public function get_max_level($thbl, $head, $child) {
        $resArr = array();
        $level = 0;
        $rec = 0;
        $group_name = "";

        $totalall = 0;
        $totalall1 = 0;
        $totalall2 = 0;

        $totalpendapatan = 0;
        $totalpendapatan1 = 0;
        $totalpendapatan2 = 0;

        $totalbiaya = 0;
        $totalbiaya1 = 0;
        $totalbiaya2 = 0;

//        print json_encode($child)."\n";
//        array_push($resArr, array('groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>NULL,'total' => $jumlah ));
        foreach ($head as $h) {
            $rec++;
            //array_push($resArr, array('kd_akun' => $level.'-'.$h->kd_akun,'parent_kd_akun' =>''));
            if ($h->groupakun != $group_name) {

//                if($group_name!=""){
//                    $totalpendapatan=$totalall;
//                    $totalpendapatan1=$totalall1;
//                    $totalpendapatan2=$totalall2;
//                    array_push($resArr, array('jenis'=>'TOTAL '.$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>$totalall1,'jumlah' => $totalall,'saldo' => $totalall2,'cls'=>'x-bls-header' ));
//                    $totalall=0;
//                    $totalall1=0;
//                    $totalall2=0;
//                }
                $group_name = $h->groupakun;

//                array_push($resArr, array('jenis'=>$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>$this->get_periode_thbl($thbl, '-1'),'jumlah' => $this->get_periode_thbl($thbl, '+0'),'saldo' => 'TH-'.substr($thbl, 0, 4),'cls'=>'x-bls-header' ));
                array_push($resArr, array(
                    'jenis' => $group_name,
                    "groupname" => NULL,
                    "groupakun" => $h->groupakun,
                    "isheader" => $h->header_status,
                    "kd_akun" => NULL,
                    "nama" => NULL,
                    "saldoawald" => NULL, "saldoawalk" => NULL,
                    "mutasid" => NULL, "mutasik" => NULL,
                    "saldoakhird" => NULL, "saldoakhirk" => NULL,
                    "labarugid" => NULL, "labarugik" => NULL,
                    "neracad" => NULL, "neracak" => NULL
                    , 'cls' => 'x-bls-header'
                ));
            }
//            array_push($resArr, $h);
//            array_push($resArr, array('jenis'=>NULL,'groupname'=>$h->nama,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>NULL,'jumlah' => NULL,'saldo' => NULL,'cls'=>'x-bls-header1' ));
            array_push($resArr, array(
                'jenis' => NULL,
                "groupname" => $h->nama,
                "groupakun" => $h->groupakun,
                "isheader" => $h->header_status,
                "kd_akun" => NULL,
                "nama" => NULL,
                "saldoawald" => NULL, "saldoawalk" => NULL,
                "mutasid" => NULL, "mutasik" => NULL,
                "saldoakhird" => NULL, "saldoakhirk" => NULL,
                "labarugid" => NULL, "labarugik" => NULL,
                "neracad" => NULL, "neracak" => NULL
                , 'cls' => 'x-bls-header1'
            ));
            $arrch = $this->get_child_level($h->kd_akun, $child, $level);

            if (count($arrch) > 0) {
                $jumlah = 0;
                $jumlah2 = 0;
                $saldo = 0;
//                echo var_dump($arrch);
                foreach ($arrch as $ac) {
                    array_push($resArr, $ac);
//                    if(!$ac['isheader']){
//                        $jumlah += $ac['jumlah'];
//                          $jumlah2 += $ac['jumlah2'];
//                            $saldo += $ac['saldo'];
//                    }
                }
//                
//                        $totalall +=$jumlah;
//                        $totalall1+=$jumlah2;
//                    $totalall2+=$saldo;
//                
//                array_push($resArr, array('jenis'=>NULL,'groupname'=>'TOTAL '.$h->nama,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>$jumlah2,'jumlah' => $jumlah,'saldo' => $saldo,'cls'=>'x-bls-header1' ));
//                
            }

            $level = 0;
//            if ($rec==count($head)){
//                array_push($resArr, array('jenis'=>'TOTAL '.$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>$totalall1,'jumlah' => $totalall,'saldo' => $totalall2,'cls'=>'x-bls-header' ));
//                $totalbiaya=$totalall;
//                $totalbiaya1=$totalall1;
//                    $totalbiaya2=$totalall2;
//                $totalall=$totalpendapatan-$totalbiaya;
//                $totalall1=$totalpendapatan1-$totalbiaya1;
//                $totalall2=$totalpendapatan2-$totalbiaya2;
//                array_push($resArr, array('jenis'=>' RUGI LABA ','groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah2' =>$totalall1,'jumlah' => $totalall,'saldo' => $totalall2,'cls'=>'x-bls-header3' ));
//            }
        }

        $jumlah_sawd = 0;
        $jumlah_sawk = 0;

        $jumlah_mutd = 0;
        $jumlah_mutk = 0;

        $jumlah_sakd = 0;
        $jumlah_sakk = 0;

        $jumlah_lrd = 0;
        $jumlah_lrk = 0;

        $jumlah_nrd = 0;
        $jumlah_nrk = 0;

        foreach ($resArr as $v) {
            if ($v["isheader"] == 0) {
                $jumlah_sawd += $v["saldoawald"];
                $jumlah_sawk += $v["saldoawalk"];

                $jumlah_mutd += $v["mutasid"];
                $jumlah_mutk += $v["mutasik"];

                $jumlah_sakd += $v["saldoakhird"];
                $jumlah_sakk += $v["saldoakhirk"];

                $jumlah_lrd += $v["labarugid"];
                $jumlah_lrk += $v["labarugik"];

                $jumlah_nrd += $v["neracad"];
                $jumlah_nrk += $v["neracak"];
            }
        }
        array_push($resArr, array(
            'jenis' => "JUMLAH",
            "groupname" => "JUMLAH",
            "groupakun" => NULL,
            "isheader" => 1,
            "kd_akun" => NULL,
            "nama" => NULL,
            "saldoawald" => $jumlah_sawd, "saldoawalk" => $jumlah_sawk,
            "mutasid" => $jumlah_mutd, "mutasik" => $jumlah_mutk,
            "saldoakhird" => $jumlah_sakd, "saldoakhirk" => $jumlah_sakk,
            "labarugid" => $jumlah_lrd, "labarugik" => $jumlah_lrk,
            "neracad" => $jumlah_nrd, "neracak" => $jumlah_nrk
            , 'cls' => 'x-bls-header'
        ));
        $selisih_sawd = 0;
        $selisih_sawk = 0;
        $selisih_mutd = 0;
        $selisih_mutk = 0;
        $selisih_sakd = 0;
        $selisih_sakk = 0;
        $selisih_lrd = 0;
        $selisih_lrk = 0;
        $selisih_nrd = 0;
        $selisih_nrk = 0;



        if ($jumlah_sawd > $jumlah_sawk) {
            $selisih_sawk = $jumlah_sawd - $jumlah_sawk;
        }
        if ($jumlah_sawd < $jumlah_sawk) {
            $selisih_sawd = $jumlah_sawk - $jumlah_sawd;
        }

        if ($jumlah_mutd > $jumlah_mutk) {
            $selisih_mutk = $jumlah_mutd - $jumlah_mutk;
        }
        if ($jumlah_mutd < $jumlah_mutk) {
            $selisih_mutd = $jumlah_mutk - $jumlah_mutd;
        }
        if ($jumlah_sakd > $jumlah_sakk) {
            $selisih_sakk = $jumlah_sakd - $jumlah_sakk;
        }
        if ($jumlah_sakd < $jumlah_sakk) {
            $selisih_sakd = $jumlah_sakk - $jumlah_sakd;
        }
        if ($jumlah_lrd > $jumlah_lrk) {
            $selisih_lrk = $jumlah_lrd - $jumlah_lrk;
        }
        if ($jumlah_lrd < $jumlah_lrk) {
            $selisih_lrd = $jumlah_lrk - $jumlah_lrd;
        }
        if ($jumlah_nrd > $jumlah_nrk) {
            $selisih_nrk = $jumlah_nrd - $jumlah_nrk;
        }
        if ($jumlah_nrd < $jumlah_nrk) {
            $selisih_nrd = $jumlah_nrk - $jumlah_nrd;
        }

        array_push($resArr, array(
            'jenis' => "SELISIH",
            "groupname" => "SELISIH",
            "groupakun" => NULL,
            "isheader" => 1,
            "kd_akun" => NULL,
            "nama" => NULL,
            "saldoawald" => $selisih_sawd, "saldoawalk" => $selisih_sawk,
            "mutasid" => $selisih_mutd, "mutasik" => $selisih_mutk,
            "saldoakhird" => $selisih_sakd, "saldoakhirk" => $selisih_sakk,
            "labarugid" => $selisih_lrd, "labarugik" => $selisih_lrk,
            "neracad" => $selisih_nrd, "neracak" => $selisih_nrk
            , 'cls' => 'x-bls-header1'
        ));
        array_push($resArr, array(
            'jenis' => "TOTAL",
            "groupname" => "TOTAL",
            "groupakun" => NULL,
            "isheader" => 1,
            "kd_akun" => NULL,
            "nama" => NULL,
            "saldoawald" => $jumlah_sawd + $selisih_sawd, "saldoawalk" => $jumlah_sawk + $selisih_sawk,
            "mutasid" => $jumlah_mutd + $selisih_mutd, "mutasik" => $jumlah_mutk + $selisih_mutk,
            "saldoakhird" => $jumlah_sakd + $selisih_sakd, "saldoakhirk" => $jumlah_sakk + $selisih_sakk,
            "labarugid" => $jumlah_lrd + $selisih_lrd, "labarugik" => $jumlah_lrk + $selisih_lrk,
            "neracad" => $jumlah_nrd + $selisih_nrd, "neracak" => $jumlah_nrk + $selisih_nrk
            , 'cls' => 'x-bls-header3'
        ));
        return $resArr;
    }

    public function getSaw($kdakun, $saw) {
        $retval = array("saldoawald" => "0", "saldoawalk" => "0");

        foreach ($saw as $v) {
//            echo $v["kd_akun"].$v->saldod.$v->saldok ;
            if ($v["kd_akun"] == $kdakun) {
                $retval = array("saldoawald" => $v["saldod"], "saldoawalk" => $v["saldok"]);
                return $retval;
            }
        }
        return $retval;
    }

    public function getMutasi($kdakun, $saw) {
        $retval = array("mutasid" => "0", "mutasik" => "0");
        foreach ($saw as $v) {
            if ($v->kd_akun == $kdakun) {
                $retval = array("mutasid" => $v->mutasid, "mutasik" => $v->mutasik);
                return $retval;
            }
        }
        return $retval;
    }

    public function getLabarugi($kdakun, $saw) {
        $retval = array("labarugid" => "0", "labarugik" => "0");
        foreach ($saw as $v) {
            if ($v["kd_akun"] == $kdakun) {
                $retval = array("labarugid" => $v["labarugid"], "labarugik" => $v["labarugik"]);
                return $retval;
            }
        }
        return $retval;
    }

    public function getNeraca($kdakun, $saw) {
        $retval = array("neracad" => "0", "neracak" => "0");
        foreach ($saw as $v) {
            if ($v["kd_akun"] == $kdakun) {
                $retval = array("neracad" => $v["neracad"], "neracak" => $v["neracak"]);
                return $retval;
            }
        }
        return $retval;
    }

    public function histo_neracasaldo($thbl, $kd_cabang) {
        $head = $this->nl_model->getheader();
        $child = $this->nl_model->getchild(); //getchild_bln_berjalan($thbl,$kd_cabang);
        $child_detail = $this->nl_model->getchild_detail();
        $saw = $this->nl_model->getSaldo(0, $child_detail, $thbl, $kd_cabang);
        $sak = $this->nl_model->getSaldo(1, $child_detail, $thbl, $kd_cabang);
        $mutasi = $this->nl_model->getMutasi($thbl, $kd_cabang);
        $labarugi = $this->nl_model->getSaldo(2, $child_detail, $thbl, $kd_cabang);
        $neraca = $this->nl_model->getSaldo(3, $child_detail, $thbl, $kd_cabang);
//        echo json_encode($saw);
        $newchild = array();

        foreach ($child as $v) {

            $saldoawal = $this->getSaw($v->kd_akun, $saw);
            $mut = $this->getMutasi($v->kd_akun, $mutasi);
            $saldoakhir = $this->getSaw($v->kd_akun, $sak);
            $lr = $this->getLabarugi($v->kd_akun, $labarugi);
            $nr = $this->getNeraca($v->kd_akun, $neraca);

            array_push($newchild, array(
                "groupakun" => $v->groupakun,
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldoawald" => $saldoawal["saldoawald"], "saldoawalk" => $saldoawal["saldoawalk"]
                ,
                "mutasid" => $mut["mutasid"], "mutasik" => $mut["mutasik"]
                ,
                "saldoakhird" => $saldoakhir["saldoawald"], "saldoakhirk" => $saldoakhir["saldoawalk"]
                ,
                "labarugid" => $lr["labarugid"], "labarugik" => $lr["labarugik"],
                "neracad" => $nr["neracad"], "neracak" => $nr["neracak"]
                    )
            );
        }

//        $resArr=array();
        $resArr = $this->get_max_level($thbl, $head, $newchild);
        if ($resArr) {
            foreach ($resArr as $v) {
                $datan = array();
                $datan = array(
                    'jenis' => $v['jenis'],
                    'groupname' => $v['groupname'],
                    'groupakun' => $v['groupakun'],
                    'isheader' => $v['isheader'],
                    'kd_akun' => $v['kd_akun'],
                    'nama' => $v['nama'],
                    'saldoawald' => $v['saldoawald'],
                    'saldoawalk' => $v['saldoawalk'],
                    'mutasid' => $v['mutasid'],
                    'mutasik' => $v['mutasik'],
                    'saldoakhird' => $v['saldoakhird'],
                    'saldoakhirk' => $v['saldoakhirk'],
                    'labarugid' => $v['labarugid'],
                    'labarugik' => $v['labarugik'],
                    'closingd' => null,
                    'closingk' => null,
                    'neracad' => $v['neracad'],
                    'neracak' => $v['neracak'],
                    'cls' => $v['cls']
                );
                $this->nl_model->insert_row('acc.t_histo_neracalajur',$datan);
                
            }
        }
//        echo var_dump($resArr);
    }

    public function get_rows() {
        $thbl = isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
//        $thbl = '201312';
//        $kd_cabang=null;
        $head = $this->nl_model->getheader();
        $child = $this->nl_model->getchild(); //getchild_bln_berjalan($thbl,$kd_cabang);
        $child_detail = $this->nl_model->getchild_detail();
        $saw = $this->nl_model->getSaldo(0, $child_detail, $thbl, $kd_cabang);
        $sak = $this->nl_model->getSaldo(1, $child_detail, $thbl, $kd_cabang);
        $mutasi = $this->nl_model->getMutasi($thbl, $kd_cabang);
        $labarugi = $this->nl_model->getSaldo(2, $child_detail, $thbl, $kd_cabang);
        $neraca = $this->nl_model->getSaldo(3, $child_detail, $thbl, $kd_cabang);
//        echo json_encode($saw);
        $newchild = array();

        foreach ($child as $v) {

            $saldoawal = $this->getSaw($v->kd_akun, $saw);
            $mut = $this->getMutasi($v->kd_akun, $mutasi);
            $saldoakhir = $this->getSaw($v->kd_akun, $sak);
            $lr = $this->getLabarugi($v->kd_akun, $labarugi);
            $nr = $this->getNeraca($v->kd_akun, $neraca);

            array_push($newchild, array(
                "groupakun" => $v->groupakun,
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldoawald" => $saldoawal["saldoawald"], "saldoawalk" => $saldoawal["saldoawalk"]
                ,
                "mutasid" => $mut["mutasid"], "mutasik" => $mut["mutasik"]
                ,
                "saldoakhird" => $saldoakhir["saldoawald"], "saldoakhirk" => $saldoakhir["saldoawalk"]
                ,
                "labarugid" => $lr["labarugid"], "labarugik" => $lr["labarugik"],
                "neracad" => $nr["neracad"], "neracak" => $nr["neracak"]
                    )
            );
        }
//        echo json_encode($newchild);
//        return;
        $resArr = $this->get_max_level($thbl, $head, $newchild);

        $total = count($resArr);

        $results = '{success:true,record:' . $total . ',data:' . json_encode($resArr) . '}';
        echo $results;
    }

    public function getFormatMY($thbl) {
        $str = date("Y F", strtotime($thbl . '01'));
        return $str;
    }

    public function print_form($thbl, $kd_cabang, $nmcabang) {
//        $thbl='201312';
//        $kd_cabang=NULL;
//        $nmcabang=NULL;
        $head = $this->nl_model->getheader();
        $child = $this->nl_model->getchild(); //getchild_bln_berjalan($thbl,$kd_cabang);
        $child_detail = $this->nl_model->getchild_detail();
        $saw = $this->nl_model->getSaldo(0, $child_detail, $thbl, $kd_cabang);
        $sak = $this->nl_model->getSaldo(1, $child_detail, $thbl, $kd_cabang);
        $mutasi = $this->nl_model->getMutasi($thbl, $kd_cabang);
        $labarugi = $this->nl_model->getSaldo(2, $child_detail, $thbl, $kd_cabang);
        $neraca = $this->nl_model->getSaldo(3, $child_detail, $thbl, $kd_cabang);
//        echo json_encode($saw);
        $newchild = array();

        foreach ($child as $v) {

            $saldoawal = $this->getSaw($v->kd_akun, $saw);
            $mut = $this->getMutasi($v->kd_akun, $mutasi);
            $saldoakhir = $this->getSaw($v->kd_akun, $sak);
            $lr = $this->getLabarugi($v->kd_akun, $labarugi);
            $nr = $this->getNeraca($v->kd_akun, $neraca);

            array_push($newchild, array(
                "groupakun" => $v->groupakun,
                "kd_akun" => $v->kd_akun,
                "groupname" => $v->groupname,
                "parent_kd_akun" => $v->parent_kd_akun,
                "nama" => $v->nama, "header_status" => $v->header_status,
                "saldoawald" => $saldoawal["saldoawald"], "saldoawalk" => $saldoawal["saldoawalk"]
                ,
                "mutasid" => $mut["mutasid"], "mutasik" => $mut["mutasik"]
                ,
                "saldoakhird" => $saldoakhir["saldoawald"], "saldoakhirk" => $saldoakhir["saldoawalk"]
                ,
                "labarugid" => $lr["labarugid"], "labarugik" => $lr["labarugik"],
                "neracad" => $nr["neracad"], "neracak" => $nr["neracak"]
                    )
            );
        }

//        $resArr=array();
        $resArr = $this->get_max_level($thbl, $head, $newchild);
        $thblconvert = $this->getFormatMY($thbl);
        if (!$resArr)
            show_404('page');
        if (!$kd_cabang) {
            $kd_cabang = "Semua Cabang";
        } else {
            $kd_cabang = "cabang " . $nmcabang;
        }
        $filter = array();
        $filter[0] = array('0' => 'NERACA SALDO', '1' => 'B');
        $filter[1] = array('0' => $thblconvert, '1' => '');
        $filter[2] = array('0' => $kd_cabang, '1' => '');
        $this->load->library('trialbalance_pdf');
//        $this->trialbalance_pdf->Header($filter);
        $pdf = new trialbalance_pdf('L','mm','A3');
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage('L');
        $pdf->create_pdf($filter, $resArr);
//        $pdf->create_pdf($this->getthbl($thbl), json_encode($result));
        $pdf->Output("nsprint", "I");
    }


}

?>
