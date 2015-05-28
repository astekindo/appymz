<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order_detail extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
		$this->load->model('pembelian_receive_order_detail_model','prod_model');
    }
	
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
        $tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal',TRUE)) : '';
        $tgl_akhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir',TRUE)) : '';
        if($tgl_awal){
            $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        }
        if($tgl_akhir){
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->prod_model->get_rows($kd_supplier,$search, $tgl_awal, $tgl_akhir, $start, $limit);
        
        echo $result;
	}

	public function get_data_ro($no_ro){
		$result = $this->prod_model->get_data_ro($no_ro);

        $h = $result['header'];
        $d = $result['detail'];
        $header = <<<EOT
			<table width="1000" border="0">
			  <tr class="header-data">
				<td width="131" class="txt-alignleft">No. RO/ SO</td>
				<td width="300" class="txt-alignleft content-data">$h->no_ro</td>
				<td width="131" class="txt-alignleft">Tanggal Terima</td>
				<td width="300" class="txt-alignleft content-data">$h->tanggal_terima</td>
			  </tr>
			  <tr class="header-data">
				<td class="txt-alignleft">No Bukti Supplier</td>
				<td class="txt-alignleft content-data">$h->no_bukti_supplier</td>
				<td class="txt-alignleft">Tanggal Input</td>
				<td class="txt-alignleft content-data">$h->tanggal</td>
			  </tr>
			  <tr class="header-data">
				<td class="txt-alignleft">Nama Supplier</td>
				<td class="txt-alignleft content-data">$h->nama_supplier</td>
				<td rowspan="2" class="txt-alignleft">Diinput oleh</td>
				<td rowspan="2" class="txt-alignleft content-data">$h->created_by</td>
			  </tr>
			</table>
EOT;

        $detail = '';
        if(!empty($d)) {
            $no = 1;
            $sum_qty_beli = 0;
            $sum_qty_terima = 0;
            $sum_qty_sisa = 0;
            foreach($d as $v) {
                $detail .= '
                        <tr class="content-data">
                            <td>'.$no.'</td>
                            <td class="txt-aligncenter">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
                            <td class="txt-aligncenter">'.$v->kd_produk_supp .'</td>
                            <td>'.$v->nama_produk.'</td>
                            <td class="txt-aligncenter">'.$v->nm_satuan.'</td>
                            <td class="txt-alignright">'.number_format($v->qty_beli, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->qty_terima, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->sisa_terima, 0,',','.').'</td>
                            <td class="txt-alignright">'.$v->blok_terima.'</td>
                            <td class="txt-alignright">'.number_format($v->jml_stok, 0,',','.').'</td>
                            <td class="txt-alignright">'.$v->keterangan.'</td>
                        </tr>';
                $sum_qty_beli = $sum_qty_beli + $v->qty_beli;
                $sum_qty_terima = $sum_qty_terima + $v->qty_terima;
                $sum_qty_sisa = $sum_qty_sisa + $v->sisa_terima;
                $no++;
            }
            $detail .= '
						<tr class="summary-data">
							<td colspan="5" class="txt-alignright">Total</td>
							<td class="txt-alignright">'. number_format($sum_qty_beli, 0,',','.') .'</td>
							<td class="txt-alignright">'.number_format($sum_qty_terima, 0,',','.').'</td>
							<td class="txt-alignright">'.number_format($sum_qty_sisa, 0,',','.').'</td>
							<td colspan="3" class="gen-spacer">&nbsp;</td>
						</tr>';
        } else {
            $detail .= '<tr><td colspan="8" align="center">-----</td></tr>';
        }

        $detail = <<<EOT
                    <table width="1000"  border="0" >
                        <tr class="header-data">
                            <td width="20" class="txt-aligncenter">No.</td>
                            <td width="90" class="txt-aligncenter">Kode Barang</td>
                            <td width="100" class="txt-aligncenter">Kd Barang Supplier</td>
                            <td width="250" class="txt-aligncenter">Nama Barang</td>
                            <td width="40" class="txt-aligncenter">Satuan</td>
                            <td width="40" class="txt-aligncenter">Qty Beli</td>
                            <td width="40" class="txt-aligncenter">Qty Terima</td>
                            <td width="40" class="txt-aligncenter">Sisa</td>
                            <td width="60" class="txt-aligncenter">Lokasi Tujuan</td>
                            <td width="50" class="txt-aligncenter">Jumlah Stok OH</td>
                            <td width="100" class="txt-aligncenter">Keterangan</td>
                        </tr>
                        $detail
                    </table>
EOT;

        $html = <<<EOT
        <style>
        .cell-container td {padding: 5px 2px;}
        td.txt-alignleft {font-weight: bold;text-align: left;}
        table.tb-detail {border-spacing: 0;margin: 10px auto;border-top: 1px solid #111;}
        .tb-detail td {vertical-align: middle;border: none;padding: 0;margin: 0;}
        table.tb-detail table {border: none;border-collapse: collapse;}
        .tb-detail td td {padding: 5px;border: 1px solid #111;}
        .section-title td {height: 20px;padding: 0 10px;font-weight: bold;background: #C8E6F0;border-left: 1px solid #111;border-right: 1px solid #111;}
        .header-data td {background: #C8E6F0;}
        tr.summary-data {font-weight: bold;background: #def;}
        td.txt-alignright {text-align: right;}
        .txt-aligncenter {text-align: center;word-wrap: break-word;}
        .content-data, td.content-data {background: #f0f0f0;}
        .tb-detail .final-calc {padding: 5px;border-bottom: solid 1px #000;border-right: solid 1px #000;}
        .tb-detail .final-calc:first-child {border-left: solid 1px #000;border-right:none}
        </style>
        <table class="tb-detail">
            <tr class="section-title"><td colspan="2">Detail Receive Order</td></tr>
            <tr>
                <td colspan="2">$header</td>
            </tr>
            <tr class="section-title"><td colspan="2">Rincian Penerimaan Barang</td></tr>
            <tr>
                <td colspan="2">$detail</td>
            </tr>
        </table>
EOT;
        echo $html;
	}

}
