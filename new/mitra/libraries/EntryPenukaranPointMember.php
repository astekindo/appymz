<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EntryPenukaranPointMember extends FormatLaporan {

	public function Header() {
		$this->SetMargins(4, 2, 4, true);
	}

	public function privateData($d) {
		$this->AddPage();
		$this->SetFont('courier', '', 12);
		$this->CI = & get_instance();
		$this->SetMargins(9,2, 4, true);
		$sum_point = 0;

		$htmlHeader = '<br /><br />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align = "left" style="font-size:16;">' . $this->CI->config->item('header_laporan_matrix') . '</td>

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
		<th align="center" width="130">Kode Barang</th>
		<th align="center" width="430">Nama Barang</th>
		<th align="center" width="50">Qty</th>
		<th align="center" width="80">Point</th>
		<th align="center" width="150">Keterangan</th>
	</tr>	';
	if (!empty($d)) {
		$no = 1;
		$sum_qty = 0;
		
		foreach ($d as $v) {
			$detail .= '<tr>
			<td align="center">' . $no . '</td>
			<td align="center">' . $v->kd_produk .'</td> 
			<td>' .  $v->nama_produk . '</td>
			<td align="center">' . $v->qty_produk * $v->qty_tukar . '</td>
			<td align="center">' . $v->qty_point_tukar . '</td>
			<td align="center">' . $v->keterangan . '</td>
		</tr>	';
		$no++;
		$sum_qty += ($v->qty_produk * $v->qty_tukar );
		$sum_point += $v->qty_point_tukar;
	}

	$detail .= '<tr><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td><td align="center">' . $sum_point . '</td><td></td></tr>';
	

} else {
	$detail .= '<tr><td>-----</td></tr>';
}

$detail .= '</table>';

$summary = '<table  border="0" cellspacing="0" cellpadding="3">';


$summary .= '<tr>
<td width="350">Catatan :</td>
<td align="center" width="180">Hormat Kami</td>
<td align="center" width="180">Manager/Ass.Manager Store</td>
<td align="center" width="180">Member</td>
</tr>	';
$summary .= '<tr>
<td align="center" width="100"></td>
<td align="right" width="550"></td>							
</tr>	';



$summary .= '<tr>
<td width="350"></td>
<td align="center" width="180">( ' . $d[0]->created_by .' )</td>
<td align="center" width="180">( -------------- )</td>
<td align="center" width="180">( ' . $d[0]->nmmember .'  )</td>
</tr>	';

$summary .= '<tr>
<td align="center" width="100"></td>
<td align="right" width="650"></td>
</tr>	';
$summary .= '</table>';

if (!empty($h->tanggal_po)) {
	$tanggal_po = date('d-m-Y', strtotime($h->tanggal_po));
}

if (!empty($h->tgl_berlaku_po)) {
	$tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
}

$point_awal = $sum_point + $d[0]->total_point;

$html = $htmlHeader . '
<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td align="left"><br/>' . $h->title . '</td>
	</tr>
	<tr>
		<td>
			<table border= "0" cellspacing="0" style="text-align:left" width="100%">

				<tr >
					<td width="120">Nama Member</td>
					<td width="310"> : ' . $d[0]->nmmember . '</td>
					<td width="150">Tgl Penukaran</td>
					<td width="300">: ' . $d[0]->tanggal . '</td>
				</tr>    
				<tr >
					<td>Alamat</td>
					<td> : ' . $d[0]->alamat_pengiriman . '</td>
					<td >No Penukaran</td>    
					<td >: ' . $d[0]->no_bukti . '</td>
				</tr>
				<tr >
					<td>No Telp</td>
					<td> : ' . $d[0]->telepon . '</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">' . $detail . '</td>
				</tr>
				<tr>
					<td colspan="4"><br/><br/>Point Awal : '. $point_awal .', Penukaran Point : '. $sum_point .', Sisa Point : ' . $d[0]->total_point	 . '</td>
				</tr>
				<tr>
					<td colspan="2"><br/><br/>' . $summary . '</td>
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
