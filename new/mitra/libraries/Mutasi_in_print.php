<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class Mutasi_in_print extends FormatLaporan {
    public function Header() {
        $this->SetMargins(4, 2, 4, true);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->Cell(0, 10, date('d-F-Y H:i'), 'T', 0, 'L');
        $this->Cell(0, 10, 'Ref : '. $this->no_ref . '           Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 'T', 0, 'R');
    }


    public function privateData($h, $d) {
        $this->no_ref = $h->no_mutasi_stok;
        $this->AddPage();
        $this->SetFont('courier', '', 12);
        $this->CI = & get_instance();
        $this->SetMargins(7,2, 4, true);
        $lap_header1 = $this->CI->config->item('header_laporan_matrix');
        $lap_header2 = $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3);
        $lap_header3 = $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4);
        $htmlHeader = <<<EOT
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="1630"  align = "left" style="font-size:14;">$lap_header1</td>
    </tr>
    <tr>
        <td  align ="left" >$lap_header2</td>
    </tr>
    <tr>
        <td  align ="left" >$lap_header3</td>
    </tr>
    <tr>
        <td  align ="left" style="border-bottom: 1px solid black"></td>
    </tr>
</table>
EOT;
        $detail = <<<EOT
<table width="100%" border="1" cellspacing="0" cellpadding="2">
    <tr>
        <th align="center" width="60">No</th>
        <th align="center" width="135">Kode Barang<br>(Kd. Brg Supp)</th>
        <th align="center" width="375">Nama Barang</th>
        <th align="center" width="60">Qty</th>
        <th align="center" width="60">Satuan</th>
        <th align="center" width="80">Lokasi Asal</th>
        <th align="center" width="180">Lokasi Tujuan</th>
    </tr>
EOT;
        if (!empty($d)) {
            $no = 1;
            $sum_qty = 0;
            foreach ($d as $v) {
                $detail .= <<<EOT
    <tr>
        <td align="center">$no</td>
        <td align="center" style="word-wrap:break-word;">$v->kd_produk</td>
        <td>$v->nama_produk</td>
        <td align="right">$v->qty</td>
        <td align="center">$v->nm_satuan</td>
        <td align="center">$v->lokasi_asal</td>
        <td align="center">$v->lokasi_tujuan</td>    
    </tr>
EOT;
                $no++;
                $sum_qty = $sum_qty + $v->qty;
            }

            $detail .= '<tr><td colspan="3">Total : </td><td align="right">' . number_format($sum_qty,0,',','.') . '</td><td colspan="3"></td></tr>';
        } else {
            $detail .= '<tr><td colspan="7" align="center">-----</td></tr>';
        }
        $detail .= '</table>';

        $summary = <<<EOT
<table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
        <td align="center" width="225">Dibuat</td>
        <td align="center" width="225">Disetujui</td>
        <td align="center" width="225">Diserahkan</td>
        <td align="center" width="225">Diterima</td>
    </tr>
    <tr>
        <td align="center" colspan="4"></td>
    </tr>
    <tr>
        <td align="center" width="225">( $h->created_by )</td>
        <td align="center" width="225">( __________ )</td>
        <td align="center" width="225">( $h->nama_pengambil )</td>
        <td align="center" width="225">( __________ )</td>
    </tr>
</table>
EOT;

        $spacer = '';
        //conditional enter

        $html = <<<EOT
$htmlHeader
<table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr>
        <td align="left"><h3>$h->title</h3></td>
    </tr>
    <tr>
        <td>
        <table border= "0" cellspacing="0" style="text-align:left" width="100%">
            <tr >
                <td width="100">No Mutasi</td>
                <td width="150">: $h->no_mutasi_stok</td>
                <td width="20">&nbsp;</td>
                <td width="100">No. Ref</td>
                <td width="450">: $h->no_ref</td>
            </tr>
            <tr >
                <td width="100">Tanggal</td>
                <td width="150">: $h->tgl_mutasi</td>
                <td width="20">&nbsp;</td>
                <td width="100">Diambil</td>
                <td >: $h->nama_pengambil</td>
            </tr>
            <tr>
                <td colspan="2">$detail</td>
            </tr>
            $spacer
            <tr>
                <td colspan="2">$summary</td>
            </tr>
        </table>
        <p align = "left">KETERANGAN :</p>
            <p align = "left">$h->keterangan</p>
        </td>
    </tr>
    <tr>
        <td align="left">&nbsp;</td>
    </tr>   
</table>
EOT;

        $this->writeHTML($html, true, false, true, false, 'C');

    }
}
