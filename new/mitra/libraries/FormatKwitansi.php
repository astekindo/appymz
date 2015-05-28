<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class FormatKwitansi extends TCPDF {

    protected $CI;
    protected $noKwitansi;

    public function getNoKwitansi() {
        return $this->noKwitansi;
    }

    public function setNoKwitansi($noKwitansi) {
        $this->noKwitansi = $noKwitansi;
    }

    public function Header() {
        $this->CI = & get_instance();
        $this->SetFont('courier', '', 13);
        $html = '<br />
				<table width="1050" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                               <td width="100" rowspan="6"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>                                               
                                               <td width="90"></td>
                                               <td width="520" align = "left"><h1>PT. SURYA KENCANA KERAMINDO</h3></td>
                                               <td width="330" align = "right"><h1>KWITANSI / RECEIPT</h3></td>
					</tr>
                                        <tr>
                                                <td width="90"></td>
                                                <td width="520" valign="bottom" align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1) . '</td>
                                        </tr>
                                        <tr>
                                               <td width="90"></td>
                                               <td width="520"  align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2) . '</td>
                                        </tr>
                                        <tr>
                                                <td width="90"></td>
						<td width="520" align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
                                                <td width="75" align="left" rowspan="2"></td>
                                                <td width="60" align="left" style="border-bottom:1px solid black;">No.</td>
                                                 <td width="20" align="center" style="line-height:8px;" rowspan="2">:</td>
                                                <td width="150" rowspan="2"align="left"><p>' . $this->getNoKwitansi() . '</p></td>
                                        </tr>
                                        <tr>
                                                <td width="90"></td>
						<td width="520" align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
                                                <td width="60" align="left">Number</td>
					</tr>
				</table>';
        $this->writeHTML($html, true, false, true, false, 'C');
        //$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
    }

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 'T', 0, 'R');
    }

    public function setKertas() {
        // remove default header/footer
        //$this->setPrintHeader(false);

        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $this->SetMargins(PDF_MARGIN_LEFT - 3, PDF_MARGIN_TOP + 3, PDF_MARGIN_RIGHT);
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
