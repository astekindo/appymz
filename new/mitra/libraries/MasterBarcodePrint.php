<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class MasterBarcodePrint extends FormatLaporan {
    
    public function setKertas() {
        parent::setKertas();
    }
    
    public function Header() {
        $this->SetMargins(0, 2, 0, true);
    }	
    public function Footer() {
    }	
        
    public function privateData(){
            $this->AddPage();
            $this->SetFont('times', '', 4);
            $this->SetMargins(0, 2, 0, true);
            $this->SetFooterMargin(0);
            $this->setPrintFooter(false);
            $this->SetAutoPageBreak(false, 0);
            
            $params1 = $this->serializeTCPDFtagParameters(array('2010040006137', 'C128', 5, 7, 300, 4, 0.2, '', 'Y'));    
            $params2 = $this->serializeTCPDFtagParameters(array('2010040006137', 'C128', 40, 7, 300, 4, 0.2, '', 'N'));    
            $params3 = $this->serializeTCPDFtagParameters(array('2010040006137', 'C128', 75, 7, 300, 4, 0.2, '', 'N'));    
                
                $html = '<table border="0">
                            <tr>
                                <td align="center">YAKOB ANGILA RUMAH</td>
                                <td align="center">YAKOB ANGILA RUMAH</td>
                                <td align="center">YAKOB ANGILA RUMAH</td>
                            </tr>
                            <tr>
                                <td align="center">KUNING NO.8</td>
                                <td align="center">KUNING NO.8</td>
                                <td align="center">KUNING NO.8</td>
                            </tr>
                            <tr>
                                <td align="center">'.
                        '<tcpdf method="write1DBarcode" params="'.$params1.'" />'.'</td>
                                <td align="center">'.'<tcpdf method="write1DBarcode" params="'.$params2.'" />'.'</td>
                                    <td align="center">'.'<tcpdf method="write1DBarcode" params="'.$params3.'" />'.'</td>
                            </tr>
                            <tr>
                                <td align="center">2010040006137</td>
                                <td align="center">2010040006137</td>
                                <td align="center">2010040006137</td>
                            </tr>
                            <tr>
                                <td align="center">MITRA BANGUNAN SUPERMARKET</td>
                                <td align="center">MITRA BANGUNAN SUPERMARKET</td>
                                <td align="center">MITRA BANGUNAN SUPERMARKET</td>
                            </tr>
                        </table>';
                            

		$this->writeHTML($html, true, false, true, false, 'C');	
                
                
    }
}
