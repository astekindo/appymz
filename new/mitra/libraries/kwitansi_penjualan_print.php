<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatKwitansi.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class kwitansi_penjualan_print extends FormatKwitansi {

    public function privateData($h, $d) {
        $this->setNoKwitansi($h->no_kwitansi);
        $this->AddPage();
        $this->SetFont('courier', '', 13);
        $this->CI = & get_instance();
        $this->SetMargins(10, 2, 4, true);
        $detailLeftTable='
                        <table border="0">
                            <tr >
                                <td width="300"><h1 style="border-top:1px solid black; border-bottom:1px solid black;font-size:80px;"> Rp.' . number_format($h->rp_total, 0, ',', '.') . ',-</h1></td>
                                <td width="480"></td>        
                            </tr>
                            <tr>
                                <td colspan="2">Catatan :</td>
                            </tr>
                            <tr><td colspan="2">1. Pembayaran dengan Cek/Bilyet Giro diakui bila dicantumkan nama :</td></tr>
                            <tr><td colspan="2">&nbsp;&nbsp;&nbsp;'  . $this->CI->session->userdata(NAMA_REKENING_BANK) .'</td></tr>
                            <tr><td colspan="2">2. Pembayaran dengan Cek/Bilyet Giro sah bila telah dicairkan di rekening :</td></tr>
                            <tr><td colspan="2">&nbsp;&nbsp;&nbsp;'  . $this->CI->session->userdata(NAMA_REKENING_BANK) .'</td></tr>
                            <tr><td colspan="2">&nbsp;&nbsp;&nbsp;'. $this->CI->session->userdata(BANK_FAKTUR) . '</td></tr>
                            <tr><td colspan="2">3. Pembayaran dengan transfer ditujukan ke rekening yang tersebut d point 2 &nbsp;&nbsp;&nbsp;diatas.</td></tr>        
                                
                            </table>
                        ';
       $detailRightTable='<table border="0">
                            <tr><td align="center"> Palembang, ' . date('d-m-Y',  strtotime($h->tanggal)) . '</td></tr>
                                <br/><br/><br/><br/><br/><br/><br/>
                            <tr>
                                <td align="center"><p>' . $this->CI->session->userdata(NAMA_FAKTUR) . '</p></td>
                            </tr>
                            <tr>
                               <td align="center">(' . $this->CI->session->userdata(JABATAN_FAKTUR) . ')</td>
                            </tr>
                        </table>'; 
        $detail = '
            <table border="0" width="1050" cellspacing="0" style="text-align:left;">
            <tr><td></td></tr>
                    <tr>
                        <td width="800">'.$detailLeftTable.'</td>
                        <td width="250">'.$detailRightTable.'</td>
                    </tr>
                    </table>';

        $html = $htmlHeader . '<br/><br/>
            
		<table width="1050" border="1" cellspacing="0" cellpadding="0" style="text-align:left;padding:10px;">
			<tr>
				<td>
				<table border="0" cellspacing="0" style="text-align:left;padding:5px;">
					<tr>
                                                <td width="10"></td>
						<td width="200" style="border-bottom:1px solid black">Sudah Terima Dari</td>
                                                <td width="30" rowspan="2" style="line-height:10px;">:</td>
						<td width="800" rowspan="2"><p>' . strtoupper($h->terima_dari) . '</p></td>
                                        </tr>
                                        <tr >
                                                <td width="10"></td>
						<td width="200">Received From</td>
					</tr>
                                        <tr >
                                                <td width="10"></td>
						<td width="200" style="border-bottom:1px solid black">Banyaknya Uang</td>
                                                <td width="30" rowspan="2" style="line-height:11px;">:</td>
                                                <td width="800" rowspan="2"><p>' . $h->terbilang_total . ' RUPIAH</p></td>
					</tr>
                                        <tr >
                                                <td width="10"></td>
						<td width="200" vertical-align="top">Amount Received</td>
					</tr>
				</table>
                        	</td>
			</tr>
			<tr>
				<td>
				<table border="0" cellspacing="0" style="text-align:left;left;padding:5px;">
					<tr>
                                                <td width="10"></td>
						<td width="200" style="border-bottom:1px solid black">Untuk Pembayaran</td>
                                                <td width="30" rowspan="2" style="line-height:10px;">:</td>
						<td width="800" rowspan="2">' . strtoupper($h->keterangan) . '</td>
					</tr>
                                        <tr >
                                                <td width="10"></td>
						<td width="200">In Payment Of</td>
					</tr>
				</table>
                                </td>
			</tr>
		</table>
                ' . $detail;

        $this->writeHTML($html, true, false, true, false, 'C');
    }

}

?>
