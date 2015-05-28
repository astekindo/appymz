<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianInvoiceOrderPrint extends FormatLaporan {
			
	public function privateData($h,$d){
		$this->AddPage();
		
		$detail = '<table width="1030" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
									<th align="center" width="20">No</th>
									<th align="center" width="100">Kode Barang<br>(Kd Brg Lama)</th>
									<th align="center" width="200">Nama Barang</th>
									<th align="center" width="40">Qty</th>
									<th align="center" width="50">Satuan</th>
									<th align="center" width="80">Harga Beli</th>
									<th align="center" width="40">Disk1</th>
									<th align="center" width="40">Disk2</th>
                                                                        <th align="center" width="40">Disk3</th>
									<th align="center" width="40">Disk4</th>
                                                                        <th align="center" width="40">Disk5</th>
									<th align="center" width="60">Total Diskon</th>
                                                                        <th align="center" width="60">Harga Net</th>
                                                                        <th align="center" width="60">Harga Net(Exc PPN)</th>
									<th align="center" width="60">Adjustmet</th>
									<th align="center" width="60">Jumlah</th>
								</tr>	';
		if(!empty($d))
		{
			$no = 1;		
			$sum_total = 0;
			$sum_qty = 0;
			$nama_ekspedisi = 0;
			foreach($d as $v)
			{
                            $diskon1 = '0%';
				$diskon2 = '0%';
				$diskon3 = '0%';
				$diskon4 = '0%';
                                $harga_net = $v->harga_supplier - $v->rp_total_diskon;
                                $harga_net_exc = $harga_net / 1.1;
				if($v->disk_amt_supp1 > 0)
				{
                                    $diskon1 = number_format($v->disk_amt_supp1, 0,',','.');
				}	
				else
				{
					//$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                                    $diskon1 = $v->disk_persen_supp1 . '%';
				}
				if($v->disk_amt_supp2 > 0)
				{
                                    $diskon2 = number_format($v->disk_amt_supp2, 0,',','.');
                                }	
				else
				{
					$diskon2 = $v->disk_persen_supp2 . '%';	
				}
				if($v->disk_amt_supp3 > 0)
				{
                                    $diskon3 = number_format($v->disk_amt_supp3, 0,',','.');
                                    			
				}	
				else
				{
					$diskon3 = $v->disk_persen_supp3 . '%';	
				}
				if($v->disk_amt_supp4 > 0)
				{
                                    $diskon4 = number_format($v->disk_amt_supp4, 0,',','.');
                                    			
				}	
				else
				{
					$diskon4 = $v->disk_persen_supp4 . '%';	
				}
                            $detail .= '<tr>
											<td align="center">'.$no.'</td>
											<td align="center">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.number_format($v->qty, 0,',','.').'</td>
											<td align="center">'.$v->nm_satuan.'</td>
                                                                                        <td align="center">'.number_format($v->harga_supplier, 0,',','.').'</td>
                                                                                        <td align="center">'.$diskon1.'</td>
                                                                                        <td align="center">'.$diskon2.'</td>
                                                                                        <td align="center">'.$diskon3.'</td>
                                                                                        <td align="center">'.$diskon4.'</td>
                                                                                        <td align="center">'.number_format($v->disk_amt_supp5, 0,',','.').'</td>
                                                                                        <td align="center">'.number_format($v->rp_total_diskon, 0,',','.').'</td>
                                                                                        <td align="center">'.number_format($harga_net, 0,',','.').'</td>
                                                                                        <td align="center">'.number_format($harga_net_exc, 0,',','.').'</td>
                                                                                        <td align="center">'.number_format($v->rp_ajd_jumlah, 0,',','.').'</td>
											<td align="center">'.number_format($v->rp_jumlah, 0,',','.').'</td>
											
										</tr>	';			
										$no++;
										$sum_qty = $sum_qty + $v->qty;
										$sum_total = $sum_total + $v->rp_jumlah;
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';
		
		$summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';

		$summary .= '<tr>
							<td align="left" width="170"></td>
							<td align="left" width="160">Total Qty</td>
							<td align="left" width="40">'.number_format($sum_qty, 0,',','.').'</td>
							<td align="right" width="450"></td>
							<td align="right" width="100">Total</td>
							<td align="right" width="75">'.number_format($sum_total, 0,',','.').'</td>
					</tr>
                                        <tr>
							<td align="left" width="170"></td>
							<td align="left" width="160"></td>
							<td align="left" width="40"></td>
							<td align="right" width="450"></td>
							<td align="right" width="100">Diskon Tambahan </td>
							<td align="right" width="75">'.number_format($h->rp_diskon, 0,',','.').'</td>
					</tr>         
                                        <tr>
							<td align="left" width="170"></td>
							<td align="left" width="160"></td>
							<td align="left" width="40"></td>
							<td align="right" width="450"></td>
							<td align="right" width="100">PPN </td>
							<td align="right" width="75">'.number_format($h->rp_ppn, 0,',','.').'</td>
					</tr>  
                                        <tr>
							<td align="left" width="170"></td>
							<td align="left" width="160"></td>
							<td align="left" width="40"></td>
							<td align="right" width="450"></td>
							<td align="right" width="100">Pembulatan </td>
							<td align="right" width="75">'.number_format($h->rp_total - ($h->rp_jumlah - $h->rp_diskon + $h->rp_ppn), 0,',','.').'</td>
					</tr> 
                                        <tr>
							<td align="left" width="170"></td>
							<td align="left" width="160"></td>
							<td align="left" width="40"></td>
							<td align="right" width="450"></td>
							<td align="right" width="100">Total Invoice </td>
							<td align="right" width="75">'.number_format($h->rp_jumlah + $h->rp_ppn, 0,',','.').'</td>
					</tr>   
                                ';
                              
                            
		$summary .= '</table>';
		
		// define barcode style
		$style = array(
				'position' => 'R',
				'align' => 'R',
				'stretch' => false,
				'fitwidth' => true,
				'cellfitalign' => '',
				'border' => false,
				'hpadding' => 'auto',
				'vpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255),
				'text' => true,
				'font' => 'helvetica',
				'fontsize' => 8,
				'stretchtext' => 4
		);
		// PRINT VARIOUS 1D BARCODES
		
		// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
		// $this->write1DBarcode($h->no_do, 'C39', '', '', '', 18, 0.4, $style, 'N');					
		
		$html = '
		<table width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$h->title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr style="font-size: 1.3em">
                                                <td width="135">No Invoice</td>
						<td width="280">: '.$h->no_invoice.'</td>
						<td width="130">Nama Supplier</td>
						<td width="450">: '.$h->nama_supplier.'</td>
					</tr>    
					<tr style="font-size: 1.3em">
						<td>No Bukti Supplier</td>
						<td>: '.$h->no_bukti_supplier.'</td>
						<td>Tgl Terima</td>
						<td>: '.$h->tgl_terima_invoice.'</td>
					</tr>
					<tr style="font-size: 1.3em">
						<td>Tgl Jatuh Tempo</td>
						<td>: '.$h->tgl_jth_tempo.'</td>
						<td>Tgl Invoice</td>
						<td>: '.$h->tgl_invoice.'</td>
					</tr>
                                        <tr style="font-size: 1.3em">
                                                <td>No Faktur Pajak</td>
						<td>: '.$h->no_faktur_pajak.'</td>
						<td>Tgl Faktur Pajak</td>
						<td>: '.$h->tgl_faktur_pajak.'</td>
					</tr>
                                        <tr style="font-size: 1.3em">
                                                <td>No RO</td>
						<td>: '.$v->no_do.'</td>
						<td>TOP</td>
						<td>: '.$h->top.'</td>
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
                                        
				</table>
				</td>
			</tr>
					
		</table>';

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
