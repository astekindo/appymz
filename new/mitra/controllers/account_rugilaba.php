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

    public function get_periode_thbl($thbl, $v) {
        $dt = substr($thbl, 0, 4) . '-' . substr($thbl, 4, 2) . '-01';
        $current_date = date('Y-m-d', strtotime($dt));
        return date('M-Y', strtotime($v . ' month', strtotime($current_date)));
    }

    public function get_child_level($findhead, $child, $level) {
        $resArr = array();
//        print 'find '.$findhead."\n";

        foreach ($child as $c) {
            if ($c->parent_kd_akun == $findhead) {
                $levelt = $level + 1;
                $arrget = $this->get_child_level($c->kd_akun, $child, $levelt);
                if (count($arrget) > 0) {
                    array_push($resArr, array('dk' => $c->dk, 'type_akun' => $c->type_akun, 'jenis' => NULL, 'groupname' => $c->groupname, 'groupakun' => $c->groupakun, 'isheader' => $c->header_status, 'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $c->jumlah2, 'jumlah' => $c->jumlah, 'saldo' => $c->saldo, 'cls' => 'x-bls-header2'));
                    $jumlah = 0;
                    $jumlah2 = 0;
                    $saldo = 0;
                    foreach ($arrget as $ag) {
                        if (is_null($ag['jumlah'])) {
                            $ag['jumlah'] = 0;
                        }
                        if (is_null($ag['jumlah2'])) {
                            $ag['jumlah2'] = 0;
                        }
                        if (is_null($ag['saldo'])) {
                            $ag['saldo'] = 0;
                        }
                        if ($ag['dk'] == $c->dk) {
                            $jumlah = $jumlah + $ag['jumlah'];
                            $jumlah2 = $jumlah2 + $ag['jumlah2'];
                            $saldo = $saldo + $ag['saldo'];
//                            $ag['jumlah']=$ag['jumlah']*(-1);
                        } else {
                            $jumlah = $jumlah + ($ag['jumlah'] * (-1));
                            $jumlah2 = $jumlah2 + ($ag['jumlah2']* (-1));
                            $saldo = $saldo + ($ag['saldo']* (-1));
                        }
//                        $jumlah=$jumlah+ $ag['jumlah'];
//                        $jumlah2 = $jumlah2 + $ag['jumlah2'];
//                        $saldo = $saldo + $ag['saldo'];

                        array_push($resArr, array('dk' => $ag['dk'], 'type_akun' => $ag['type_akun'], 'jenis' => NULL, 'groupname' => NULL, 'groupakun' => $ag['groupakun'], 'isheader' => $ag['header_status'], 'kd_akun' => $ag['kd_akun'], 'nama' => $ag['nama'], 'jumlah2' => $ag['jumlah2'], 'jumlah' => $ag['jumlah'], 'saldo' => $ag['saldo'], 'cls' => NULL));
                    }
                    array_push($resArr, array('dk' => $c->dk, 'type_akun' => $c->type_akun, 'jenis' => NULL, 'groupname' => NULL, 'groupakun' => $c->groupakun, 'isheader' => $c->header_status, 'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL ' . $c->nama, 'jumlah2' => $jumlah2, 'jumlah' => $jumlah, 'saldo' => $saldo, 'cls' => 'x-bls-header5'));
                } else {
                    $jumlah = 0;
                    $jumlah2 = 0;
                    $saldo = 0;
                    if (is_null($c->jumlah2)) {
                        $jumlah2 = 0;
                    } else {
                        $jumlah2 = $c->jumlah2;
                    }
                    if (is_null($c->jumlah)) {
                        $jumlah = 0;
                    } else {
                        $jumlah = $c->jumlah;
                    }
                    if (is_null($c->saldo)) {
                        $saldo = 0;
                    } else {
                        $saldo = $c->saldo;
                    }

//                    if($c->dk!=$dk){
//                        $jumlah=$jumlah*(-1);
//                    }
                    array_push($resArr, array('dk' => $c->dk, 'type_akun' => $c->type_akun, 'jenis' => NULL, 'groupname' => $c->groupname, 'groupakun' => $c->groupakun, 'isheader' => $c->header_status, 'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah2' => $jumlah2, 'jumlah' => $jumlah, 'saldo' => $saldo, 'cls' => NULL));
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

                if ($group_name != "") {
                    $totalpendapatan = $totalall;
                    $totalpendapatan1 = $totalall1;
                    $totalpendapatan2 = $totalall2;
                    array_push($resArr, array('jenis' => 'TOTAL ' . $group_name, 'groupname' => NULL, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => $totalall1, 'jumlah' => $totalall, 'saldo' => $totalall2, 'cls' => 'x-bls-header6'));
                    $totalall = 0;
                    $totalall1 = 0;
                    $totalall2 = 0;
                }
                $group_name = $h->groupakun;

                array_push($resArr, array('jenis' => $group_name, 'groupname' => NULL, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => $this->get_periode_thbl($thbl, '-1'), 'jumlah' => $this->get_periode_thbl($thbl, '+0'), 'saldo' => 'TH-' . substr($thbl, 0, 4), 'cls' => 'x-bls-header'));
            }
//            array_push($resArr, $h);
            array_push($resArr, array('jenis' => NULL, 'groupname' => $h->nama, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => NULL, 'jumlah' => NULL, 'saldo' => NULL, 'cls' => 'x-bls-header1'));
            $arrch = $this->get_child_level($h->kd_akun, $child, $level);

            if (count($arrch) > 0) {
                $jumlah = 0;
                $jumlah2 = 0;
                $saldo = 0;
                
                $jumlahg = 0;
                $jumlah2g = 0;
                $saldog = 0;
                
                foreach ($arrch as $ac) {

                    if (!$ac['isheader']) {
//                        echo ($ac['kd_akun'].','.$ac['dk'].','.$h->dk.';');
                        
                        if ($ac['dk'] == 'D' && $ac['type_akun'] == 'P') {
                            $jumlah +=($ac['jumlah'] * (-1));
                            $jumlah2 +=($ac['jumlah2'] * (-1));
                            $saldo += ($ac['saldo'] * (-1));
                        } elseif ($ac['dk'] == 'K' && $ac['type_akun'] == 'B') {
                            $jumlah += ($ac['jumlah'] * (-1));
                            $jumlah2 +=($ac['jumlah2'] * (-1));
                            $saldo += ($ac['saldo'] * (-1));
                        } else {
                            $jumlah +=$ac['jumlah'];
                            $jumlah2 +=$ac['jumlah2'];
                            $saldo += $ac['saldo'];
                        }
                        
                       

//                        $jumlah += $ac['jumlah'];
//                        $jumlah2 += $ac['jumlah2'];
//                        $saldo += $ac['saldo'];
                        if ($ac['dk'] != $h->dk) {
                            $ac['jumlah'] = $ac['jumlah'] * (-1);
                            $ac['jumlah2'] = $ac['jumlah2'] * (-1);
                            $ac['saldo'] = $ac['saldo'] * (-1);
                        }
                        $jumlahg +=$ac['jumlah'];
                            $jumlah2g +=$ac['jumlah2'];
                            $saldog += $ac['saldo'];
                    }

                    array_push($resArr, $ac);
                }

                $totalall +=$jumlah;
                $totalall1+=$jumlah2;
                $totalall2+=$saldo;

                array_push($resArr, array('jenis' => NULL, 'groupname' => 'TOTAL ' . $h->nama, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => $jumlah2g, 'jumlah' => $jumlahg, 'saldo' => $saldog, 'cls' => 'x-bls-header4'));
//                array_push($resArr, array('groupname'=>NULL,'groupakun'=>$h->groupakun,'isheader' => $c->header_status,'kd_akun' => $h->kd_akun, 'nama' => 'TOTAL '.$h->nama, 'jumlah' =>NULL,'total' => $jumlah ));
            }

            $level = 0;
            if ($rec == count($head)) {
                array_push($resArr, array('jenis' => 'TOTAL ' . $group_name, 'groupname' => NULL, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => $totalall1, 'jumlah' => $totalall, 'saldo' => $totalall2, 'cls' => 'x-bls-header6'));
                $totalbiaya = $totalall;
                $totalbiaya1 = $totalall1;
                $totalbiaya2 = $totalall2;
                $totalall = $totalpendapatan - $totalbiaya;
                $totalall1 = $totalpendapatan1 - $totalbiaya1;
                $totalall2 = $totalpendapatan2 - $totalbiaya2;
                array_push($resArr, array('jenis' => ' RUGI LABA ', 'groupname' => NULL, 'groupakun' => $h->groupakun, 'isheader' => $h->header_status, 'kd_akun' => null, 'nama' => NULL, 'jumlah2' => $totalall1, 'jumlah' => $totalall, 'saldo' => $totalall2, 'cls' => 'x-bls-header3'));
            }
        }
        return $resArr;
    }

    public function get_rows() {
        $thbl=isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
//        $thbl = '201402';
//        $kd_cabang = NULL;
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kd_cabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kd_cabang);

        $resArr = $this->get_max_level($thbl, $head, $child);
        $total = count($resArr);
        $results = '{success:true,record:' . $total . ',data:' . json_encode($resArr) . '}';
        echo $results;
    }

    public function getFormatMY($thbl) {
        $str = date("Y F", strtotime($thbl . '01'));
        return $str;
    }

    public function histo_rugilaba($thbl, $kdcabang) {
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kdcabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kdcabang);
        $resArr = $this->get_max_level($thbl, $head, $child);

        if ($resArr) {
            foreach ($resArr as $v) {
                $datan = array();
                $datan = array(
                    'thbl' => $thbl,
                    'kd_cabang' => $kdcabang,
                    'jenis' => $v['jenis'],
                    'groupname' => $v['groupname'],
                    'groupakun' => $v['groupakun'],
                    'isheader' => $v['isheader'],
                    'kd_akun' => $v['kd_akun'],
//                    'nama' => $v['jenis']
                    'jumlah2' => $v['jumlah2'],
                    'jumlah' => $v['jumlah'],
                    'saldo' => $v['saldo'],
                    'cls' => $v['cls']
                );
                $this->rl_model->insert_row('acc.t_histo_rugilaba', $datan);
            }
        }
        return true;
    }

    public function print_form($thbl, $kdcabang, $nmcabang) {
        $kdcabang = $kdcabang=='%5B%5D' ? NULL : $kdcabang;
        $thblconvert = $this->getFormatMY($thbl);
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kdcabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kdcabang);

        $resArr = $this->get_max_level($thbl, $head, $child);

        if (!$resArr)
            show_404('page');
        if (!$kdcabang) {
            $kdcabang = "Semua Cabang";
        } else {
            $kdcabang = "cabang " . $nmcabang;
        }

        $filter = array();
        $filter[0] = array('0' => 'RUGI-LABA', '1' => 'B');
        $filter[1] = array('0' => $thblconvert, '1' => '');
        $filter[2] = array('0' => $kdcabang, '1' => '');
        $this->load->library('Rugilaba_pdf');
        $pdf = new Rugilaba_pdf();
        $pdf->SetMargins(10,10,10);
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage('L');
        
        $pdf->create_pdf($filter, $resArr,1);
//        $pdf->create_pdf_nonnol($filter, $resArr,1);
        $pdf->Output("rlprint", "I");
    }
    
    public function print_form2($thbl, $kdcabang, $nmcabang) {
        $kdcabang = $kdcabang=='%5B%5D' ? NULL : $kdcabang;
        $thblconvert = $this->getFormatMY($thbl);
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kdcabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kdcabang);

        $resArr = $this->get_max_level($thbl, $head, $child);

        if (!$resArr)
            show_404('page');
        if (!$kdcabang) {
            $kdcabang = "Semua Cabang";
        } else {
            $kdcabang = "cabang " . $nmcabang;
        }

        $filter = array();
        $filter[0] = array('0' => 'RUGI-LABA', '1' => 'B');
        $filter[1] = array('0' => $thblconvert, '1' => '');
        $filter[2] = array('0' => $kdcabang, '1' => '');
        $this->load->library('Rugilaba_pdf');
        $pdf = new Rugilaba_pdf();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage('L');
        $pdf->create_pdf($filter, $resArr,0);
        $pdf->Output("rlprint", "I");
    }

    public function print_form3($thbl, $kdcabang, $nmcabang) {
        $kdcabang = $kdcabang=='%5B%5D' ? NULL : $kdcabang;
        $thblconvert = $this->getFormatMY($thbl);
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kdcabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kdcabang);

        $resArr = $this->get_max_level($thbl, $head, $child);

        if (!$resArr)
            show_404('page');
        if (!$kdcabang) {
            $kdcabang = "Semua Cabang";
        } else {
            $kdcabang = "cabang " . $nmcabang;
        }

        $filter = array();
        $filter[0] = array('0' => 'RUGI-LABA', '1' => 'B');
        $filter[1] = array('0' => $thblconvert, '1' => '');
        $filter[2] = array('0' => $kdcabang, '1' => '');
        $this->load->library('Rugilaba_pdf');
        $pdf = new Rugilaba_pdf();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage('L');
        
//        $pdf->create_pdf($filter, $resArr,1);
        $pdf->create_pdf_nonnol($filter, $resArr,1);
        $pdf->Output("rlprint", "I");
    }
    public function print_form4($thbl, $kdcabang, $nmcabang) {
        $kdcabang = $kdcabang=='%5B%5D' ? NULL : $kdcabang;
        $thblconvert = $this->getFormatMY($thbl);
        $head = $this->rl_model->getheader();
        $child = $this->rl_model->getchild_bln_berjalan($thbl, $kdcabang);
        $this->rl_model->getchild_saldo($child, $thbl, $kdcabang);

        $resArr = $this->get_max_level($thbl, $head, $child);

        if (!$resArr)
            show_404('page');
        if (!$kdcabang) {
            $kdcabang = "Semua Cabang";
        } else {
            $kdcabang = "cabang " . $nmcabang;
        }

        $filter = array();
        $filter[0] = array('0' => 'RUGI-LABA', '1' => 'B');
        $filter[1] = array('0' => $thblconvert, '1' => '');
        $filter[2] = array('0' => $kdcabang, '1' => '');
        $this->load->library('Rugilaba_pdf');
        $pdf = new Rugilaba_pdf();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage('L');
        
//        $pdf->create_pdf($filter, $resArr,1);
        $pdf->create_pdf_nonnol($filter, $resArr,0);
        $pdf->Output("rlprint", "I");
    }
    public function testdata(){
        $loadkolom=array(0,1,2,3,4,5,6);
        $w = array(35, 58, 20, 58, 35, 35, 35);
        $nb=0;
        for($n=0;$n<count($loadkolom);$n++)
            $nb=max($nb,$w[$loadkolom[$n]]);
        $h=$nb*5;
        echo $h;
        
        
    }
    
    //put your code here
}

?>
