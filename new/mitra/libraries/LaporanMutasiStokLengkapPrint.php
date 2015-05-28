<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanMutasiStokLengkapPrint extends FormatLaporan {

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
                        <th align="center" width="90">No Bukti</th>
                        <th align="center" width="70">Tgl SO<br></th>
                        <th align="center" width="60">Tgl DO</th>
                        <th align="center" width="70">Kode Member</th>
                        <th align="center" width="70">Nama Konsumen</th>
                        <th align="center" width="70">Kode Produk</th>
                        <th align="center" width="80">Nama Produk</th>
                        <th align="center" width="70">Qty SO</th>
                        <th align="center" width="60">Qty Kirim</th>
                        <th align="center" width="60">Qty Sisa</th>
                        <th align="center" width="70">Tagihan Awal</th>
                        <th align="center" width="70">Tagihan Update</th>
                        <th align="center" width="70">Nilai Barang Sisa</th>
                        <th align="center" width="70">Gantung (Hari)</th>
                        
                        
                </tr>	';
       
        
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center" width="90">'. $v->no_so . '</td>
                                <td align="center" width="70">' . $v->tgl_so . '</td>
                                <td align="center" width="60">' . $v->tgl_do . '</td>
                                <td align="center" width="70">' . $v->kd_member . '</td>
                                <td align="center" width="70">' . $v->nama_konsumen . '</td>
                                <td align="center" width="70">' . $v->kd_produk . '</td>
                                <td align="center" width="80">' . $v->nama_produk . '</td>
                                <td align="center" width="70">' . $v->qty . '</td>
                                <td align="center" width="60">' . $v->qty_kirim . '</td>
                                <td align="center" width="60">' . $v->qty_sisa . '</td>
                                <td align="center" width="70">' . $v->rp_tagihan_awal . '</td>
                                <td align="center" width="70">' . $v->rp_tagihan_update . '</td>
                                <td align="center" width="70">' . $v->rp_nilai_sisa . '</td>
                                <td align="center" width="70">' . $v->hari_gantung . '</td>
                                
                              
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
                                        <td width="210">Laporan Sales Order</td>                                        
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
