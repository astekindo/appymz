<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PelunasanHutangPrint extends FormatLaporan {
		
    
    public function privateData($h, $d,$db,$bl){
	$this->AddPage();
        $this->SetFont('courier', '', 8);
        $this->CI = & get_instance();
//        $this->SetMargins(10,2, 4, true);
        
		if($h->tgl_jth_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
		}
		$detail = '<table width="1000" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="40">No</th>
						<th align="center" width="100">No Invoice</th>
                                                <th align="center" width="100">Tgl Terima Invoice</th>
                                                <th align="center" width="100">Tgl Invoice Supplier</th>
                                                <th align="center" width="100">No Bukti Supp</th>
                                                <th align="center" width="100">Rp Invoice</th>
                                                <th align="center" width="80">Potongan</th>
                                                <th align="center" width="100">Jumlah Bayar</th>
						<th align="center" width="100">Total Bayar</th>
						<th align="center" width="100">Rp Sisa Invoice</th>
												
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_bayar = 0;	
                        $sum_potongan = 0;	
			foreach($d as $v)
			{
				
                                $title = $v->title;
                                if($v->tgl_invoice){
                                        $tgl_invoice = date('d-m-Y', strtotime($v->tgl_invoice));
                                }
                                if($v->tgl_terima_invoice){
                                        $tgl_terima_invoice = date('d-m-Y', strtotime($v->tgl_terima_invoice));
                                }
                                $sisa_invoice = $v->rp_total - $v->rp_pelunasan_hutang;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->no_invoice .'</td>
                                                                <td align="center">'.$tgl_terima_invoice .'</td>
                                                                <td align="center">'.$tgl_invoice .'</td>
                                                                <td align="center">'.$v->no_bukti_supplier .'</td>
                                                               	<td align="right">'.number_format($v->rp_total, 0,',','.').'</td>
								<td align="right">'.number_format($v->potongan, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_bayar, 0,',','.').'</td>
                                                                <td align="right">'.number_format($v->rp_pelunasan_hutang, 0,',','.').'</td>
                                                                <td align="right">'.number_format($sisa_invoice, 0,',','.').'</td>
								
								</tr>	';			
							$no++;
                                                        $sum_bayar= $sum_bayar + $v->rp_bayar;
                                                        $sum_potongan = $sum_potongan + $v->potongan;
			}
                    $detail .= '<tr><td colspan="7"></td><td align="right">Total Bayar</td><td align="right">'.number_format($sum_bayar, 0,',','.').'</td><td></td></tr>';
                    $detail .= '<tr><td colspan="7"></td><td align="right">Biaya Lain-Lain</td><td align="right">'.number_format($v->biaya_lain, 0,',','.').'</td><td></td></tr>';
                    $detail .= '<tr><td colspan="7"></td><td align="right">Grand Total</td><td align="right">'.number_format($v->grand_total, 0,',','.').'</td><td></td></tr>';
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
							<td align="right" width="140"></td>
							<td align="right" width="50"></td>
							<td align="right" width="75"></td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="645"></td>
							<td align="right" width="140"></td>
							<td align="right" width="50"></td>
							<td align="right" width="75"></td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="130"></td>
							<td align="left" width="515"></td>
							<td align="right" width="140"></td>
							<td align="right" width="50"></td>
							<td align="right" width="75"></td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="center" width="130">( ' . $h->created_by .' )</td>
							<td align="left" width="515">(-----------)</td>
							<td align="right" width="140"></td>
							<td align="right" width="50"></td>
							<td align="right" width="75"></td>
					</tr>	';			
		$summary .= '<tr>
							
					</tr>	';			
		$summary .= '</table>';
		
                if($h->tanggal){
			$tanggal = date('d-m-Y', strtotime($h->tanggal));
		}
                $detailbayar = '<table width="1000" border="1" cellspacing="0" cellpadding="3">';
		$detailbayar .= '<tr>
						<th align="center" width="40">No</th>
						<th align="center" width="130">Jenis Pembayaran</th>
                                                <th align="center" width="130">Jumlah Bayar</th>
						<th align="center" width="100">No Bank</th>
                                                <th align="center" width="100">No Warkat</th>
                                                <th align="center" width="100">Tgl Jatuh Tempo</th>
                                                
					</tr>	';
		if(!empty($db))
		{
			$no = 1;
			$sum_qty = 0;
                        $bayar = 0;
			foreach($db as $vb)
			{
				 if($vb->tgl_jth_tempo){
                                            $tgl_jth_tempo = date('d-m-Y', strtotime($vb->tgl_jth_tempo));
                                    }
                                 
                                $detailbayar .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$vb->nm_pembayaran .'</td>
                                                                <td align="right">'.number_format($vb->rp_bayar, 0,',','.').'</td>
								<td align="center">'.$vb->nomor_bank.'</td>
                                                                <td align="center">'.$vb->nomor_ref.'</td>
                                                                <td align="center">'.$tgl_jth_tempo.'</td>
                                                                						
								</tr>	';			
							$no++;
                                                        $bayar= $bayar + $vb->rp_bayar;
	
			}
			$detailbayar .= '<tr><td></td><td align="right">Total Bayar</td><td align="right">'.number_format($bayar, 0,',','.').'</td><td></td><td></td><td></td></tr>';
		}
		else
		{
			$detailbayar .= '<tr><td>-----</td></tr>';			
		}
		
		$detailbayar .= '</table>';
                
                $biaya_lain = '<table width="1000" border="1" cellspacing="0" cellpadding="3">';
		$biaya_lain .= '<tr>
						<th align="center" width="40">No</th>
                                                <th align="center" width="200">Keterangan</th>
                                                <th align="center" width="130">Jenis Pembayaran</th>
                                                <th align="center" width="130">Jumlah Bayar</th>
						<th align="center" width="100">No Bank</th>
                                                <th align="center" width="100">No Warkat</th>
                                                <th align="center" width="100">Tgl Jatuh Tempo</th>
                                                                                                
					</tr>	';
		if(!empty($bl))
		{
			$no = 1;
			$sum_qty = 0;
                        $bayar_lain = 0;
			foreach($bl as $vbl)
			{
				 if($vbl->tgl_jth_tempo){
                                            $tgl_jth_tempo = date('d-m-Y', strtotime($vbl->tgl_jth_tempo));
                                    }
                                 
                                $biaya_lain .= '<tr>
								<td align="center">'.$no.'</td>
                                                                 <td align="center">'.$vbl->keterangan.'</td>	
								<td align="center">'.$vbl->nm_pembayaran .'</td>
                                                                <td align="right">'.number_format($vbl->rp_bayar, 0,',','.').'</td>
								<td align="center">'.$vbl->nomor_bank.'</td>
                                                                <td align="center">'.$vbl->nomor_ref.'</td>
                                                                <td align="center">'.$tgl_jth_tempo.'</td>
                                                                						
								</tr>	';			
							$no++;
                                                        $bayar_lain= $bayar_lain + $vbl->rp_bayar;
	
			}
			$biaya_lain .= '<tr><td></td><td></td><td align="right">Total Bayar</td><td align="right">'.number_format($bayar_lain, 0,',','.').'</td><td></td><td></td></tr>';
		}
		else
		{
			$biaya_lain .= '<tr><td>-----</td></tr>';			
		}
		
		$biaya_lain .= '</table>';
                
                $title = $h->title;
		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="0" style="text-align:left">
                                        <tr style="font-size: 1.3em">
						<td width="145">No.Bukti</td>
						<td width="300">: '.$h->no_bukti.'</td>
						<td width="145">Tanggal</td>
						<td width="200">: '.$tanggal.'</td>
						
					</tr>    
					<tr style="font-size: 1.3em">
						<td width="145">Nama Supplier</td>
						<td width="300">: '.$h->nama_supplier.'</td>
                                                <td width="145">Keterangan</td>
						<td width="300">: '.$h->keterangan.'</td>
					</tr>
                                        
                                       	<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                       <tr>
                                                <td><h3 align="left">Rincian Invoice</h3></td>
                                        </tr>
					<tr>
						<td colspan="1">' . $detail . '</td>
					</tr>
                                        <tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                        
				</table>
                                <table cellspacing="0" style="text-align:left">
                                        <tr>
                                                <td><h3 align="left">Rincian Detail Pembayaran</h3></td>
                                        </tr>
					<tr>
						<td colspan="1">' . $detailbayar . '</td>
					</tr>
                                        <tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                        
				</table>
                                <table cellspacing="0" style="text-align:left">
                                        <tr>
                                                <td><h3 align="left">Rincian Biaya Lain-Lain</h3></td>
                                        </tr>
					<tr>
						<td colspan="1">' . $biaya_lain . '</td>
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
