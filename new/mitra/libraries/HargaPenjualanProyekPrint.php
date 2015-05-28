<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class HargaPenjualanProyekPrint extends FormatLaporan {
		
	public function privateData($d){
		$this->AddPage();
		$breakLine = "\n";
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="30">No</th>
						<th align="center" width="80">Tanggal<br>(Tgl Approved)</th>
						<th align="center" width="95">Kode Barang<br>&<br>(Nama Supplier)</th>
						<th align="center" width="120">Nama Barang</th>
						<th align="center" width="55">Satuan</th>
						<th align="center" width="80">Net Price Beli Sup (Inc) / COGS (Exc)</th>
						<th align="center" width="50">OngKir (Rp)</th>
						<th align="center" width="50">Margin (%)</th>
						<th align="center" width="80">HET Net Price Beli / COGS (Inc)</th>
						<th align="center" width="90">Hrg Jual Proyek</th>
						<th align="center" width="135">Disk Proyek / Net Price Jual Proyek</th>	
						<th align="center" width="90">Ket. Perubahan</th>
						<th align="center" width="90">Status<br>(Approved By)</th>
					</tr>	';
							
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
				//hitung diskon
				$hrg_kons = $v->rp_jual_proyek;
				$diskon = 0;
								
				if($v->disk_persen_kons1 != '' && $v->disk_persen_kons1 != 0){
					$diskon_kons1 = $v->disk_persen_kons1;
					$disk_kons1 = $diskon_kons1."%";

					$hrg_kons = ($hrg_kons * ($v->disk_persen_kons1 / 100));
				}else{
					if($v->disk_amt_kons1 != '' && $v->disk_amt_kons1 != 0){
						$diskon_kons1 = $v->disk_amt_kons1;
						$disk_kons1 = "Rp".$diskon_kons1;

						$hrg_kons = $hrg_kons - $v->disk_amt_kons1;
					}else{
						$diskon_kons1 = 0;
						$disk_kons1 = 0 . "%";
					}
				}
				
				if($v->disk_persen_kons2 != '' && $v->disk_persen_kons2 != 0){
					$diskon_kons2 = $v->disk_persen_kons2;
					$disk_kons2 = $diskon_kons2."%";
			
					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen_kons2 / 100));
				}else{
					if($v->disk_amt_kons2 != '' && $v->disk_amt_kons2 != 0){
						$diskon_kons2 = $v->disk_amt_kons2;
						$disk_kons2 = "Rp ".$diskon_kons2;

						$hrg_kons = $hrg_kons - $v->disk_amt_kons2;
					}else{
						$diskon_kons2 = 0;
						$disk_kons2 = 0 . "%";
					}
				}
				
				if($v->disk_persen_kons3 != '' && $v->disk_persen_kons3 != 0){
					$diskon_kons3 = $v->disk_persen_kons3;
					$disk_kons3 = $diskon_kons3."%";

					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen_kons3 / 100));
				}else{
					if($v->disk_amt_kons3 != '' && $v->disk_amt_kons3 != 0){
						$diskon_kons3 = $v->disk_amt_kons3;
						$disk_kons3 = "Rp ".$diskon_kons3;

						$hrg_kons = $hrg_kons - $v->disk_amt_kons3;
					}else{
						$diskon_kons3 = 0;
						$disk_kons3 = 0 . "%";
					}
				}
				
				if($v->disk_persen_kons4 != '' && $v->disk_persen_kons4 != 0){
					$diskon_kons4 = $v->disk_persen_kons4;
					$disk_kons4 = $diskon_kons4."%";

					$hrg_kons = $hrg_kons - ($hrg_kons * ($v->disk_persen_kons4 / 100));
				}else{
					if($v->disk_amt_kons4 != '' && $v->disk_amt_kons4 != 0){
						$diskon_kons4 = $v->disk_amt_kons4;
						$disk_kons4 = "Rp ".$diskon_kons4;

						$hrg_kons = $hrg_kons - $v->disk_amt_kons4;
					}else{
						$diskon_kons4 = 0;
						$disk_kons4 = 0 . "%";
					}
				}
				
				if($v->disk_amt_kons5 != ''){
					$diskon_amt_kons5 = $v->disk_amt_kons5;
					$disk_kons5 = "Rp ".$v->disk_amt_kons5;

					$hrg_kons = $hrg_kons - $v->disk_amt_kons5;
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
				
				if($v->is_bonus_kelipatan == 0){
					$v->is_bonus_kelipatan = 'Tidak';
				}else{
					$v->is_bonus_kelipatan = 'Ya';
				}
				
				$v->margin_op = '%';
				$v->margin = $v->pct_margin;
				
				
				 
				
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
											<td align="center">'.$v->tanggal .'<br>('.$v->approve_date.')</td>
											<td align="center">'.$v->kd_produk .' ('.$v->nama_supplier.' )'.'</td>
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.$v->nm_satuan.'</td>
											<td align="center">'.number_format($v->net_hrg_supplier_sup_inc, 0,',','.') . ' / '. number_format($v->rp_cogs, 0,',','.') .'</td>
											<td align="center">'.number_format($v->rp_ongkos_kirim, 0,',','.').'</td>
											<td align="center">'.number_format($v->pct_margin, 0,',','.').' %</td>
											<td align="center">'.number_format($v->rp_het_harga_beli, 0,',','.') . ' / ' . number_format($v->rp_het_cogs, 0,',','.').'</td>	
											<td align="center">'.number_format($v->rp_jual_proyek, 0,',','.').'</td>
											
											<td align="center">('.number_format($hrg_kons,0,',','.').') / '. number_format($v->rp_jual_proyek_net, 0,',','.') .'</td>
											<td align="left">'.$v->keterangan.'</td>
											<td align="center">'.$v->status_approve.'<br>('.$v->approve_by.')</td>
										</tr>	';		
										$no++;
											
	
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
