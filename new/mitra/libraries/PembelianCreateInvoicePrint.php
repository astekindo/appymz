<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianCreateInvoicePrint extends FormatLaporan {
		
	public function privateData($h, $d){
		$this->AddPage();
		
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="30">No</th>
						<th align="center" width="100">No RO<br>(Tanggal Terima)</th>
						<th align="center" width="60">Kode Barang</th>
						<th align="center" width="180">Nama Barang</th>
						<th align="center" width="40">Qty</th>
						<th align="center" width="40">Satuan</th>
						<th align="center" width="70">Pricelist</th>
						<th align="center" width="35">Disk 1</th>	
						<th align="center" width="35">Disk 2</th>	
						<th align="center" width="35">Disk 3</th>	
						<th align="center" width="35">Disk 4</th>	
						<th align="center" width="35">Disk 5</th>	
						<th align="center" width="50">Total Diskon</th>
						<th align="center" width="70">Jumlah (Exc. PPN)</th>
						<th align="center" width="60">Adj</th>	
						<th align="center" width="70">Total</th>	
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				if($v->tanggal){
                                        $tanggal = date('d-m-Y', strtotime($v->tanggal));
                                }
                                if($v->tanggal_terima){
                                        $tanggal_terima = date('d-m-Y', strtotime($v->tanggal_terima));
                                }
                               
				$diskon = 0;
							
				if($v->disk_persen_supp1 != '' && $v->disk_persen_supp1 != 0){
					$diskon_supp1 = ($v->disk_persen_supp1 * $v->harga_supplier) /100;
					$disk_grid_supp1 = number_format($v->disk_persen_supp1).'%';
				}else{
					if($v->disk_amt_supp1 != '' && $v->disk_amt_supp1 != 0){
						$diskon_supp1 = $v->disk_amt_supp1;
						$disk_grid_supp1 = 'Rp. '.number_format($diskon_supp1);
					}else{
						$diskon_supp1 = 0;
						$disk_grid_supp1 = '0%';
					}
				}
				
				if($v->disk_persen_supp2 != '' && $v->disk_persen_supp2 != 0){
					$diskon_supp2 = ($v->disk_persen_supp2 * $diskon_supp1) /100;
					$disk_grid_supp2 = number_format($v->disk_persen_supp2).'%';
				}else{
					if($v->disk_amt_supp2 != '' && $v->disk_amt_supp2 != 0){
						$diskon_supp2 = $v->disk_amt_supp2;
						$disk_grid_supp2 = 'Rp. '.number_format($diskon_supp2);
					}else{
						$diskon_supp2 = 0;
						$disk_grid_supp2 = '0%';
					}
				}
				
				if($v->disk_persen_supp3 != '' && $v->disk_persen_supp3 != 0){
					$diskon_supp3 = ($v->disk_persen_supp3 * $diskon_supp2) /100;
					$disk_grid_supp3 = number_format($v->disk_persen_supp3).'%';
				}else{
					if($v->disk_amt_supp3 != '' && $v->disk_amt_supp3 != 0){
						$diskon_supp3 = $v->disk_amt_supp3;
						$disk_grid_supp3 = 'Rp. '.number_format($diskon_supp3);
					}else{
						$diskon_supp3 = 0;
						$disk_grid_supp3 = '0%';
					}
				}
				
				if($v->disk_persen_supp4 != '' && $v->disk_persen_supp4 != 0){
					$diskon_supp4 = ($v->disk_persen_supp4 * $diskon_supp3) /100;
					$disk_grid_supp4 = number_format($v->disk_persen_supp4).'%';
				}else{
					if($v->disk_amt_supp4 != '' && $v->disk_amt_supp4 != 0){
						$diskon_supp4 = $v->disk_amt_supp4;
						$disk_grid_supp4 = 'Rp. '.number_format($diskon_supp4);
					}else{
						$diskon_supp4 = 0;
						$disk_grid_supp4 = '0%';
					}
				}
				
				if($v->disk_amt_supp5 != '' && $v->disk_amt_supp5 != 0){
					$diskon_amt_supp5 = $v->disk_amt_supp5;
					$disk_grid_supp5 = 'Rp. '.number_format($diskon_amt_supp5);
				}else{
					$diskon_amt_supp5 = 0;
					$disk_grid_supp5 = '0%';
				}
				
				 
				$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_amt_supp5;
				
				//diskon Rp
				$v->disk_grid_supp1 = $disk_grid_supp1;
				$v->disk_grid_supp2 = $disk_grid_supp2;
				$v->disk_grid_supp3 = $disk_grid_supp3;
				$v->disk_grid_supp4 = $disk_grid_supp4;
				$v->disk_grid_supp5 = $disk_grid_supp5;
				$v->disk_supp1 = $diskon_supp1;
				$v->disk_supp2 = $diskon_supp2;
				$v->disk_supp3 = $diskon_supp3;
				$v->disk_supp4 = $diskon_supp4;
				$v->disk_supp4 = $diskon_supp4;
                                
                                if($h->pkp == '1'){
                                        $harga_exc= (($v->harga_supplier - $v->rp_total_diskon) / 1.1);
                                }else {
                                    $harga_exc= ($v->harga_supplier - $v->rp_total_diskon);
                                }
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->no_do .' <br>('.$tanggal_terima.')</td>
								<td align="center">'.$v->kd_produk .'</td>
								<td align="left">'.$v->nama_produk.'</td>
								<td align="center">'.$v->qty.'</td>
								<td align="center">'.$v->nm_satuan.'</td>
								<td align="center">'.number_format($v->harga_supplier, 0,',','.').'</td>	
								<td align="right">'.$v->disk_grid_supp1.'</td>
								<td align="right">'.$v->disk_grid_supp2.'</td>
								<td align="right">'.$v->disk_grid_supp3.'</td>
								<td align="right">'.$v->disk_grid_supp4.'</td>
								<td align="right">'.$v->disk_grid_supp5.'</td>
								<td align="right">'.number_format($v->rp_total_diskon, 0,',','.').'</td>
								<td align="right">'.number_format($harga_exc, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_ajd_jumlah, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_jumlah, 0,',','.').'</td>
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
							<td align="left" width="100"></td>
							<td align="right" width="290"></td>
							<td align="right" width="70"></td>
							<td align="right" width="75"></td>
							<td align="right" width="75"></td>
                                                        
                            </tr>	';		
                
		$summary .= '<tr>
							<td align="left" width="50">Remark</td>
							<td align="left" width="670"></td>
							<td align="right" width="70"><strong>Total</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_jumlah, 0,',','.').'</td>
                                                        
                            </tr>	';			
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="520"></td>
							<td align="right" width="170"><strong>Diskon Tambahan</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_diskon, 0,',','.').'</td>
					</tr>	';			
		$summary .= '<tr>
							<td align="center" width="100">Hormat Kami</td>
							<td align="right" width="520"></td>
							<td align="right" width="170">DPP</td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_jumlah - $h->rp_diskon, 0,',','.').'</td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="720"></td>
							<td align="right" width="70"><strong>PPN</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_ppn, 0,',','.').'</td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="110"></td>
							<td align="left" width="510"></td>
							<td align="right" width="170"><strong>Total Invoice</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_jumlah - $h->rp_diskon + $h->rp_ppn, 0,',','.').'</td>
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="620"></td>
							<td align="right" width="70">Pembulatan</td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_total - ($h->rp_jumlah - $h->rp_diskon + $h->rp_ppn), 0,',','.').'</td>
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100">( '. $h->created_by .' )</td>
							<td align="right" width="620"></td>
							<td align="right" width="70">Grand Total</td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_total, 0,',','.').'</td>
					</tr>	';					
		$summary .= '</table>';

		if($h->tgl_terima_invoice){
			$tgl_terima_invoice = date('d-m-Y', strtotime($h->tgl_terima_invoice));
		}

		if($h->tgl_invoice){
			$tgl_invoice = date('d-m-Y', strtotime($h->tgl_invoice));
		}

		if($h->tgl_jth_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
		}

		if($h->tgl_faktur_pajak){
			$tgl_faktur_pajak = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
		}
                if($h->pkp == '1'){
			$pkp= 'YA';
		}else {
                    $pkp= 'TIDAK';
                }
                if($h->kd_peruntukan == '1'){
			$title= $h->title.' DISTRIBUSI';
		}else {
                         $title= $h->title;
                }

		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr style="font-size: 1.3em">
						<td width="120">No Invoice</td>
						<td width="310">: '.$h->no_invoice.' ( PKP = '.$pkp.'  )</td>
						<td width="120">Tgl Terima</td>
						<td width="110">: '.$tgl_terima_invoice.'</td>
						<td width="120">Tgl Invoice</td>
						<td width="250">: '.$tgl_invoice.'</td>
					</tr>    
					<tr style="font-size: 1.3em">
						<td>Supplier</td>
						<td>: '.$h->nama_supplier.'</td>
						<td>Tgl Jth Tempo</td>
						<td>: '.$tgl_jth_tempo.'</td>
						<td>TOP</td>
						<td>: '.$h->top.' Hari</td>
					</tr>
					<tr style="font-size: 1.3em">
						<td>No Bukti Supp</td>
						<td>: '.$h->no_bukti_supplier.'</td>
						<td>Tgl Faktur Pajak</td>
						<td>: '.$tgl_faktur_pajak.'</td>
						<td>No Faktur Pajak</td>
						<td>: '.$h->no_faktur_pajak.'</td>
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
			<tr>
				<td align="left">&nbsp;</td>
			</tr>	
		</table>';	

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
