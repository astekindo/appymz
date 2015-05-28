<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('fpdf/fpdf.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rugilaba_pdf
 *
 * @author miyzan
 */
class Rugilaba_pdf extends FPDF {

    //put your code here
    function Header() {

//Logo
        $this->Image('assets/img/logo-mbs.jpg', 10, 5, 33);

//Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(35);
        $this->Cell(70, 16, 'PT.SURYA KENCANA KERAMINDO');
        
        $this->Cell(127);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(20, 5, 'TANGGAL', 0, 0, 'R');
        $this->Cell(5, 5, ':', 0, 0, 'C');
        $this->Cell(20, 5, date('d/m/Y'), 0, 0, 'R');
        $this->Ln(2);
        $this->Cell(232);
        $this->Cell(20, 10, 'WAKTU', 0, 0, 'R');
        $this->Cell(5, 10, ':', 0, 0, 'C');
        $this->Cell(20, 10, date('h:i:s'), 0, 0, 'R');
        $this->Ln(2);
        $this->Cell(232);
        $this->Cell(20, 15, 'HALAMAN', 0, 0, 'R');
        $this->Cell(5, 15, ':', 0, 0, 'C');
        $this->Cell(20,15, $this->PageNo() . ' dari {nb}', 0, 0, 'R');
//        $this->Ln();
        
        
//Move to the right
//        $this->Cell(130);
//        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.5);
        $this->Line(10, 24, 287, 24);
//Title
//        $this->Cell(10, 10, 'Neraca', 0, 0, 'C');
//Line break
        $this->Ln(15);
    }

    function Footer() {
//        //Position at 1.5 cm from bottom
//        $this->SetY(-20);
//        //Arial italic 8
//        $this->SetFont('Arial', 'I', 8);
//        //Page number
//        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'C');
    }

    function set_subtitle($filter) {
        foreach ($filter as $v) {
            $this->SetFont('Arial', $v['1'], 12);
            $this->Cell(130);
            $this->Cell(9, 5, $v['0'], 0, 0, 'C');
            $this->Ln();
        }
    }

    function set_header_column($w, $load) {
        $header = array('Jenis', 'Group Name', 'Kode Akun', 'Nama Akun', 'Bulan Lalu', 'Bulan Ini', 'S/D Bulan Ini');
//        $w = array(53, 54, 17, 17, 54, 54, 17, 17);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(249, 249, 249);
        if (count($load) > 0) {
            foreach ($load as $v) {
                $this->Cell($w[$v], 5, $header[$v], 1, 0, 'C', true);
            }
        }
 
        $this->Ln();
    }

    function create_pdf($filter, $data, $showkodeakun) {

        $w = array(35, 58, 20, 67, 32, 32, 32);
//         $this->set_subtitle($filter);
//        $this->set_header_column();
        $i = 0;
        $fill = false;
        foreach ($data as $v) {
            if (is_numeric($v['jumlah2'])) {
                $v['jumlah2'] = number_format($v['jumlah2']);
            }
            if (is_numeric($v['jumlah'])) {
                $v['jumlah'] = number_format($v['jumlah']);
            }

            if (is_numeric($v['saldo'])) {
                $v['saldo'] = number_format($v['saldo']);
            }

            if (($i % 26) == 0) {
                if ($i != 0) {
                    $this->AddPage('L');
                }
                $this->set_subtitle($filter);
                $this->Ln(5);
                if ($showkodeakun == 1) {
                    $loadkolom = array(0, 1, 2, 3, 4, 5, 6);
                } else {
                    $loadkolom = array(0, 1, 3, 4, 5, 6);
                }

                $this->set_header_column($w, $loadkolom);
            }
            $this->SetFillColor(255);
            $this->SetFont('Arial', '', 8);

            $fill = false;
            if ($v['cls'] == 'x-bls-header') {
                $this->SetFillColor(255, 176, 196);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header1') {
                $this->SetFillColor(176, 255, 197);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header2') {
                $this->SetFillColor(211, 225, 241);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header3') {
                $this->SetFillColor(122, 202, 225);
//                $this->SetFillColor(91,168,225);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header4') {
                $this->SetFillColor(255, 165, 0);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header5') {
                $this->SetFillColor(255, 215, 0);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header6') {
                $this->SetFillColor(230, 255, 153);
                $fill = true;
            }
            else
                $fill = false;

            if ($v['jenis']) {
                $this->Cell($w[0], 5, $v['jenis'], 1, 0, 'L', $fill);
                $this->Cell($w[1], 5, $v['groupname'], 1, 0, 'L', $fill);
            } else {
                $this->Cell($w[0], 5, $v['jenis'], 'LR', 0, 'L', false);
                if (!$v['groupname']) {
                    $this->Cell($w[1], 5, $v['groupname'], 'LR', 0, 'L', false);
                } else {
                    $this->Cell($w[1], 5, $v['groupname'], 1, 0, 'L', $fill);
                }
            }
            if ($showkodeakun == 1) {
                $this->Cell($w[2], 5, $v['kd_akun'], 1, 0, 'C', $fill);
            }


            $this->Cell($w[3], 5, $v['nama'], 1, 0, 'L', $fill);
            $this->Cell($w[4], 5, $v['jumlah2'], 1, 0, 'R', $fill);
            $this->Cell($w[5], 5, $v['jumlah'], 1, 0, 'R', $fill);
            $this->Cell($w[6], 5, $v['saldo'], 1, 0, 'R', $fill);

            $this->Ln();
            $i++;
        }
    }

    function create_pdf_nonnol($filter, $data, $showkodeakun) {

        $w = array(35, 58, 20, 67, 32, 32, 32);
//         $this->set_subtitle($filter);
//        $this->set_header_column();
        $i = 0;
        $fill = false;
        $arrdata = array();
        foreach ($data as $v) {
            if (is_numeric($v['jumlah2'])) {
                $v['jumlah2'] = number_format($v['jumlah2']);
            }
            if (is_numeric($v['jumlah'])) {
                $v['jumlah'] = number_format($v['jumlah']);
            }

            if (is_numeric($v['saldo'])) {
                $v['saldo'] = number_format($v['saldo']);
            }

            if (!$v['cls']) {
                if ($v['jumlah2'] != 0 || $v['jumlah'] != 0 || $v['saldo'] != 0) {
                    array_push($arrdata, $v);
                }
            } else {
                array_push($arrdata, $v);
            }
        }


        foreach ($arrdata as $v) {

            if (is_numeric($v['jumlah2'])) {
                $v['jumlah2'] = number_format($v['jumlah2']);
            }
            if (is_numeric($v['jumlah'])) {
                $v['jumlah'] = number_format($v['jumlah']);
            }

            if (is_numeric($v['saldo'])) {
                $v['saldo'] = number_format($v['saldo']);
            }

            if (($i % 26) == 0) {
                if ($i != 0) {
                    $this->AddPage('L');
                }
                $this->set_subtitle($filter);
                $this->Ln(5);
                if ($showkodeakun == 1) {
                    $loadkolom = array(0, 1, 2, 3, 4, 5, 6);
                } else {
                    $loadkolom = array(0, 1, 3, 4, 5, 6);
                }

                $this->set_header_column($w, $loadkolom);
            }
            $this->SetFillColor(255);
            $this->SetFont('Arial', '', 8);

            $fill = false;
            if ($v['cls'] == 'x-bls-header') {
                $this->SetFillColor(255, 176, 196);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header1') {
                $this->SetFillColor(176, 255, 197);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header2') {
                $this->SetFillColor(211, 225, 241);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header3') {
                $this->SetFillColor(122, 202, 225);
//                $this->SetFillColor(91,168,225);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header4') {
                $this->SetFillColor(255, 165, 0);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header5') {
                $this->SetFillColor(255, 215, 0);
                $fill = true;
            } else if ($v['cls'] == 'x-bls-header6') {
                $this->SetFillColor(230, 255, 153);
                $fill = true;
            }
            else
                $fill = false;

            if ($v['jenis']) {
                $this->Cell($w[0], 5, $v['jenis'], 1, 0, 'L', $fill);
                $this->Cell($w[1], 5, $v['groupname'], 1, 0, 'L', $fill);
            } else {
                $this->Cell($w[0], 5, $v['jenis'], 'LR', 0, 'L', false);
                if (!$v['groupname']) {
                    $this->Cell($w[1], 5, $v['groupname'], 'LR', 0, 'L', false);
                } else {
                    $this->Cell($w[1], 5, $v['groupname'], 1, 0, 'L', $fill);
                }
            }
            if ($showkodeakun == 1) {
                $this->Cell($w[2], 5, $v['kd_akun'], 1, 0, 'C', $fill);
            }


            $this->Cell($w[3], 5, $v['nama'], 1, 0, 'L', $fill);
            $this->Cell($w[4], 5, $v['jumlah2'], 1, 0, 'R', $fill);
            $this->Cell($w[5], 5, $v['jumlah'], 1, 0, 'R', $fill);
            $this->Cell($w[6], 5, $v['saldo'], 1, 0, 'R', $fill);

            $this->Ln();


            $i++;
        }
    }

}

?>
