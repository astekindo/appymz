<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ReturPenjualanNonStrukPrint extends FormatLaporan {

    public function Header() {
        $this->SetMargins(4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('courier', '', 12);
        $this->CI = & get_instance();
        $this->SetMargins(7,2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1630"  align = "left" style="font-size:14;">' . $this->CI->config->item('header_laporan_matrix') . '</td>
						
					</tr>
					
					<tr>
						<td  align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td  align ="left" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
                                        <tr>
                                            <td  align ="left">__________________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table width="100%" border="1" cellspacing="0" cellpadding="2">';
        $detail .= '<tr>
                        <th align="center" width="30">No</th>
                        <th align="center" width="140">Kode Barang<br>Kode Brg Supp</th>
                        <th align="center" width="220">Nama Barang</th>
                        <th align="center" width="110">Lokasi</th>
                        <th align="center" width="40">Qty</th>
                        <th align="center" width="70">Satuan</th>
                        <th align="center" width="90">Harga</th>
                        <th align="center" width="70">Diskon</th>
                        <th align="center" width="70">Ekstra Diskon</th>
                        <th align="center" width="110">Total</th>
                    </tr>	';
        if (!empty($d)) {
            $no = 1;
            $sum_qty = 0;
            $sum_harga = 0;
            $sum_diskon = 0;
            $sum_diskon_ekstra = 0;
            $sum_total = 0;
            foreach ($d as $v) {
//                $v->lokasi = "SL1-SL1-SL1";
                $detail .= '<tr>
                    <td align="center">' . $no . '</td>
                    <td align="center">' . $v->kd_produk . '<br>('.$v->kd_produk_supp.')</td>
                    <td align="left">'. $v->nama_produk . '</td>
                    <td align="center">' . $v->lokasi . '</td>
                    <td align="center">' . $v->qty . '</td>
                    <td align="center">' . $v->nm_satuan . '</td>
                    <td align="right">' . number_format($v->rp_jumlah,0,',','.') . '</td>
                    <td align="right">' . number_format($v->rp_disk,0,',','.') . '</td>
                    <td align="right">' . number_format($v->rp_potongan,0,',','.') . '</td>
                    <td align="right">' . number_format($v->rp_total,0,',','.') . '</td>
                </tr>	';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
                $sum_harga = $sum_harga + $v->rp_jumlah;
                $sum_diskon = $sum_diskon + $v->rp_disk;
                $sum_diskon_ekstra = $sum_diskon_ekstra + $v->rp_potongan;
                $sum_total = $sum_total + $v->rp_total;
            }

           $detail .= '<tr><td></td><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td><td></td><td align="right"></td><td align="center"></td><td align="center"></td><td align="right">' .number_format($sum_total,0,',','.') . '</td></tr>';
          

           // $detail .= '<tr><td colspan = "7" >Sisa Tagihan Titip Supir sebesar Rp. : <strong>' . number_format($h->rp_kurang_bayar, 0, ',', '.') . '</strong></td></tr>';
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';
        $summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';
		
               		
                
		$summary .= '<tr>
							<td align="left" width="50"></td>
							<td align="left" width="520"></td>
							<td align="right" width="170"><strong>Jumlah Retur</strong></td>
							<td align="right" width="90"></td>
							<td align="right" width="120">'.number_format($h->rp_jumlah, 0,',','.').'</td>
                                                        
                            </tr>	';			
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="470"></td>
							<td align="right" width="170"><strong>Potongan Retur</strong></td>
							<td align="right" width="90">'.$h->pct_potongan.'%</td>
							<td align="right" width="120">'.number_format($h->rp_potongan, 0,',','.').'</td>
					</tr>	';			
                $summary .= '<tr>
							<td align="center" width="110"></td>
							<td align="left" width="460"></td>
							<td align="right" width="170"><strong>Grand Total</strong></td>
							<td align="right" width="90"></td>
							<td align="right" width="120">'.number_format($h->rp_total, 0,',','.').'</td>
					</tr>	';		
		
							
		$summary .= '</table>';

        $tandatangan = '<table width="100%" border="0" cellspacing="0" cellpadding="3">';

        
        $tandatangan .= '<tr>
							<td align="center" width="150">Cust Service</td>
                                                        <td align="center" width="150">SPG/SPB</td>
                                                        <td align="center" width="150">Security</td>
							<td align="center" width="150">Manager/Asisten</td>
                                                        <td align="center" width="150">Konsumen</td>
							<td align="center" width="150">Lokasi Penerima</td>
                                                        
					</tr>	';
        $tandatangan .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';

     

        $tandatangan .= '<tr>
							<td align="center" width="150">( '. $h->created_by .' )</td>
							<td align="center" width="150">( ---------- )</td>
                                                        <td align="center" width="150">( ---------- )</td>
							<td align="center" width="150">( ---------- )</td>
                                                        <td align="center" width="150">( ---------- )</td>
                                                        <td align="center" width="150">( ---------- )</td>
                                                       
					</tr>	';
       
        $tandatangan .= '</table>';

        if ($h->tgl_retur) {
            $tgl_retur = date('d-m-Y', strtotime($h->tgl_retur));
        }
/*
        if ($h->tgl_berlaku_po) {
            $tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
        }*/

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td align="left"><h3>' . $h->title . '</h3></td>
			</tr>
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%">
					
					<tr >
						<td width="100">No Retur</td>
						<td width="150">: ' . $h->no_retur . '</td>
                                                <td width="100">Tanggal</td>
						<td width="130">: '.$tgl_retur.' </td> 
                                                  
                                                
					</tr>  
					 <tr >
						<td width="100">Alasan</td>
						<td width="900">: '. $h->remark.' </td>
					</tr>
                                        <tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        <tr>
						<td colspan="2">' . $summary . '</td>
					</tr>
					<tr>
						<td colspan="2">' . $tandatangan . '</td>
					</tr>
				</table>
				<p align = "left">CATATAN :</p>
                                <p align = "left">1. Harap barang diperiksa dengan baik</p>
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
