<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembayaranUangMukaPrint extends FormatLaporan {
		
    
    public function privateData($h, $d,$db){
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
						<th align="center" width="130">No SO</th>
                                                <th align="center" width="130">Tgl SO</th>
						<th align="center" width="120">Rp Jumlah</th>
                                                <th align="center" width="100">Rp DPP</th>
                                                <th align="center" width="100">Rp PPN</th>
                                                <th align="center" width="100">Jumlah Uang Muka</th>
											
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_uang_muka = 0;		
			foreach($d as $v)
			{
				 
                                 if($v->tgl_so){
                                            $tgl_so = date('d-m-Y', strtotime($v->tgl_so));
                                    }
                                $rp_kurang_bayar = $v->rp_kurang_bayar;
                                $title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->no_so .'</td>
                                                                <td align="center">'.$tgl_so .'</td>
								<td align="right">'.number_format($v->rp_jumlah, 0,',','.').'</td>
                                                                <td align="right">'.number_format($v->rp_dpp, 0,',','.').'</td>
                                                                <td align="right">'.number_format($v->rp_ppn, 0,',','.').'</td>
                                                                <td align="right">'.number_format($v->uang_muka, 0,',','.').'</td>
								
								
								</tr>	';			
							$no++;
                                                        $sum_uang_muka = $sum_uang_muka + $v->uang_muka;
			}
			$detail .= '<tr><td></td><td></td><td></td><td align="right"></td><td ></td><td align="right">Total </td><td align="right">'.number_format($sum_uang_muka, 0,',','.').'</td></tr>';
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
							
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="645"></td>
							<td align="right" width="140"></td>
							<td align="right" width="60"></td>
							<td align="right" width="75"></td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="130"></td>
							<td align="left" width="515"></td>
							<td align="right" width="140"></td>
							<td align="right" width="60"></td>
							<td align="right" width="75"></td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="center" width="130">( ' . $h->created_by .' )</td>
							<td align="left" width="515">(-----------)</td>
							<td align="right" width="140"></td>
							<td align="right" width="60"></td>
							<td align="right" width="75"></td>
					</tr>	';			
		$summary .= '<tr>
							
					</tr>	';			
		$summary .= '</table>';
		
                if($h->tgl_bayar){
			$tanggal = date('d-m-Y', strtotime($h->tgl_bayar));
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
								<td align="center">'.$vb->no_bank.'</td>
                                                                <td align="center">'.$vb->no_ref.'</td>
                                                                <td align="center">'.$tgl_jth_tempo.'</td>
                                                                						
								</tr>	';			
							$no++;
                                                        $bayar= $bayar + $vb->rp_bayar;
			}
			$detailbayar .= '<tr><td></td><td align="right">Total Jumlah Bayar</td><td align="right">'.number_format($bayar, 0,',','.').'</td><td></td><td></td><td></td></tr>';
		}
		else
		{
			$detailbayar .= '<tr><td>-----</td></tr>';			
		}
		
		$detailbayar .= '</table>';
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
						<td width="145">No.Pembayaran</td>
						<td width="580">: '.$h->no_bayar.'</td>
						
						
					</tr>    
					
                                        <tr style="font-size: 1.3em">
						<td>Tanggal Pembayaran</td>
						<td>: '.$tanggal.'</td>
                                               
					</tr>
                                        <tr style="font-size: 1.3em">
						<td>Keterangan</td>
						<td>: '.$h->keterangan.'</td>
                                               
					</tr>
                                       	<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                        <tr>
                                                <td><h3 align="left">Rincian SO</h3></td>
                                        </tr>
					<tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        <tr>
						<td colspan="2">&nbsp;</td>
					</tr>
                                       
				</table>
                                
                                <table cellspacing="1" style="text-align:left">
                                        <tr>
                                                <td><h3 align="left">Rincian Detail Pembayaran</h3></td>
                                        </tr>
					<tr>
						<td colspan="2">' . $detailbayar . '</td>
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
