<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_retur_receive_order extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('cetak_retur_receive_order_model', 'crro_model');
        $this->load->model('pembelian_retur_model', 'pret_model');
        $this->load->model('view_retur_beli_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';

        $result = $this->crro_model->get_rows($kd_supplier, $search, $start, $limit);

        echo $result;
    }

    public function get_rows_detail($no_retur = '') {
        $hasil = $this->crro_model->get_rows_detail($no_retur);
        $results = array();
        foreach ($hasil as $result) {
            //hitung diskon
            $diskon = 0;
            $diskon1 = '0%';
            $diskon2 = '0%';
            $diskon3 = '0%';
            $diskon4 = '0%';
            if ($result->disk_amt_supp1 > 0) {
                $diskon1 = number_format($result->disk_amt_supp1, 0, ',', '.');
                $diskon_supp_hitung = $result->price_supp - $result->disk_amt_supp1;
            } else {
                //$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                $diskon1 = $result->disk_persen_supp1 . '%';
                $diskon_supp_hitung = $result->price_supp - (($result->disk_persen_supp1 * $result->price_supp) / 100);
            }
            if ($result->disk_amt_supp2 > 0) {
                $diskon2 = number_format($result->disk_amt_supp2, 0, ',', '.');
                $diskon_supp_hitung = $diskon_supp_hitung - $result->disk_amt_supp2;
            } else {
                $diskon2 = $result->disk_persen_supp2 . '%';
                $diskon_supp_hitung = $diskon_supp_hitung - (($result->disk_persen_supp2 * $diskon_supp_hitung) / 100);
            }
            if ($result->disk_amt_supp3 > 0) {
                $diskon3 = number_format($result->disk_amt_supp3, 0, ',', '.');
                $diskon_supp_hitung = $diskon_supp_hitung - $result->disk_amt_supp3;
            } else {
                $diskon3 = $result->disk_persen_supp3 . '%';
                $diskon_supp_hitung = $diskon_supp_hitung - (($result->disk_persen_supp3 * $diskon_supp_hitung) / 100);
            }
            if ($result->disk_amt_supp4 > 0) {
                $diskon4 = number_format($result->disk_amt_supp4, 0, ',', '.');
                $diskon_supp_hitung = $diskon_supp_hitung - $result->disk_amt_supp4;
            } else {
                $diskon4 = $result->disk_persen_supp4 . '%';
                $diskon_supp_hitung = $diskon_supp_hitung - (($result->disk_persen_supp4 * $diskon_supp_hitung) / 100);
            }
            $diskon5 = number_format($result->disk_amt_supp5, 0, ',', '.');
            $diskon_supp_hitung = $diskon_supp_hitung - $result->disk_amt_supp5;
            //diskon Rp
            $result->disk_grid_supp1 = $diskon1;
            $result->disk_grid_supp2 = $diskon2;
            $result->disk_grid_supp3 = $diskon3;
            $result->disk_grid_supp4 = $diskon4;
            $result->disk_grid_supp5 = $diskon5;


            $dpp_po = ($result->dpp_po) * $result->qty_terima;
            $rp_total_po = $dpp_po;
            $result->rp_diskon = $result->price_supp - $diskon_supp_hitung;
            $harga_net = $diskon_supp_hitung;
            $result->net_price = $harga_net;
            $harga_net_ect = $harga_net / 1.1;
            $result->harga_net_ect = $harga_net_ect;
            $result->rp_jumlah = $result->qty * $harga_net_ect;
            $result->dpp_po = $dpp_po;
            $result->rp_total_po = $rp_total_po;
            $results[] = $result;
            //print_r($results[]);
        }
        echo '{success:true,data:' . json_encode($results) . '}';
        //echo $result;
    }

    public function print_form($no_retur = '') {
        $data = $this->pret_model->get_data_print_ro($no_retur);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianReturROPrint.php');
        $pdf = new PembelianReturROPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_data_retur_ro($no_retur = '') {
        $data = $this->crro_model->get_data_html($no_retur);

        $h = $data['header'];
        $d = $data['detail'];
       //var_dump($d);
        $header = '
			<table width="1000" border="0" cellspacing="1" cellpadding="5">
				<tr>
					<td height="28" colspan="2" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>RETUR RECEIVE ORDER FORM</b></td>
				</tr>
				<tr>
					<td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Retur&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->no_retur . '</td>
                                        <td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. RO&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->no_do . '</td>
				</tr>
				<tr>
					<td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->tgl_retur . '</td>
                                        <td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal RO&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->tanggal . '</td>
				</tr>
				<tr>
					<td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Supplier&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->nama_supplier . '</td>
                                        <td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.PO&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->no_po . '</td>
				</tr>
				<tr>
					<td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>Remark&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->remark . '</td>
                                        <td height="28" width="50" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal PO&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->tanggal_po . '</td>
				</tr>
			</table><br>';

        echo $header;

        $detail = <<<EOT
			<table width="1000"  border="0" >
				<tr>
                    <td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
                    <td width="80" align="center" bgcolor="#ACD9EB"><b>Kode Produk (Kd. Produk Supplier)</b></td>
                    <td width="320" align="center" bgcolor="#ACD9EB"><b>Nama Produk</b></td>
                    <td width="80" align="center" bgcolor="#ACD9EB"><b>Lokasi</b></td>
                    <td width="60" align="center" bgcolor="#ACD9EB"><b>Qty Retur</b></td>
                    <td align="center" bgcolor="#ACD9EB"><b>Harga Supplier</b></td>
                    <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 1</b></td>
                    <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 2</b></td>
                    <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 3</b></td>
                    <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 4</b></td>
                    <td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 5</b></td>
                    <td align="center" bgcolor="#ACD9EB"><b>Harga Net</b></td>
                    <td align="center" bgcolor="#ACD9EB"><b>Harga Exc PPN</b></td>
                    <td align="center" bgcolor="#ACD9EB"><b>Jumlah</b></td>
				</tr>
EOT;

        echo $detail;

        $no = 1;
        $qty = 0;
        $total = 0;
        foreach ($d as $v) {
            if($h->pkp === '1'){
            $harga_exc_ppn = $v->net_price / 1.1;
            }else if($h->pkp === '0'){
            $harga_exc_ppn = $v->net_price;
            }
            $jumlah_diskon = $v->price_supp - $v->net_price;
            $v->disk_amt_supp1 > 0 ? $diskon1 = number_format($v->disk_amt_supp1, 0, ',', '.') : $diskon1 = $v->disk_persen_supp1 . '%';
            $v->disk_amt_supp2 > 0 ? $diskon2 = number_format($v->disk_amt_supp2, 0, ',', '.') : $diskon2 = $v->disk_persen_supp2 . '%';
            $v->disk_amt_supp3 > 0 ? $diskon3 = number_format($v->disk_amt_supp3, 0, ',', '.') : $diskon3 = $v->disk_persen_supp3 . '%';
            $v->disk_amt_supp4 > 0 ? $diskon4 = number_format($v->disk_amt_supp4, 0, ',', '.') : $diskon4 = $v->disk_persen_supp4 . '%';

            echo '<tr>
				<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#f5f5f5">' . $no . '</td>
				<td width="100" align="center" bgcolor="#f5f5f5">' . $v->kd_produk . '<br>( ' . $v->kd_produk_supp . ' )</td>
				<td width="320" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $v->nama_produk . '</td>
				<td width="300" align="center" bgcolor="#f5f5f5">' . $v->lokasi . '</td>
				<td width="60" align="center" bgcolor="#f5f5f5">' . $v->qty . '</td>
				<td width="100" align="center" bgcolor="#f5f5f5">' . number_format($v->price_supp, 0, ',', '.') . '</td>
                                <td align="center" bgcolor="#f5f5f5">' . $diskon1 . '</td>
				<td align="center" bgcolor="#f5f5f5">' . $diskon2 . '&nbsp;&nbsp;</td>
				<td align="center" bgcolor="#f5f5f5">' . $diskon3 . '</td>
				<td align="center" bgcolor="#f5f5f5">' . $diskon4 . '</td>
				<td align="center" bgcolor="#f5f5f5">' . number_format($v->disk_amt_supp5, 0, ',', '.') . '</td>
                                <td width="100" align="center" bgcolor="#f5f5f5">' . number_format($v->net_price, 0, ',', '.') . '</td>
                                <td width="100" align="center" bgcolor="#f5f5f5">' . number_format($harga_exc_ppn, 0, ',', '.') . '</td>
                                <td width="100" align="center" bgcolor="#f5f5f5">' . number_format($v->rp_total, 0, ',', '.') . '</td>
				</tr>';
            $qty = $qty + $v->qty;
            $total = $total + $v->rp_total;
            $no++;
        }

        echo '<tr>
                <td colspan="4" height="28" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
                <td align="center" bgcolor="#f5f5f5"><b>' . $qty . '</b></td>
                <td align="left" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"></td>
                <td align="center" bgcolor="#f5f5f5"><b>' . number_format($total, 0, ',', '.') . '</b></td>
            </tr>';
        echo '</table><br>';
        
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
}
