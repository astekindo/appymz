<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporanPajak.php');

class FakturPajakUangMukaPrint extends FormatLaporanPajak {

		
	public function privateData($h, $d){
	for ($counter1 = 1; $counter1 <= 2; $counter1++){
          
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
		foreach($d as $v){
                    $no_bayar = $v->no_bayar;
                }
                
		$detail = '<table width="650" border="1" cellspacing="0" cellpadding="3">';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="240">Kode dan Nomor Seri Faktur Pajak </td><td width="10">:</td><td width="250"> '.$h->no_faktur_pajak.'</td><td width="140" align="right">'.$no_bayar.'</td></tr>
                            </table></td></tr>';
                $detail .= '<tr><td>Pengusaha Kena Pajak </td></tr>';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="180">Nama </td>   <td width="10">:</td> <td width="460">PT. SURYA KENCANA KERAMINDO</td></tr>
                            <tr><td width="180">Alamat</td>  <td width="10">:</td> <td width="460">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).''.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).' '.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td></tr>
                            <tr><td width="180">NPWP</td> <td width="10">:</td> <td width="460">'.$this->CI->session->userdata(NO_NPWP).'</td></tr>
                            <tr><td width="180">Tanggal Pengukuhan PKP</td> <td width="10">:</td><td>'.$this->CI->session->userdata(TGL_PENGUKUHAN).'</td></tr>
                            </table></td></tr>';
                $detail .= '<tr><td>Pembeli Barang Kena Pajak/Penerima Jasa Kena Pajak </td></tr>';
                $detail .= '<tr><td><table width="650" cellspacing="0" cellpadding="3">
                            <tr><td width="180">Nama</td>    <td width="10">:</td> <td width="460">'.$nama_pelanggan.'</td></tr>
                            <tr><td width="180">Alamat</td>  <td width="10">:</td>  <td>'.$alamat_npwp.'</td></tr>
                            <tr><td width="180">NPWP</td> <td width="10">:</td>  <td>'.$no_npwp.'</td></tr>
                            </table></td></tr>';
		$detail .= '<tr>
                                <th align="center" width="40">No. Urut</th>
                                <th align="center" width="420">Nama Barang Kena Pajak / Jasa Kena Pajak</th>
                                <th align="center" width="190">Harga Jual /Penggantian/Uang Muka/Termin (Rp.)</th>

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
                               $harga_jual_net = $v->rp_harga_jual - $v->rp_total_diskon - $v->rp_diskon_satuan;
				$title = $v->title;
				$detail .= '<tr>
								<td align="center">'.$no.'</td>
								<td align="left" height ="230">Pembayaran Uang Muka Untuk Sales Order No : ' .$v->no_so.'</td>
								<td align="right">'.number_format($v->rp_uang_muka, 0,',','.').'</td>
								
							</tr>	';			
							$no++;
	
			}
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		$dasar_pajak = $v->rp_uang_muka / 1.1;
                $pajak = 0.1 * $dasar_pajak;
		$detail .= '</table>';	
                $detail .= '<table width="650" border ="1" cellspacing="0" cellpadding="2">
                            <tr><td width="460">a. Jumlah Harga Jual/Penggantian/Uang Muka/Termin *)</td> <td width="190" align="right">'.number_format($v->rp_uang_muka, 0,',','.').'</td></tr>
                            <tr><td width="460">b. Dikurangi Potongan Harga </td>                       <td width="190" align="right">0</td></tr>
                            <tr><td width="460">c. Dikurangi Uang Muka Yang Telah Diterima</td>         <td width="190" align="right">0</td></tr>
                            <tr><td width="460">d. Dasar Pengenaan Pajak = 100/110 x (a-b-c)</td>       <td width="190" align="right">'.number_format($dasar_pajak, 0,',','.').'</td></tr>
                            <tr><td width="460">e. PPN = 10% x Dasar Pengenaan Pajak </td>              <td width="190" align="right">'.number_format($pajak, 0,',','.').'</td></tr>
                            
                            </table>';
               $detail .= ' <table  width="650" border ="1" cellspacing="0" cellpadding="3"><tr><td>
                            <table width="650" border ="0" cellspacing="0" cellpadding="3">
                            <tr><td> Pajak Penjualan Atas Barang Mewah </td></tr>
                            <tr><td width = "250"> <table width="240" border ="1" cellspacing="0" cellpadding="1"> 
                            <tr><td> TARIF</td><td>DPP</td><td>PPn BM</td></tr>
                            <tr><td> .............%</td><td>Rp ...........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ...........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ..........</td><td>Rp ............</td></tr>
                            <tr><td> .............%</td><td>Rp ..........</td><td>Rp ............</td></tr>
                            <tr><td colspan ="2"> Jumlah</td><td>Rp ............</td></tr>
                            
                            </table></td>
                            <td width="400" align="center"> Palembang , '.$tgl_faktur.'</td>
                            </tr>
                            <tr><td width="250"></td><td width="10"></td><td width="380" align="center">'.$this->CI->session->userdata(NAMA_FAKTUR).'</td><td width="10"></td></tr>
                            <tr><td width="250"></td><td width="10"></td><td width="380" align="center">('.$this->CI->session->userdata(JABATAN_FAKTUR).')</td><td width="10"></td></tr>
                            </table></td> </tr></table>';
		 if ($counter1 == 1){
                        $ket_atas = 'Lembar Ke 1 : untuk Pembeli BKP/Penerima JKP'; 
                        $ket_atas1 = 'sebagai Bukti Pajak Masukan'; 
                    }else {
                        $ket_atas = 'Lembar Ke 2 : untuk Penjual BKP/Pemberi JKP'; 
                        $ket_atas1 = 'sebagai Bukti Pajak Keluaran';
                    }	
		$html = '
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
                        <tr>
				<td width="250"></td><td align="right" width="400">'.$ket_atas.'</td>
			</tr>
                        <tr>
				<td width="440"></td><td align="left" width="210">'.$ket_atas1.'</td>
			</tr><tr></tr>
                        <tr>
				<td width="460"></td><td align="left" width="190"></td>
			</tr>
                        <tr>
				<td width="1"></td><td width="650"><h3 align="center">'.$h->title.'</h3></td>
			</tr>
                        <tr></tr><tr></tr><tr></tr>
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
				<td width="460" align="left">*) Coret yang tidak perlu</td><td align="left" width="190"></td>
			</tr>
			
		</table>
                ';	

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
}
