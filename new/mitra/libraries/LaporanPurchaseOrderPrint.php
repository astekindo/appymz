<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanPurchaseOrderPrint extends FormatLaporan {

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
                        <th align="center" width="80">TANGGAL</th>
                        <th align="center" width="80">NO BUKTI<br></th>
                        <th align="center" width="100">KODE SUPPLIER</th>
                        <th align="center" width="120">NAMA SUPPLIER</th>
                        <th align="center" width="150">NO BARCODE</th>
                        <th align="center" width="200">NAMA BARANG</th>
                        <th align="center" width="50">QTY </th>
                        <th align="center" width="80">HARGA</th>

                </tr>	';
        $detail .= '<tr>
                        <th align="center" width="50">DISK1%</th>
                        <th align="center" width="50">DISK2%<br></th>
                        <th align="center" width="50">DISK3%</th>
                        <th align="center" width="50">DISK4%</th>
                        <th align="center" width="80">DISK NILAI</th>
                        <th align="center" width="100">TOTAL DISKON</th>
                        <th align="center" width="100">NET HARGA</th>
                        <th align="center" width="80">DPP</th>
                        <th align="center" width="80">PPN</th>
                        <th align="center" width="100">JUMLAH</th>
                        <th align="center" width="100">JUMLAH PPN</th>
                        <th align="center" width="100">TOTAL</th>

                </tr>	';
         // Belum diset ke field di databasenya
        if (!empty($d)) {
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											
									
                                <td align="center" width="80">'. $v->tanggal_po . '</td>
                                <td align="center" width="80">' . $v->no_po . '</td>
                                <td align="center" width="100">' . $v->kd_suplier_po . '</td>
                                <td align="left" width="120">' . $v->nama_supplier . '</td>
                                <td align="center" width="150">' . $v->no_barcode . '</td>
                                <td align="center" width="200">' . $v->nama_produk . '</td>
                                <td align="center" width="50">' . $v->qty_po . '</td>
                                <td align="center" width="80">' . $v->price_supp_po . '</td>
                        </tr>	';
                $detail .= '<tr>
											
									
                                <td align="center" width="50">'. $v->diskon1 . '</td>
                                <td align="center" width="50">' . $v->diskon2 . '</td>
                                <td align="center" width="50">' . $v->diskon3 . '</td>
                                <td align="center" width="50">' . $v->diskon4 . '</td>
                                <td align="center" width="80">' . $v->diskon_nilai . '</td>
                                <td align="center" width="100">' . $v->rp_disk_po . '</td>
                                <td align="center" width="100">' . $v->net_price_po . '</td>
                                <td align="center" width="80">' . $v->rp_dpp_po . '</td>
                                <td align="center" width="80">' . $v->rp_ppn . '</td>
                                <td align="center" width="100">' . $v->rp_jumlah . '</td>
                                <td align="center" width="100">' . $v->rp_jumlah_ppn . '</td>
                                <td align="center" width="100">' . $v->rp_total . '</td>
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

        if ($h->tgl_retur) {
            $tgl_retur = date('d-m-Y', strtotime($h->tgl_retur));
        }

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">			
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%" >                                        
					<tr style="font-size:16:">
                                        <td width="210">Laporan Purchase Order</td>                                        
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
