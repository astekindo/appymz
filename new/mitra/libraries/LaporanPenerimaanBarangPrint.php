<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class LaporanPenerimaanBarangPrint extends FormatLaporan {
		
	public function privateData($d){
		$this->AddPage();
		
		$detail = '<table width="730" border="0" cellspacing="0" >';
		$detail .= '<tr>
									<th align="center" width="55" border="1">TGL TERIMA</th>
									<th align="center" width="55" border="1">TGL INPUT</th>
									<th align="center" width="70" border="1">NOMOR BUKTI</th>
									<th align="center" width="55" border="1">BUKTI SUPPLIER</th>
									<th align="center" width="55" border="1">KODE SUPPLIER</th>
									<th align="center" width="55" border="1">NAMA SUPLIER</th>
									<th align="center" width="55" border="1">NOMOR PO</th>
									<th align="center" width="55" border="1">KODE BARANG</th>
									<th align="center" width="55" border="1">NAMA BARANG</th>
									<th align="center" width="55" border="1">Qty. PO</th>
									<th align="center" width="55" border="1">Sisa PO</th>
									<th align="center" width="55" border="1">Qty. RO</th>
									<th align="center" width="55" border="1">P.List</th>
									<th align="center" width="55" border="1">Dis.1</th>
									<th align="center" width="55" border="1">Dis.2</th>
									<th align="center" width="55" border="1">Dis.3</th>
									<th align="center" width="55" border="1">Dis.4</th>
									<th align="center" width="55" border="1">Disc. Nilai</th>
									<th align="center" width="55" border="1">Net. P.List</th>
									<th align="center" width="55" border="1">DPP</th>
									<th align="center" width="55" border="1">PPN</th>
									<th align="center" width="55" border="1">JUMLAH</th>
									<th align="center" width="55" border="1">PPN</th>
									<th align="center" width="55" border="1">TOTAL</th>
									<th align="center" width="55" border="1">BERAT (KG)</th>
									<th align="center" width="55" border="1">KODE EXPEDISI</th>
									<th align="center" width="55" border="1">NAMA EXPEDISI</th>
								</tr>	';
		if(!empty($d))
		{									
			$no = 1;	
			
			$subpo = 0;
			$subsisa = 0;
			$subro = 0;
			$subjumlah = 0;
			$subppn = 0;
			$subtotal = 0;
			$subberat = 0;			
			
			$totpo = 0;
			$totsisa = 0;
			$totro = 0;
			$totjumlah = 0;
			$totppn = 0;
			$tottotal = 0;
			$totberat = 0;
			
			$nodo = "";
			foreach($d as $v)
			{			
				if ($no == 1 or $nodo == $v->no_do)
				{
					$nodo = $v->no_do;
					
					$detail .= '<tr>
								<td>'.$v->tanggal.'</td>
								<td>'.$v->created_date .'</td>
								<td>'.$v->no_do.'</td>
								<td>'.$v->no_bukti_supplier.'</td>
								<td>'.$v->kd_supplier.'</td>
								<td>'.$v->nama_supplier.'</td>
								<td>'.$v->no_po.'</td>
								<td>'.$v->kd_produk.'</td>
								<td>'.$v->nama_produk.'</td>
								<td>'.$v->qty_beli.'</td>
								<td>'.$v->qty_sisa.'</td>
								<td>'.$v->qty_terima.'</td>
								<td>'.$v->price_supp_po.'</td>
								<td>'.$v->disk1.'</td>
								<td>'.$v->disk2.'</td>
								<td>'.$v->disk3.'</td>
								<td>'.$v->disk4.'</td>
								<td>'.$v->disk5.'</td>
								<td>'.$v->net_price_po.'</td>
								<td>'.$v->dpp_po.'</td>
								<td>'.$v->rp_ppn.'</td>
								<td>'.$v->rp_total_po.'</td>
								<td>'.$v->rp_total_ppn.'</td>
								<td>'.$v->rp_total.'</td>
								<td>'.$v->berat_ekspedisi.'</td>
								<td>'.$v->kd_ekspedisi.'</td>
								<td>'.$v->nama_ekspedisi.'</td>
							</tr>	';			
							$no++;
						
						$subpo		= $subpo + $v->qty_beli;
						$subsisa	= $subsisa + $v->qty_sisa;
						$subro		= $subro + $v->qty_terima;
						$subjumlah 	= $subjumlah + $v->rp_total_po;
						$subppn		= $subppn + $v->rp_total_ppn;
						$subtotal	= $subtotal + $v->rp_total;
						$subberat	= $subberat + $v->berat_ekspedisi;
				}
				else
				{
					$nodo = $v->no_do;
					$detail .= '<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td border="1">Sub Total :</td>
								<td></td>
								<td>'.$subpo.'</td>
								<td>'.$subsisa.'</td>
								<td>'.$subro.'</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>'.$subjumlah.'</td>
								<td>'.$subppn.'</td>
								<td>'.$subtotal.'</td>
								<td>'.$subberat.'</td>
								<td></td>
								<td></td>
							</tr>	';
							
						$subpo		= $v->qty_beli;
						$subsisa	= $v->qty_sisa;
						$subro		= $v->qty_terima;
						$subjumlah 	= $v->rp_total_po;
						$subppn		= $v->rp_total_ppn;
						$subtotal	= $v->rp_total;
						$subberat	= $v->berat_ekspedisi;
						
						$detail .= '<tr>
								<td>'.$v->tanggal.'</td>
								<td>'.$v->created_date .'</td>
								<td>'.$v->no_do.'</td>
								<td>'.$v->no_bukti_supplier.'</td>
								<td>'.$v->kd_supplier.'</td>
								<td>'.$v->nama_supplier.'</td>
								<td>'.$v->no_po.'</td>
								<td>'.$v->kd_produk.'</td>
								<td>'.$v->nama_produk.'</td>
								<td>'.$v->qty_beli.'</td>
								<td>'.$v->qty_sisa.'</td>
								<td>'.$v->qty_terima.'</td>
								<td>'.$v->price_supp_po.'</td>
								<td>'.$v->disk1.'</td>
								<td>'.$v->disk2.'</td>
								<td>'.$v->disk3.'</td>
								<td>'.$v->disk4.'</td>
								<td>'.$v->disk5.'</td>
								<td>'.$v->net_price_po.'</td>
								<td>'.$v->dpp_po.'</td>
								<td>'.$v->rp_ppn.'</td>
								<td>'.$v->rp_total_po.'</td>
								<td>'.$v->rp_total_ppn.'</td>
								<td>'.$v->rp_total.'</td>
								<td>'.$v->berat_ekspedisi.'</td>
								<td>'.$v->kd_ekspedisi.'</td>
								<td>'.$v->nama_ekspedisi.'</td>
							</tr>	';			
							$no++;
				}
				$totppn		= $totppn + $v->rp_total_ppn;
				$tottotal	= $tottotal + $v->rp_total;
				$totberat	= $totberat + $v->berat_ekspedisi;
				$totjumlah 	= $totjumlah + $v->rp_total_po;
				$totppn 	= $totppn + $v->rp_total_ppn;
				$tottotal 	= $tottotal + $v->rp_total;
				$totberat 	= $totberat + $v->berat_ekspedisi;
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
				<td><h3 align="left">LAPORAN PENERIMAAN BARANG</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr style="font-size: 1.3em">
						<td width="55">Periode</td>
						<td width="10">: </td>
						<td width="60">1/1/2013</td>
						<td width="10">-</td>
						<td width="60">1/12/2013</td>
					</tr>    
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
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
