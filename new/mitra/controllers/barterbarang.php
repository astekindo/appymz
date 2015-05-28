<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of barterbarang
 *
 * @author faroq
 */
class barterbarang extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('barterbarang_model','barter');
    }

    public function search_barang()
    {
        $kd_supplier    = $this->form_data('supplier');
        $no_po          = $this->form_data('no_po');
        $params = array(
            'kd_kategori1' => $this->form_data('kategori1'),
            'kd_kategori2' => $this->form_data('kategori2'),
            'kd_kategori3' => $this->form_data('kategori3'),
            'kd_kategori4' => $this->form_data('kategori4'),
            'kd_satuan'    => $this->form_data('satuan'),
            'kd_ukuran'    => $this->form_data('ukuran')
        );

        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('start', $this->config->item("length_records"));
        $search     = $this->form_data('start', null);
        if(empty($no_po)) {
            $this->print_result_json($this->barter->get_by_databarang($kd_supplier, $params, $search, $start, $limit), $this->test);
        } else {
            $this->print_result_json($this->barter->get_by_po($kd_supplier, $no_po, $search, $start, $limit), $this->test);
        }
    }

    /**
     * simpan data barter ke t_barang_barter dan t_barang_barter_detail
     */
    public function update_row()
    {
        header('Content-Type:application/json');

        $detail     = json_decode($this->input->post('data', TRUE));
        if (count($detail) == 0) $this->returnError("Proses gagal. Data kosong!");

        $tgl_barter = $this->form_data('tgl_barter',date('Y-m-d'));
        $kode       = 'TR' . date('Ym', strtotime($tgl_barter));
        $sequence   = $this->barter->get_kode_sequence($kode, 3);
        $no_transfer= $kode .'-'. $sequence;

        $no_po      = $this->input->post('no_po');
        $header     = array(
            'no_transfer_stok'  => $no_transfer,
            'tanggal'           => $tgl_barter,
            'keterangan'        => $this->form_data('keterangan'),
            'jenis_transfer'    => empty($no_po) ? 0 : 1,
            'created_by'        => $this->session->userdata('username'),
            'created_date'      => date('Y-m-d'),
            'no_po'             => $this->form_data('no_po',null),
            'kd_supplier'       => $this->form_data('kd_supplier'),
            'status'            => 0
        );

        $this->db->trans_start();
        $result = $this->barter->insert_header_data($header);
        if(!$result['success']) $this->returnError("Proses gagal. <br/>Gagal menyimpan data header barter!", true);

        foreach ($detail as $detail_temp) {
            $data[]         = array(
                'no_transfer_stok'   => $no_transfer,
                'kd_produk_awal'     => $detail_temp->kd_produk,
                'kd_produk_tujuan'   => $detail_temp->kd_produk_target,
                'qty'                => $detail_temp->qty
            );
            
            $result = $this->barter->insert_detail_data($data);
            if(!$result['success']) $this->returnError("Proses gagal. <br/>Gagal menyimpan data detail barter!", true);
        }

        $this->db->trans_complete();
        echo '{"success": true,"errMsg": "", "successMsg": "Data berhasil disimpan<br/> No. Dokumen: ' . $no_transfer . '"}';
    }

    public function approval_ops()
    {
        $this->approval($this->form_data('no_bukti'),1);
    }

    public function approval_buyer()
    {
        $this->approval($this->form_data('no_bukti'),2);
    }

    public function approval($no_transfer_stok, $status = 0)
    {
        $result = array('success' => false);
        if(!empty($no_transfer_stok) && intval($status) > 0) {
            $update = $this->barter->update_header_data($no_transfer_stok, array(
                'status'            => $status,
                'updated_by'        => $this->session->userdata('username'),
                'updated_date'      => date('Y-m-d'),
                'approve_buyer_by'  => $this->session->userdata('username'),
                'approve_buyer_date'=> date('Y-m-d')
            ));
            $result['success'] = $update['success'];
        }
        if($result['success']) {
            $result['successMsg'] = 'Update success';
        } else {
            $result['errMsg'] = 'Update failed';
        }
        echo json_encode($result);
    }

    public function save_pengantar()
    {
        $detail     = json_decode($this->input->post('data', TRUE));
        if (empty($detail)) $this->returnError("Proses gagal. Data kosong!");

        $this->db->trans_start();

        $tgl_barter = $this->form_data('tgl_barter',date('Y-m-d'));

        $kode       = 'SB' . date('Ymd', strtotime($tgl_barter));
        $sequence   = $this->barter->get_kode_sequence($kode, 3);

        $no_sb      = $kode .'-'. $sequence;
        $username   = $this->session->userdata('username');
        //simpan data surat_barter
        $header = array(
            'no_sb'             => $no_sb,
            'tanggal'           => $this->form_data('tgl_sb', $tgl_barter),
            'no_transfer_stok'  => $this->form_data('no_transfer_stok'),
            'kd_ekspedisi'      => $this->form_data('kd_ekspedisi'),
            'no_kendaraan'      => $this->form_data('no_kendaraan'),
            'sopir'             => $this->form_data('sopir'),
            'pic_penerima'      => $this->form_data('pic_terima'),
            'alamat_penerima'   => $this->form_data('alm_penerima'),
            'no_telp_penerima'  => $this->form_data('telp_terima'),
            'keterangan'        => $this->form_data('keterangan'),
            'created_by'        => $username,
            'created_date'      => $tgl_barter
        );
        $tmp_total = count($header);
        if($tmp_total < 12) $this->returnError("Proses gagal. Data tidak lengkap!", true);
        $proses = $this->barter->simpan_header_sb($header);
        if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan header Surat Barter!", true, $proses['lq']);

        $data = array();
        foreach ($detail as $data) {
            $lokasi     = substr($data->sub,0,2);
            $blok       = substr($data->sub,2,2);
            $subblok    = substr($data->sub,4,2);

            //simpan data transaksi di surat_barter_detail
            $proses = $this->barter->simpan_detail_sb(array(
                'no_sb'         => $no_sb,
                'kd_produk'     => $data->kd_produk,
                'qty'           => $data->qty,
                'kd_lokasi'     => $lokasi,
                'kd_blok'       => $blok,
                'kd_sub_blok'   => $subblok
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan detail Surat Barter!", true, $proses['lq']);

            //kurangi stok di brg_inventory
            $stok_lokasi= intval($data->qty_per_lokasi - $data->qty);
            $proses = $this->barter->update_stok_lokasi($data->kd_produk, $lokasi, $blok, $subblok, $stok_lokasi);
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan nilai Barter!", true, $proses['lq']);

            //simpan di trx_inventory kode: 8
            $proses = $this->barter->rekam_transaksi(array(
                'kd_produk'     => $data->kd_produk,
                'no_ref'        => $no_sb,
                'kd_lokasi'     => $lokasi,
                'kd_blok'       => $blok,
                'kd_sub_blok'   => $subblok,
                'qty_in'        => 0,
                'qty_out'       => $data->qty,
                'type'          => 8,
                'created_by'    => $username,
                'created_date'  => $tgl_barter,
                'tgl_trx'       => $this->form_data('tgl_sb', $tgl_barter)
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data transaksi!", true, $proses['lq']);

            $proses = $this->barter->update_header_data($this->form_data('no_transfer_stok'), array(
                'status' => 3
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data detail barter!", true, $proses['lq']);

            //update blok asal dan qty kirim di barter_barang_detail.
            $proses = $this->barter->update_detail_data($this->form_data('no_transfer_stok'), $data->kd_produk, array(
                'qty_kirim'         => $data->qty_kirim + $data->qty
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data detail barter!", true, $proses['lq']);
        }

        $this->db->trans_complete();
        echo json_encode(array(
            'success'   => true,
            'printUrl'  => site_url("barterbarang/print_form/" . $no_sb)
        ),true);
    }

    public function proses_penerimaan()
    {
        $detail     = json_decode($this->input->post('data', TRUE));
        if (empty($detail)) $this->returnError("Proses gagal. Data kosong!");

        $this->db->trans_start();

        $tanggal         = date('Y-m-d');
        $username        = $this->session->userdata('username');

        $tanggal_kembali = $this->form_data('tanggal_kembali',date('Y-m-d'));
        $no_sb           = $this->form_data('no_sb');
        $no_transfer_stok= $this->form_data('no_transfer_stok');
        $penerima        = $this->form_data('penerima');
        $is_kembali      = $this->form_data('is_kembali');
        $ket_pengembalian= $this->form_data('ket_pengembalian');

        //update kode status ke 4 (terima) di inv.t_barter_barang
        $proses = $this->barter->update_header_data($no_transfer_stok, $arrayName = array(
            'status'        => 4,
            'updated_by'    => $username,
            'updated_date'  => $tanggal

        ));
        if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal memperbarui status barter!", true, $proses['lq']);

        foreach($detail as $data) {
            $lokasi_t   = substr($data->sub_terima,0,2);
            $blok_t     = substr($data->sub_terima,2,2);
            $subblok_t  = substr($data->sub_terima,4,2);

            //update jumlah kembali, blok tujuan, dan keterangan di inv.t_surat_barter_detail
            $proses = $this->barter->update_detail_sb($no_sb, $data->kd_produk, array(
                'kd_lokasi_kembali'   => $lokasi_t,
                'kd_blok_kembali'     => $blok_t,
                'kd_sub_blok_kembali' => $subblok_t,
                'qty_kembali'         => intval($data->qty_kembali) + intval($data->qty_batal),
                'keterangan'          => $data->keterangan
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal memperbarui detail data penerimaan!", true, $proses['lq']);

            //update jumlah stok di inv.t_t_brg_inventory
            $jumlah_update = $data->qty_sub_terima + $data->qty_kembali;
            $proses = $this->barter->update_stok_lokasi($data->kd_produk_tujuan, $lokasi_t, $blok_t, $subblok_t, $jumlah_update);
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data barang di lokasi!", true, $proses['lq']);

            //simpan di trx_inventory kode: 8
            $proses = $this->barter->rekam_transaksi(array(
                'kd_produk'     => $data->kd_produk_tujuan,
                'no_ref'        => $no_sb,
                'kd_lokasi'     => $lokasi_t,
                'kd_blok'       => $blok_t,
                'kd_sub_blok'   => $subblok_t,
                'qty_in'        => $data->qty_kembali,
                'qty_out'       => 0,
                'type'          => 8,
                'created_by'    => $username,
                'created_date'  => $tanggal,
                'tgl_trx'       => $tanggal_kembali
            ));
            if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data transaksi barang!", true, $proses['lq']);

            //jika ada batal barter
            if(intval($data->qty_batal) > 0) {
                if(empty($data->sub_batal)) $this->returnError("Proses gagal. Sub blok pembatalan barter kosong!", true, $proses['lq']);

                $lokasi_b   = substr($data->sub_batal,0,2);
                $blok_b     = substr($data->sub_batal,2,2);
                $subblok_b  = substr($data->sub_batal,4,2);

                //update jumlah kirim di inv.t_barter_barang_detail
                $proses = $this->barter->update_detail_data($no_transfer_stok, $data->kd_produk, array(
                    'qty_kirim'     => intval($data->qty_kirim) - intval($data->qty_batal)
                ));
                if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal memperbarui jumlah kirim!", true, $proses['lq']);

                //update jumlah stok di inv.t_t_brg_inventory
                $jumlah_update = $data->qty_sub_batal + $data->qty_batal;
                $proses = $this->barter->update_stok_lokasi($data->kd_produk, $lokasi_b, $blok_b, $subblok_b, $jumlah_update);
                if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data barang di lokasi!", true, $proses['lq']);

                //simpan di trx_inventory kode: 8
                $proses = $this->barter->rekam_transaksi(array(
                    'kd_produk'     => $data->kd_produk,
                    'no_ref'        => $no_sb,
                    'kd_lokasi'     => $lokasi_b,
                    'kd_blok'       => $blok_b,
                    'kd_sub_blok'   => $subblok_b,
                    'qty_in'        => $data->qty_batal,
                    'qty_out'       => 0,
                    'type'          => 8,
                    'created_by'    => $username,
                    'created_date'  => $tanggal,
                    'tgl_trx'       => $tanggal_kembali
                ));
                if( empty($proses['success']) || !$proses['success'] ) $this->returnError("Proses gagal. Gagal menyimpan data transaksi barang!", true, $proses['lq']);
            }
        }

        $this->db->trans_complete();
        echo json_encode(array(
            'success'       => true,
            'successMsg'    => "Data berhasil disimpan"
        ),true);
    }

    public function print_form($no_sb) {
        $data = $this->barter->get_data_print($no_sb);
        //var_dump($data);exit;
        if (!$data) show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/InvBarterPrint.php');
        $pdf = new InvBarterPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_rows()
    {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        $status = $this->form_data('stat',0,true);
        $this->print_result_json($this->barter->get_rows($status, $search, $start, $limit), $this->test);
    }

    public function get_rows_approval()
    {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        $status = $this->form_data('stat','0,1',true);
        $this->print_result_json($this->barter->get_rows($status, $search, $start, $limit), $this->test);
    }

    public function get_rows_kirim()
    {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        // $status = $this->form_data('stat', '2,3', true);
        $this->print_result_json($this->barter->get_rows_kirim($search, $start, $limit), $this->test);
    }

    public function get_rows_kembali()
    {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        // $status = $this->form_data('stat', '3,4', true);
        $this->print_result_json($this->barter->get_rows_kembali($search, $start, $limit), $this->test);
    }

    public function get_rows_detail()
    {
        $no_bukti = $this->form_data('no_transfer_stok');
        $this->print_result_json($this->barter->get_rows_detail($no_bukti), $this->test);
    }

    public function get_rows_detail_kembali()
    {
        $no_bukti = $this->form_data('no_sb');
        $this->print_result_json($this->barter->get_rows_detail($no_bukti,true), $this->test);
    }

}

?>
