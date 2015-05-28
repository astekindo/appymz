<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanUmurOutstandingPOPrint extends FormatLaporan {

    public function Header() {
        $this->SetMargins( 4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('times', '', 8);
        $this->CI = & get_instance();
        $this->SetMargins( 10, 2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1630"  align = "left" style="font-size:24;">' . $this->CI->config->item('header_laporan_matrix') . '</td>
						
					</tr>
					
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
                                        <tr>
                                            <td  align ="left">____________________________________________________________________________________________________________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table width="1030" border="0" cellspacing="0">';
        $detail .= '<tr>
                        <th align="center" width="60">TGL</th>
                        <th align="center" width="100">NO PO<br></th>
                        <th align="center" width="60">KODE SUPPLIER</th>
                        <th align="center" width="210">NAMA SUPPLIER</th>
                        <th align="center" width="100">KODE BARANG</th>
                        <th align="center" width="210">NAMA BARANG</th>
                        <th align="center" width="35">QTY PO</th>
                        <th align="center" width="35">QTY RO</th>
                        <th align="center" width="35">SISA</th>
                        <th align="center" width="65">MASA BERLAKU PO</th>
                        <th align="center" width="60">JATUH TEMPO PO</th>

                </tr>	';
        $detail .= '<tr>
                        <th align="center" width="180">BELUM JATUH TEMPO</th>
                        <th align="center" width="180">SUDAH JATUH TEMPO<br></th>     
                </tr>	';
         $detail .= '<tr>
                        <th align="center" width="60">> 60 Hari</th>
                        <th align="center" width="60">31-60 Hari<br></th>  
                        <th align="center" width="60">1-30 Hari<br></th>
                        <th align="center" width="60">1-30 Hari<br></th>
                        <th align="center" width="60">31-60 Hari<br></th>
                        <th align="center" width="60">> 60 Hari</th>
                </tr>	';
         // Belum diset ke field di databasenya
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center">'. $v->nama_produk . '</td>
                                <td align="center">' . $v->qty . '</td>
                                <td align="center">' . $v->nm_satuan . '</td>
                                <td align="center">' . $v->rp_jumlah . '</td>
                                <td align="center">' . $v->rp_disk . '</td>
                                <td align="center">' . $v->rp_potongan . '</td>
                                <td align="center">' . $v->rp_total . '</td>
                        </tr>	';
                $detail .= '<tr>
											
									
                                <td align="center">'. $v->nama_produk . '</td>
                                <td align="center">' . $v->qty . '</td>
                                <td align="center">' . $v->nm_satuan . '</td>
                                <td align="center">' . $v->rp_jumlah . '</td>
                                <td align="center">' . $v->rp_disk . '</td>
                                <td align="center">' . $v->rp_potongan . '</td>
                                <td align="center">' . $v->rp_total . '</td>
                        </tr>	';
                $sum_qty = $sum_qty + $v->qty;
                $sum_harga = $sum_harga + $v->rp_jumlah;
                $sum_diskon = $sum_diskon + $v->rp_disk;
                $sum_diskon_ekstra = $sum_diskon_ekstra + $v->rp_potongan;
                $sum_total = $sum_total + $v->rp_total;
            }

           $detail .= '<tr><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td><td></td><td align="center">' . $sum_harga . '</td><td align="center">' . $sum_diskon . '</td><td align="center">' . $sum_diskon_ekstra . '</td><td align="center">' . $sum_total . '</td></tr>';
          

         
        }
        
        $detail .= '</table>';

        

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">			
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%" >                                        
					<tr style="font-size:16:">
                                        <td width="280">Laporan Umur Outstanding PO</td>                                        
					</tr>  
                                        <tr style="font-size:14:">
                                        <td width="80">Periode</td>
                                        <td>: </td>
                                        </tr>
                                        <tr><td></td></tr>                                 
					<table width="980" border="1" cellspacing="0">
                                        <tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        
					<tr>
						<td colspan="2">' . $summary . '</td>
					</tr>
                                       </table>
				</table>
				
				</td>
			</tr>
			<tr>
				<td align="left">&nbsp;</td>
			</tr>	
		</table>';

        $this->writeHTML($html, true, false, true, false, 'C');
        
    }

    
    public function Footer() {
		// Position at 15 mm from bottom
		
	}	
}

?>
