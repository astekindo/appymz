<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class HargaPenjualanDistribusiPrint extends FormatLaporan {
		
	public function privateData($d){
		$this->AddPage();
		$breakLine = "\n";
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="30">No</th>
						<th align="center" width="65">Tanggal<br>(Tgl Approved)</th>
						<th align="center" width="95">Kode Barang<br>&<br>(Nama Supplier)</th>
						<th align="center" width="120">Nama Barang</th>
						<th align="center" width="55">Satuan</th>
						<th align="center" width="80">Net Price Beli Sup (Inc) / COGS (Exc)</th>
						<th align="center" width="50">OngKir (Rp)</th>
						<th align="center" width="50">Margin (%)</th>
						<th align="center" width="80">HET Net Price Beli / COGS (Inc)</th>
						<th align="center" width="70">Hrg Jual </th>
						<th align="center" width="90">Disk Toko / Net Price Jual Toko</th>	
						<th align="center" width="90">Disk Agen / Net Price Jual Agen</th>
                                                <th align="center" width="90">Disk Modern Market / Net Price Jual Modern MArket</th>
						<th align="center" width="68">Ket. Perubahan</th>
						<th align="center" width="57">Status<br>(Approved By)</th>
					</tr>	';
						// <th align="center" width="40">Qty Beli (Kons)</th>	
						// <th align="center" width="80">Kd Produk (Kons)</th>
						// <th align="center" width="40">Qty Bonus (Kons)</th>	
						// <th align="center" width="40">Kelipatan (Kons)</th>	
						// <th align="center" width="40">Qty Beli (Memb)</th>	
						// <th align="center" width="80">Kd Produk (Memb)</th>
						// <th align="center" width="40">Qty Bonus (Memb)</th>	
						// <th align="center" width="40">Kelipatan (Memb)</th>	
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
				//hitung diskon
				$hrg_kons = $v->rp_jual_toko;
				$diskon = 0;
								
				if($v->disk_persen1 != '' && $v->disk_persen1 != 0){
					$diskon_kons1 = $v->disk_persen1;
					$disk_kons1 = $diskon_kons1."%";

					$hrg_kons = ($hrg_kons * ($v->disk_persen1 / 100));
				}else{
					if($v->disk_amt1 != '' && $v->disk_amt1 != 0){
						$diskon_kons1 = $v->disk_amt1;
						$disk_kons1 = "Rp".$diskon_kons1;

						$hrg_kons = $hrg_kons - $v->disk_amt1;
					}else{
						$diskon_kons1 = 0;
						$disk_kons1 = 0 . "%";
					}
				}
				
				if($v->disk_persen2 != '' && $v->disk_persen2 != 0){
					$diskon_kons2 = $v->disk_persen2;
					$disk_kons2 = $diskon_kons2."%";
			
					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen2 / 100));
				}else{
					if($v->disk_amt2 != '' && $v->disk_amt2 != 0){
						$diskon_kons2 = $v->disk_amt2;
						$disk_kons2 = "Rp ".$diskon_kons2;

						$hrg_kons = $hrg_kons - $v->disk_amt2;
					}else{
						$diskon_kons2 = 0;
						$disk_kons2 = 0 . "%";
					}
				}
				
				if($v->disk_persen3 != '' && $v->disk_persen3 != 0){
					$diskon_kons3 = $v->disk_persen3;
					$disk_kons3 = $diskon_kons3."%";

					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen3 / 100));
				}else{
					if($v->disk_amt3 != '' && $v->disk_amt3 != 0){
						$diskon_kons3 = $v->disk_amt3;
						$disk_kons3 = "Rp ".$diskon_kons3;

						$hrg_kons = $hrg_kons - $v->disk_amt3;
					}else{
						$diskon_kons3 = 0;
						$disk_kons3 = 0 . "%";
					}
				}
				
				if($v->disk_persen4 != '' && $v->disk_persen4 != 0){
					$diskon_kons4 = $v->disk_persen4;
					$disk_kons4 = $diskon_kons4."%";

					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen4 / 100));
				}else{
					if($v->disk_amt4 != '' && $v->disk_amt4 != 0){
						$diskon_kons4 = $v->disk_amt4;
						$disk_kons4 = "Rp ".$diskon_kons4;

						$hrg_kons = $hrg_kons - $v->disk_amt4;
					}else{
						$diskon_kons4 = 0;
						$disk_kons4 = 0 . "%";
					}
				}
				
				if($v->disk_amt5 != ''){
					$diskon_amt_kons5 = $v->disk_amt5;
					$disk_kons5 = "Rp ".$v->disk_amt5;

					$hrg_kons = $hrg_kons - $v->disk_amt5;
				}else{
					$diskon_amt_kons5 = 0;
					$disk_kons5 = "Rp 0";
				}
				
				 
				$diskon = $diskon_kons1 + $diskon_kons2 + $diskon_kons3 + $diskon_kons4 + $diskon_amt_kons5;
				
				//diskon Rp
				$v->disk_kons1 = $disk_kons1;
				$v->disk_kons2 = $disk_kons2;
				$v->disk_kons3 = $disk_kons3;
				$v->disk_kons4 = $disk_kons4;
				$v->disk_kons5 = $disk_kons5;
				
				$diskon_member = 0;
				$hrg_member = $v->rp_jual_agen;
								
				if($v->disk_persen_agen1 != '' && $v->disk_persen_agen1 != 0){
					$diskon_member1 = $v->disk_persen_agen1;
					$disk_memb1 = $diskon_member1."%";

					$hrg_member = ($hrg_member * ($v->disk_persen_agen1 / 100));
				}else{
					if($v->disk_amt_agen1 != '' && $v->disk_amt_agen1 != 0){
						$diskon_member1 = $v->disk_amt_agen1;
						$disk_memb1 = "Rp ".$diskon_member1;

						$hrg_member = $hrg_member - $v->disk_amt_agen1;
					}else{
						$diskon_member1 = 0;
						$disk_memb1 = 0 . "%";
					}
				}
				
				if($v->disk_persen_agen2 != '' && $v->disk_persen_agen2 != 0){
					$diskon_member2 = $v->disk_persen_agen2;
					$disk_memb2 = $diskon_member2."%";

					$hrg_member = $hrg_member - ($hrg_member * ($v->disk_persen_agen2 / 100));
				}else{
					if($v->disk_amt_agen2 != '' && $v->disk_amt_agen2 != 0){
						$diskon_member2 = $v->disk_amt_agen2;
						$disk_memb2 = "Rp ".$diskon_member2;

						$hrg_member = $hrg_member - $v->disk_amt_agen2;
					}else{
						$diskon_member2 = 0;
						$disk_memb2 = 0 . "%";
					}
				}
				
				if($v->disk_persen_agen3 != '' && $v->disk_persen_agen3 != 0){
					$diskon_member3 = $v->disk_persen_agen3;
					$disk_memb3 = $diskon_member3."%";

					$hrg_member = $hrg_member - ($hrg_member * ($v->disk_persen_agen3 / 100));
				}else{
					if($v->disk_amt_agen3 != '' && $v->disk_amt_agen3 != 0){
						$diskon_member3 = $v->disk_amt_agen3;
						$disk_memb3 = "Rp ".$diskon_member3;

						$hrg_member = $hrg_member - $v->disk_amt_agen3;
					}else{
						$diskon_member3 = 0;
						$disk_memb3 = 0 . "%";
					}
				}
				
				if($v->disk_persen_agen4 != '' && $v->disk_persen_agen4 != 0){
					$diskon_member4 = $v->disk_persen_agen4;
					$disk_memb4 = $diskon_member4."%";

					$hrg_member = $hrg_member - ($hrg_member * ($v->disk_persen_agen4 / 100));
				}else{
					if($v->disk_amt_agen4 != '' && $v->disk_amt_agen4 != 0){
						$diskon_member4 = $v->disk_amt_agen4;
						$disk_memb4 = "Rp ".$diskon_member4;
					
						$hrg_member = $hrg_member - $v->disk_amt_agen4;
					}else{
						$diskon_member4 = 0;
						$disk_memb4 = 0 . "%";
					}
				}
				
				if($v->disk_amt_agen5 != ''){
					$diskon_amt_member5 = $v->disk_amt_agen5;
					$disk_memb5 = "Rp ".$v->disk_amt_agen5;

					$hrg_member = $hrg_member - $v->disk_amt_agen5;
				}else{
					$diskon_amt_member5 = 0;
					$disk_memb5 = "Rp 0";
				}
                                
                                $hrg_modern_market = $v->rp_jual_modern_market;
								
				if($v->disk_persen_modern_market1 != '' && $v->disk_persen_modern_market1 != 0){
					$diskon_modern_market1 = $v->disk_persen_modern_market1;
					$disk_modern1 = $diskon_modern_market1."%";

					$hrg_modern_market = ($hrg_modern_market * ($v->disk_persen_modern_market1 / 100));
				}else{
					if($v->disk_amt_modern_market1 != '' && $v->disk_amt_modern_market1 != 0){
						$diskon_modern_market1 = $v->disk_amt_modern_market1;
						$disk_modern1 = "Rp ".$diskon_modern_market1;

						$hrg_modern_market = $hrg_modern_market - $v->disk_amt_modern_market1;
					}else{
						$diskon_modern_market1 = 0;
						$disk_modern1 = 0 . "%";
					}
				}
				
				if($v->disk_persen_modern_market2 != '' && $v->disk_persen_modern_market2 != 0){
					$diskon_modern_market2 = $v->disk_persen_modern_market2;
					$disk_modern2 = $diskon_modern_market2."%";

					$hrg_modern_market = $hrg_modern_market - ($hrg_modern_market * ($v->disk_persen_modern_market2 / 100));
				}else{
					if($v->disk_amt_modern_market2 != '' && $v->disk_amt_modern_market2 != 0){
						$diskon_modern_market2 = $v->disk_amt_modern_market2;
						$disk_modern2 = "Rp ".$diskon_modern_market2;

						$hrg_modern_market = $hrg_modern_market - $v->disk_amt_modern_market2;
					}else{
						$diskon_modern_market2 = 0;
						$disk_modern2 = 0 . "%";
					}
				}
				
				if($v->disk_persen_modern_market3 != '' && $v->disk_persen_modern_market3 != 0){
					$diskon_modern_market3 = $v->disk_persen_modern_market3;
					$disk_modern3 = $diskon_modern_market3."%";

					$hrg_modern_market = $hrg_modern_market - ($hrg_modern_market * ($v->disk_persen_modern_market3 / 100));
				}else{
					if($v->disk_amt_modern_market3 != '' && $v->disk_amt_modern_market3 != 0){
						$diskon_modern_market3 = $v->disk_amt_modern_market3;
						$disk_modern3 = "Rp ".$diskon_modern_market3;

						$hrg_modern_market = $hrg_modern_market - $v->disk_amt_modern_market3;
					}else{
						$diskon_modern_market3 = 0;
						$disk_modern3 = 0 . "%";
					}
				}
				
				if($v->disk_persen_modern_market4 != '' && $v->disk_persen_modern_market4 != 0){
					$diskon_modern_market4 = $v->disk_persen_modern_market4;
					$disk_modern4 = $diskon_modern_market4."%";

					$hrg_modern_market = $hrg_modern_market - ($hrg_modern_market * ($v->disk_persen_modern_market4 / 100));
				}else{
					if($v->disk_amt_modern_market4 != '' && $v->disk_amt_modern_market4 != 0){
						$diskon_modern_market4 = $v->disk_amt_modern_market4;
						$disk_modern4 = "Rp ".$diskon_modern_market4;
					
						$hrg_modern_market = $hrg_modern_market - $v->disk_amt_modern_market4;
					}else{
						$diskon_modern_market4 = 0;
						$disk_modern4 = 0 . "%";
					}
				}
				
				if($v->disk_amt_modern_market5 != ''){
					$diskon_amt_modern_market5 = $v->disk_amt_modern_market5;
					$disk_modern5 = "Rp ".$v->disk_amt_modern_market5;

					$hrg_modern_market = $hrg_modern_market - $v->disk_amt_modern_market5;
				}else{
					$diskon_amt_modern_market5 = 0;
					$disk_modern5 = "Rp 0";
				}
				
				if($v->is_member_kelipatan == 0){
					$v->is_member_kelipatan = 'Tidak';
				}else{
					$v->is_member_kelipatan = 'Ya';
				}
				
				if($v->is_bonus_kelipatan == 0){
					$v->is_bonus_kelipatan = 'Tidak';
				}else{
					$v->is_bonus_kelipatan = 'Ya';
				}
				
				$v->margin_op = '%';
				$v->margin = $v->pct_margin;
				
				
				 
				$diskon_member = $diskon_member1 + $diskon_member2 + $diskon_member3 + $diskon_member4 + $diskon_amt_member5;
				
				//diskon Rp
				$v->disk_member1 = $disk_memb1;
				$v->disk_member2 = $disk_memb2;
				$v->disk_member3 = $disk_memb3;
				$v->disk_member4 = $disk_memb4;
				$v->disk_member5 = $disk_memb5;
				
				//$v->rp_ongkos_kirim = 1.1 * $v->rp_ongkos_kirim;
				//$margin = ($v->pct_margin * $v->net_hrg_supplier_sup_inc)/100;
				//$v->rp_het_harga_beli = ($v->net_hrg_supplier_sup_inc + $margin + $v->rp_ongkos_kirim) * 1.1;
				
				if($v->rp_cogs > 0 || $v->rp_cogs != ''){
					$margin = ($v->pct_margin * $v->rp_cogs)/100;
					$v->rp_het_cogs = ($v->rp_cogs + $margin + $v->rp_ongkos_kirim) * 1.1;
				}else{
					$v->rp_het_cogs = 0;
					$v->rp_cogs = 0;
				}


				$title = $v->title;
				$detail .= '<tr>
											<td align="center">'.$no.'</td>
											<td align="center">'.$v->tanggal .'<br>('.$v->tgl_approve.')</td>
											<td align="center">'.$v->kd_produk .' ('.$v->nama_supplier.' )'.'</td>
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.$v->nm_satuan.'</td>
											<td align="center">'.number_format($v->net_hrg_supplier_sup_inc, 0,',','.') . ' / '. number_format($v->rp_cogs, 0,',','.') .'</td>
											<td align="center">'.number_format($v->rp_ongkos_kirim, 0,',','.').'</td>
											<td align="center">'.number_format($v->pct_margin, 0,',','.').' %</td>
											<td align="center">'.number_format($v->rp_het_harga_beli, 0,',','.') . ' / ' . number_format($v->rp_het_cogs, 0,',','.').'</td>	
											<td align="center">'.number_format($v->rp_jual_toko, 0,',','.').'</td>
											
											<td align="center">('.number_format($hrg_kons, 0,',','.').') / '. number_format($v->rp_jual_toko_net, 0,',','.') .'</td>
											<td align="center">('.number_format($hrg_member, 0,',','.').') / '. number_format($v->rp_jual_agen_net, 0,',','.') .'</td>
                                                                                            <td align="center">('.number_format($hrg_modern_market, 0,',','.').') / '. number_format($v->rp_jual_modern_market_net, 0,',','.') .'</td>
											<td align="left">'.$v->keterangan.'</td>
											<td align="center">'.$v->status_approve.'<br>('.$v->approve_by.')</td>
										</tr>	';		
										$no++;
											// <td align="right">'.$v->qty_beli_bonus.'</td>
											// <td align="right">'.$v->kd_produk_bonus.'</td>
											// <td align="right">'.$v->qty_bonus.'</td>
											// <td align="right">'.$v->is_bonus_kelipatan.'</td>
											// <td align="right">'.$v->qty_beli_member.'</td>
											// <td align="right">'.$v->kd_produk_member.'</td>
											// <td align="right">'.$v->qty_member.'</td>
											// <td align="right">'.$v->is_member_kelipatan.'</td>	
	
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';		
		
		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr>
						<td colspan="2">' . $detail . '</td>
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
