<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('fpdf/fpdf.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of neraca_pdf
 *
 * @author miyzan
 */
class neraca_pdf extends FPDF {
private $lineh;
private $width;
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
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function set_subtitle($filter) {
        foreach ($filter as $v) {
            $this->SetFont('Arial', $v['1'], 12);
            $this->Cell(130);
            $this->Cell(9, 5, $v['0'], 0, 0, 'C');
            $this->Ln();
        }
    }

    function set_header_column($w) {
        $headmerge = array('AKTIVA', 'PASSIVA');
        $header = array('Group Name', 'Kode Akun','Nama Akun', 'S/D Bulan Lalu', 'S/D Bulan Ini', 'Group Name', 'Kode Akun','Nama Akun', 'S/D Bulan Lalu', 'S/D Bulan Ini');
        //$header = array('Group Name', 'Nama Akun', 'S/D Bulan Lalu', 'S/D Bulan Ini', 'Group Name', 'Nama Akun', 'S/D Bulan Lalu', 'S/D Bulan Ini');
//        $w = array(53, 54, 17, 17, 54, 54, 17, 17);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(249, 249, 249);
//        $this->SetTextColor(205);
        if(count($w)==8){
        $this->Cell($w[0] + $w[1] + $w[2] + $w[3], 5, $headmerge[0], 1, 0, 'C', true);
        $this->Cell($w[4] + $w[5] + $w[6] + $w[7], 5, $headmerge[1], 1, 0, 'C', true);        
        }else{
            $this->Cell($w[0] + $w[1] + $w[2] + $w[3] + $w[4], 5, $headmerge[0], 1, 0, 'C', true);
            $this->Cell($w[5] + $w[6] + $w[7]+ $w[8] + $w[9], 5, $headmerge[1], 1, 0, 'C', true);        
        }
        $this->Ln();
        for($i=0;$i<count($w);$i++){
            $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', true);
        }
//        $this->Cell($w[0], 5, $header[0], 1, 0, 'C', true);
//        $this->Cell($w[1], 5, $header[1], 1, 0, 'C', true);
//        $this->Cell($w[2], 5, $header[2], 1, 0, 'C', true);
//        $this->Cell($w[3], 5, $header[3], 1, 0, 'C', true);
//        $this->Cell($w[4], 5, $header[4], 1, 0, 'C', true);
//        $this->Cell($w[5], 5, $header[5], 1, 0, 'C', true);
//        $this->Cell($w[6], 5, $header[6], 1, 0, 'C', true);
//        $this->Cell($w[7], 5, $header[7], 1, 0, 'C', true);
        $this->Ln();
    }

    function create_pdf($filter, $data) {
        $this->SetAutoPageBreak(true,25);
        $w = array(28,19,38,28,28,28,19,38,28,28);
        $this->widths=array(28,19,38,28,28,28,19,38,28,28);
        $al=array('L','C','L','R','R','L','C','L','R','R');
        $i = 0;
        $fill = false;
        $this->set_subtitle($filter);
                $this->Ln(5);
                $this->set_header_column($w);
        foreach ($data as $v) {
            $filler=array();
//            if (($i % 25) == 0) {
//                if ($i != 0) {
//                    $this->AddPage('L');
//                }
//                $this->set_subtitle($filter);
//                $this->Ln(5);
//                $this->set_header_column($w);
//            }
            $this->SetFillColor(255);
            $this->SetFont('Arial', '', 8);
            
            if (is_numeric($v['subtotal_a'])) {
                $v['subtotal_a'] = number_format($v['subtotal_a']);
            }
            if (is_numeric($v['total_a'])) {
                $v['total_a'] = number_format($v['total_a']);
            }
            
            if (is_numeric($v['subtotal_p'])) {
                $v['subtotal_p'] = number_format($v['subtotal_p']);
            }
            if (is_numeric($v['total_p'])) {
                $v['total_p'] = number_format($v['total_p']);
            }
            
            $this->SetFillColor(255);
            $this->SetFont('Arial', '', 8);
            $fillcolor=array(255, 0, 0);
            $fill = false;
            if ($v['cls_a'] == 'x-bls-header') {
                $fillcolor=array(255, 176, 196);
//                $this->SetFillColor(255, 176, 196);
                $fill = true;
            } else if ($v['cls_a'] == 'x-bls-header1') {
//                $this->SetFillColor(176, 255, 197);
                $fillcolor=array(176, 255, 197);
                $fill = true;
            }else if ($v['cls_a'] == 'x-bls-header2') {
                $fillcolor=array(221,225,241);
                $fill = true;
            } else if ($v['cls_a'] == 'x-bls-header3') {
                $fillcolor=array(122,202,225);
//                $this->SetFillColor(91,168,225);
                $fill = true;
            }else if ($v['cls_a'] == 'x-bls-header4') {
                $fillcolor=array(255, 165, 0);
                $fill = true;
            } else if ($v['cls_a'] == 'x-bls-header5') {
//                $this->SetFillColor(255,215,0);
                $fillcolor=array(255,215,0);
                $fill = true;
            }else if ($v['cls_a'] == 'x-bls-header6') {
                $fillcolor=array(230, 255, 153);
                $fill = true;
            }
            else $fill = false;
            if ($v['groupname_a']) {                
                array_push($filler, array($fill,$fillcolor));
//                $this->Cell($w[0], 5, $v['groupname_a'], 0, 0, 'L', $fill);
            }else
                array_push($filler, array(false,$fillcolor));
//                $this->Cell($w[0], 5, $v['groupname_a'], 0, 0, 'L', false);
            array_push($filler, array($fill,$fillcolor));
            array_push($filler, array($fill,$fillcolor));
            array_push($filler, array($fill,$fillcolor));
            array_push($filler, array($fill,$fillcolor));
//            $this->Cell($w[1], 5, $v['nama_a'], 0, 0, 'L', $fill);
//            $this->Cell($w[2], 5, $v['subtotal_a'], 0, 0, 'R', $fill);
            
//            if ($v['total_a']) {
//                array_push($filler, array($fill,$fillcolor));
////                $this->Cell($w[3], 5, $v['total_a'], 0, 0, 'R', $fill);
//            } 
//            else {
//                if ($v['groupname_a']) {
//                    array_push($filler, array($fill,$fillcolor));
////                    $this->Cell($w[3], 5, $v['total_a'], 0, 0, 'R', $fill);
//                } else
//                    array_push($filler, array(false,$fillcolor));
////                    $this->Cell($w[3], 5, $v['total_a'], 0, 0, 'R', false);
//            }

            $fill = false;
            if ($v['cls_p'] == 'x-bls-header') {
                $fillcolor=array(255, 176, 196);
//                $this->SetFillColor(255, 176, 196);
                $fill = true;
            } else if ($v['cls_p'] == 'x-bls-header1') {
//                $this->SetFillColor(176, 255, 197);
                $fillcolor=array(176, 255, 197);
                $fill = true;
            }else if ($v['cls_p'] == 'x-bls-header2') {
                $fillcolor=array(221,225,241);
                $fill = true;
            } else if ($v['cls_p'] == 'x-bls-header3') {
                $fillcolor=array(122,202,225);
//                $this->SetFillColor(91,168,225);
                $fill = true;
            }else if ($v['cls_p'] == 'x-bls-header4') {
                $fillcolor=array(255, 165, 0);
                $fill = true;
            } else if ($v['cls_p'] == 'x-bls-header5') {
//                $this->SetFillColor(255,215,0);
                $fillcolor=array(255,215,0);
                $fill = true;
            }else if ($v['cls_p'] == 'x-bls-header6') {
                $fillcolor=array(230, 255, 153);
                $fill = true;
            }
            else $fill = false;
            
            if ($v['groupname_p']) {
                array_push($filler, array($fill,$fillcolor));
//                $this->Cell($w[4], 5, $v['groupname_p'], 0, 0, 'L', $fill);
            }else{
                array_push($filler, array(false,$fillcolor));
//                $this->Cell($w[4], 5, $v['groupname_p'], 0, 0, 'L', false);
            }   
            if($v['nama_p']){
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
//                $this->Cell($w[5], 5, $v['nama_p'], 0, 0, 'L', $fill);
//                $this->Cell($w[6], 5, $v['subtotal_p'], 0, 0, 'R', $fill);
                
            }else if($v['groupname_p']){
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
                array_push($filler, array($fill,$fillcolor));
//                $this->Cell($w[5], 5, $v['nama_p'], 0, 0, 'L', $fill);
//                $this->Cell($w[6], 5, $v['subtotal_p'], 0, 0, 'R', $fill);
                
            }
            else{
                array_push($filler, array(false,$fillcolor));
                array_push($filler, array(false,$fillcolor));
                array_push($filler, array(false,$fillcolor));
                array_push($filler, array(false,$fillcolor));
//                $this->Cell($w[5], 5, $v['nama_p'], 0, 0, 'L', false);
//                $this->Cell($w[6], 5, $v['subtotal_p'], 0, 0, 'R', false);
                
            }
            if($v['total_p']){
                array_push($filler, array($fill,$fillcolor));
//                $this->Cell($w[7], 5, $v['total_p'], 0, 0, 'R', $fill);
            }
//            elseif ($v['groupname_p']) {
//                array_push($filler, array($fill,$fillcolor));
////                $this->Cell($w[7], 5, $v['total_p'], 0, 0, 'R', $fill);
//            }
            else array_push($filler, array(false,$fillcolor));
//                $this->Cell($w[7], 5, $v['total_p'], 0, 0, 'R', false);
            
            $this->RowHead($filter,
                        array(
                            $v['groupname_a'],
                            $v['kd_akun_a'],
                            $v['nama_a'],
                            $v['total_a'],
                            $v['subtotal_a'],
                            $v['groupname_p'],
                            $v['kd_akun_p'],
                            $v['nama_p'],
                            $v['total_p'],
                            $v['subtotal_p']
                        ),
                        $w,
                        $al,   
                        5,0,1,0,$filler
                    );
            
            
            
            
            
            
//            $this->Ln();
            $i++;
        }
    }
    
    function RowHead($filter,$data,$wd,$al,$hkali,$rect,$pb=0,$b=0,$clsarr=null,$bcolumn=NULL)
	{
		//Calculate the height of the row
            
            
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($wd[$i],$data[$i]));
		$h=$hkali*$nb;
		//Issue a page break first if needed
                if($pb==1){
                    $this->CheckPageBreak($h,$filter);
                }
		//Draw the cells of the row
                $this->SetFillColor(255);
            $this->SetFont('Arial', '', 8);
		for($i=0;$i<count($data);$i++)
		{
			$w=$wd[$i];
			$a=isset($al[$i]) ? $al[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
                        
                        $fill=0;
                        $this->SetFont('Arial', '', 8);
                        if(count($clsarr)>0){
//                            $fill=$clsarr[$i][0];
                           if($clsarr[$i][0]){
                               $fill=1;
                               $this->SetFillColor($clsarr[$i][1][0],$clsarr[$i][1][1],$clsarr[$i][1][2]);
                           }
                        }
                        if($fill==1){
                            $this->SetFont('Arial', 'B', 8);
                            $this->Rect($x,$y,$w,$h,'F');
                        }
                        if($bcolumn){
                            $ada=0;
                            foreach($bcolumn as $k){
                                if($k==$i){
                                    $ada=1;
                                }
                            }
                            if($ada==1){                                
                                $this->MultiCell($w,$hkali,$data[$i],$b,$a,$fill);
                            }else{
                                $this->MultiCell($w,$hkali,$data[$i],0,$a,$fill);
                            }
                            
                        }else{
                            $this->MultiCell($w,$hkali,$data[$i],0,$a,$fill);
                        }
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
                
		//Go to the next line
		$this->Ln($h);
                $this->lineh=$h;
                
                
	}
        
        function CheckPageBreak($h,$st=array())
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger){
                    	$this->AddPage($this->CurOrientation);
                        $this->set_subtitle($st);
                        $this->set_header_column($this->widths);
                }
		
                
	}
        
        function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}

}

?>
