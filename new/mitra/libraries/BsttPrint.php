<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Bsttprint extends FormatLaporan {

    public function Header() {
        $this->SetMargins(4, 2, 4, true);
    }

    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('courier', '', 10);
        $this->CI = & get_instance();
        $this->SetMargins(5,2, 4, true);
        $htmlHeader = '<br /><br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100" rowspan="4"><img src="' . $this->CI->config->item('logo_print_color') . '" /></td>
						<td width="800" rowspan="2" align = "center"><h2>PT. SURYA KENCANA KERAMINDO</h2></td>
						<td width="395" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1).'</td>
					</tr>
					<tr>
						<td width="400" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2).'</td>
					</tr>
					<tr>
						<td width="800" rowspan="2" align = "center">' . $this->CI->config->item('header_laporan') . '</td>
						<td width="395" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3).'</td>
					</tr>
					<tr>
						<td width="395" align ="right" >'.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4).'</td>
					</tr>
                                        <tr>
                                            <td width="1295"  align ="left">____________________________________________________________________________________________________________________________________________________________________________</td>
                                        </tr>
				</table>';
        
        $detail = '<table border="1" cellspacing="0" cellpadding = "3">';
        $detail .= '<tr>						<th align="center" width="30">No</th>
									<th align="center" width="130">Nama Pelanggan</th>
									<th align="center" width="140">No Faktur</th>
                                                                        <th align="center" width="100">Tgl Faktur</th>
									<th align="center" width="100">Tgl Jth Tempo</th>
									<th align="center" width="80">Rp Faktur</th>
                                                                        <th align="center" width="80">Tunai</th>
                                                                        <th align="center" width="80">Transfer</th>
                                                                        <th align="center" width="80">No Cek/Giro</th>
                                                                        <th align="center" width="80">No Cek/Giro</th>
                                                                        <th align="center" width="100">Tgl Jth Tempo Cek/Giro</th>
                                                                        <th align="center" width="100">Total Bayar</th>
                                                                        <th align="center" width="100">Sisa Faktur</th>
                                                                        <th align="center" width="100">Keterangan</th>
								</tr>	';
        if (!empty($d)) {
            $no = 1;
            $sum_rp_faktur = 0;
            foreach ($d as $v) {
                if ($v->tgl_faktur) {
                    $tanggal_faktur = date('d-m-Y', strtotime($v->tgl_faktur));
                }

                if ($v->tgl_jatuh_tempo) {
                    $tgl_jatuh_tempo = date('d-m-Y', strtotime($v->tgl_jatuh_tempo));
                }
                
                $detail .= '<tr>
											<td align="center">' . $no . '</td>
											<td align="center">' . $v->nama_pelanggan .'</td>
                                                                                        <td align="center">' . $v->no_faktur . '</td>    
											<td align="center">' . $tanggal_faktur . '</td>
											<td align="center">' . $tgl_jatuh_tempo . '</td>
											<td align="center">' . $v->rp_faktur . '</td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
                                                                                        <td align="center"></td>
										</tr>	';
                $no++;
                $sum_rp_faktur = $sum_rp_faktur + $v->rp_faktur;
            }

            $detail .= '<tr><td></td><td></td><td></td><td></td><td>Total : </td><td align="center">' . $sum_rp_faktur . '</td></tr>';

            
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

        $summary = '<table  border="0" cellspacing="0" cellpadding="3">';

        
        $summary .= '<tr>
							<td align="center" width="220">Faktur Diserahkan Oleh</td>
							<td align="center" width="280">Faktur Diterima Oleh</td>
                                                        <td align="center" width="280">Pembayaran Diterima</td>
                                                        <td align="center" width="280">Penyetoran Diterima</td>
                                                        <td align="center" width="280">Diketahui Oleh</td>
					</tr>	';
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';

       $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="550"></td>							
					</tr>	';


        $summary .= '<tr>
							<td align="center" width="220">( ' . $h->created_by .' )</td>
							<td align="center" width="280">( -------------- )</td>
                                                        <td align="center" width="280">( -------------- )</td>
                                                        <td align="center" width="280">( -------------- )</td>
                                                        <td align="center" width="280">( -------------- )</td>
					</tr>	';
        
        $summary .= '<tr>
							<td align="center" width="100"></td>
							<td align="right" width="650"></td>
					</tr>	';
        $summary .= '</table>';

       

        $html = $htmlHeader .'
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td align="left"><br/>' . $h->title . '</td>
			</tr>
			<tr>
				<td>
				<table border= "0" cellspacing="0" style="text-align:left" width="100%">
					
					<tr >
						<td width="120">Tanggal</td>
						<td width="310"> : ' . $h->tanggal . '</td>
						
					</tr>    
					<tr >
						<td width="120">Kolektor</td>
						<td width="310"> : ' . $h->nama_collector . '</td>
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
				<td align="left">&nbsp;</td>
			</tr>	
		</table>';

        $this->writeHTML($html, true, false, true, false, 'C');
        
    }

}

?>
