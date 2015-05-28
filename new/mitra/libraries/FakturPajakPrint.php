<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporanPajak.php');

class FakturPajakPrint extends FormatLaporanPajak {
		
	public function privateData($h, $d){
		$this->AddPage();
		if($h->tgl_faktur_pajak){
			$tgl_faktur = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
		}
                if ($h->nama_npwp != ""){
                    $nama_pelanggan = $h->nama_npwp;
                    $no_npwp = $h->no_npwp;
                    $alamat_npwp = $h->alamat_npwp;
                }else {
                    $nama_pelanggan = $h->nama_pelanggan;
                    $no_npwp = $h->npwp;
                    $alamat_npwp = $h->alamat_kirim;
                }
		$detail = '<table width="650" border="1" cellspacing="0" cellpadding="3">';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="190">Kode dan Nomor Seri Faktur Pajak </td><td width="10">:</td><td width="240"> '.$h->no_faktur_pajak.'</td><td width="100">'.$h->no_faktur.' /</td><td>'.$h->no_urut_so.'</td></tr>
                            </table></td></tr>';
                $detail .= '<tr><td>Pengusaha Kena Pajak </td></tr>';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="100">Nama </td>   <td width="10">:</td> <td width="540">PT. SURYA KENCANA KERAMINDO</td></tr>
                            <tr><td width="100">Alamat</td>  <td width="10">:</td> <td width="540" align="left">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).''.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).' '.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td></tr>
                            <tr><td width="100">N.P.W.P</td> <td width="10">:</td> <td width="540">'.$this->CI->session->userdata(NO_NPWP).'</td></tr>
                            <tr><td width="100">Tanggal Pengukuhan PKP</td> <td width="10">:</td><td>'.$this->CI->session->userdata(TGL_PENGUKUHAN).'</td></tr>
                            </table></td></tr>';
                $detail .= '<tr><td>Pengambil Barang Kena Pajak/Penerima Jasa Kena Pajak </td></tr>';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="100">Nama</td>    <td width="10">:</td> <td>'.$nama_pelanggan.'</td></tr>
                            <tr><td width="100">Alamat</td>  <td width="10">:</td>  <td>'.$alamat_npwp.'</td></tr>
                            <tr><td width="100">N.P.W.P</td> <td width="10">:</td>  <td>'.$no_npwp.'</td></tr>
                            </table></td></tr>';
		$detail .= '<tr>
						<th align="center" width="30">No.Urut</th>
						<th align="center" width="450">Nama Barang Kena Pajak / Jasa Kena Pajak</th>
						<th align="center" width="170">Harga Jual/Penggantian/Uang Muka/Termin(Rp.)</th>
						
					</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;	
                        $jumlah = 0;
                        $rp_total_diskon = 0;
			foreach($d as $v)
			{
				if($v->tanggal){
                                        $tanggal = date('d-m-Y', strtotime($v->tanggal));
                                }
                               $harga_jual_net = $v->rp_harga_jual - $v->rp_total_diskon - $v->rp_diskon_satuan;
				$title = $v->title;
                                $rp_jumlah = $v->qty * $v->rp_harga_jual;
                                $diskon = $v->qty * $v->rp_total_diskon;
                                $detail .= '<tr style="height:300px">
                                            <td align="center">'.$no.'</td>
                                            <td align="left" >'.$v->nama_produk.','.$v->qty.' '.$v->nm_satuan.' @ '.number_format($v->rp_harga_jual, 0,',','.').'</td>
                                            <td align="right">'.number_format($rp_jumlah, 0,',','.').'</td>

                                    	  </tr>';			
                                    $no++;
                                    $jumlah = $jumlah + $rp_jumlah;
                                    $rp_total_diskon = $rp_total_diskon + $diskon;
                                    $dasar_pajak = 100/110 * ($jumlah - $rp_total_diskon - $h->rp_uang_muka);
                                    $ppn = 0.1 * $dasar_pajak;
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';	
                $detail .= '<table width="650" border ="1" cellspacing="0" cellpadding="3">
                            <tr><td width="480">a. Jumlah Harga Jual/Penggantian/Uang Muka/Termin*</td> <td width="170" align="right">'.number_format($jumlah, 0,',','.').'</td></tr>
                            <tr><td width="480">b. Dikurangi Potongan Harga </td>                       <td width="170" align="right">'.number_format($rp_total_diskon, 0,',','.').'</td></tr>
                            <tr><td width="480">c. Dikurangi Uang Muka Yang Telah Diterima</td>         <td width="170" align="right">'.number_format($h->rp_uang_muka, 0,',','.').'</td></tr>
                            <tr><td width="480">d. Dasar Pengenaan Pajak = 100/110 x (a-b-c)</td>       <td width="170" align="right">'.number_format($dasar_pajak, 0,',','.').'</td></tr>
                            <tr><td width="480">e. PPN = 10% x Dasar Pengenaan Pajak </td>              <td width="170" align="right">'.number_format($ppn, 0,',','.').'</td></tr>
                            
                            </table>';
               $detail .= '<table  width="650" border ="1" cellspacing="0" cellpadding="3"><tr><td>
                            <table width="650" border ="0" cellspacing="0" cellpadding="3">
                            <tr><td> Pajak Penjualan Atas Barang Mewah </td></tr>
                            <tr><td width = "250"> <table width="240" border ="1" cellspacing="0" cellpadding="3"> 
                            <tr><td> TARIF</td><td>DPP</td><td>PPn BM</td></tr>
                            <tr><td> .............%</td><td>Rp ...........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ...........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ..........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ..........</td><td>Rp ............</td></tr>
                            
                            </table></td>
                            <td width="400" align="center"> Palembang , '.$tgl_faktur.'</td>
                            </tr>
                            <tr><td width="250"></td><td width="10"></td><td width="380" align="center">'.$this->CI->session->userdata(NAMA_FAKTUR).'</td><td width="10"></td></tr>
                            <tr><td width="250"></td><td width="10"></td><td width="380" align="center">('.$this->CI->session->userdata(JABATAN_FAKTUR).')</td><td width="10"></td></tr>
                            </table></td> </tr></table>';
		
		

		

		if($h->tgl_jatuh_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jatuh_tempo));
		}


		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="center">'.$h->title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" border="0" style="text-align:left">
					
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
