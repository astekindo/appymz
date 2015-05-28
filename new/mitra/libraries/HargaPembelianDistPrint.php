<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class HargaPembelianDistPrint extends FormatLaporan {
		
	public function privateData($d){
		$this->AddPage();
		
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
						<th align="center" width="25">No</th>
						<th align="center" width="80">Tanggal<br>(Tgl Approved)</th>
						<th align="center" width="95">No Bukti<br>(Status)<br>(Approved By)</th>
						<th align="center" width="95">Kode Barang<br>&<br>(Nama Supplier)</th>
						<th align="center" width="160">Nama Barang</th>
						<th align="center" width="45">Satuan</th>
						<th align="center" width="80">Harga Supplier</th>
						<th align="center" width="130">Disk Distribusi</th>
						<th align="center" width="90">Net Price Beli Dist (Inc)</th>
						<th align="center" width="90">Net Price Beli Dist (Exc)</th>
						<th align="center" width="100">Keterangan</th>
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				
				//hitung diskon
				$diskon = 0;
								
				if($v->disk_persen_supp1 != '' && $v->disk_persen_supp1 != 0){
					$diskon_supp1 = $v->disk_persen_supp1." %";
				}else{
					if($v->disk_amt_supp1 != '' && $v->disk_amt_supp1 != 0){
						$diskon_supp1 = "Rp ".$v->disk_amt_supp1;
					}else{
						$diskon_supp1 = '0 %';
					}
				}
				
				if($v->disk_persen_supp2 != '' && $v->disk_persen_supp2 != 0){
					$diskon_supp2 = $v->disk_persen_supp2." %";
				}else{
					if($v->disk_amt_supp2 != '' && $v->disk_amt_supp2 != 0){
						$diskon_supp2 =  "Rp ".$v->disk_amt_supp2;
					}else{
						$diskon_supp2 = '0 %';
					}
				}
				
				if($v->disk_persen_supp3 != '' && $v->disk_persen_supp3 != 0){
					$diskon_supp3 = $v->disk_persen_supp3." %";
				}else{
					if($v->disk_amt_supp3 != '' && $v->disk_amt_supp3 != 0){
						$diskon_supp3 =  "Rp ".$v->disk_amt_supp3;
					}else{
						$diskon_supp3 = '0 %';
					}
				}
				
				if($v->disk_persen_supp4 != '' && $v->disk_persen_supp4 != 0){
					$diskon_supp4 = $v->disk_persen_supp4." %";
				}else{
					if($v->disk_amt_supp4 != '' && $v->disk_amt_supp4 != 0){
						$diskon_supp4 =  "Rp ".$v->disk_amt_supp4;
					}else{
						$diskon_supp4 = '0 %';
					}
				}
				
				if($v->diskon_amt_supp5 != ''){
					$diskon_amt_supp5 =  "Rp ".$v->diskon_amt_supp5;
				}else{
					$diskon_amt_supp5 = 0;
				}
				
				 
				$diskon = $diskon_supp1 + $diskon_supp2 + $diskon_supp3 + $diskon_supp4 + $diskon_amt_supp5;
				
				//diskon Rp
				$v->disk_supp1 = $diskon_supp1;
				$v->disk_supp2 = $diskon_supp2;
				$v->disk_supp3 = $diskon_supp3;
				$v->disk_supp4 = $diskon_supp4;
				
				$diskon = 0;
								
				if($v->disk_persen_dist1 != '' && $v->disk_persen_dist1 != 0){
					$diskon_dist1 = $v->disk_persen_dist1." %";
				}else{
					if($v->disk_amt_dist1 != '' && $v->disk_amt_dist1 != 0){
						$diskon_dist1 =  "Rp ".$v->disk_amt_dist1;
					}else{
						$diskon_dist1 = '0 %';
					}
				}
				
				if($v->disk_persen_dist2 != '' && $v->disk_persen_dist2 != 0){
					$diskon_dist2 = $v->disk_persen_dist2." %";
				}else{
					if($v->disk_amt_dist2 != '' && $v->disk_amt_dist2 != 0){
						$diskon_dist2 =  "Rp ".$v->disk_amt_dist2;
					}else{
						$diskon_dist2 = '0 %';
					}
				}
				
				if($v->disk_persen_dist3 != '' && $v->disk_persen_dist3 != 0){
					$diskon_dist3 = $v->disk_persen_dist3." %";
				}else{
					if($v->disk_amt_dist3 != '' && $v->disk_amt_dist3 != 0){
						$diskon_dist3 =  "Rp ".$v->disk_amt_dist3;
					}else{
						$diskon_dist3 = '0 %';
					}
				}
				
				if($v->disk_persen_dist4 != '' && $v->disk_persen_dist4 != 0){
					$diskon_dist4 = $v->disk_persen_dist4." %";
				}else{
					if($v->disk_amt_dist4 != '' && $v->disk_amt_dist4 != 0){
						$diskon_dist4 =  "Rp ".$v->disk_amt_dist4;
					}else{
						$diskon_dist4 = '0 %';
					}
				}
				
				if($v->diskon_amt_dist5 != ''){
					$diskon_amt_dist5 =  "Rp ".$v->diskon_amt_dist5;
				}else{
					$diskon_amt_dist5 = 0;
				}
				
				 
				$diskon = $diskon_dist1 + $diskon_dist2 + $diskon_dist3 + $diskon_dist4 + $diskon_amt_dist5;
				
				//diskon Rp
				$v->disk_dist1 = $diskon_dist1;
				$v->disk_dist2 = $diskon_dist2;
				$v->disk_dist3 = $diskon_dist3;
				$v->disk_dist4 = $diskon_dist4;
			
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->tanggal .'<br>('.$v->tgl_approve.')</td>
								<td align="center">'.$v->no_bukti .'<br>('.$v->status_approve.')'.'<br>('.$v->approve_by.')</td>
								<td align="center">'.$v->kd_produk .' ('.$v->nama_supplier.')</td>
								<td>'.$v->nama_produk.'</td>
								<td align="center">'.$v->nm_satuan.'</td>
								<td align="center">'.number_format($v->hrg_supplier_dist, 0,',','.').'</td>	
								<td align="center">'.$v->disk_dist1 .'+'.$v->disk_dist2 .'+'.$v->disk_dist3 .'+'.$v->disk_dist4 .'+'.$v->disk_amt_dist5 .'</td>
								<td align="right">'.number_format($v->net_hrg_supplier_dist_inc, 0,',','.').'</td>
								<td align="right">'.number_format($v->net_hrg_supplier_dist, 0,',','.').'</td>
								<td align="center">'.$v->keterangan.'</td>
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
