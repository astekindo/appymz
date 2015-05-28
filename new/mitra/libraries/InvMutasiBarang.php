<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class InvMutasiBarang extends FormatLaporan {
        private $no_ref = "";
        
    public function Header() {
                
        $this->CI = & get_instance();
        $this->SetFont('courier', '', 16);
                $company_name = 'PT. SURYA KENCANA KERAMINDO';
                if(isset($this->pkp) && $this->pkp === '0')
                    $company_name = '';
                
        $html = '<br /><br />'.$this->CI->config->item('header_laporan_matrix').'<br /> '.$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3);
        $this->writeHTML($html, true, false, true, false, 'L');        
        $this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
    }
    
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
        // Page number
        $this->Cell(0, 10, 'Ref : '. $this->no_ref . '           Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
    }    
        
    public function privateData($h,$d){
                $this->no_ref = $h->no_mutasi_stok;
        $this->AddPage();
                $this->SetFont('courier', '', 12);
        $detail = '<table width="100%" border="1" cellspacing="0" cellpadding="3">';
        $detail .= '<tr>
                                    <th align="center" width="25">No</th>
                                    <th align="center" width="130">Kode Barang</th>
                                    <th align="center" width="300">Nama Barang</th>
                                    <th align="center" width="35">Qty</th>
                                    <th align="center" width="60">Satuan</th>
                                    <th align="center" width="120">Lokasi<br />Asal</th>
                                    <th align="center" width="120">Lokasi<br />Tujuan    </th>
                                </tr>    ';
        if(!empty($d))
        {
            $no = 1;
            $sum_qty = 0;        
            foreach($d as $v)
            {
                $kd_produk_supp = empty($v->kd_produk_supp) ? '-' : $v->kd_produk_supp;
                $detail .= '<tr>
                                            <td align="center">'.$no.'</td>
                                            <td align="center">'.$v->kd_produk.'<br/>('. $kd_produk_supp .')</td>
                                            <td>'. $v->nama_produk.'</td>
                                            <td align="center">'.$v->qty.'</td>
                                            <td align="center">'.$v->nm_satuan.'</td>
                                            <td align="center">'.$v->lokasi_asal.'</td>
                                            <td align="center">'.$v->lokasi_tujuan.'</td>
                                        </tr>    ';            
                                        $no++;
                                        $sum_qty = $sum_qty + $v->qty;
    
            }

            $detail .= '<tr><td></td><td></td><td>Total : </td><td align="center">'. $sum_qty .'</td></tr>';
            
        }
        else
        {
            $detail .= '<tr><td>-----</td></tr>';            
        }
        
        $detail .= '</table>';
        
        $summary = '<table width="100%" border="0" cellspacing="0" cellpadding="3">';
            
        
        $summary .= '<tr>
                            <td align="center" width="170">Dibuat</td>
                            <td align="center" width="170">Mengetahui</td>
                                                        <td align="center" width="170">Dikeluarkan</td>
                                                        <td align="center" width="170">Diterima</td>
                    </tr>    ';            
        $summary .= '<tr>
                            <td align="center" width="100"></td>
                            <td align="right" width="550"></td>                            
                    </tr>    ';    
        
        $summary .= '<tr>
                            <td align="right" width="750"></td>
                    </tr>    ';        
        
        $summary .= '<tr>
                            <td align="center" width="170">( ' . $h->created_by .' )</td>
                            <td align="center" width="170">(------------)</td>
                                                        <td align="center" width="170">(------------)</td>
                                                        <td align="center" width="170">(------------)</td>
                    </tr>    ';    
        $summary .= '<tr>
                            <td align="center" width="100"></td>
                            <td align="right" width="650"></td>
                    </tr>    ';        
        $summary .= '</table>';
        

        if($h->tgl_mutasi){
            $tgl_mutasi = date('d-m-Y', strtotime($h->tgl_mutasi));
        }                
        
        $html = '
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
                <td><h3 align="left">MUTASI BARANG FORM </h3></td>
            </tr>
            <tr>
                <td>
                <table cellspacing="1" style="text-align:left" >
                    <tr>
                        <td width="130">No Mutasi</td>
                        <td width="270"> : '.$h->no_mutasi_stok.'</td>
                        <td width="120">No Ref</td>
                        <td >: '.$h->no_ref.'</td>
                    </tr>    
                    <tr>
                        <td>Tgl Mutasi</td>
                        <td> : '.$tgl_mutasi.'</td>
                        <td>Diambil</td>
                        <td>: '.$h->nama_pengambil.'</td>
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
