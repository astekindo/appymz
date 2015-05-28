<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class CetakPelunasanHutangPrint extends FormatLaporan {
		
    
    public function privateData($h, $d){
	$this->AddPage();
        $this->SetFont('courier', '', 8);
        $this->CI = & get_instance();
        $this->SetMargins(10,2, 4, true);
        
		if($h->tgl_jth_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
		}
		$detail = '<table width="1000" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="40">No</th>
						<th align="center" width="100">No Invoice</th>
						<th align="center" width="100">Jumlah Outstanding</th>
						<th align="center" width="120">Jumlah Bayar</th>
						<th align="center" width="100">Jenis Pembayaran</th>
						<th align="center" width="100">No Bank</th>
						<th align="center" width="110">No Ref</th>	
						<th align="center" width="120">Tanggal Jatuh Tempo</th>	
						<th align="center" width="120">Jumlah Pembayaran</th>	
						
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
                                $title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->no_invoice .'</td>
								<td align="center">'.number_format($v->rp_total, 0,',','.').'</td>
								<td align="center">'.number_format($v->rp_bayar, 0,',','.').'</td>
								<td align="center">'.$v->nm_pembayaran.'</td>
								<td align="center">'.$v->nomor_bank.'</td>
								<td align="center">'.$v->nomor_ref.'</td>
								<td align="center">'.$tgl_jth_tempo.'</td>
								<td align="right">'.number_format($v->rp_bayar, 0,',','.').'</td>
								</tr>	';			
							$no++;
	
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';	
                
                $summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';
                
		$summary .= '<tr>
							<td align="center" width="140">Dibuat Oleh</td>
							<td align="left" width="505">Mengetahui</td>
							<td align="right" width="140">Total Invoice</td>
							<td align="right" width="50"></td>
							<td align="right" width="75">'.number_format($h->rp_total_invoice, 0,',','.').'</td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="645"></td>
							<td align="right" width="140">Total Potongan</td>
							<td align="right" width="50"></td>
							<td align="right" width="75">'.number_format($h->rp_total_potongan, 0,',','.').'</td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="130"></td>
							<td align="left" width="515"></td>
							<td align="right" width="140">Total Pembayaran</td>
							<td align="right" width="50"></td>
							<td align="right" width="75">'.number_format($h->rp_total_dibayar, 0,',','.').'</td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="center" width="130">( ' . $h->created_by .' )</td>
							<td align="left" width="515">(-----------)</td>
							<td align="right" width="140">Selisih / Sisa</td>
							<td align="right" width="50"></td>
							<td align="right" width="75">'.number_format($v->rp_total_invoice - $v->rp_total_potongan - $v->rp_total_dibayar , 0,',','.').'</td>
					</tr>	';			
		$summary .= '<tr>
							
					</tr>	';			
		$summary .= '</table>';
		
                if($h->tanggal){
			$tanggal = date('d-m-Y', strtotime($h->tanggal));
		}
                $title = $h->title;
		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
                                        <tr style="font-size: 1.3em">
						<td width="145">No.Bukti</td>
						<td width="280">: '.$h->no_bukti.'</td>
						
						
					</tr>    
					<tr style="font-size: 1.3em">
						<td>Nama Supplier</td>
						<td>: '.$h->nama_supplier.'</td>
                                               
					</tr>
                                        <tr style="font-size: 1.3em">
						<td>Tanggal Pembayaran</td>
						<td>: '.$tanggal.'</td>
                                               
					</tr>
                                       	<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        <tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                        <tr>
						<td colspan="2">' . $summary . '</td>
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
