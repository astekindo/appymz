<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporanDistribusi.php');

class PenjualanDistribusiPrint extends FormatLaporanDistribusi {
		
	public function privateData($h, $d){
		$this->AddPage();
		
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="25">No</th>
						<th align="center" width="80">Kode Barang</th>
						<th align="center" width="180">Nama Barang</th>
						<th align="center" width="50">Qty</th>
						<th align="center" width="45">Satuan</th>
						<th align="center" width="60">Harga Jual</th>
						<th align="center" width="50">Disk 1</th>	
						<th align="center" width="50">Disk 2</th>	
						<th align="center" width="50">Disk 3</th>	
						<th align="center" width="50">Disk 4</th>	
						<th align="center" width="50">Disk 5</th>
                                                <th align="center" width="60">Ekstra Diskon</th>
						<th align="center" width="60">Total Diskon</th>
						<th align="center" width="65">Harga Net</th>
						<th align="center" width="65">Jumlah</th>
                                                </tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
				$diskon = 0;
                                
				if($v->disk_persen1 != '' && $v->disk_persen1 != 0){
					$diskon_supp1 = ($v->disk_persen1 * $v->rp_harga_jual) /100;
					$disk_grid_supp1 = number_format($v->disk_persen1).'%';
				}else{
					if($v->disk_amt1 != '' && $v->disk_amt1 != 0){
						$diskon_supp1 = $v->disk_amt1;
						$disk_grid_supp1 = 'Rp. '.number_format($diskon_supp1);
					}else{
						$diskon_supp1 = 0;
						$disk_grid_supp1 = '0%';
					}
				}
				
				if($v->disk_persen2 != '' && $v->disk_persen2 != 0){
					$diskon_supp2 = ($v->disk_persen2 * $diskon_supp1) /100;
					$disk_grid_supp2 = number_format($v->disk_persen2).'%';
				}else{
					if($v->disk_amt2 != '' && $v->disk_amt2 != 0){
						$diskon_supp2 = $v->disk_amt2;
						$disk_grid_supp2 = 'Rp. '.number_format($diskon_supp2);
					}else{
						$diskon_supp2 = 0;
						$disk_grid_supp2 = '0%';
					}
				}
				
				if($v->disk_persen3 != '' && $v->disk_persen3 != 0){
					$diskon_supp3 = ($v->disk_persen3 * $diskon_supp2) /100;
					$disk_grid_supp3 = number_format($v->disk_persen3).'%';
				}else{
					if($v->disk_amt3 != '' && $v->disk_amt3 != 0){
						$diskon_supp3 = $v->disk_amt3;
						$disk_grid_supp3 = 'Rp. '.number_format($diskon_supp3);
					}else{
						$diskon_supp3 = 0;
						$disk_grid_supp3 = '0%';
					}
				}
				
				if($v->disk_persen4 != '' && $v->disk_persen4 != 0){
					$diskon_supp4 = ($v->disk_persen4 * $diskon_supp3) /100;
					$disk_grid_supp4 = number_format($v->disk_persen4).'%';
				}else{
					if($v->disk_amt4 != '' && $v->disk_amt4 != 0){
						$diskon_supp4 = $v->disk_amt4;
						$disk_grid_supp4 = 'Rp. '.number_format($diskon_supp4);
					}else{
						$diskon_supp4 = 0;
						$disk_grid_supp4 = '0%';
					}
				}
				
//				if($v->diskon_amt5 != ''){
					$diskon_amt_supp5 = $v->disk_amt5;
					$disk_grid5 = 'Rp. '.number_format($v->disk_amt5);
//				}else{
//					$diskon_amt_supp5 = 0;
//					$disk_grid_supp5 = '0%';
//				}
				
				 
				$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $disk_grid5;
				
				//diskon Rp
				$v->disk_grid_supp1 = $disk_grid_supp1;
				$v->disk_grid_supp2 = $disk_grid_supp2;
				$v->disk_grid_supp3 = $disk_grid_supp3;
				$v->disk_grid_supp4 = $disk_grid_supp4;
				$v->disk_grid_supp5 = $disk_grid5;
				$v->disk_supp1 = $diskon_supp1;
				$v->disk_supp2 = $diskon_supp2;
				$v->disk_supp3 = $diskon_supp3;
				$v->disk_supp4 = $diskon_supp4;
				$v->disk_supp4 = $diskon_supp4;
			
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->kd_produk .'</td>
								<td align="left">'.$v->nama_produk.'</td>
								<td align="center">'.$v->qty.'</td>
								<td align="center">'.$v->nm_satuan.'</td>
								<td align="center">'.number_format($v->rp_harga_jual, 0,',','.').'</td>	
								<td align="right">'.$v->disk_grid_supp1.'</td>
								<td align="right">'.$v->disk_grid_supp2.'</td>
								<td align="right">'.$v->disk_grid_supp3.'</td>
								<td align="right">'.$v->disk_grid_supp4.'</td>
								<td align="right">'.$v->disk_grid_supp5.'</td>
                                                                <td align="right">'.number_format($v->rp_diskon_satuan, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_diskon, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_net_harga_jual, 0,',','.').'</td>
								<td align="right">'.number_format($v->rp_jumlah, 0,',','.').'</td>
                                                                
							</tr>	';			
                               $no++;
                               $sum_qty = $sum_qty + $v->qty;
			}
			$detail .= '<tr><td></td><td></td><td>Total : </td><td align="center">' . $sum_qty . '</td></tr>';
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
							<td align="left" width="100">Dibuat Oleh</td>
							<td align="left" width="520"></td>
							<td align="right" width="170"><strong>Jumlah</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_total, 0,',','.').'</td>
                                                        
                            </tr>	';			
		
                $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="520"></td>
							<td align="right" width="170"><strong>Rp DPP</strong></td>
							<td align="right" width="75"></td>
							<td align="right" width="75">'.number_format($h->rp_dpp, 0,',','.').'</td>
					</tr>	';
                $summary .= '<tr>
							<td align="center" width="100"></td>
                                                        <td align="right" width="520"></td>
							<td align="right" width="170"><strong>PPN</strong></td>
							<td align="right" width="75">'.$h->pct_ppn.' %</td>
							<td align="right" width="75">'.number_format($h->rp_ppn, 0,',','.').'</td>
					</tr>	';		
		
			
		$summary .= '<tr>
							
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100">( '. $h->created_by .' )</td>
							<td align="right" width="620"></td>
							
					</tr>	';					
		$summary .= '</table>';

		if($h->tgl_so){
			$tgl_so = date('d-m-Y', strtotime($h->tgl_so));
		}
                if($h->is_pkp = 1){
			$is_pkp = 'YA';
		}elseif ($h->is_pkp = 0) {
                        $is_pkp = 'TIDAK';
                }

		
		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$h->title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr style="font-size: 1.3em">
						<td width="110">No SO</td>
						<td width="180">: '.$h->no_so.'</td>
                                                <td width="110">Pelanggan</td>
						<td width="200">: '.$h->nama_pelanggan.'</td>
						<td width="120">NPWP</td>
						<td width="180">: '.$h->npwp.'</td>
					</tr>    
					<tr style="font-size: 1.3em">
						<td>Tanggal</td>
						<td>: '.$tgl_so.'</td>
                                                <td>No Telepon</td>
						<td>: '.$h->kirim_telp_so.'</td>
                                                <td>Dikirim Ke</td>
						<td width="250">: '.$h->kirim_so.'</td>
					</tr>
					<tr style="font-size: 1.3em">
						<td>Sales</td>
						<td>: '.$h->nama_sales.'</td>
                                                <td>Contact Person</td>
						<td>: '.$h->nama_pic.'</td>
                                                <td>PKP</td>
						<td>: '.$is_pkp.'</td>
                                                
					</tr>
                                        <tr style="font-size: 1.3em">
                                                <td>No Referensi</td>
						<td>: '.$h->no_ref.'</td>
                                        </tr>
                                        <tr style="font-size: 1.3em">
						<td>Alamat Kirim</td>
						<td width="500">: '.$h->kirim_alamat_so.'</td>
						
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
