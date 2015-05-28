<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanPenjualanPerKategori1Print extends FormatLaporan {

    public function Header() {
        $this->SetMargins( 4, 2, 4, true);
    }

    public function privateData($h, $d) {
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
        
        $detail = '<table width="1030" border="1" cellspacing="0">';
        $detail .= '<tr>
                        <th align="center" width="80">Kategori1</th>
                        <th align="center" width="50">Jan<br></th>
                        <th align="center" width="50">Feb</th>
                        <th align="center" width="50">Maret</th>
                        <th align="center" width="50">April</th>
                        <th align="center" width="50">Mei</th>
                        <th align="center" width="60">Juni</th>
                        <th align="center" width="60">Juli</th>
                        <th align="center" width="60">Agust</th>
                        <th align="center" width="60">Sept</th>
                        <th align="center" width="60">Okt</th>
                        <th align="center" width="60">Nov</th>
                        <th align="center" width="60">Des</th>
                        <th align="center" width="60">Jumlah</th>
                        <th align="center" width="60">Persen</th>
                        <th align="center" width="60">Frek Traks</th>
                        <th align="center" width="50">Qty</th>
                        
                        
                </tr>	';
       
        
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center" width="80">'. $v->kategori1 . '</td>
                                <td align="center" width="50">' . $v->jan . '</td>
                                <td align="center" width="50">' . $v->feb . '</td>
                                <td align="center" width="50">' . $v->mar . '</td>
                                <td align="center" width="50">' . $v->apr . '</td>
                                <td align="center" width="50">' . $v->mei . '</td>
                                <td align="center" width="60">' . $v->jun . '</td>
                                <td align="center" width="60">' . $v->jul . '</td>
                                <td align="center" width="60">' . $v->agu . '</td>
                                <td align="center" width="60">' . $v->sep . '</td>
                                <td align="center" width="60">' . $v->okt . '</td>
                                <td align="center" width="60">' . $v->nop . '</td>
                                <td align="center" width="60">' . $v->des . '</td>
                                <td align="center" width="60">' . $v->jumlah . '</td>
                                <td align="center" width="60">' . $v->persen . '</td>
                                <td align="center" width="60">' . $v->frek_trx . '</td>
                                <td align="center" width="50">' . $v->qty . '</td>
                              
                        </tr>	';
                
                
            }

        }
        
        $detail .= '</table>';

        if ($h->tgl_retur) {
            $tgl_retur = date('d-m-Y', strtotime($h->tgl_retur));
        }

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">			
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%" >                                        
					<tr style="font-size:14:">
                                        <td width="400">Laporan Penjualan Per Kategori1</td>                                        
					</tr>  
                                        <tr style="font-size:12:">
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
