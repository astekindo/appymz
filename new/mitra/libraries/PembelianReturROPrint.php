<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianReturROPrint extends FormatLaporan {
		
    
    public function privateData($h, $d){
	$this->AddPage();
        $this->SetFont('courier', '', 8);
        $this->CI = & get_instance();
        $this->SetMargins(10,2, 4, true);
        
		if($h->tgl_retur){
			$tanggal_retur = date('d-m-Y', strtotime($h->tgl_retur));
		}
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="35">No</th>
                                                <th align="center" width="95">No PO <br>(Tgl PO)</th>
						<th align="center" width="95">Kode Barang <br>(Kode Brg Supp)</th>
						<th align="center" width="170">Nama Barang</th>
						<th align="center" width="30">Qty</th>
						<th align="center" width="45">Satuan</th>
						<th align="center" width="65">Harga Supplier</th>
						<th align="center" width="40">Disk 1</th>	
						<th align="center" width="40">Disk 2</th>	
						<th align="center" width="40">Disk 3</th>	
						<th align="center" width="40">Disk 4</th>	
						<th align="center" width="40">Disk 5</th>	
						<th align="center" width="60">Total Diskon</th>
						<th align="center" width="70">Harga Exc. PPN</th>
						<th align="center" width="80">Jumlah</th>
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
				$diskon = 0;
				if($v->tanggal_po){
                                        $tanggal_po= date('d-m-Y', strtotime($v->tanggal_po));
                                }			
				if($v->disk_persen_supp1 != '' && $v->disk_persen_supp1 != 0){
					$diskon_supp = $v->price_supp - (($v->disk_persen_supp1 * $v->price_supp) /100);
					$disk_grid_supp1 = number_format($v->disk_persen_supp1).'%';
				}else{
					if($v->disk_amt_supp1 != '' && $v->disk_amt_supp1 != 0){
						$diskon_supp = $v->harga_supplier - $v->disk_amt_supp1;
						$disk_grid_supp1 = 'Rp. '.number_format($v->disk_amt_supp1);
					}else{
						$diskon_supp1 = 0;
						$disk_grid_supp1 = '0%';
					}
				}
				
				if($v->disk_persen_supp2 != '' && $v->disk_persen_supp2 != 0){
					$diskon_supp = $diskon_supp - (($v->disk_persen_supp2 * $diskon_supp) /100);
					$disk_grid_supp2 = number_format($v->disk_persen_supp2).'%';
				}else{
					if($v->disk_amt_supp2 != '' && $v->disk_amt_supp2 != 0){
						$diskon_supp = $diskon_supp - $v->disk_amt_supp2;
						$disk_grid_supp2 = 'Rp. '.number_format($v->disk_amt_supp2);
					}else{
						$diskon_supp2 = 0;
						$disk_grid_supp2 = '0%';
					}
				}
				
				if($v->disk_persen_supp3 != '' && $v->disk_persen_supp3 != 0){
					$diskon_supp = $diskon_supp - (($v->disk_persen_supp3 * $diskon_supp) /100);
					$disk_grid_supp3 = number_format($v->disk_persen_supp3).'%';
				}else{
					if($v->disk_amt_supp3 != '' && $v->disk_amt_supp3 != 0){
						$diskon_supp = $diskon_supp - $v->disk_amt_supp3;
						$disk_grid_supp3 = 'Rp. '.number_format($v->disk_amt_supp3);
					}else{
						$diskon_supp3 = 0;
						$disk_grid_supp3 = '0%';
					}
				}
				
				if($v->disk_persen_supp4 != '' && $v->disk_persen_supp4 != 0){
					$diskon_supp = $diskon_supp - (($v->disk_persen_supp4 * $diskon_supp) /100);
					$disk_grid_supp4 = number_format($v->disk_persen_supp4).'%';
				}else{
					if($v->disk_amt_supp4 != '' && $v->disk_amt_supp4 != 0){
						$diskon_supp = $diskon_supp - $v->disk_amt_supp4;
						$disk_grid_supp4 = 'Rp. '.number_format($v->disk_amt_supp4);
					}else{
						$diskon_supp4 = 0;
						$disk_grid_supp4 = '0%';
					}
				}
				
				if($v->diskon_amt_supp5 != ''){
					$diskon_supp = $diskon_supp - $v->diskon_amt_supp5;
					$disk_grid_supp5 = 'Rp. '.number_format($v->diskon_amt_supp5);
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
                                
                                $total_diskon = $v->price_supp - $diskon_supp;
                                $net_price = $diskon_supp;
                                if($h->pkp === '1'){
                                    $net_price_exc = $net_price / 1.1;
                                    $rp_jumlah = $v->qty * $net_price_exc;
                                }else{
                                    $net_price_exc = $net_price;
                                    $rp_jumlah = $v->qty * $net_price_exc;
                                }
                                
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
                                                                <td align="center">'.$v->no_po.'<br>('.$tanggal_po.')</td>
								<td align="center">'.$v->kd_produk .'<br>('.$v->kd_produk_supp.')</td>
								<td align="left">'.$v->nama_produk.'</td>
								<td align="center">'.$v->qty.'</td>
								<td align="center">'.$v->nm_satuan.'</td>

								<td align="center">'.number_format($v->price_supp, 0,',','.').'</td>	
								<td align="right">'.$v->disk_grid_supp1.'</td>
								<td align="right">'.$v->disk_grid_supp2.'</td>
								<td align="right">'.$v->disk_grid_supp3.'</td>
								<td align="right">'.$v->disk_grid_supp4.'</td>
								<td align="right">'.$v->disk_grid_supp5.'</td>
								<td align="right">'.number_format($total_diskon,0,',','.').'</td>
								<td align="right">'.number_format($net_price_exc, 0,',','.').'</td>
								<td align="right">'.number_format($rp_jumlah, 0,',','.').'</td>
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
							<td align="left" width="485">Mengetahui</td>
							<td align="right" width="170">Total Nilai Retur</td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_jumlah, 0,',','.').'</td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="725"></td>
							<td align="right" width="70"></td>
							<td align="right" width="75"></td>
							<td align="right" width="75"></td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="130">( ' . $h->created_by .' )</td>
							<td align="left" width="565">(-----------)</td>
							<td align="right" width="100"></td>
							<td align="right" width="75"></td>
							<td align="right" width="75"></td>
					</tr>	';	
		
		$summary .= '<tr>
							<td align="left" width="650"></td>
							<td align="right" width="100"></td>
							<td align="right" width="70"></td>
							<td align="right" width="75"></td>
							<td align="right" width="75"></td>
					</tr>	';			
		$summary .= '<tr>
							
					</tr>	';			
		$summary .= '</table>';
		
                
                $pkp = $h->pkp === '1' ? 'YA' : 'TIDAK';
                if($v->tanggal){
			$tanggal= date('d-m-Y', strtotime($v->tanggal));
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
						<td width="130">No Retur</td>
						<td width="280">: '.$h->no_retur.'</td>
						<td width="120">NO RO</td>
						<td>: '.$v->no_do.'</td>
					</tr> 
                                         <tr style="font-size: 1.3em">
						<td>Tanggal Retur</td>
						<td>: '.$tanggal_retur.'</td>
						<td>Tanggal RO</td>
						<td>: '.$tanggal.'</td>
					</tr>   
					<tr style="font-size: 1.3em">
						<td>Supplier</td>
						<td>: '.$h->nama_supplier.'</td>
                                                
					</tr>
                                        <tr style="font-size: 1.3em">
						<td>Remark</td>
						<td>: '.$h->remark.'</td>
                                                
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
