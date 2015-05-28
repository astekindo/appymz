<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('FormatLaporan.php');

class CetakVoucher_pdf extends FormatLaporan {
    var $cetak;
    var $lineh;
    var $revdata;

    public function Header() {
        $this->SetMargins(2, 2, 2, true);
    }

    public function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-23);
        $this->SetFont('courier', 'I', 8);
        //Page number
        $dtcetak=$this->getCetak();
        if(count($dtcetak)>0){
            if($dtcetak['tglcetak']){
                $dtcetak['tglcetak']=date('d-M-Y',strtotime($dtcetak['tglcetak'])).' '.date('H:i:s');
            }
        }
        $this->Cell(40, 5, 'Cetakan Ke:'.$dtcetak['cetakke'], 0, 0, 'L');
        $this->Cell(70, 5, 'Tanggal Cetak:'.$dtcetak['tglcetak'], 0, 0, 'L');
        $this->Cell(70, 5, 'Cetak By:'.$dtcetak['cetakby'], 0, 0, 'L');
        $this->Cell(32, 5, 'Page '. $this->getAliasNumPage() .' of '. $this->getAliasNbPages(), 0, 0, 'R');
    }


    public function privateData($h, $d) {
        $this->AddPage();
        $this->SetFont('courier', '', 12);
        $this->CI = & get_instance();
        $this->SetMargins(7,2, 4, true);
        $lap_header1 = $this->CI->config->item('header_laporan_matrix');
        $lap_header2 = $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3);
        $lap_header3 = $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4);
        $htmlHeader = <<<EOT
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align = "left" style="font-size:14;">$lap_header1</td>
    </tr>
<--
    <tr>
        <td align ="left" >$lap_header2</td>
    </tr>
    <tr>
        <td align ="left" >$lap_header3</td>
    </tr>
--!>
    <tr>
        <td width="680" align ="left" style="border-bottom: 1px solid black"></td>
    </tr>
</table>
EOT;
        $detail = <<<EOT
<table width="100%" border="1" cellspacing="0" cellpadding="1">
    <tr>
        <th align="center" width="60">Kode Akun</th>
        <th align="center" width="160">Nama Akun</th>
        <th align="center" width="60">Cost Ctr</th>
        <th align="center" width="200">Keterangan</th>
        <th align="center" width="100">Debet</th>
        <th align="center" width="100">Kredit</th>
    </tr>
EOT;
        if (!empty($d)) {
            $totald = 0;
            $totalk = 0;
            foreach ($d as $v) {
                $kd_akun    = !empty($v->kd_akun) ? $v->kd_akun: '';
                $nama       = !empty($v->nama) ? $v->nama: '';
                $costcenter = !empty($v->costcenter) ? $v->costcenter: '';
                $keterangan = !empty($v->keterangan_detail) ? $v->keterangan_detail: '';
                $debet      = !empty($v->debet) ? number_format($v->debet,0,',','.'): 0;
                $kredit     = !empty($v->kredit) ? number_format($v->kredit,0,',','.'): 0;
                $detail .= <<<EOT
    <tr>
        <td align="center">$kd_akun</td>
        <td>$nama</td>
        <td>$costcenter</td>
        <td>$keterangan</td>
        <td align="right">$debet</td>
        <td align="right">$kredit</td>
    </tr>
EOT;
                $totald += $v->debet;
                $totalk += $v->kredit;
            }
            $detail .= '<tr><td colspan="4">Total : </td>'
                .'<td align="right">' . number_format($totald,0,',','.').'</td>'
                .'<td align="right">' . number_format($totalk,0,',','.').'</td>'
                .'</tr>';
        } else {
            $detail .= '<tr><td colspan="6" align="center">-----</td></tr>';
        }
        $detail .= '</table>';

        $tglcd = !empty($h[0]->created_date) ? date('d-M-Y',strtotime($h[0]->created_date)) : '';
        $tglapp1 = (($h[0]->approval1 == 1) && !empty($h[0]->approval_date)) ? date('d-M-Y',strtotime($h[0]->approval_date)) : '';
        $tglapp2 = (($h[0]->approval2 == 1) && !empty($h[0]->approval2_date)) ? date('d-M-Y',strtotime($h[0]->approval2_date)) : '';
        $tglapp3 = (($h[0]->approval3 == 1) && !empty($h[0]->approval3_date)) ? date('d-M-Y',strtotime($h[0]->approval3_date)) : '';

        $created_by     = $h[0]->created_by;
        $app1jabatan    = $h[0]->app1jabatan;
        $app2jabatan    = $h[0]->app2jabatan;
        $app3jabatan    = $h[0]->app3jabatan;
        $dikeluarkan    = '';
        $diterima       = $h[0]->diterima_oleh;
        $summary = <<<EOT
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" width="116">Voucher Entry</td>
        <td align="center" width="114">Approval1</td>
        <td align="center" width="114">Approval2</td>
        <td align="center" width="114">Approval3</td>
        <td align="center" width="110">Dikeluarkan Oleh</td>
        <td align="center" width="110">Diterima Oleh</td>
    </tr>
    <tr>
        <td align="center" width="116">$tglcd</td>
        <td align="center" width="114">$tglapp1</td>
        <td align="center" width="114">$tglapp2</td>
        <td align="center" width="114">$tglapp3</td>
        <td align="center" width="110">&nbsp;</td>
        <td align="center" width="110">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" colspan="4"></td>
    </tr>
    <tr>
        <td align="center" colspan="4"></td>
    </tr>
    <tr>
        <td align="center" width="116">$created_by</td>
        <td align="center" width="114">$app1jabatan</td>
        <td align="center" width="114">$app2jabatan</td>
        <td align="center" width="114">$app3jabatan</td>
        <td align="center" width="110">$dikeluarkan</td>
        <td align="center" width="110">$diterima</td>
    </tr>
</table>
EOT;

        $tgl_trx    = empty($h[0]->tgl_transaksi) ? '' : date('d F Y',strtotime($h[0]->tgl_transaksi));
        $tgl_jurnal = empty($h[0]->posting_date) ? '' : $tgl=date('d F Y',strtotime($h[0]->posting_date));
        $no_giro    = $h[0]->no_giro_cheque;
        $kd_voucher = $h[0]->kd_voucher;
        $no_jurnal  = $h[0]->idjurnal;
        $tgl_jthtmp = empty($h[0]->tgl_jttempo) ? '' : date('d F Y',strtotime($h[0]->tgl_jttempo));
        $keterangan = $h[0]->keterangan;
        $spacer = '';
        $this->SetFont('courier', '', 8);
        $html = <<<EOT
$htmlHeader
<table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td>
        <table border= "0" cellspacing="0" style="text-align:left" width="100%">
            <tr >
                <td width="120">Tgl. Voucher</td>
                <td width="120">: $tgl_trx</td>
                <td width="120">Tgl. Jurnal</td>
                <td width="100">: $tgl_jurnal</td>
                <td width="120">Tgl. Jatuh tempo</td>
                <td width="100">: $tgl_jthtmp</td>
            </tr>
            <tr >
                <td width="120">No. Voucher</td>
                <td width="120">: $kd_voucher</td>
                <td width="120">No. Jurnal</td>
                <td width="100">: $no_jurnal</td>
                <td width="120">No. Giro/ Cheque</td>
                <td width="100">: $no_giro</td>
            </tr>
            <tr>
                <td width="120">Keterangan</td>
                <td colspan="5">: $keterangan</td>
            </tr>
            $spacer
            <tr>
                <td>$detail</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">$summary</td>
            </tr>
        </table>
    </tr>
</table>
EOT;

        $this->writeHTML($html, true, false, true, false, 'C');

    }
    function setCetak($c=NULL){
        $this->cetak=$c;
    }

    function getCetak(){
        return $this->cetak;
    }
    function setRevisi($c=NULL){
        $this->revdata=$c;
    }
    function getRevisi(){
        return $this->revdata;
    }
}