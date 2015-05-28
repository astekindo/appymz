<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_rugilaba
 *
 * @author faroq
 */
class account_rugilaba extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('account_rugilaba_model', 'rl_model');
    }

    public function get_child_level($findhead, $child, $level) {
        $resArr = array();
//        print 'find '.$findhead."\n";

        foreach ($child as $c) {
            if ($c->parent_kd_akun == $findhead) {
                $levelt = $level + 1;
                //array_push($resArr, array('groupakun'=>$c->groupakun,'kd_akun' => $levelt . '-' . $c->kd_akun, 'nama' => $c->nama, 'jumlah' => $c->jumlah));
//                array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah' => $c->jumlah,'total' => NULL,'cls'=>NULL ));
                $arrget = $this->get_child_level($c->kd_akun, $child, $levelt);
                if (count($arrget) > 0) {
                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah' => $c->jumlah,'total' => NULL,'cls'=>'x-bls-header2' ));
                    $jumlah=0;                    
                    foreach ($arrget as $ag) {
                        if(is_null($ag['jumlah'])){
                            $ag['jumlah']=0;
                        }
                        $jumlah=$jumlah+ $ag['jumlah'];
                        array_push($resArr, array('jenis'=>NULL,'groupname'=>NULL,'groupakun'=>$ag['groupakun'],'isheader'=>$ag['header_status'],'kd_akun' => $ag['kd_akun'], 'nama' => $ag['nama'], 'jumlah' =>$ag['jumlah'],'total' => NULL,'cls'=>NULL ));
                    }
                   // array_push($resArr, array('groupakun'=>$c->groupakun,'kd_akun' => $levelt . '-' . $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>$jumlah));
                     array_push($resArr, array('jenis'=>NULL,'groupname'=>NULL,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>NULL,'total' => $jumlah,'cls'=>'x-bls-header2' ));
                }else{
                    array_push($resArr, array('jenis'=>NULL,'groupname'=>$c->groupname,'groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah' => $c->jumlah,'total' => NULL,'cls'=>NULL ));
                }
            }
        }
        return $resArr;
    }

    public function get_max_level($head, $child) {
        $resArr = array();
        $level = 0;
        $rec=0;
        $group_name="";
//        print json_encode($child)."\n";
//        array_push($resArr, array('groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>NULL,'total' => $jumlah ));
        foreach ($head as $h) {
            $rec ++;
            //array_push($resArr, array('kd_akun' => $level.'-'.$h->kd_akun,'parent_kd_akun' =>''));
            if($h->groupakun != $group_name){
                if($group_name!=""){
                    array_push($resArr, array('jenis'=>'TOTAL '.$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah' =>0,'total' => 0,'cls'=>'x-bls-header' ));
                }
                $group_name=$h->groupakun;
                
                array_push($resArr, array('jenis'=>$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah' =>0,'total' => 0,'cls'=>'x-bls-header' ));
            }
//            array_push($resArr, $h);
            array_push($resArr, array('jenis'=>NULL,'groupname'=>$h->nama,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah' =>0,'total' => 0,'cls'=>'x-bls-header1' ));
            $arrch = $this->get_child_level($h->kd_akun, $child, $level);
            if (count($arrch) > 0) {
                foreach ($arrch as $ac) {
                    array_push($resArr, $ac);
                }
//                array_push($resArr, array('groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $c->header_status,'kd_akun' => $h->kd_akun, 'nama' => 'TOTAL '.$h->nama, 'jumlah' =>NULL,'total' => $jumlah ));
            }
            $level = 0;
            if ($rec==count($head)){
                array_push($resArr, array('jenis'=>'TOTAL '.$group_name,'groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $h->header_status,'kd_akun' => null, 'nama' => NULL, 'jumlah' =>0,'total' => 0,'cls'=>'x-bls-header' ));
            }
        }
        return $resArr;
    }

    public function get_rows() {
//        $thbl=isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
//        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
        $thbl='201311';
        $kd_cabang=NULL;
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl,$kd_cabang);
        $resArr = $this->get_max_level($head, $child);

        $total=count($resArr);
        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($resArr) . '}';
        echo $results;
    }

    //put your code here
}

?>
