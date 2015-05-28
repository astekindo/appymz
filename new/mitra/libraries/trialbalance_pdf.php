<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('fpdf/fpdf.php');

class trialbalance_pdf extends FPDF {

    function Header() {
       
//Logo
        $this->Image('assets/img/logo-mbs.jpg', 10, 5, 33);

//Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(35);
        $this->Cell(70, 16, 'PT.SURYA KENCANA KERAMINDO');
        
        $this->Cell(250);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(20, 5, 'TANGGAL', 0, 0, 'R');
        $this->Cell(5, 5, ':', 0, 0, 'C');
        $this->Cell(20, 5, date('d/m/Y'), 0, 0, 'R');
        $this->Ln(2);
        $this->Cell(355);
        $this->Cell(20, 10, 'WAKTU', 0, 0, 'R');
        $this->Cell(5, 10, ':', 0, 0, 'C');
        $this->Cell(20, 10, date('h:i:s'), 0, 0, 'R');
        $this->Ln(2);
        $this->Cell(355);
        $this->Cell(20, 15, 'HALAMAN', 0, 0, 'R');
        $this->Cell(5, 15, ':', 0, 0, 'C');
        $this->Cell(20,15, $this->PageNo() . ' dari {nb}', 0, 0, 'R');
//        $this->Ln();
        $this->SetLineWidth(.5);
        $this->Line(10, 24, 410, 24);

        $this->Ln(15);
    }
    function Footer()
    {
            //Position at 1.5 cm from bottom
//            $this->SetY(-15);
//            //Arial italic 8
//            $this->SetFont('Arial','I',8);
//            //Page number
//            $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    public function set_header_column($w) {
        $headmerge = array('Rekening', 'Saldo Awal', 'Mutasi', 'Saldo Akhir', 'Rugi/Laba', 'Neraca');
        $header = array('Group Name', 'Kode Akun', 'Nama Akun', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit');
//        $w = array(48, 32, 55, 25, 25, 25, 25, 25, 25, 24, 24, 24, 24);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(249,249,249);
//        $this->SetTextColor(205);
        $this->Cell($w[0] + $w[1] + $w[2], 5, $headmerge[0], 1, 0, 'C', true);
        $this->Cell($w[3] + $w[4], 5, $headmerge[1], 1, 0, 'C', true);
        $this->Cell($w[5] + $w[6], 5, $headmerge[2], 1, 0, 'C', true);
        $this->Cell($w[7] + $w[8], 5, $headmerge[3], 1, 0, 'C', true);
        $this->Cell($w[9] + $w[10], 5, $headmerge[4], 1, 0, 'C', true);
        $this->Cell($w[11] + $w[12], 5, $headmerge[5], 1, 0, 'C', true);

        $this->Ln();

        $this->Cell($w[0], 5, $header[0], 1, 0, 'C', true);
        $this->Cell($w[1] + $w[2], 5, $header[2], 1, 0, 'C', true);
//        $this->Cell($w[2], 5, $header[2], 1, 0, 'C', true);
        $this->Cell($w[3], 5, $header[3], 1, 0, 'C', true);
        $this->Cell($w[4], 5, $header[4], 1, 0, 'C', true);
        $this->Cell($w[5], 5, $header[5], 1, 0, 'C', true);
        $this->Cell($w[6], 5, $header[6], 1, 0, 'C', true);
        $this->Cell($w[7], 5, $header[7], 1, 0, 'C', true);
        $this->Cell($w[8], 5, $header[8], 1, 0, 'C', true);
        $this->Cell($w[9], 5, $header[9], 1, 0, 'C', true);
        $this->Cell($w[10], 5, $header[10], 1, 0, 'C', true);
        $this->Cell($w[11], 5, $header[11], 1, 0, 'C', true);
        $this->Cell($w[12], 5, $header[12], 1, 0, 'C', true);
        $this->Ln();
    }
    function set_subtitle($filter){
        foreach ($filter as $v) {
            $this->SetFont('Arial', $v['1'], 12);
            $this->Cell(191);
            $this->Cell(9, 5, $v['0'], 0, 0, 'C');
            $this->Ln();
        }
        
    }
    function create_pdf($filter,$data) {        

//        $this->SetDrawColor(128,0,0);
//        $this->SetLineWidth(.3);
//        $w = array(75, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20);
        $w = array(48, 32, 55, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25);
////        $this->AddPage('L');
//        $this->SetFont('Arial', 'B', 12);
////        $this->Cell($w[0] + $w[1] + $w[2] + $w[3] + $w[4], 5, 'Tanggal Cetak : ' . date("d-m-Y H:i:s"), 0, 0, 'L', false);
////        $this->Ln();
//        $this->set_header_column();

        $jml = count($data);
        $i = 0;
        foreach ($data as $v) {
            if (is_numeric($v['saldoawald'])) {
                $v['saldoawald'] = number_format($v['saldoawald']);
            }
            if (is_numeric($v['saldoawalk'])) {
                $v['saldoawalk'] = number_format($v['saldoawalk']);
            }
            
            if (is_numeric($v['mutasid'])) {
                $v['mutasid'] = number_format($v['mutasid']);
            }
            if (is_numeric($v['mutasik'])) {
                $v['mutasik'] = number_format($v['mutasik']);
            }
            
            if (is_numeric($v['saldoakhird'])) {
                $v['saldoakhird'] = number_format($v['saldoakhird']);
            }
            if (is_numeric($v['saldoakhirk'])) {
                $v['saldoakhirk'] = number_format($v['saldoakhirk']);
            }
            
            if (is_numeric($v['labarugid'])) {
                $v['labarugid'] = number_format($v['labarugid']);
            }
            if (is_numeric($v['labarugik'])) {
                $v['labarugik'] = number_format($v['labarugik']);
            }
            
            if (is_numeric($v['neracad'])) {
                $v['neracad'] = number_format($v['neracad']);
            }
            if (is_numeric($v['neracak'])) {
                $v['neracak'] = number_format($v['neracak']);
            }
            if (($i % 42) == 0) {
                if ($i != 0) {
                    $this->AddPage('L');
                }
                $this->set_subtitle($filter);
                $this->Ln(5);
                $this->set_header_column($w);
            }
            if ($v['groupname'] != NULL || $v['kd_akun'] != NULL) {
                $this->SetFillColor(255);
                $this->SetFont('Arial', '', 8);
                if($v['groupname'] != NULL ){
                $this->SetFillColor(176,255,197);
                }
                
                $nmakun = '';
                if ($v['kd_akun']) {
                    $nmakun = $v['kd_akun'] . '-' . $v['nama'];
                }
                if ($v['groupname'] == 'SELISIH' || $v['groupname'] == 'JUMLAH' || $v['groupname'] == 'TOTAL') {
                    if($v['groupname'] == 'JUMLAH'){
                        $this->SetFillColor(255,176,196);
                    }
                    if($v['groupname'] == 'TOTAL'){
                        $this->SetFillColor(122, 202, 225);
                    }
                    $this->Cell($w[0] + $w[1] + $w[2], 5, $v['groupname'], 1, 0, 'L', true);
                } ELSE {
//                    if($v['cls'] == 'x-bls-header1' || $v['cls'] == 'x-bls-header1' || $v['cls'] == 'x-bls-header2' || $v['cls'] == 'x-bls-header3'){
                        if( $v['cls'] == 'x-bls-header2' ){
                        $this->SetFillColor(221,225,241);
                         $this->Cell($w[0], 5, $v['groupname'], 1, 0, 'L', false);
                         $this->Cell($w[1] + $w[2], 5, $nmakun, 1, 0, 'L', true);
                    }else{
                        $this->Cell($w[0], 5, $v['groupname'], 1, 0, 'L', true);
                        $this->Cell($w[1] + $w[2], 5, $nmakun, 1, 0, 'L', true);
                    }
                    
                }

                $this->Cell($w[3], 5, $v['saldoawald'], 1, 0, 'R', true);
                $this->Cell($w[4], 5, $v['saldoawalk'], 1, 0, 'R', true);
                $this->Cell($w[5], 5, $v['mutasid'], 1, 0, 'R', true);
                $this->Cell($w[6], 5, $v['mutasik'], 1, 0, 'R', true);
                $this->Cell($w[7], 5, $v['saldoakhird'], 1, 0, 'R', true);
                $this->Cell($w[8], 5, $v['saldoakhirk'], 1, 0, 'R', true);
                $this->Cell($w[9], 5, $v['labarugid'], 1, 0, 'R', true);
                $this->Cell($w[10], 5, $v['labarugik'], 1, 0, 'R', true);
                $this->Cell($w[11], 5, $v['neracad'], 1, 0, 'R', true);
                $this->Cell($w[12], 5, $v['neracak'], 1, 0, 'R', true);
                $this->Ln();
            }
            $i++;
        }

        
    }

}

?>