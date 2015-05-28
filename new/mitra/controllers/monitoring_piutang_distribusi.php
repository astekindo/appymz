<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
    
class Monitoring_piutang_distribusi extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Monitoring_piutang_distribusi_model', 'mpdist_model');
    }
    public function search_pelanggan() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mpdist_model->search_pelanggan($search, $start, $limit);

        echo $result;
    }
    public function search_faktur() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mpdist_model->search_faktur($search, $start, $limit);

        echo $result;
    }
     public function search_no_bayar() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mpdist_model->search_no_bayar($search, $start, $limit);

        echo $result;
    }
    public function get_rows(){
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : 0;
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : '';
        $pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan',TRUE)) : '';
        $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';
        $tgl_min = isset($_POST['tgl_min']) ? $this->db->escape_str($this->input->post('tgl_min',TRUE)) : '';
        $tgl_max = isset($_POST['tgl_max']) ? $this->db->escape_str($this->input->post('tgl_max',TRUE)) : '';
        $no_bayar = isset($_POST['no_bayar']) ? $this->db->escape_str($this->input->post('no_bayar',TRUE)) : '';
        if($tgl_min){
			$tgl_min = date('Y-m-d', strtotime($tgl_min));
		}
		if($tgl_max){
			$tgl_max = date('Y-m-d', strtotime($tgl_max));
		}
        $result = $this->mpdist_model->get_rows($search, $no_faktur, $pelanggan, $status, $tgl_min, $tgl_max,$no_bayar, $start, $limit);
        echo $result;
       
	}
        
        public function get_data_faktur($no_faktur = ''){

        $data = $this->mpdist_model->get_data_per_faktur($no_faktur);

        $h = $data['header'];
        $d_jual = $data['detail_penjualan'];
        $d_bayar = $data['detail_pembayaran'];
        $d_retur = $data['detail_retur'];
        $rp_jual = 0;
        $rp_bayar = 0;
        $rp_retur = 0;
        unset($data);

        $header = <<<EOT
			<table width="1000" border="0">
			  <tr class="header-data">
				<td width="131" class="txt-alignleft">No. Faktur</td>
				<td width="300" class="txt-alignleft content-data">$h->no_faktur</td>
				<td width="131" class="txt-alignleft">Tanggal Faktur</td>
				<td width="300" class="txt-alignleft content-data">$h->tgl_faktur</td>
			  </tr>
			  <tr class="header-data">
				<td class="txt-alignleft">Nama Pelanggan</td>
				<td class="txt-alignleft content-data">$h->nama_pelanggan</td>
				<td class="txt-alignleft">No. Telp</td>
				<td class="txt-alignleft content-data">$h->no_telp</td>
			  </tr>
			  <tr class="header-data">
				<td class="txt-alignleft">Status Pembayaran</td>
				<td class="txt-alignleft content-data">$h->status</td>
				<td rowspan="2" class="txt-alignleft">Alamat</td>
				<td rowspan="2" class="alamat content-data">$h->alamat_kirim</td>
			  </tr>
			</table>
EOT;

        $detail_bayar = '';
        if(!empty($d_bayar)) {
            $no = 1;
            $sum_total = 0;
            foreach($d_bayar as $v) {
//                if(strpos($v->no_bukti, 'SOS') === 0) {
//                    $v->keterangan = 'BAYAR DI KASIR';
//                    if($v->nm_pembayaran == 'CASH' && $v->rp_bayar == 0) {
//                        $v->nm_pembayaran = 'PIUTANG';
//                        $v->keterangan = '';
//                    };
//                    if($v->nm_pembayaran == 'CASH RETUR' && $v->rp_bayar < 0) {
//                        $v->keterangan = 'RETUR KEMBALI UANG';
//                    };
//                };
                $detail_bayar .= '
                        <tr class="content-data">
                            <td class="txt-aligncenter">'.$no.'</td>
                            <td>'.$v->no_pembayaran_piutang.'</td>
                            <td>'.$v->tgl_bayar.'</td>
                            <td>'.$v->nm_pembayaran.'</td>
                            <td>'.$v->nomor_bank.'</td>
                            <td>'.$v->nomor_ref.'</td>
                            <td>'.$v->tgl_jth_tempo.'</td>
                            <td class="txt-alignright">'. number_format($v->rp_bayar, 0,',','.') .'</td>
                            <td>'.$v->keterangan.'</td>
                        </tr>';
                $no++;
                $sum_total = $sum_total + $v->rp_bayar;

            }
            $detail_bayar .= '
                        <tr class="summary-data">
							<td colspan="7" class="txt-alignright">Total Pembayaran</td>
							<td class="txt-alignright">'. number_format($sum_total, 0,',','.') .'</td>
							<td class="gen-spacer">&nbsp;</td>
						</tr>
                        <tr class="summary-data">
							<td colspan="7" class="txt-alignright">Rp. Kurang Bayar</td>
							<td class="txt-alignright">'. number_format($h->rp_kurang_bayar, 0,',','.') .'</td>
							<td class="gen-spacer">&nbsp;</td>
						</tr>';
            $rp_bayar = $sum_total;
        } else {
            $detail_bayar .= '<tr colspan="9" class="txt-aligncenter"><td>-----</td></tr>';
        }

        $detail_bayar = <<<EOT
                    <table width="1000"  border="0" >
                        <tr class="header-data">
                            <td class="txt-aligncenter" width="30">No.</td>
                            <td class="txt-aligncenter" width="90">No. Bukti Pembayaran</td>
                            <td class="txt-aligncenter" width="50">Tanggal</td>
                            <td class="txt-aligncenter" width="90">Jenis Pembayaran</td>
                            <td class="txt-aligncenter" width="50">Nama Bank</td>
                            <td class="txt-aligncenter" width="60">No Warkat</td>
                            <td class="txt-aligncenter" width="70">Tgl. Jatuh Tempo</td>
                            <td class="txt-aligncenter" width="90">Jumlah Bayar</td>
                            <td class="txt-aligncenter" width="200">Keterangan</td>
                        </tr>
                        $detail_bayar
                    </table>
EOT;

        $detail_jual = '';
        if(!empty($d_jual)) {
            $no = 1;
            $sum_qty = 0;
            $sum_qty_kirim = 0;
            $sum_nilai_kirim = 0;
            $sum_total = 0;
            foreach($d_jual as $v) {
                $nilai_kirim = ($v->rp_harga-$v->rp_diskon) * $v->qty_kirim;
                $detail_jual .= '
                        <tr class="content-data">
                            <td>'.$no.'</td>
                            <td class="txt-aligncenter">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
                            <td>'.$v->nama_produk.'</td>
                            <td class="txt-alignright">'.number_format($v->qty, 0,',','.').'</td>
                            <td class="txt-aligncenter">'.$v->nm_satuan.'</td>
                            <td class="txt-alignright">'.number_format($v->rp_harga_jual, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_diskon_satuan, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_total_diskon, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_harga_net, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_jumlah, 0,',','.').'</td>
                        </tr>';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
                $sum_nilai_kirim = $sum_nilai_kirim + $nilai_kirim;
                $sum_total = $sum_total + $v->rp_jumlah;
                //$sum_qty_kirim = $sum_qty_kirim + $v->qty_kirim;

            }
            $detail_jual .= '
						<tr class="summary-data">
							<td colspan="3" class="txt-alignright">Total Tagihan</td>
							<td class="txt-alignright">'. number_format($sum_qty, 0,',','.') .'</td>
							<td colspan="4" class="gen-spacer">&nbsp;</td>
							<td class="txt-alignright"></td>
							<td class="txt-alignright">'.number_format($sum_total, 0,',','.').'</td>
						</tr>
                                                <tr class="summary-data">
							<td colspan="3" class="txt-alignright">Uang Muka</td>
							<td class="txt-alignright"></td>
							<td colspan="4" class="gen-spacer">&nbsp;</td>
							<td class="txt-alignright"></td>
							<td class="txt-alignright">'.number_format($v->rp_uang_muka, 0,',','.').'</td>
						</tr>
                                                 <tr class="summary-data">
							<td colspan="3" class="txt-alignright">Cash Diskon</td>
							<td class="txt-alignright"></td>
							<td colspan="4" class="gen-spacer">&nbsp;</td>
							<td class="txt-alignright"></td>
							<td class="txt-alignright">'.number_format($v->cash_diskon, 0,',','.').'</td>
						</tr>
                                                <tr class="summary-data">
							<td colspan="3" class="txt-alignright">Rp Piutang</td>
							<td class="txt-alignright"></td>
							<td colspan="4" class="gen-spacer">&nbsp;</td>
							<td class="txt-alignright"></td>
							<td class="txt-alignright">'.number_format($sum_total - $v->rp_uang_muka - $v->cash_diskon, 0,',','.').'</td>
						</tr>';
            $rp_jual = $sum_total;
        } else {
            $detail_jual .= '<tr><td colspan="8" align="center">-----</td></tr>';
        }

        $detail_jual = <<<EOT
                    <table width="1000"  border="0" >
                        <tr class="header-data">
                            <td width="30" class="txt-aligncenter">No.</td>
                            <td width="90" class="txt-aligncenter">Kode Barang</td>
                            <td width="300" class="txt-aligncenter">Nama Barang</td>
                            <td width="30" class="txt-aligncenter">Qty</td>
                            <td width="40" class="txt-aligncenter">Satuan</td>
                            <td width="50" class="txt-aligncenter">Harga Barang</td>
                            <td width="40" class="txt-aligncenter">Diskon Satuan</td>
                            <td width="50" class="txt-aligncenter">Total Diskon</td>
                            <td width="70" class="txt-aligncenter">Harga Bayar</td>
                            <td width="30" class="txt-aligncenter">Jumlah</td>
                        </tr>
                        $detail_jual
                    </table>
EOT;

        $detail_retur = '';
        if(!empty($d_retur)) {
            $no = 1;
            $sum_qty = 0;
            $sum_total = 0;
            foreach($d_retur as $v) {
                $detail_retur .= '
                        <tr class="content-data">
                            <td>'.$no.'</td>
                            <td class="txt-aligncenter">'.$v->no_retur .'<br>('.$v->no_so .')</td>
                            <td class="txt-aligncenter">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
                            <td>'.$v->nama_produk.'</td>
                            <td class="txt-alignright">'.number_format($v->qty, 0,',','.').'</td>
                            <td class="txt-aligncenter">'.$v->nm_satuan.'</td>
                            <td class="txt-alignright">'.number_format($v->rp_jumlah, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_disk, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_potongan, 0,',','.').'</td>
                            <td class="txt-alignright">'.number_format($v->rp_total, 0,',','.').'</td>
                        </tr>';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
                $sum_total = $sum_total + $v->rp_total;

            }
            $detail_retur .= '
						<tr class="summary-data">
							<td colspan="4" class="txt-alignright">Total Retur</td>
							<td class="txt-alignright">'. number_format($sum_qty, 0,',','.') .'</td>
							<td colspan="4" class="gen-spacer">&nbsp;</td>
							<td class="txt-alignright">'.number_format($sum_total, 0,',','.').'</td>
                        </tr>';
            $rp_retur = $sum_total;
        } else {
            $detail_retur .= '<tr><td colspan="10" align="center">-----</td></tr>';
        }

        $detail_retur = <<<EOT
                    <table width="1000"  border="0" >
                        <tr class="header-data">
                            <td width="30" class="txt-aligncenter">No.</td>
                            <td width="100" class="txt-aligncenter">No RJ & SO</td>
                            <td width="90" class="txt-aligncenter">Kode Barang</td>
                            <td width="300" class="txt-aligncenter">Nama Barang</td>
                            <td width="30" class="txt-aligncenter">Qty</td>
                            <td width="40" class="txt-aligncenter">Satuan</td>
                            <td width="50" class="txt-aligncenter">Harga Barang</td>
                            <td width="40" class="txt-aligncenter">Diskon</td>
                            <td width="50" class="txt-aligncenter">Potongan Retur</td>
                            <td width="70" class="txt-aligncenter">Nilai Retur</td>
                        </tr>
                        $detail_retur
                    </table>
EOT;

        $html = '
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
            <tr class="section-title"><td colspan="2">Laporan Piutang</td></tr>
            <tr>
                <td colspan="2">
                '. $header . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2">Rincian Pembayaran</td></tr>
            <tr>
                <td colspan="2">
                '. $detail_bayar . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2">Rincian Penjualan</td></tr>
            <tr>
                <td colspan="2">
                '. $detail_jual . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2">Rincian Retur</td></tr>
            <tr>
                <td colspan="2">
                '. $detail_retur . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2" style="border-bottom: 1px solid #000;padding-top:10px;">Rincian Akhir</td></tr>
            <tr class="summary-data">
                <td class="final-calc txt-alignright" width="900">Total Penjualan</td>
                <td class="final-calc txt-alignright">'. number_format($rp_jual, 0,',','.') .'</td>
            </tr>
            <tr class="summary-data">
                <td class="final-calc txt-alignright">Total Retur</td>
                <td class="final-calc txt-alignright">'. number_format($rp_retur*-1, 0,',','.') .'</td>
            </tr>
            <tr class="summary-data">
                <td class="final-calc txt-alignright">Total Nett</td>
                <td class="final-calc txt-alignright">'. number_format($rp_jual-$rp_retur, 0,',','.') .'</td>
            </tr>
        </table>';
        echo $html;
    }
}
?>
