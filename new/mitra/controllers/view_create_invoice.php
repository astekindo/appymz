<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View_create_invoice extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('view_create_invoice_model');
    }
    public function get_data_invoice($no_invoice = ''){
        $data = $this->view_create_invoice_model->get_data_html($no_invoice);

                $h = $data['header'];
                $d = $data['detail'];

        $detail = '<table width="1000"  border="0" >';
        $detail .= '<tr>
                        <td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
                        <td width="90" align="left" bgcolor="#ACD9EB"><b>Kode Produk</b></td>
                        <td width="300" align="left" bgcolor="#ACD9EB"><b>Nama Produk</b></td>
                        <td width="50" align="right" bgcolor="#ACD9EB"><b>Qty</b></td>
                        <td width="60" align="center" bgcolor="#ACD9EB"><b>Satuan</b></td>
                        <td width="90" align="right" bgcolor="#ACD9EB"><b>Harga Supplier</b></td>
                        <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 1</b></td>
                        <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 2</b></td>
                        <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 3</b></td>
                        <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 4</b></td>
                        <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 5</b></td>
                        <td width="80" align="center" bgcolor="#ACD9EB"><b>Total Diskon</b></td>
                        <td width="90" align="right" bgcolor="#ACD9EB"><b>Harga Net</b></td>
                        <td width="90" align="right" bgcolor="#ACD9EB"><b>Harga Nett(Exc.)</b></td>
                        <td width="90" align="right" bgcolor="#ACD9EB"><b>Adjustment</b></td>
                        <td width="90" align="right" bgcolor="#ACD9EB"><b>Jumlah</b></td>
                    </tr>';
        if(!empty($d))
        {
            $no = 1;
            $sum_qty = 0;
                        $total_tagihan = 0;
            foreach($d as $v)
            {
                $diskon1 = '0%';
                $diskon2 = '0%';
                $diskon3 = '0%';
                $diskon4 = '0%';
                    $harga_net = $v->harga_supplier - $v->rp_total_diskon;
                    $harga_net_exc = $harga_net / 1.1;
                if($v->disk_amt_supp1 > 0) {
                    $diskon1 = number_format($v->disk_amt_supp1, 0,',','.');
                } else {
                    //$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                    $diskon1 = $v->disk_persen_supp1 . '%';
                }
                if($v->disk_amt_supp2 > 0) {
                    $diskon2 = number_format($v->disk_amt_supp2, 0,',','.');
                } else {
                    $diskon2 = $v->disk_persen_supp2 . '%';
                }
                if($v->disk_amt_supp3 > 0) {
                    $diskon3 = number_format($v->disk_amt_supp3, 0,',','.');

                } else {
                    $diskon3 = $v->disk_persen_supp3 . '%';
                }
                if($v->disk_amt_supp4 > 0) {
                    $diskon4 = number_format($v->disk_amt_supp4, 0,',','.');

                } else {
                    $diskon4 = $v->disk_persen_supp4 . '%';
                }
                $kd_produk_lama = empty($v->kd_produk_lama);

                $detail .= '<tr>
                                <td align="center" bgcolor="#f5f5f5">'.$no.'</td>
                                <td align="left" bgcolor="#f5f5f5">'.$v->kd_produk .'<br>('.$kd_produk_lama .')</td>
                                <td align="left" bgcolor="#f5f5f5">'.$v->nama_produk.'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->qty, 0,',','.').'</td>
                                <td align="center" bgcolor="#f5f5f5">'.$v->nm_satuan.'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->harga_supplier, 0,',','.').'</td>
                                <td align="center" bgcolor="#f5f5f5">'.$diskon1.'</td>
                                <td align="center" bgcolor="#f5f5f5">'.$diskon2.'</td>
                                <td align="center" bgcolor="#f5f5f5">'.$diskon3.'</td>
                                <td align="center" bgcolor="#f5f5f5">'.$diskon4.'</td>
                                <td align="center" bgcolor="#f5f5f5">'.number_format($v->disk_amt_supp5, 0,',','.').'</td>
                                <td align="center" bgcolor="#f5f5f5">'.number_format($v->rp_total_diskon, 0,',','.').'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($harga_net, 0,',','.').'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($harga_net_exc, 0,',','.') .'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_ajd_jumlah, 0,',','.') .'</td>
                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_jumlah, 0,',','.') .'</td>
                            </tr>
                ';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
                $total_tagihan = $total_tagihan + $v->rp_jumlah;
            }



            $detail .= '<tr>
                            <td height="28" colspan="2" align="center" bgcolor="#f5f5f5">&nbsp;</td>
                            <td align="right" bgcolor="#f5f5f5"><b>Total</b></td>
                            <td align="right" bgcolor="#f5f5f5"><b>'. number_format($sum_qty, 0,',','.') .'</b></td>
                            <td colspan="11" align="right" bgcolor="#f5f5f5"><b>Total</b></td>
                            <td align="right" bgcolor="#f5f5f5"><b>'.number_format($h->rp_jumlah, 0,',','.').'</b></td>
                            <td align="center" bgcolor="#f5f5f5"></td>
                        </tr>';


        }
        else
        {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

        if($h->tgl_invoice){
            $tgl_invoice = date('d-m-Y', strtotime($h->tgl_invoice));
        }

        if($h->tgl_faktur_pajak){
            $tgl_faktur_pajak = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
        }
        if($h->tgl_jth_tempo){
            $tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
        }
        if($h->tgl_terima_invoice){
            $tgl_terima_invoice = date('d-m-Y', strtotime($h->tgl_terima_invoice));
        }
        if($h->created_date){
            $created_date = date('d-m-Y', strtotime($h->created_date));
        }

        $header = '
            <table width="1000" border="0" cellspacing="1" cellpadding="5">
              <tr>
                <td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB"><b>'.$h->title.'</b></td>
              </tr>
              <tr>
                <td height="28" width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.Invoice</b></td>
                <td width="300" valign="middle" bgcolor="#f5f5f5">'.$h->no_invoice.'</td>
                <td align="right" valign="middle" bgcolor="#ACD9EB"><b>Dibuat Oleh</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$h->created_by.'</td>
              </tr>
              <tr>
                <td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Supplier</td>
                <td valign="middle" bgcolor="#f5f5f5">'.$h->nama_supplier.'</b></td>
                <td align="right" valign="middle" bgcolor="#ACD9EB"><b>TOP</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$h->top.' Hari</td>
              </tr>
              <tr>
                <td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>No Faktur Pajak</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$h->no_faktur_pajak.'</td>
                <td  width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Invoice</b></td>
                <td  width="300" valign="middle" bgcolor="#f5f5f5">'.$tgl_invoice.'</b></td>
              </tr>
              <tr>
                <td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Faktur Pajak</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$tgl_faktur_pajak.'</td>
                <td align="right" valign="middle" bgcolor="#ACD9EB"><b>Jatuh Tempo</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$tgl_jth_tempo.'</td>
              </tr>
              <tr>
                <td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.RO</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$v->no_do.'</td>
                <td align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Input</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$created_date.'</td>
              </tr>
              <tr>
                <td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>No Bukti Supplier</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$v->no_bukti_supplier.'</td>
                <td align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Terima</b></td>
                <td valign="middle" bgcolor="#f5f5f5">'.$tgl_terima_invoice.'</td>
              </tr>

            </table>
        ';

        $summary = '
            <table width="1000" border="0" cellspacing="1" cellpadding="5">
              <tr>
                <td colspan="3" align="left" valign="top">
                <td width="370" valign="middle"><table width="370" border="0" cellspacing="1" cellpadding="5">

                  <tr>
                    <td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Tagihan</b></td>
                    <td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">'.number_format($h->rp_jumlah, 0,',','.').'</td>
                    </tr>
                                 <tr>
                    <td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Diskon Tambahan</b></td>
                    <td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">'.number_format($h->rp_diskon, 0,',','.').'</td>
                    </tr>
                  <tr>
                    <td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>PPN</b></td>
                    <td valign="middle" align="right" bgcolor="#f5f5f5">'.number_format(10, 0,',','.').' %</td>
                    <td valign="middle" align="right" bgcolor="#f5f5f5">'.number_format($h->rp_ppn, 0,',','.').'</td>
                    </tr>
                                  <tr>
                    <td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Pembulatan </b></td>
                    <td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">'.number_format($h->rp_total - ($h->rp_jumlah - $h->rp_diskon + $h->rp_ppn), 0,',','.').'</td>
                    </tr>
                  <tr>
                  <tr>
                    <td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Invoice</b></td>
                    <td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">'.number_format($h->rp_jumlah + $h->rp_ppn, 0,',','.').'</td>
                    </tr>

                </table></td>
              </tr>
            </table>
        ';

        $html = '
        <table width="100%" border="0" cellspacing="5" cellpadding="1">
            <tr>
                <td>
                <table cellspacing="1" style="text-align:left" >
                    <tr>
                        <td colspan="2">' . $header . '</td>
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
        </table>';


         echo $html;
    }

    public function search_noinvoice() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->view_create_invoice_model->search_noinvoice($kd_peruntukan,$search, $start, $limit);


        echo $result;
    }

    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->view_retur_beli_model->search_produk($search, $start, $limit);


        echo $result;
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
       // $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $no_invoice = isset($_POST['no_invoice']) ? $this->db->escape_str($this->input->post('no_invoice', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $peruntukan_sup = isset($_POST['peruntukan_sup']) ? $this->db->escape_str($this->input->post('peruntukan_sup', TRUE)) : '';
        $peruntukan_dist = isset($_POST['peruntukan_dist']) ? $this->db->escape_str($this->input->post('peruntukan_dist', TRUE)) : '';

        if ($tglAwal) {
            $tglAwal = date('Y-m-d', strtotime($tglAwal));
        }
        if ($tglAkhir) {
            $tglAkhir = date('Y-m-d', strtotime($tglAkhir));
        }
        if($peruntukan_sup == 'true'){
            $peruntukan_sup = '0';
        }else {
            $peruntukan_sup = '';
        }
        if($peruntukan_dist == 'true'){
            $peruntukan_dist = '1';
        }else {
            $peruntukan_dist = '';
        }
        $result = $this->view_create_invoice_model->get_rows($peruntukan_sup,$peruntukan_dist,$tglAwal, $tglAkhir, $no_invoice, $kd_supplier, $search, $start, $limit);

        echo $result;
    }


}

?>
