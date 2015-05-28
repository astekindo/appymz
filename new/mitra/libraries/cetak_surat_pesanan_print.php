<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class cetak_surat_pesanan_print extends FormatLaporan {

	public $cetak_ke_non_harga = '0';
	
        public $pkp = '0';
        
        public function setPkp($h){
            $this->pkp = $h->pkp; 
        }
        
	public function Header() {
            
                
		$this->CI = & get_instance();
		$this->SetFont('times', '', 8);
                
                if($this->pkp == '0'){
                    $company_name = '';
                }else if($this->pkp == '1'){
                    $company_name = 'PT. SURYA KENCANA KERAMINDO';
                }
		$html = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100" rowspan="4"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>
						<td width="300" rowspan="2" align = "center"><h2>'. $company_name .'</h2></td>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).'</td>
					</tr>
					<tr>
						<td width="250" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).'</td>
					</tr>
					<tr>
						<td width="300" rowspan="2" align = "center">' . $this->CI->config->item('header_laporan') . '</td>
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
		$this->Cell(0, 10, 'Cetakan ke : '. $this->cetak_ke_non_harga . '           Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
	}	
		
	public function privateData($h,$d){
		$this->AddPage();
		$this->cetak_ke_non_harga = $h->cetak_ke_non_harga; 

		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
									<th align="center" width="25">No</th>
									<th align="center" width="100">Kode Barang<br>&<br>(Kd Barang Lama)</th>
									<th align="center" width="100">Kode Barang Supplier</th>
									<th align="center" width="280">Nama Barang</th>
									<th align="center" width="60">Qty</th>
									<th align="center" width="80">Satuan</th>
								</tr>	';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{		
				$detail .= '<tr>
											<td align="center">'.$no.'</td>
											<td align="center">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
											<td align="center">'.$v->kd_produk_supp .'</td>
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.$v->qty_sp.'</td>
											<td align="center">'.$v->nm_satuan.'</td>											
										</tr>	';			
										$no++;
										$sum_qty = $sum_qty + $v->qty_sp;
	
			}

			$detail .= '<tr><td></td><td></td><td></td><td>Total : </td><td align="center">'. $sum_qty .'</td></tr>';
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';
		
		$summary = '<table width="730" border="0" cellspacing="0" cellpadding="3">';
			
		$summary .= '<tr>
						<td align="center" width="100"></td>
						<td align="right" width="550"></td>
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100">Hormat Kami</td>
							<td align="right" width="550"></td>
					</tr>	';			
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';	
		
		$summary .= '<tr>
							<td align="right" width="750"></td>
					</tr>	';		
		
		$summary .= '<tr>
							<td align="center" width="130">( ' . $h->approval_by .' )</td>
							<td align="left" width="590"></td>
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="650"></td>
					</tr>	';	
		$summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="650"></td>
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
		// $this->write1DBarcode($h->no_po, 'C39', '', '', '', 18, 0.4, $style, 'N');	

		if($h->tgl_sp){
			$tgl_sp = date('d-m-Y', strtotime($h->tgl_sp));
		}

		if($h->tgl_berlaku_sp2){
			$tgl_berlaku_sp = date('d-m-Y', strtotime($h->tgl_berlaku_sp2));
		}				
		if($h->kd_peruntukan ==='1'){
                    $title = $h->title.' DISTRIBUSI FORM';
                    $no_sp ='No SP';
                }else if($h->is_bonus ==='1'){
                    $title = $h->title.' BONUS FORM';
                    $no_sp ='No SP Bonus';
                }else{
                    $title = $h->title.' FORM';
                    $no_sp ='No SP';
                }
                
		$html = '
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.' (NON HARGA)</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left" >
					<tr style="font-size: 1.0em">
						<td width="85">'.$no_sp.'</td>
						<td width="10"> : </td>
						<td width="270">'.$h->no_sp.'</td>
                                                <td width="120">No SP Induk</td>   
						<td >: '.$h->no_sp_induk.$this->write1DBarcode($h->no_sp, 'C39', '', '', '', 10, 0.2, $style, 'N').'</td>
					</tr>    
					<tr style="font-size: 1.0em">
						<td>Tanggal SP</td>
						<td> : </td>
						<td>'.$tgl_sp.'</td>
                                                <td width="120">Dibuat Oleh</td>
                                                <td>: '.$h->order_by.'</td>
						
					</tr>
					<tr style="font-size: 1.0em">
						<td>Supplier</td>
						<td> : </td>
						<td>'.$h->nama_supplier.'</td>
                                                <td>TOP</td>
						<td>: '.$h->top.' Hari</td>
						
					</tr>
					
					<tr style="font-size: 1.0em">
						<td>Kepada</td>
						<td> : </td>
						<td>'.$h->pic.'</td>
                                                <td>Masa Berlaku SP</td>
						<td>: '.$tgl_berlaku_sp.'</td>
					</tr>
					<tr style="font-size: 1.0em">
						<td>No Telp</td>
						<td> : </td>
						<td>'.$h->telpon.'</td>
					</tr>
					<tr style="font-size: 1.0em">
						<td>No Fax</td>
						<td> : </td>
						<td>'.$h->fax.'</td>
					</tr>
					<tr style="font-size: 1.0em">
						<td>Kirim Ke</td>
						<td> : </td>
						<td>'.$h->kirim_sp.'</td>
					</tr>
					<tr style="font-size: 1.0em">
						<td>Alamat</td>
						<td> : </td>
						<td width="80%">'.$h->alamat_kirim_sp.'</td>
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
				<br/>
				<p>*) FORM INI TERCETAK LANGSUNG DARI SYSTEM, SEHINGGA TIDAK DIPERLUKAN TANDA TANGAN</p>
				</td>
			</tr>
			<tr>
				<td align="left">&nbsp;</td>
			</tr>	
		</table>';

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
