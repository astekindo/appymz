<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianReceiveOrderPrint extends FormatLaporan {
			
	public function privateData($h,$d){
		$this->AddPage();
		
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
									<th align="center" width="30">No</th>
									<th align="center" width="100">No PO</th>
									<th align="center" width="100">Kode Barang<br>(Kd Brg Lama)</th>
									<th align="center" width="100">Kode Barang Supplier</th>
									<th align="center" width="250">Nama Barang</th>
									<th align="center" width="40">Qty</th>
									<th align="center" width="70">Satuan</th>
									<th align="center" width="150">Lokasi</th>
									<th align="center" width="70">Satuan Ekspedisi</th>
									<th align="center" width="70">Berat Ekspedisi</th>
								</tr>	';
		if(!empty($d))
		{
			$no = 1;		
			$sum_berat = 0;
			$sum_qty = 0;
			$nama_ekspedisi = 0;
			foreach($d as $v)
			{
				$detail .= '<tr>
											<td align="center">'.$no.'</td>
											<td align="center">'.$v->no_po .'</td>
											<td align="center">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
											<td align="center">'.$v->kd_produk_supp .'</td>
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.number_format($v->qty_terima, 0,',','.').'</td>
											<td align="center">'.$v->nm_satuan.'</td>
											<td>'.$v->gudang.'</td>
											<td align="center">'.$v->nm_satuan_ekspedisi.'</td>
											<td align="right">'.number_format($v->berat_ekspedisi, 0,',','.').'</td>
										</tr>	';			
										$no++;
										$sum_berat = $sum_berat + $v->berat_ekspedisi;
										$sum_qty = $sum_qty + $v->qty_terima;
										$nama_ekspedisi = $v->nama_ekspedisi;
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';
		
		$summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';

		$summary .= '<tr>
							<td align="left" width="440"></td>
							<td align="left" width="150">Total Qty</td>
							<td align="left" width="40">'.number_format($sum_qty, 0,',','.').'</td>
							<td align="right" width="190"></td>
							<td align="right" width="90">Total Berat</td>
							<td align="right" width="65">'.number_format($sum_berat, 0,',','.').'</td>
					</tr>	';		
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

        /**
         * kode
         * RO => RECEIVE ORDER FORM
         * RA => RECEIVE ORDER FORM (ASSET)
         * RB => RECEIVE ORDER FORM (BONUS)
         * RK => RECEIVE ORDER FORM (KONSINYASI)
         */
        $kode = substr($h->no_do, 0, 2);
        switch($kode) {
            case 'RA':
                $info = ' (ASSET)';
                break;
            case 'RB':
                $info = ' (BONUS)';
                break;
//            case 'RK':
//                $info = ' (KONSINYASI)';
//                break;
            case 'RO':
                $info = '';
                break;
            default:
                break;
        }
         if($h->kd_peruntukan == '1'){
                    $title = 'DISTRIBUSI';
                }else {
                    $title = '';
                }
        if($h->tanggal_terima){
			$tanggal_terima = date('d-m-Y', strtotime($h->tanggal_terima));
		}
        if($h->tanggal){
			$tanggal = date('d-m-Y', strtotime($h->tanggal));
		}
		$html = '
		<table width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td><h3 align="left">'. $h->title . $info .' '.$title.' FORM</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr style="font-size: 1.3em">
						<td width="130">Nama Supplier</td>
						<td width="450">: '.$h->nama_supplier.'</td>
						<td width="85">No Bukti</td>
						<td width="280">: '.$h->no_do.'</td>
					</tr>    
					<tr style="font-size: 1.3em">
						<td>No Referensi</td>
						<td>: '.$h->no_bukti_supplier.'</td>
						<td>Tgl Terima</td>
						<td>: '.$tanggal_terima.'</td>
					</tr>
					<tr style="font-size: 1.3em">
						<td>Nama Ekspedisi</td>
						<td>: '.$nama_ekspedisi.'</td>
						<td>Tgl Input</td>
						<td>: '.$tanggal.'</td>
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
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>User</td>
						<td>Disetujui</td>
						<td>Diketahui</td>						
					</tr>
					<tr>
						<td><br /><br /><br /><br /></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>		
					<tr>
						<td>( '.$h->created_by.' )</td>
						<td>( ................................. )</td>
						<td>( ................................. )</td>
					</tr>	
											
				</table>
				
				</td>
			</tr>			
		</table>';

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
