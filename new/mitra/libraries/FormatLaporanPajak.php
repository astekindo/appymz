<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class FormatLaporanPajak extends TCPDF {

	protected $CI;
	public function Header() {
        $this->CI = & get_instance();
        }
//	public function Footer() {
//		// Position at 15 mm from bottom
//		$this->SetY(-15);
//		// Set font
//		$this->SetFont('helvetica', 'I', 8);
//		$this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
//		// Page number
//		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
//	}	
	
	public function setKertas(){
		// remove default header/footer
		//$this->setPrintHeader(false);
		
		//$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));		
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP , PDF_MARGIN_RIGHT);
		//$this->SetFooterMargin(PDF_MARGIN_FOOTER);		
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		//$this->setLanguageArray($l);
		// set font
		$this->SetFont('helvetica', '', 11);		
	}
}
