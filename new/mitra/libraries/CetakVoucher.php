<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('FormatLaporan.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CetakVoucher
 *
 * @author miyzan
 */
class CetakVoucher extends FormatLaporan {
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
        $header2 ='<table width="100%" border="0" cellspacing="0">';
        $header2 .=            
                '<tr>
                    <td width="1415" colspan="7" align = "center" style="font-size:16;">' .$h[0]->title. '</td>
                 </tr>
                 <tr>
                    <td align = "left" width="110" style="font-size:12;">Tanggal</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" width="140" style="font-size:12;">' .$h[0]->tgl_transaksi. '</td>
                    <td align = "left" width="750" style="font-size:12;">&nbsp;</td>
                    <td align = "left" width="130" style="font-size:12;">Referensi</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" width="265" style="font-size:12;">' .$h[0]->referensi. '</td>
                  </tr>
                  <tr>
                    <td align = "left" style="font-size:12;">No.Voucher</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" style="font-size:12;">' .$h[0]->kd_voucher. '</td>
                    <td align = "left" style="font-size:12;">&nbsp;</td>
                    <td align = "left" style="font-size:12;">Keterangan</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" style="font-size:12;">' .$h[0]->keterangan. '</td>
                  </tr>
                  <tr>
                    <td align = "left" style="font-size:12;">No.Jurnal</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" colspan="2" style="font-size:12;">' .$h[0]->idjurnal.'/'.$h[0]->posting_date. '</td>
                    '
//                <td align = "left" style="font-size:12;">&nbsp;</td>'
                       ;
               if($h[0]->no_giro_cheque){
               $header2 .=   '<td align = "left" style="font-size:12;">No.Giro/Cheque</td>
                    <td align = "left" width="10" style="font-size:12;">:</td>
                    <td align = "left" style="font-size:12;">'.$h[0]->no_giro_cheque.'</td>
                  </tr>
                '  ;
               }else{
                $header2 .=     '<td align = "left" style="font-size:12;"></td>
                    <td align = "left" width="10" style="font-size:12;"></td>
                    <td align = "left" style="font-size:12;"></td>
                  </tr>
                ';
               }
        $header2 .= '</table>';
        $detail='<br /><br /> <table width="100%" border="1" cellspacing="1">';
        $detail.=
        '<tr>
            <th width="150">Kode Akun</th>
            <th width="290">Nama Akun</th>
            <th width="320">Cost Center</th>
            <th width="350">Keterangan</th>
            <th width="150">Debet</th>
            <th width="150">Kredit</th>
          </tr>';
        $totald=0;
        $totalk=0;
        
        foreach ($d as $v) {
            $totald +=$v->debet;
            $totalk +=$v->kredit;
            
            if(is_numeric($v->debet)) $v->debet=number_format($v->debet);
            if(is_numeric($v->kredit)) $v->kredit=number_format($v->kredit);
           $detail.= 
              '<tr>
                <td align = "center" style="font-size:12;">'.$v->kd_akun.'</td>
                <td align = "left" style="font-size:12;text-indent:8px;">'.$v->nama.'</td>
                <td align = "left" style="font-size:12;text-indent:8px;">'.$v->costcenter.'</td>
                <td align = "left" style="font-size:12;text-indent:8px;">'.$v->keterangan_detail.'</td>
                <td align = "right" style="font-size:12;">'.$v->debet.'</td>
                <td align = "right" style="font-size:12;">'.$v->kredit.'</td>
              </tr>';
        }
        $totald=number_format($totald);
        $totalk=number_format($totalk);
       $detail.=  '<tr>
                <td align = "center" style="font-size:12;"></td>
                <td align = "left" style="font-size:12;text-indent:8px;"></td>
                <td align = "left" style="font-size:12;text-indent:8px;"></td>
                <td align = "left" style="font-size:12;text-indent:8px;">Total</td>
                <td align = "right" style="font-size:12;">'.$totald.'</td>
                <td align = "right" style="font-size:12;">'.$totalk.'</td>
              </tr>';
        
        $detail .= '</table>';    
        $summary='<br /><br /> <table width="100%" border="0" cellspacing="1">';
        $summary .=
            '<tr>
                <td width="235" align = "center" style="font-size:12;">Voucher Entry</td>';
        
        if($h[0]->approval1 == 1){
            $summary .='<td width="235" align = "center" style="font-size:12;">Approval-1</td>';        
        }else{
            $summary .='<td width="235" align = "center" style="font-size:12;"></td>';        
        }
        
        if($h[0]->approval12 == 1){
            $summary .='<td width="235" align = "center" style="font-size:12;">Approval-2</td>';       
        }else{
            $summary .='<td width="235" align = "center" style="font-size:12;"></td>';        
        }
        
        if($h[0]->approval13 == 1){
            $summary .='<td width="235" align = "center" style="font-size:12;">Approval-3</td>';       
        }else{
            $summary .='<td width="235" align = "center" style="font-size:12;"></td>';        
        }        
        
        if($h[0]->diterima_oleh){
            $summary .='<td width="235" align = "center" style="font-size:12;">Diterima Oleh</td>';       
            $summary .='<td width="235" align = "center" style="font-size:12;">DiKeluarkan Oleh</td>';       
        }else{
            $summary .='<td width="235" align = "center" style="font-size:12;"></td>';     
            $summary .='<td width="235" align = "center" style="font-size:12;"></td>';  
        }                
                          
            $summary .='</tr>
            <tr>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->created_date. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval_date. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval2_date. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval3_date. '</td>
                <td width="235" align = "center" style="font-size:12;"></td>                
                <td width="235" align = "center" style="font-size:12;"></td> 
            </tr>
            <tr>
                <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
                <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
                <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
                <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
                <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>    
    <td width="235" align = "center" style="font-size:12;"><p>&nbsp;</p>
    <p>&nbsp;</p></td>    
            </tr>
            <tr>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->created_by. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval_by. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval2_by. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->approval3_by. '</td>
                <td width="235" align = "center" style="font-size:12;">' .$h[0]->diterima_oleh. '</td> 
                <td width="235" align = "center" style="font-size:12;"></td>     
            </tr>';
        $summary .='</table>';  
        $html = $htmlHeader.$header2.$detail.$summary ;
        $this->writeHTML($html, true, false, true, false, 'C');
    }
    
    public function Footer() {
		// Position at 15 mm from bottom
		
	}
}

?>
