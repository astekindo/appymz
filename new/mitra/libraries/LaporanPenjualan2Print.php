<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanPenjualan2Print extends FormatLaporan {

    public function Header() {
        $this->SetMargins( 4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('times', '', 9);
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
        
        $detail = '<table width="1030" border="0" cellspacing="0">';
        $detail .= '<tr>
                        <th align="center" width="80">Tanggal</th>
                        <th align="center" width="100">No Bukti<br></th>
                        <th align="center" width="50">ID</th>
                        <th align="center" width="100">No Member</th>
                        <th align="center" width="100">Nama Member</th>
                        <th align="center" width="80">Status</th>
                        <th align="center" width="80">Kode Produk</th>
                        <th align="center" width="130">Nama Produk</th>
                        <th align="center" width="70">Jam</th>
                        <th align="center" width="40">QTY</th>
                        <th align="center" width="50">Satuan</th>
                        <th align="center" width="70">Hrg Jual</th>
                        
                        
                </tr>	';
        $detail .= '<tr>
                        <th align="center" width="50">Diskon1</th>
                        <th align="center" width="50">Diskon2<br></th>
                        <th align="center" width="50">Diskon3</th>
                        <th align="center" width="50">Diskon4</th>
                        <th align="center" width="60">Jumlah</th>
                        <th align="center" width="70">Diskon Tambahan</th>
                        <th align="center" width="70">Ongkos Kirim</th>
                        <th align="center" width="60">Ongkos Pasang</th>
                        <th align="center" width="60">Bank Charge</th>
                        <th align="center" width="70">G.Total</th>
                        

                </tr>	';
        
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center" width="80">'. $v->tgl . '</td>
                                <td align="center" width="100">' . $v->no_bukti . '</td>
                                <td align="center" width="50">' . $v->id . '</td>
                                <td align="left" width="100">' . $v->no_member . '</td>
                                <td align="center" width="100">' . $v->nm_member . '</td>
                                <td align="center" width="80">' . $v->status . '</td>
                                <td align="center" width="80">' . $v->kd_produk . '</td>
                                <td align="center" width="130">' . $v->nama_produk . '</td>
                                <td align="center" width="70">' . $v->jam . '</td>
                                <td align="center" width="40">' . $v->qty . '</td>
                                <td align="center" width="50">' . $v->satuan . '</td>
                                <td align="center" width="70">' . $v->rp_harga . '</td>
                              
                        </tr>	';
                $detail .= '<tr>
									
                               <td align="center" width="50">'. $v->diskon1 . '</td>
                                <td align="center" width="50">' . $v->diskon2 . '</td>
                                <td align="center" width="50">' . $v->diskon3 . '</td>
                                <td align="left" width="50">' . $v->diskon4 . '</td>
                                <td align="center" width="60">' . $v->rp_total. '</td>
                                <td align="center" width="70">' . $v->rp_ekstra_diskon . '</td>
                                <td align="center" width="70">' . $v->rp_ongkos_kirim . '</td>
                                <td align="center" width="60">' . $v->rp_ongkos_pasang . '</td>
                                <td align="center" width="60">' . $v->rp_bank_charge . '</td>
                                <td align="center" width="70">' . $v->g_total. '</td>
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
					<tr style="font-size:16:">
                                        <td width="210">Laporan Penjualan 2</td>                                        
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
