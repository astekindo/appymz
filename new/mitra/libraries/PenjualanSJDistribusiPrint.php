<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PenjualanSJDistribusiPrint extends FormatLaporan {

    public function Header() {
        $this->SetMargins(4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('courier', '', 12);
        $this->CI = & get_instance();
        $this->SetMargins(5,2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align = "left" style="font-size:16;">' . $this->CI->config->item('header_laporan_matrix_distribusi') . '</td>
						
					</tr>
					
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
                                        <tr>
                                            <td  align ="left">_____________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table border="1" cellspacing="0" cellpadding = "3">';
        $detail .= '<tr>
									<th align="center" width="30">No</th>
                                                                        <th align="center" width="130">No DO</th>
									<th align="center" width="155">Kode Barang <br>(Kd Brg Supp)</th>
									<th align="center" width="280">Nama Barang</th>
									<th align="center" width="60">Qty</th>
									<th align="center" width="50">Satuan</th>
                                                                        <th align="center" width="110">Lokasi</th>
                                                                        <th align="center" width="90">Ket</th>
								</tr>	';
        if (!empty($d)) {
            $no = 1;
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= '<tr>
											<td align="center">' . $no . '</td>
                                                                                        <td align="center">' . $v->no_do . '</td> 
											<td align="center">' . $v->kd_produk .'<br> ('.$v->kd_produk_supp.')</td>
                                                                                        <td>' .  $v->nama_produk . '</td>
											<td align="center">' . $v->qty . '</td>
											<td align="center">' . $v->nm_satuan . '</td>
                                                                                        <td align="center">' . $v->lokasi . '</td>
                                                                                        <td align="center">' . $v->keterangan . '</td>
										</tr>	';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
            }

            $detail .= '<tr><td></td><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td></tr>';

            
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

        $summary = '<table  border="0" cellspacing="0" cellpadding="3">';

        
        $summary .= '<tr>
							<td align="center" width="180">Hormat Kami</td>
							<td align="center" width="180">Kepala Gudang</td>
                                                        <td align="center" width="180">Security</td>
                                                        <td align="center" width="180">Pengirim Barang</td>
                                                        <td align="center" width="180">Penerima Barang</td>
					</tr>	';
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';

       

        $summary .= '<tr>
							<td align="center" width="180">( ' . $h->created_by .' )</td>
							<td align="center" width="180">( -------------- )</td>
                                                        <td align="center" width="180">( -------------- )</td>
                                                        <td align="center" width="180">( -------------- )</td>
                                                        <td align="center" width="180">( -------------- )</td>
					</tr>	';
        
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="650"></td>
					</tr>	';
        $summary .= '</table>';

        if ($h->tanggal_po) {
            $tanggal_po = date('d-m-Y', strtotime($h->tanggal_po));
        }

        if ($h->tgl_berlaku_po) {
            $tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
        }

        $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td align="left"><br/>' . $h->title . '</td>
			</tr>
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%">
					
					<tr >
						<td width="120">Kepada Yth.</td>
						<td width="310"> : ' . $h->pic_penerima . '</td>
						<td width="80">No SJ</td>
						<td width="170"> : ' . $h->no_sj . '</td>
                                                <td width="100">No Mobil</td>
						<td width="300"> : ' . $h->no_kendaraan . '</td>
                                                
                                                
					</tr>    
					<tr >
						<td>No Telp</td>
						<td> : ' . $h->no_telp_penerima . '</td>
                                                <td>Tgl SJ</td>
						<td> : ' . $h->tanggal . '</td>
                                                <td>Supir</td>
						<td> : ' . $h->sopir . '</td>
                                                
						
					</tr>
					<tr >
                                                <td>Keterangan</td>
						<td> : ' . $h->keterangan . '</td>
						<td >No SO</td>    
                                                <td > : ' . $h->no_so . '</td>
						
                                                
					</tr>
                                        <tr >
						<td>Alamat</td>
						<td> : ' . $h->alamat_penerima . '</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
					<tr>
						<td colspan="2">' . $summary . '</td>
					</tr>
                                        <tr>
						<td >CATATAN : </td>
					</tr>
                                        <tr>
						<td colspan="5">1. Harap barang diperiksa dengan baik</td>
					</tr>
                                        <tr>
						<td colspan="5">2. Mohon isi kolom keterangan di atas apabila barang pecah, rusak atau jumlah barang yang diterima tidak sesuai dengan jumlah yang tercantum di atas</td>
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

}

?>
