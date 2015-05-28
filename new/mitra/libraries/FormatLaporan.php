<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class FormatLaporan extends TCPDF {

	protected $CI;
	
	public function Header() {
		$this->CI = & get_instance();
		$this->SetFont('times', '', 8);
		$html = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100" rowspan="4"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>
						<td width="600" rowspan="2" align = "center"><h2>PT. SURYA KENCANA KERAMINDO</h2></td>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).'</td>
					</tr>
					<tr>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).'</td>
					</tr>
					<tr>
						<td width="600" rowspan="2" align = "center">' . $this->CI->config->item('header_laporan') . '</td>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td>
					</tr>
					<tr>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4).'</td>
					</tr>
				</table>';
		$this->writeHTML($html, true, false, true, false, 'C');		
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
	}
	
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		$this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
	}	
	
	public function setKertas(){
		// remove default header/footer
		//$this->setPrintHeader(false);
		
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));		
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT - 3, PDF_MARGIN_TOP+3, PDF_MARGIN_RIGHT);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);		
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		//$this->setLanguageArray($l);
		// set font
		$this->SetFont('times', '', 10);		
	}
}
