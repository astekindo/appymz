<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanRekapPOPrint extends FormatLaporan {

    public function Header() {
        $this->SetMargins( 4, 2, 4, true);
    }

    public function privateData($d) {
        $this->AddPage();
        $this->SetFont('courier', '', 11);
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
                                            <td  align ="left">______________________________________________________________________________________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table width="750" border="1" cellspacing="0">';
        $detail .= '<tr>
                        <th align="center" width="100">TANGGAL PO</th>
                        <th align="center" width="130">NOMOR BUKTI<br></th>
                        <th align="center" width="120">KODE SUPPLIER</th>
                        <th align="center" width="250">NAMA SUPPLIER</th>
                        <th align="center" width="50">QTY </th>
                        <th align="center" width="`180">JUMLAH</th>

                </tr>	';
        
         // Belum diset ke field di databasenya
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center">'. $v->tanggal_po. '</td>
                                <td align="center">' . $v->no_po . '</td>
                                <td align="center">' . $v->kd_suplier_po . '</td>
                                <td align="center">' . $v->nama_supplier . '</td>
                                <td align="center">' . $v->qty_po . '</td>
                                <td align="center">' . $v->rp_jumlah . '</td>
                                
                        </tr>	';
                
            }

        }
        
        $detail .= '</table>';

       

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">			
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%" >                                        
					<tr style="font-size:16:">
                                        <td width="350">Laporan Rekap Purchase Order</td>                                        
					</tr>  
                                        <tr style="font-size:14:">
                                        <td width="80">Periode</td>
                                        <td>: </td>
                                        </tr>
                                        <tr><td></td></tr>                                 
					
                                        <tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        
					
                                      
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
