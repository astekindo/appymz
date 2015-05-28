<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanPenjualan1Print extends FormatLaporan {

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
                        <th align="center" width="90">No Bukti<br></th>
                        <th align="center" width="50">ID</th>
                        <th align="center" width="100">No Member</th>
                        <th align="center" width="100">Nama Member</th>
                        <th align="center" width="80">Status</th>
                        <th align="center" width="80">Kode Produk</th>
                        <th align="center" width="100">Nama Produk</th>
                        <th align="center" width="70">Jam</th>
                        <th align="center" width="40">QTY</th>
                        <th align="center" width="50">Satuan</th>
                        <th align="center" width="70">Hrg Jual</th>
                        <th align="center" width="80">Disk Reg</th>
                        
                </tr>	';
        $detail .= '<tr>
                        <th align="center" width="50">Jumlah</th>
                        <th align="center" width="50">Disk Tambahan<br></th>
                        <th align="center" width="50">Ongkos Kirim</th>
                        <th align="center" width="50">Ongkos Pasang</th>
                         <th align="center" width="50">Bank Charge</th>
                        <th align="center" width="60">G.Total</th>
                        <th align="center" width="70">Debit</th>
                        <th align="center" width="70">Kredit</th>
                        <th align="center" width="60">Cash</th>
                        <th align="center" width="60">Transfer</th>
                        <th align="center" width="70">Cek</th>
                        <th align="center" width="70">Giro</th>
                        <th align="center" width="70">Voucher</th>
                        <th align="center" width="70">Piutang</th>
                        <th align="center" width="70">Retur</th>
                        <th align="center" width="70">G.Ttl Bayar</th>

                </tr>	';
         // Belum diset ke field di databasenya
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center" width="80">'. $v->tgl . '</td>
                                <td align="center" width="90">' . $v->no_bukti . '</td>
                                <td align="center" width="50">' . $v->id . '</td>
                                <td align="left" width="100">' . $v->no_member . '</td>
                                <td align="center" width="100">' . $v->nm_member . '</td>
                                <td align="center" width="80">' . $v->status . '</td>
                                <td align="center" width="80">' . $v->kd_produk . '</td>
                                <td align="center" width="100">' . $v->nama_produk . '</td>
                                <td align="center" width="70">' . $v->jam . '</td>
                                <td align="center" width="40">' . $v->qty . '</td>
                                <td align="center" width="50">' . $v->satuan . '</td>
                                <td align="center" width="70">' . $v->rp_harga . '</td>
                                <td align="center" width="80">' . $v->rp_diskon . '</td>
                        </tr>	';
                $detail .= '<tr>
											
									
                                <td align="center" width="50">'. $v->rp_total . '</td>
                                <td align="center" width="50">' . $v->rp_ekstra_diskon . '</td>
                                <td align="center" width="50">' . $v->rp_ongkos_kirim . '</td>
                                <td align="center" width="50">' . $v->rp_ongkos_pasang . '</td>
                                <td align="center" width="50">' . $v->rp_bank_charge. '</td>
                                <td align="center" width="60">' . $v->g_total . '</td>
                                <td align="center" width="70">' . $v->debit . '</td>
                                <td align="center" width="70">' . $v->kredit . '</td>
                                <td align="center" width="60">' . $v->cash . '</td>
                                <td align="center" width="60">' . $v->transfer . '</td>
                                <td align="center" width="70">' . $v->cek . '</td>
                                <td align="center" width="70">' . $v->giro . '</td>
                                <td align="center" width="70">' . $v->voucher . '</td>
                                <td align="center" width="70">' . $v->piutang . '</td>
                                <td align="center" width="70">' . $v->retur . '</td>
                                 <td align="center" width="70">' . $v->g_total_byr . '</td>
                        </tr>	';
                
            }

        }
        
        $detail .= '</table>';

        if ($d->tgl) {
            $tgl = date('d-m-Y', strtotime($d->tgl));
        }

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">			
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%" >                                        
					<tr style="font-size:16:">
                                        <td width="210">Laporan Penjualan1</td>                                        
					</tr>  
                                        <tr style="font-size:14:">
                                        <td width="80">Periode</td>
                                        <td>: </td>
                                        </tr>
                                        <tr><td>'.$tgl.'</td></tr>                                 
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
