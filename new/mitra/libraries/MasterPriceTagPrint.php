<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

class MasterPriceTagPrint extends FormatLaporan {

    public function setKertas() {
        parent::setKertas();
    }

    public function Header() {
        $this->SetMargins(0, 4, 1, true);
    }

    public function Footer() {
        
    }

    public function privateData($h) {
        $tgl_cetak = date('d/M/Y');
        $this->AddPage();
        //$this->SetFont('times', '', 16);
        $this->SetMargins(0, 4, 1, true);
        $this->SetFooterMargin(0);
        $this->setPrintFooter(false);
        $this->SetAutoPageBreak(false, 0);

        $html2 = '<br/><table border="0" style="font-family:courier;">';
        if (!empty($h->pricetag->produk1->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk1->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk1->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk1->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk1->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk1->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk1->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk1->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk1->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk1->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk1->harga_jual), 0, ',', '.');
            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
            if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = $h->pricetag->produk1->chk_rp_coret .'
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family: Arial;"><strong>' . $h->pricetag->produk1->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk1->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk1->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk1->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk1->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk1->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0"  >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk2->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk2->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk2->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));
            
            $rp_jual = $h->pricetag->produk2->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk2->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk2->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk2->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk2->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk2->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk2->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk2->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family: Arial;"><strong>' . $h->pricetag->produk2->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk2->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk2->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
                
            $htmlkecil = '<td width="25%">
                                        <table border="0" >
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk2->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk2->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk2->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr >
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk3->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk3->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk3->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk3->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk3->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk3->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk3->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk3->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk3->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk3->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk3->harga_jual), 0, ',', '.');

            $htmlbesar = '';
            $htmlkecil = '';
            
            
            if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family: Arial;"><strong>' . $h->pricetag->produk3->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk3->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk3->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk3->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk3->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk3->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk4->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk4->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk4->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk4->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk4->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk4->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk4->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk4->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk4->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk4->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk4->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
            if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family:sans;"><strong>' . $h->pricetag->produk4->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk4->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk4->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk4->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk4->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk4->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk5->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk5->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk5->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk5->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk5->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk5->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk5->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk5->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk5->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk5->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk5->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
            if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family:sans;"><strong>' . $h->pricetag->produk5->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk5->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk5->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk5->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk5->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk5->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk6->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk6->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk6->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk6->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk6->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk6->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk6->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk6->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk6->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk6->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk6->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
             if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family:sans;"><strong>' . $h->pricetag->produk6->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk6->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk6->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk6->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk6->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk6->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk7->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk7->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk7->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk7->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk7->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk7->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk7->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk7->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk7->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk7->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk7->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
             if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family:sans;"><strong>' . $h->pricetag->produk7->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk7->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk7->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk7->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk7->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk7->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }
        
        if (!empty($h->pricetag->produk8->kd_produk)) {
            $params1 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk8->kd_produk, 'C128', '', '', 450, 9, 0.4, '', 'Y'));
            $params2 = $this->serializeTCPDFtagParameters(array($h->pricetag->produk8->kd_produk, 'C128', '', '', 450, 5, 0.3, '', 'Y'));

            $rp_jual = $h->pricetag->produk8->chk_rp_coret === '1' ? '<strike>' . number_format(round($h->pricetag->produk8->harga_jual), 0, ',', '.') . '</strike>' : number_format(round($h->pricetag->produk8->harga_jual), 0, ',', '.');
            $rp_jual_coret = $h->pricetag->produk8->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk8->rp_coret), 0, ',', '.') : '';
            $rp_jual_kecil = $h->pricetag->produk8->chk_rp_coret === '1' ? number_format(round($h->pricetag->produk8->rp_coret), 0, ',', '.') : number_format(round($h->pricetag->produk8->harga_jual), 0, ',', '.');

            
            $htmlbesar = '';
            $htmlkecil = '';
            
            
             if($h->pricetag->produk1->cetak_besar === '1')
            $htmlbesar = '
                            <tr>
                                    <td colspan="3" align="left" style="font-size:50px;font-family:sans;"><strong>' . $h->pricetag->produk8->nama_produk . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="3" align="center" width="220px">' . '<tcpdf method="write1DBarcode" params="' . $params1 . '" />' . '<br/><br/>' . $h->pricetag->produk8->kd_produk . '</td>
                                    <td rowspan="4" align="center" width="40px" style="font-size:74px;"><strong>Rp</strong></td>
                                    <td rowspan="2" align="right" style="font-family: courier;font-size:100px;width:220px;"><strong>' . $rp_jual . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td rowspan="2" align="right" style="font-size:100px;"><strong>' . $rp_jual_coret . '</strong></td>
                                    <td></td>
                            </tr>

                            <tr>
                                    <td align="left" style="font-size:25px;">' . $tgl_cetak . ' MITRA BANGUNAN SUPERMARKET</td>
                                    <td align="left" style="font-size:25px;"> / ' . $h->pricetag->produk8->nm_satuan . '</td>
                                  
                            </tr>
                   ';
            
            if($h->pricetag->produk1->cetak_kecil === '1')
            $htmlkecil = '<td width="25%">
                                        <table border="0">
                                                <tr>
                                                        <td style="font-size:30px;"><strong>' . $h->pricetag->produk8->nama_produk . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td style="font-size:40px;"><strong>Rp. ' . $rp_jual_kecil . ' / ' . $h->pricetag->produk8->nm_satuan . '</strong></td>
                                                </tr>

                                                <tr>
                                                        <td>' . '<tcpdf method="write1DBarcode" params="' . $params2 . '" />' . '<br/>' . $h->pricetag->produk8->kd_produk . '</td>
                                                </tr>

                                                <tr>
                                                        <td align="center" style="font-size:20px;"> MITRA BANGUNAN SUPERMARKET</td>
                                                </tr>
                                        </table>
                                </td >';
            
            $html = '<table border="0" >
                        <tr>
                            <td width="75%" height="140px" align="center">

                                        <table border="0" width="730px" >
                                '. $htmlbesar .'  </table>

                                </td>
                                '. $htmlkecil .'
                        </tr>
                </table>';

            $html2 = $html2 . '<tr><td>' . $html . '</td></tr>';
        }


        $html2 = $html2 . '</table>';
        
        $this->writeHTML($html2, true, false, true, false, 'C');
    }

}
