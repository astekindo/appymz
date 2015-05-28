<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporanFaktur.php');

class FakturPenjualanPrint extends FormatLaporanFaktur {
	function spellNumberInIndonesian ($number) {
		$number = strval($number);
		if (!ereg("^[0-9]{1,15}$", $number)) 
			return(false); 
		$ones = array("", "satu", "dua", "tiga", "empat", 
			"lima", "enam", "tujuh", "delapan", "sembilan");
		$majorUnits = array("", "ribu", "juta", "milyar", "trilyun");
		$minorUnits = array("", "puluh", "ratus");
		$result = "";
		$isAnyMajorUnit = false;
		$length = strlen($number);
		for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
			if ($number{$i} != '0') {
				if ($number{$i} != '1')
					$result .= $ones[$number{$i}].' '.$minorUnits[$pos % 3].' ';
				else if ($pos % 3 == 1 && $number{$i + 1} != '0') {
					if ($number{$i + 1} == '1') 
						$result .= "sebelas "; 
					else 
						$result .= $ones[$number{$i + 1}]." belas ";
					$i++; $pos--;
				} else if ($pos % 3 != 0)
					$result .= "se".$minorUnits[$pos % 3].' ';
				else if ($pos == 3 && !$isAnyMajorUnit)
					$result .= "se";
				else
					$result .= "satu ";
				$isAnyMajorUnit = true;
			}
			if ($pos % 3 == 0 && $isAnyMajorUnit) {
				$result .= $majorUnits[$pos / 3].' ';
				$isAnyMajorUnit = false;
			}
		}
		$result = trim($result);
		if ($result == "") $result = "nol";
		return($result);
	}	
	public function privateData($h, $d){
		$this->AddPage();
		$this->SetFont('courier', '', 11);
		$detail = '<table width="910" border="1" cellspacing="0" cellpadding="0">';
		$detail .= '<tr>
						<th align="center" width="30">No</th>
						<th align="center" width="130">No SJ<br>(Tanggal SJ)</th>
						<th align="center" width="200">Nama Barang</th>
						<th align="center" width="60">Qty</th>
						<th align="center" width="70">Satuan</th>
						<th align="center" width="100">Harga Jual (Rp)</th>
						<th align="center" width="100">Total Diskon (Rp)</th>
						<th align="center" width="100">Harga Jual Net (Rp)</th>	
						<th align="center" width="110">Total (Rp)</th>	
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
                               $harga_jual_net = $v->rp_harga_jual - $v->rp_total_diskon;
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="center">'.$v->no_sj .' <br>('.$tanggal.')</td>
								<td align="left">'.$v->nama_produk.'</td>
								<td align="center">'.$v->qty.'</td>
								<td align="center">'.$v->nm_satuan.'</td>
								<td align="center">'.number_format($v->rp_harga_jual, 0,',','.').'</td>	
								<td align="right">'.number_format($v->rp_total_diskon, 0,',','.').'</td>
								<td align="right">'.number_format($harga_jual_net, 0,',','.').'</td>
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

                $dpp = ($h->rp_faktur - $h->rp_uang_muka) / 1.1;
                if($h->tgl_faktur){
			$tgl_faktur = date('d-m-Y', strtotime($h->tgl_faktur));
		}
                $total_tagihan = $h->rp_faktur - $h->rp_uang_muka - $h->cash_diskon;
                $terbilang = strtoupper($this->spellNumberInIndonesian($total_tagihan));
                
		$summary = '<table width="910" border="0" cellspacing="0" cellpadding="0">';
		
                $summary .= '<tr>
							<td align="left" width="100"></td>
							<td align="right" width="290"></td>
							<td align="right" width="70"></td>
							<td align="right" width="75"></td>
							<td align="right" width="75"></td>
                                                        
                            </tr>	';		
                
		$summary .= '<tr>
							<td align="left" width="210">TAGIHAN TERBILANG :</td>
							<td align="left" width="155"></td>
							<td align="right" width="420">Total :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($h->rp_faktur, 0,',','.').'</td>
                                                        
                            </tr>	';			
		$summary .= '<tr>
							<td align="left" width="450" border="0">'.$terbilang.' RUPIAH</td>
							<td align="right" width="10"></td>
							<td align="right" width="325">Uang Muka :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($h->rp_uang_muka, 0,',','.').'</td>
					</tr>	';			
		$summary .= '<tr>
							<td align="center" width="280"></td>
							<td align="right" width="110"></td>
							<td align="right" width="395">Total Net :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($h->rp_faktur - $h->rp_uang_muka, 0,',','.').'</td>
					</tr>	';
                $summary .= '<tr>
							<td align="left" width="280">Catatan :</td>
							<td align="right" width="110"></td>
							<td align="right" width="395">Cash Diskon :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($h->cash_diskon, 0,',','.').'</td>
					</tr>	';
                $summary .= '<tr>
							<td align="left" width="450">1.Pembayaran dengan Cek/Bilyet Giro diakui bila</td>
							<td align="center" width="185"> Palembang ,'.$tgl_faktur.'</td>
							<td align="right" width="150">Total Tagihan :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100" border ="1">'.number_format($total_tagihan, 0,',','.').'</td>
					</tr>	';
		$summary .= '<tr>
							<td align="left" width="400">  dicantumkan nama : </td>
							<td align="right" width="90"></td>
							<td align="right" width="170"></td>
							<td align="right" width="15"></td>
							<td align="right" width="75"></td>
					</tr>	';
                $summary .= '<tr>
							<td align="left" width="400">  '.$this->CI->session->userdata(NAMA_REKENING_BANK).'</td>
							<td align="right" width="90"></td>
							<td align="right" width="170"></td>
							<td align="right" width="15"></td>
							<td align="right" width="75"></td>
					</tr>	';
		$summary .= '<tr>
							<td align="left" width="500">2.Pembayaran dengan Cek/Bilyet Giro sah bila telah</td>
							<td align="left" width="115"></td>
							<td align="right" width="170">DPP :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($dpp, 0,',','.').'</td>
					</tr>	';		
		$summary .= '<tr>
							<td align="left" width="400">   dicairkan di rekening bank : </td>
							<td align="center" width="215"></td>
							<td align="right" width="170">PPN :</td>
							<td align="right" width="15"></td>
							<td align="right" width="100">'.number_format($h->rp_ppn, 0,',','.').'</td>
					</tr>	';
                 $summary .= '<tr>
							<td align="left" width="400">  '.$this->CI->session->userdata(NAMA_REKENING_BANK).'</td>
							<td align="right" width="90"></td>
							<td align="right" width="170"></td>
							<td align="right" width="15"></td>
							<td align="right" width="75"></td>
					</tr>	';

		$summary .= '<tr>
							<td align="left" width="450">  '.$this->CI->session->userdata(BANK_FAKTUR).'</td>
							<td align="center" width="185">'.$this->CI->session->userdata(NAMA_FAKTUR).'</td>
							<td align="right" width="265">Putih : ASLI - Pembeli</td>
							
					</tr>	';
                $summary .= '<tr>
                                       <td align="left" width="450">3.Pembayaran dengan transfer ditujukan ke</td>
                                       <td align="center" width="185">('.$this->CI->session->userdata(JABATAN_FAKTUR).')</td>
                                       <td align="right" width="265">Biru : Copy - Akutansi</td>
                                       
                       </tr>	';
                $summary .= '<tr>
                                       <td align="left" width="400">  rekening yang tersebut di point 2 di atas.</td>
                                       <td align="right" width="90"></td>
                                       <td align="right" width="170"></td>
                                       <td align="right" width="15"></td>
                                       <td align="right" width="75"></td>
                       </tr>	';
               	
		$summary .= '</table>';

		if($h->tgl_terima_invoice){
			$tgl_terima_invoice = date('d-m-Y', strtotime($h->tgl_terima_invoice));
		}

		

		if($h->tgl_jatuh_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jatuh_tempo));
		}
                if($h->nama_npwp != ''){
                    $nama_pelanggan = $h->nama_npwp;
                    $alamat_pelanggan = $h->alamat_npwp;
                }else {
                    $nama_pelanggan = $h->nama_pelanggan;
                    $alamat_pelanggan = $h->alamat_tagih;
                }

		$html = '
		<table width="100%" border="0" cellspacing="20" cellpadding="0">
			<tr>
				<td><h1 align="center">'.$h->title.'</h1></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="0" style="text-align:left">
					<tr style="font-size: 1.1em">
                                                <td width="350">KEPADA YTH :</td>
						<td width="220"></td>
						<td width="160">NO.FAKTUR</td>
						<td width="180">: '.$h->no_faktur.'</td>
						
						
					</tr>    
					<tr style="font-size: 1.1em">
						<td>  '.$nama_pelanggan.'</td>
                                                <td>  </td>
						<td>NO SO</td>
						<td>: '.$h->no_so.'</td>
						
					</tr>
                                        <tr style="font-size: 1.1em">
						<td>  '.$alamat_pelanggan.'</td>
                                                <td></td>
						<td>JATUH TEMPO</td>
						<td>: '.$tgl_jth_tempo.'</td>
						
					</tr>
                                        <tr style="font-size: 1.1em">
						<td></td>
                                                <td></td>
						<td>TERMIN PEMBAYARAN</td>
						<td>: '.$h->top.' Hari</td>
						
					</tr>
					<tr style="font-size: 1.1em">
						<td></td>
                                                <td></td>
						<td>NAMA SALESMAN</td>
						<td>: '.$h->nama_sales.'</td>
						
					</tr>
                                        <tr style="font-size: 1.1em">
						<td>NO REF : '.$h->no_ref.'</td>
                                                <td></td>
						<td>KODE PELANGGAN</td>
						<td>: '.$h->kd_pelanggan.'</td>
						
					</tr>
                                         <tr style="font-size: 1.1em">
						<td></td>
                                                <td></td>
						<td></td>
						<td></td>
						
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
