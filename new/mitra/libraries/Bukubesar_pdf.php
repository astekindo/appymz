<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('fpdf/fpdf.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 */

class Bukubesar_pdf extends FPDF {
    var $cabang;
    var $periode;
    var $noakun;
    var $judul;
    
    var $lineh;
    function Header() {
        $this->Image('assets/img/logo-mbs.jpg', 5, 5, 23);
        $this->SetFont('courier', 'B', 12);
        $this->Cell(23);
        $this->Cell(70, 12, 'PT.SURYA KENCANA KERAMINDO');
        $this->Ln();
        $this->SetFont('courier', 'B', 10);
        $this->Cell(0, 5, 'LAPORAN BUKU BESAR', 0, 0, 'C');
        $this->Ln();
        $this->Ln(2);
        
        $this->SetFont('courier', '', 8);
        $this->Cell(14, 4, 'Cabang', 0, 0, 'L');
        $this->Cell(3, 4, ':', 0, 0, 'C');
        $this->Cell(146, 4, $this->cabang, 0, 0, 'L');
                
        $this->Cell(14, 4, 'Tanggal', 0, 0, 'L');
        $this->Cell(3, 4, ':', 0, 0, 'C');
        $this->Cell(20, 4, date('d/m/Y'), 0, 0, 'L');
        
        $this->Ln(3);
        $this->Cell(14, 4, 'Periode', 0, 0, 'L');
        $this->Cell(3, 4, ':', 0, 0, 'C');
        $this->Cell(146, 4, $this->periode, 0, 0, 'L');
        
        $this->Cell(14, 4, 'Jam', 0, 0, 'L');
        $this->Cell(3, 4, ':', 0, 0, 'C');
        $this->Cell(20, 4, date('h:i:s'), 0, 0, 'L');
        
        $this->Ln(3);
        
	$this->RowHead(
                array(
                    'No.Akun',
                    ':',
                    $this->noakun,
                    'Halaman',
                    ':',
                    $this->PageNo() . ' of {nb}'
			),
                array(14,3, 146, 14,3,20),
                array('L','C', 'L', 'L','C','L'),   
                4,0
                );
                       
                        
	$this->Ln(1);
        
    }
    function Footer() {
       
    }
    
    function setJudul($mjudul){
        $this->judul=$mjudul;
    }
    function setPeriode($mperiode){
        $this->periode=$mperiode;
    }
    function setNoakun($mnoakun){
        $this->noakun=$mnoakun;
    }
    function setCabang($mcabang){
        $this->cabang=$mcabang;
    }
    
    
    function set_header_column($w) {
        $header = array('TANGGAL JURNAL','NO.JURNAL', 'NO.VOUCHER', 'KETERANGAN', 'COST CENTER', 'DEBET', 'KREDIT', 'SALDO');
        if($this->cabang=='Semua Cabang'){
            $header = array('TANGGAL JURNAL','NO.JURNAL', 'NO.VOUCHER CABANG', 'KETERANGAN', 'COST CENTER', 'DEBET', 'KREDIT', 'SALDO');
        }
        
//        $header = array('01/01/2014','JR-201402-00002', 'EV-201402-00002', 'KETERANGAN DDDDDDDD', 'COST CENTER', '10.000.000.000', '10.000.000.000', '10.000.000.000');
        $this->SetFont('courier', '', 8);
        $this->RowHead(
                $header,
                $w,
                array('C','C', 'C', 'C','C','C','C','C'),   
                3,1
                );
        
//        for($i=0;$i<count($w);$i++) $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', false);               
        $this->Ln();
    }
    
    function create_pdf($data) {
         $this->SetAutoPageBreak(true,18);
        $w=array(19,28,28,26,15,28,28,28);
        $al=array('C','C', 'C', 'L','C','R','R','R');
        $this->SetWidths($w);
//        $this->widths=$w;
        $this->SetAligns($al);
       
        $this->set_header_column($w);
        
        foreach ($data as $v) {
            $this->SetFont('courier', 'B', 8);
            if($v['tgl_transaksi']=='Account:'){
                $this->CheckPageBreak($this->lineh+8);
//                $this->lineh=$h;
                $this->SetFont('courier', 'B', 8);
                $this->Cell(200, 4, $v['tgl_transaksi'].$v['keterangan'], 0, 0, 'L', false);
                $this->Ln();
            }else{
                $this->SetFont('courier', '', 8);
                if($v['keterangan']=='Saldo Awal'){
                    $this->SetFont('courier', 'B', 8);
                    $v['tgl_transaksi']=NULL;
                    $this->CheckPageBreak($this->lineh+4);
                    $this->RowHead(
                        array(
                            $v['tgl_transaksi'],
                            $v['idjurnal'],
                            $v['novoucher'],
                            $v['keterangan'],
                            $v['costcenter'],
                            $v['jumlahd'],
                            $v['jumlahk'],
                            $v['jumlah']
                        ),
                        $w,
                        $al,   
                        3,0,1
                    );
                    
                }elseif($v['keterangan']=='Saldo Akhir'){
                    $this->SetFont('courier', 'B', 8);
                    $v['tgl_transaksi']=NULL;
                    $v['keterangan']=NULL;
                    $this->RowHead(
                        array(
                            $v['tgl_transaksi'],
                            $v['idjurnal'],
                            $v['novoucher'],
                            $v['keterangan'],
                            $v['costcenter'],
                            $v['jumlahd'],
                            $v['jumlahk'],
                            $v['jumlah']
                        ),
                        $w,
                        $al,   
                        3,0,1,'T',array(5,6,7)
                    );
                }else{
                    $v['tgl_transaksi']=date('d/m/Y',strtotime($v['tgl_transaksi']));
                    if($this->cabang=='Semua Cabang'){
                        $v['novoucher'] .= ' '.$v['cabang'];
                    }
                    $this->RowHead(
                        array(
                            $v['tgl_transaksi'],
                            $v['idjurnal'],
                            $v['novoucher'],
                            $v['keterangan_detail'],
                            $v['costcenter'],
                            $v['jumlahd'],
                            $v['jumlahk'],
                            $v['jumlah']
                        ),
                        $w,
                        $al,   
                        3,0,1
                    );
                }
                
                
                
            }
            $this->Ln(1);
            
            if($v['keterangan']=='Saldo Akhir'){
                $this->Ln();
            }
            $this->CheckPageBreak($this->lineh+2);
            
        }
        
        
    }
    
    //===============
        private $widths;
	private $aligns;
 
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
 
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
        function RowHead($data,$wd,$al,$hkali,$rect,$pb=0,$b=0,$bcolumn=NULL)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($wd[$i],$data[$i]));
		$h=$hkali*$nb;
		//Issue a page break first if needed
                if($pb==1){
                    $this->CheckPageBreak($h);
                }
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$wd[$i];
			$a=isset($al[$i]) ? $al[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
                        if($rect==1){
                            $this->Rect($x,$y,$w,$h);
                        }
//			
			//Print the text
                        if(is_numeric($data[$i])){
                            $data[$i]=number_format($data[$i]);
                        }
                        if($bcolumn){
                            $ada=0;
                            foreach($bcolumn as $k){
                                if($k==$i){
                                    $ada=1;
                                }
                            }
                            if($ada==1){
                                $this->MultiCell($w,$hkali,$data[$i],$b,$a);
                            }else{
                                $this->MultiCell($w,$hkali,$data[$i],0,$a);
                            }
                            
                        }else{
                            $this->MultiCell($w,$hkali,$data[$i],0,$a);
                        }
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
                       
                        
                        
                        
		}
                
		//Go to the next line
		$this->Ln($h);
                $this->lineh=$h;
                
                
	}
        
	function Row($data,$st)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=4*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h,$st);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
                        if(is_numeric($data[$i])){
                            $data[$i]=number_format($data[$i]);
                        }
			$this->MultiCell($w,4,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
                       
                        
                        
                        
		}
                
		//Go to the next line
		$this->Ln($h);
                $this->lineh=$h;
                
                
	}
 
        function CheckPageBreakLegal($h,$st=array())
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger){
                    	$this->AddPage($this->CurOrientation);
                        $this->set_subtitle($st);
//                        $this->set_header_column($this->widths);
                }
		
                
	}
	function CheckPageBreak($h,$st=array())
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger){
                    	$this->AddPage($this->CurOrientation);
//                        $this->set_subtitle($st);
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
//===================================================   
}
?>
