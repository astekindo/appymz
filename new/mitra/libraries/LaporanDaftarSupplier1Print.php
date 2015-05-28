<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LaporanDaftarSupplier1Print extends FormatLaporan {

    public function Header() {
        $this->SetMargins(4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('courier', '', 16);
        $this->CI = & get_instance();
        $this->SetMargins(10,2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1630"  align = "left" style="font-size:24;">' . $this->CI->config->item('header_laporan_matrix') . '</td>
						
					</tr>
					
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3) . '</td>
					</tr>
					<tr>
						<td  align ="left" style="font-size:12;">' . $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4) . '</td>
					</tr>
                                        <tr>
                                            <td  align ="left">______________________________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table width="1030" border="1" cellspacing="0">';
         $detail .= '<tr>
									<th align="center" width="30">No</th>
									<th align="center" width="70">Tgl Input<br></th>
									<th align="center" width="70">Kode</th>
									<th align="center" width="220">Nama Supplier</th>
									<th align="center" width="150">Alamat & Kota</th>                                                            
                                                                        <th align="center" width="100">Telp/Fax/Email</th>
                                                                        <th align="center" width="90">Contact Person</th>
                                                                        <th align="center" width="40">PKP</th>
                                                                        <th align="center" width="60">Konsinyasi</th>
                                                                        <th align="center" width="40">TOP</th>
                                                                        <th align="center" width="50">Status</th>
								</tr>	';
        if (!empty($d)) {
            $no = 1;
            foreach ($d as $v) {
                $detail .= '<tr>
											<td align="center">' . $no . '</td>
											<td align="center">' . $v->created_date . '</td>
											<td align="center">'. $v->kd_supplier . '</td>
											<td align="center">' . $v->nama_supplier . '</td>
											<td align="center">' . $v->alamat . '</td>
                                                                                        <td align="center">' . $v->telpon . '</td>
                                                                                        <td align="center">' . $v->pic . '</td>
                                                                                        <td align="center">' . $v->pkp . '</td>
                                                                                        <td align="center">' . $v->npwp . '</td>
                                                                                        <td align="center">' . $v->top . '</td>
                                                                                        <td align="center">' . $v->status . '</td>
                                                                                       
										</tr>	';
                $no++;

            }

           // $detail .= '<tr><td colspan = "7" >Sisa Tagihan Titip Supir sebesar Rp. : <strong>' . number_format($h->rp_kurang_bayar, 0, ',', '.') . '</strong></td></tr>';
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

       
/*
        if ($h->tgl_berlaku_po) {
            $tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
        }*/

       $html = $htmlHeader . '
		<table width="100%" border="0" cellspacing="0" cellpadding="3">	
                        
                        <tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%">					
					<tr style="font-size:16:">
                                        <td width="200">DAFTAR SUPPLIER</td>	
                                        </tr>  
                                        <tr><td></td></tr>
                                        
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

    
    public function Footer() {
		// Position at 15 mm from bottom
		
	}	
}

?>
