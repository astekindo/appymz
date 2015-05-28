<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View_mutasi extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mutasi_barang_model','mdl');
        $this->load->model('Monitoring_mutasi_model','monitoring');
    }

    public function search_produk() {
        $start = $this->form_data('start', 0);
        $limit = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        $result = $this->mdl->search_produk($search, $start, $limit);


        echo $result;
    }

    public function get_rows() {
        $start          = $this->form_data('start', 0);
        $limit          = $this->form_data('limit', $this->config->item("length_records"));
        $search         = $this->form_data('query');
        $no_mutasi      = $this->form_data('no_mutasi');
        $tgl_awal       = $this->form_data('tgl_awal');
        $tgl_akhir      = $this->form_data('tgl_akhir');
        $kd_produk      = $this->form_data('kd_produk');
        $lokasi_awal    = $this->form_data('lokasi_awal');
        $lokasi_tujuan  = $this->form_data('lokasi_tujuan');
        if ($tgl_awal) {
            $tgl_awal = date('Y-m-d', strtotime($tgl_awal));
        }
        if ($tgl_akhir) {
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
        }

        $result = $this->monitoring->get_rows( $search, $this->session->userdata('user_peruntukan'), $start, $limit, array(
            'no_mutasi'     => $no_mutasi,
            'kd_produk'     => $kd_produk,
            'tgl_awal'      => $tgl_awal,
            'tgl_akhir'     => $tgl_akhir,
            'lokasi_awal'   => $lokasi_awal,
            'lokasi_tujuan' => $lokasi_tujuan
        ) );
        $this->print_result_json($result, $this->test);
    }

    public function get_no_mutasi() {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');

        $this->print_result_json($this->monitoring->get_no_mutasi($search, $this->session->userdata('user_peruntukan'), $start, $limit, true), $this->test);
    }

    public function get_mb_pdf($no_mutasi) {
        $status = $this->mdl->get_status_mutasi($no_mutasi);
        if($status === 'in') {
            echo '{"success":true,"errMsg":"","printUrl":"' . site_url("mutasi_barang/print_form_mli/" . $no_mutasi) . '"}';
        } elseif($status === 'out') {
            echo '{"success":true,"errMsg":"","printUrl":"' . site_url("mutasi_barang/print_form_mlo/" . $no_mutasi) . '"}';
        } else {
            echo '{"success":true,"errMsg":"","printUrl":"' . site_url("mutasi_barang/print_form_mutasi/" . $no_mutasi) . '"}';
        }
    }

    public function get_data_mutasi($no_bukti){
        $data = $this->mdl->get_data_html($no_bukti);
        $h = $data['header'];
        $d = $data['detail'];
        $header = <<<EOT
            <table width="800" border="0">
              <tr class="header-data">
                <td width="131" class="txt-alignleft">No. Mutasi/ SO</td>
                <td width="300" class="txt-alignleft content-data">$h->no_mutasi_stok</td>
                <td width="131" class="txt-alignleft">Dibuat oleh</td>
                <td width="300" class="txt-alignleft content-data">$h->created_by</td>
              </tr>
              <tr class="header-data">
                <td class="txt-alignleft">Tanggal Mutasi</td>
                <td class="txt-alignleft content-data">$h->tgl_mutasi</td>
                <td class="txt-alignleft">Diambil Oleh</td>
                <td class="txt-alignleft content-data">$h->nama_pengambil</td>
              </tr>
              <tr class="header-data">
                <td class="txt-alignleft">No. Referensi</td>
                <td class="txt-alignleft content-data">$h->no_ref</td>
                <td class="txt-alignleft">Lokasi Awal</td>
                <td class="txt-alignleft content-data">$h->nama_lokasi_awal</td>
              </tr>
              <tr class="header-data">
                <td class="txt-alignleft">Status</td>
                <td class="txt-alignleft content-data">$h->status</td>
                <td class="txt-alignleft">Lokasi Tujuan</td>
                <td class="txt-alignleft content-data">$h->nama_lokasi_tujuan</td>
              </tr>
              <tr class="header-data">
                <td class="txt-alignleft">Keterangan</td>
                <td class="alamat content-data" colspan="3">$h->keterangan</td>
              </tr>
            </table>
EOT;
        $detail = '';
        if(!empty($d)) {
            $no = 1;
            $sum_qty = 0;
            foreach($d as $v) {
                $detail .= '
                        <tr class="content-data">
                            <td>'.$no.'</td>
                            <td class="txt-aligncenter">'.$v->kd_produk .'<br>('.$v->kd_produk_lama .')</td>
                            <td>'.$v->nama_produk.'</td>
                            <td class="txt-alignright">'.number_format($v->qty, 0,',','.').'</td>
                            <td class="txt-aligncenter">'.$v->nm_satuan.'</td>
                            <td class="txt-aligncenter">'.$v->lokasi_awal.'</td>
                            <td class="txt-aligncenter">'.$v->lokasi_tujuan.'</td>
                        </tr>';
                $no++;
                $sum_qty = $sum_qty + $v->qty;
            }
            $detail .= '
                        <tr class="summary-data">
                            <td colspan="3" class="txt-alignright">Total Tagihan</td>
                            <td class="txt-alignright">'. number_format($sum_qty, 0,',','.') .'</td>
                            <td colspan="3" class="gen-spacer">&nbsp;</td>
                        </tr>';
        } else {
            $detail .= '<tr><td colspan="8" align="center">-----</td></tr>';
        }

        $detail = <<<EOT
                    <table width="800"  border="0" >
                        <tr class="header-data">
                            <td width="30" class="txt-aligncenter">No.</td>
                            <td width="90" class="txt-aligncenter">Kode Barang</td>
                            <td width="200" class="txt-aligncenter">Nama Barang</td>
                            <td width="30" class="txt-aligncenter">Qty</td>
                            <td width="40" class="txt-aligncenter">Satuan</td>
                            <td width="50" class="txt-aligncenter">Lokasi awal</td>
                            <td width="50" class="txt-aligncenter">Lokasi tujuan</td>
                        </tr>
                        $detail
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
            <tr class="section-title"><td colspan="2">Mutasi Barang</td></tr>
            <tr>
                <td colspan="2">
                '. $header . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td colspan="2">
                '. $detail . '
                </td>
            </tr>
            <tr class="section-title"><td colspan="2" style="border-bottom: 1px solid #000;">&nbsp;</td></tr>
        </table>';
        echo $html;
    }

}

?>
