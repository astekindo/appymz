<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class PembelianCreateRequestPrint extends FormatLaporan {
	public $cetak_ke = '0';

	public function Header() {
		$this->CI = & get_instance();
		$this->SetFont('courier', '', 12);
		$html = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100" rowspan="4"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>
						<td width="400" rowspan="2" align = "center"><h2>PT. SURYA KENCANA KERAMINDO</h2></td>
						<td width="400" align ="right" style="font-size:10;">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).'</td>
					</tr>
					<tr>
						<td width="400" align ="right" style="font-size:10;">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).'</td>
					</tr>
					<tr>
						<td width="400" rowspan="2" align = "center">' . $this->CI->config->item('header_laporan') . '</td>
						<td width="400" align ="right" style="font-size:10;">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td>
					</tr>
					<tr>
						<td width="400" align ="right" style="font-size:10;">'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4).'</td>
					</tr>
				</table>';
		$this->writeHTML($html, true, false, true, false, 'C');		
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
	}

	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('courier', '', 8);
		$this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
		// Page number
		$this->Cell(0, 10, 'Cetakan ke : '. $this->cetak_ke . '           Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
	}	

		
	public function privateData($h,$d){
		$this->cetak_ke = $h->cetak_ke; 
		$this->AddPage();
                
                $this->SetFont('courier', '', 12);
		
		$approval1 = '.................';
		$approval2 = '.................';
		
		$detail = '<table width="730" border="1" cellspacing="0" cellpadding="3">';
		$detail .= '<tr>
									<th align="center" width="30">No</th>
									<th align="center" width="130">Kode Barang</th>
                                                                        <th align="center" width="130">Kode Brg Supp</th>
									<th align="center" width="330">Nama Barang</th>
									<th align="center" width="80">Qty</th>
									<th align="center" width="90">Satuan</th>
									<th align="center" width="100">Keterangan</th>
								</tr>	';


		$sum_qty = 0;
		
		if(!empty($d))
		{
			$no = 1;
		
			foreach($d as $v)
			{

				
				if($v->keterangan2 != ''){
					$keterangan = $v->keterangan2;
				}else{
					$keterangan = $v->keterangan1;
				}
				$detail .= '<tr>
											<td align="center">'.$no.'</td>
											<td align="center">'.$v->kd_produk .'</td>
                                                                                        <td align="center">'.$v->kd_produk_supp .'</td>     
											<td>'.$v->nama_produk.'</td>
											<td align="center">'.number_format($v->qty_adj, 0,',','.').'</td>
											<td align="center">'.$v->nm_satuan.'</td>
											<td>'.$keterangan.'</td>
										</tr>	';			
										$no++;
										$sum_qty = $sum_qty + $v->qty_adj;
				if($v->approval1 != '')$approval1 = $v->approval1;
				if($v->approval2 != '')$approval2 = $v->approval2;
			}

			$detail .= '<tr><td></td><td></td><td></td><td>Total :</td><td align="center">'. number_format($sum_qty, 0,',','.') .'</td><td></td><td></td></tr>';
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}

		
		
		$detail .= '</table>';
							
		

		
		if($h->tgl_ro){
			$tgl_ro = date('d-m-Y', strtotime($h->tgl_ro));
		}
                if($h->kd_peruntukan == '1'){
                    $title = 'FORM PURCHASE REQUEST DISTRIBUSI';
                }else {
                    $title = 'FORM PURCHASE REQUEST';
                }
		$html = '
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td><h3 align="left">'.$title.'</h3></td>
			</tr>
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left">
					<tr >
						<td width="120">No PR</td>
						<td width="300">: '.$h->no_ro.'</td>
                                                <td width="120">Tanggal</td>
						<td>: '.$tgl_ro.'</td>    
					</tr>  	
					<tr >
						<td >Supplier</td>
						<td >: '.$h->nama_supplier.'</td>
                                                <td>Alamat</td>
						<td>: '.$h->alamat.'</td>    
					</tr>    
								
					<tr >
						<td>Keterangan</td>
						<td colspan="4">: '.$h->subject.'</td>
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
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>Administrator</td>
						<td>Approval Ass Manager</td>
						<td>Approval Manager</td>						
					</tr>
					<tr>
						<td><br /><br /></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>		
					<tr>
						<td>(    '.$h->created_by.'    )</td>
						<td>(    '.$approval1.    '    )</td>
						<td>(    '.$approval2.    '    )</td>
					</tr>	
											
				</table>
				</td>
			</tr>			
		</table>';

		$this->writeHTML($html, true, false, true, false, 'C');	
	}
}
