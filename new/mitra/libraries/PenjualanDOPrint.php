<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PenjualanDOprint extends FormatLaporan {

    public function Header() {
        $this->SetMargins(4, 2, 4, true);
        /**$this->CI = & get_instance();
        $this->SetFont('courier', '', 12);
        $html = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="480" rowspan="2" align = "left" style="font-size:24;">' . $this->CI->config->item('header_laporan_matrix') . '</td>
						<td width="775" align ="right" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1) . '</td>
					</tr>
					<tr>
						<td width="780" align ="right" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2) . '</td>
					</tr>
					<tr>
						<td width="400" rowspan="2" align = "left"></td>
						<td width="860" align ="right" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td width="860" align ="right" >' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
				</table>';
        $this->writeHTML($html, true, false, true, false, 'C');
        $this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');**/
    }

    public function privateData($h, $d) {
        $this->CI = & get_instance();
        $this->SetMargins(10,2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td   align = "left" style="font-size:16;">' . $this->CI->config->item('header_laporan_matrix') . '</td>
						
					</tr>
					
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
                                        <tr>
                                            <td  align ="left">__________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $this->AddPage();
        $this->SetFont('courier', '', 12);
        
        $detail = '<table  border="1" cellspacing="0" cellpadding="3" >';
        $detail .= '<tr>
									<th align="center" width="45">No</th>
									<th align="center" width="140">Kode Barang</th>
									<th align="center" width="400">Nama Barang</th>
									<th align="center" width="70">Qty DO</th>
									<th align="center" width="70">Qty Retur</th>
									<th align="center" width="70">Qty</th>
									<th align="center" width="100">Satuan</th>
								</tr>	';
        if (!empty($d)) {
            $no = 1;
            $sum_qty = 0;
            $sum_qty_retur_do = 0;
            $sum_qty_total = 0;
            foreach ($d as $v) {
            	$qty_total = $v->qty  - $v->qty_retur_do;
                $detail .= '<tr >
											<td align="center">' . $no . '</td>
											<td align="center">' . $v->kd_barang . '</td>
											<td>' . $v->nama_produk . '</td>
											<td align="center">' . $v->qty  . '</td>
											<td align="center">' . $v->qty_retur_do  . '</td>
											<td align="center">' . $qty_total  . '</td>
											<td align="center">' . $v->nm_satuan . '</td>
										</tr>	';
                $no++;
                $sum_qty += $v->qty;
                $sum_qty_retur_do += $v->qty_retur_do;
                $sum_qty_total += $qty_total;
            }

            $detail .= '<tr><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td><td align="center">' . $sum_qty_retur_do . '</td><td align="center">' . $sum_qty_total . '</td></tr>';
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

        $summary = '<table  border="0" cellspacing="0" cellpadding="3">';

       
        
        $summary .= '<tr>
							<td align="center" width="250">Customer Service</td>
                                                        <td align="center" width="250">Admin Gudang</td>
					</tr>	';
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';


        $summary .= '<tr>
							<td align="center" width="250">( '.  $h->created_by .' )</td>
                                                        <td align="center" width="250">( ---------------- )</td>
					</tr>	';
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="650"></td>
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
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
                        
			<tr>
				<td align="left">' . $h->title . '</td>
			</tr>
			<tr>
				<td>
				<table border="0" cellspacing="0" style="text-align:left" width="100%">
					<tr >
						<td width="100">No DO</td>
						<td width="160"> : ' . $h->no_do . '</td>
                                                <td width="90">Tgl Kirim</td>
						<td>: ' . $h->tanggal_kirim . '</td>   
                                                <td width="100">No SO</td>
						<td width="170">: ' . $h->no_so  . '</td>    
                                                
					</tr>    
					<tr >
						<td>Tgl DO</td>
						<td> : ' . $h->tanggal . '</td>
                                                <td>No Telp</td>
						<td>: ' . $h->kirim_telp_so . '</td>    
                                                <td width="100">Tgl SO</td>
						<td width="300">: ' . $h->tanggal_so  . '</td>    
					</tr>
					<tr >
						<td>Penerima</td>
						<td width="150"> : ' . $h->pic_penerima . '</td>
						
						  
					</tr>
                                        <tr >
						<td>Alamat</td>
						<td colspan="6"> : ' . $h->alamat_penerima . '</td>
					</tr>
                                        <tr >
						<td>Ket. Kasir</td>
						<td colspan = "6"> : ' . $h->ket_kasir . '</td> 
					</tr>
                                        <tr >
						<td>Ket.</td>
						<td colspan="6"> : ' . $h->keterangan . '</td>
					</tr>
					
					<tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
					<tr>
						<td colspan="2">' . $summary . '</td>
					</tr>
				</table>
				<br/>
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
