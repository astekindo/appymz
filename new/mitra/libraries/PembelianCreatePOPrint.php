<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianCreatePOPrint extends FormatLaporan {

	public $cetak_ke = '0';
        
        public $pkp = '0';
        
        public function setPkp($h){
            $this->pkp = $h->pkp; 
        }
        
        public function Header() {
		$this->CI = & get_instance();
		$this->SetFont('times', '', 8);
                $company_name = 'PT. SURYA KENCANA KERAMINDO';
                if($this->pkp === '0')
                    $company_name = '';
                
		$html = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100" rowspan="4"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>
						<td width="600" rowspan="2" align = "center"><h2>'. $company_name .'</h2></td>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).'</td>
					</tr>
					<tr>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).'</td>
					</tr>
					<tr>
						<td width="600" rowspan="2" align = "center">' . $this->CI->config->item('header_laporan') . '</td>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td>
					</tr>
					<tr>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4).'</td>
					</tr>
				</table>';
		$this->writeHTML($html, true, false, true, false, 'C');		
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
	}

	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		$this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
		// Page number
		$this->Cell(0, 10, 'Cetakan ke : '. $this->cetak_ke . '           Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
	}	
		
//	public function privateData($h,$d){
//		$this->AddPage();
//		$this->cetak_ke = $h->cetak_ke;
//
//
//		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
//		$detail .= '<tr>
//									<th align="center" width="25">No</th>
//									<th align="center" width="100">Kode Barang<br>(Kd Brg Lama)</th>
//									<th align="center" width="80">Kode Barang Supplier</th>
//									<th align="center" width="180">Nama Barang</th>
//									<th align="center" width="40">Qty</th>
//									<th align="center" width="50">Satuan</th>
//									<th align="center" width="50">Harga Beli</th>
//									<th align="center" width="40">Disk1</th>
//									<th align="center" width="40">Disk2</th>
//									<th align="center" width="40">Disk3</th>
//									<th align="center" width="40">Disk4</th>
//									<th align="center" width="40">Disk5</th>
//									<th align="center" width="50">Total Diskon</th>
//									<th align="center" width="50">Harga NET</th>
//									<th align="center" width="75">Harga NET (Exc. PPN)</th>
//									<th align="center" width="75">Jumlah (Exc. PPN)</th>
//								</tr>	';
//		if(!empty($d))
//		{
//			$no = 1;
//			$sum_qty = 0;
//			foreach($d as $v)
//			{
//				$diskon1 = '0%';
//				$diskon2 = '0%';
//				$diskon3 = '0%';
//				$diskon4 = '0%';
//				if($v->disk_persen_supp1_po > 0)
//				{
//					$diskon1 = $v->disk_persen_supp1_po . '%';
//				}
//				else
//				{
//					//$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
//                                    $diskon1 = $v->disk_amt_supp1_po;
//				}
//				if($v->disk_persen_supp2_po > 0)
//				{
//					$diskon2 = $v->disk_persen_supp2_po . '%';
//				}
//				else
//				{
//					$diskon2 = number_format($v->disk_amt_supp2_po, 0,',','.');
//				}
//				if($v->disk_persen_supp3_po > 0)
//				{
//					$diskon3 = $v->disk_persen_supp3_po . '%';
//				}
//				else
//				{
//					$diskon3 = number_format($v->disk_amt_supp3_po, 0,',','.');
//				}
//				if($v->disk_persen_supp4_po > 0)
//				{
//					$diskon4 = $v->disk_persen_supp4_po . '%';
//				}
//				else
//				{
//					$diskon4 = number_format($v->disk_amt_supp4_po, 0,',','.');
//				}
//
//				$detail .= '<tr>
//											<td align="center">'.$no.'</td>
//											<td align="center">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
//											<td align="center">'.$v->kd_produk_supp .'</td>
//											<td>'.$v->nama_produk.'</td>
//											<td align="center">'.number_format($v->qty_po, 0,',','.').'</td>
//											<td align="center">'.$v->nm_satuan.'</td>
//											<td align="center">'.number_format($v->price_supp_po, 0,',','.').'</td>
//											<td align="center">'.$diskon1.'</td>
//											<td align="center">'.$diskon2.'</td>
//											<td align="center">'.$diskon3.'</td>
//											<td align="center">'.$diskon4.'</td>
//											<td align="center">'.number_format($v->disk_amt_supp5_po, 0,',','.').'</td>
//											<td align="right">'.number_format($v->rp_disk_po, 0,',','.').'</td>
//											<td align="right">'.number_format($v->net_price_po, 0,',','.').'</td>
//											<td align="right">'.number_format($v->dpp_po, 0,',','.') .'</td>
//											<td align="right">'.number_format($v->rp_total_po, 0,',','.') .'</td>
//										</tr>	';
//										$no++;
//										$sum_qty = $sum_qty + $v->qty_po;
//
//			}
//
//			$detail .= '<tr><td></td><td></td><td></td><td>Total : </td><td align="center">'. number_format($sum_qty, 0,',','.') .'</td></tr>';
//
//		}
//		else
//		{
//			$detail .= '<tr><td>-----</td></tr>';
//		}
//
//		$detail .= '</table>';
//
//		$summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';
//
//		$summary .= '<tr>
//							<td align="left" width="50"></td>
//							<td align="left" width="600"></td>
//							<td align="left" width="100"></td>
//							<td align="right" width="70">Total</td>
//							<td align="right" width="75"></td>
//							<td align="right" width="75">'.number_format($h->rp_jumlah_po, 0,',','.').'</td>
//					</tr>	';
//		$summary .= '<tr>
//							<td align="center" width="100">Hormat Kami</td>
//							<td align="right" width="550"></td>
//							<td align="right" width="170">Diskon Tambahan</td>
//							<td align="right" width="75">'.number_format($h->rp_jumlah_po / $h->rp_diskon_po, 0,',','.').' %</td>
//							<td align="right" width="75">'.number_format($h->rp_diskon_po, 0,',','.').'</td>
//					</tr>	';
//		$summary .= '<tr>
//							<td align="center" width="100"></td>
//							<td align="right" width="550"></td>
//							<td align="right" width="170">Total Tagihan</td>
//							<td align="right" width="75"></td>
//							<td align="right" width="75">'.number_format($h->rp_jumlah_po - $h->rp_diskon_po, 0,',','.').'</td>
//					</tr>	';
//
//		$summary .= '<tr>
//							<td align="right" width="750"></td>
//							<td align="right" width="70">PPN</td>
//							<td align="right" width="75">'.number_format($h->ppn_percent_po, 0,',','.').' %</td>
//							<td align="right" width="75">'.number_format($h->rp_ppn_po, 0,',','.').'</td>
//					</tr>	';
//
//		$summary .= '<tr>
//							<td align="center" width="130">( ' . $h->approval_by .' )</td>
//							<td align="left" width="590"></td>
//							<td align="right" width="100">Grand Total</td>
//							<td align="right" width="75"></td>
//							<td align="right" width="75">'.number_format($h->rp_total_po, 0,',','.').'</td>
//					</tr>	';
//		$summary .= '<tr>
//							<td align="center" width="100"></td>
//							<td align="right" width="650"></td>
//							<td align="right" width="70">DP</td>
//							<td align="right" width="75"></td>
//							<td align="right" width="75">'.number_format($h->rp_dp, 0,',','.').'</td>
//					</tr>	';
//		$summary .= '<tr>
//							<td align="left" width="650">*) FORM INI TERCETAK LANGSUNG DARI SYSTEM, SEHINGGA TIDAK DIPERLUKAN TANDA TANGAN</td>
//							<td align="right" width="100"></td>
//							<td align="right" width="70">Sisa Bayar</td>
//							<td align="right" width="75"></td>
//							<td align="right" width="75">'.number_format($h->rp_total_po - $h->rp_dp, 0,',','.').'</td>
//					</tr>	';
//		$summary .= '<tr>
//							<td align="left" width="100">PERHATIAN : </td>
//							<td align="left" width="620">'.nl2br($h->remark).'</td>
//					</tr>	';
//		$summary .= '</table>';
//
//		// define barcode style
//		$style = array(
//				'position' => 'R',
//				'align' => 'R',
//				'stretch' => false,
//				'fitwidth' => true,
//				'cellfitalign' => '',
//				'border' => false,
//				'hpadding' => 'auto',
//				'vpadding' => 'auto',
//				'fgcolor' => array(0,0,0),
//				'bgcolor' => false, //array(255,255,255),
//				'text' => true,
//				'font' => 'helvetica',
//				'fontsize' => 8,
//				'stretchtext' => 4
//		);
//		// PRINT VARIOUS 1D BARCODES
//
//		// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
//		// $this->write1DBarcode($h->no_po, 'C39', '', '', '', 18, 0.4, $style, 'N');
//
//		if($h->tanggal_po){
//			$tanggal_po = date('d-m-Y', strtotime($h->tanggal_po));
//		}
//
//		if($h->tgl_berlaku_po){
//			$tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
//		}
//
//		$html = '
//		<table width="100%" border="0" cellspacing="5" cellpadding="0">
//			<tr>
//				<td><h3 align="left">'.$h->title.'</h3></td>
//			</tr>
//			<tr>
//				<td>
//				<table cellspacing="1" style="text-align:left" >
//					<tr style="font-size: 1.3em">
//						<td width="85">No PO</td>
//						<td width="280">: '.$h->no_po.'</td>
//
//						<td width="120">Tanggal PO</td>
//						<td>: '.$tanggal_po.$this->write1DBarcode($h->no_po, 'C39', '', '', '', 10, 0.2, $style, 'N').'</td>
//					</tr>
//					<tr style="font-size: 1.3em">
//						<td>Supplier</td>
//						<td>: '.$h->nama_supplier.'</td>
//
//						<td>Dibuat Oleh</td>
//						<td >: '.$h->order_by_po.'</td>
//					</tr>
//					<tr style="font-size: 1.3em">
//						<td>NPWP</td>
//						<td>: '.$h->npwp.'</td>
//
//
//						<td>TOP</td>
//						<td>: '.$h->top.' Hari</td>
//					</tr>
//
//					<tr style="font-size: 1.3em">
//						<td>Kepada</td>
//						<td>: '.$h->pic.'</td>
//
//						<td>Masa Berlaku PO</td>
//						<td>: '.$tgl_berlaku_po.'</td>
//
//					</tr>
//					<tr style="font-size: 1.3em">
//						<td>No Telp</td>
//						<td>: '.$h->telpon.'</td>
//
//						<td>Kirim Ke</td>
//						<td width="7">:</td>
//						<td width="50%">'.$h->kirim_po.'</td>
//					</tr>
//					<tr style="font-size: 1.3em">
//						<td>No Fax</td>
//						<td>: '.$h->fax.'</td>
//
//						<td>Alamat</td>
//						<td width="7">:</td>
//						<td width="50%" rowspan="2">'.$h->alamat_kirim_po.'</td>
//					</tr>
//					<tr style="font-size: 1.3em">
//						<td>E-mail</td>
//						<td>: '.$h->email.'</td>
//					</tr>
//					<tr>
//						<td colspan="2">&nbsp;</td>
//					</tr>
//					<tr>
//						<td colspan="2">' . $detail . '</td>
//					</tr>
//					<tr>
//						<td colspan="2">' . $summary . '</td>
//					</tr>
//				</table>
//				</td>
//			</tr>
//		</table>';
//
//		$this->writeHTML($html, true, false, true, false, 'C');
//	}
}
