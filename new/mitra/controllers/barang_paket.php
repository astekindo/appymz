<?php
/**
 * Created by PhpStorm.
 * User: FIDZAL
 * Date: 5/19/14
 * Time: 8:06 PM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Barang_paket extends MY_Controller {

//    public $test = true;
    public $test = false;

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('master_barang_model', 'master_barang');
        $this->load->model('barang_paket_model', 'barang_paket');
    }

    public function get_rows() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->barang_paket->get_rows($search, $limit, $start),$this->test);
    }

    public function get_produk() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->barang_paket->get_produk($search, $limit, $start),$this->test);
    }

    public function get_produk_detail() {
        $kd_produk  = $this->form_data('kd_produk',null);
        $this->print_result_json($this->barang_paket->get_produk_detail($kd_produk),$this->test);
    }

    public function get_detail_paket() {
        $kd_produk   = $this->form_data('kd_produk',false);
        $this->print_result_json($this->barang_paket->get_detail_paket($kd_produk),$this->test);
    }

    public function get_produk_paket() {
        $kd_produk  = $this->form_data('kd_produk',null);
        $this->print_result_json($this->barang_paket->get_produk_paket($kd_produk),$this->test);
    }

    public function search_produk_paket(){
        $kd_produk  = $this->form_data('kd_produk',null);
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $this->print_result_json($this->barang_paket->search_produk_paket($kd_produk,$search,$limit,$start),$this->test);
    }

    public function update_row() {
//        echo json_encode($_POST);exit;
        $detail           = json_decode($_POST['detail']);
        $kd_produk_paket  = $this->form_data('kd_produk_paket');
        $tgl_dari         = $this->form_data('tgl_berlaku_dari');
        $tgl_sampai       = $this->form_data('tgl_berlaku_sampai');
        $jum_paket        = intval($this->form_data('jum_paket'));
        $qty_paket        = $this->form_data('qty_paket');
        $rp_total_paket   = $this->form_data('rp_total_paket');
        $created_by       = $this->session->userdata('username');
        $created_date     = date('Y-m-d H:i:s');
        $lokasi_paket     = $this->barang_paket->get_lokasi_default($kd_produk_paket, true);

        $pesan = array(
            'tanggal'           => 'Masa berlaku barang paket salah.',
            'jumlah_paket_0'    => 'Jumlah paket minimal 1',
            'qty_stok_kurang'   => 'Jumlah stok produk tidak cukup untuk pembuatan paket sebanyak ' . $jum_paket,
            'duplikat_save'     => 'Data paket produk sudah ada di daftar barang paket.',
            'duplikat_input'    => 'Kode produk ganda tidak diperbolehkan.<br> Gabungkan atau hapus duplikat.',
            'lokasi_default'    => 'Lokasi default untuk barang paket ini belum di set.',
            'db_error'          => 'Kesalahan saat menyimpan data.'
        );

        $hasil = array(
          'success'   => false,
          'errMsg'    => ''
        );

        if($jum_paket < 1) {
            $hasil['errMsg'] = $pesan['jumlah_paket_0'];
            echo json_encode($hasil);
            exit;
        }

        if(strtotime($tgl_sampai) - strtotime($tgl_dari) < 0) {
            $hasil['errMsg'] = $pesan['tanggal'];
            echo json_encode($hasil);
            exit;
        }

        if(!$lokasi_paket || $this->barang_paket->cek_lokasi_default($kd_produk_paket) == 0 ) {
            $hasil['errMsg'] = $pesan['lokasi_default'];
            echo json_encode($hasil);
            exit;
        }

        $kd_produk = array();
        foreach($detail as $barang) {
            $stok_oh = $this->barang_paket->get_stok_oh($barang->kd_produk);
            $total_barang = $barang->qty * $jum_paket;
            if($total_barang > $stok_oh) {
                $hasil['errMsg'] = $pesan['qty_stok_kurang'];
            }
            if($this->barang_paket->cek_duplikat($kd_produk_paket, $barang->kd_produk) > 0) {
                $hasil['errMsg'] = $pesan['duplikat_save'];
            }

            if($hasil['errMsg'] !== '') {
                echo json_encode($hasil);
                exit;
            }
            $kd_produk[] =  $barang->kd_produk;
        }

        if(count($kd_produk) !== count(array_unique($kd_produk))) {
            $hasil['errMsg'] = $pesan['duplikat_input'];
            echo json_encode($hasil);
            exit;
        }

        /* transaksi barang paket:
         * simpan jumlah barang per paket di t_produk_paket.
         * mutasi keluar di t_trx_inventory sejumlah total dari semua lokasi yang tersedia.
         * update t_brg_inventory u/ mengikuti transaksi
         * mutasi masuk di trx_inventory sejumlah total ke lokasi_default
         * update harga jual (BELUM)
         **/
        $kode           = 'MP' . date('Ymd');
        $sequence       = $this->barang_paket->get_kode_sequence($kode, 3);
        $no_mutasi_stok = $kode .'-'. $sequence;

        $no_hp      = 'HP' . date('Ymd') . '-';
        $sequence   = $this->barang_paket->get_kode_sequence($no_hp, 3);
        $no_hp      = $no_hp .'-'. $sequence;

        $this->db->trans_begin();

        //buat surat mutasi
        $mutasi_h = $this->barang_paket->save_mutasi(array(
          'no_mutasi_stok'    => $no_mutasi_stok,
          'tgl_mutasi'        => $created_date,
          'userid'            => $created_by,
          'keterangan'        => 'BARANG PAKET',
          'created_by'        => $created_by,
          'created_date'      => $created_date,
          'status'            => 1,
          'approval_out'      => $created_by,
          'approval_in'       => $created_by,
          'tgl_approval_out'  => $created_date,
          'tgl_approval_in'   => $created_date,
          'nama_pengambil'    => '',
          'revisi_ke'         => 0
        ));

        if(!$mutasi_h) {
            $hasil['success'] = false;
            $hasil['errMsg'] = $pesan['db_error'];
        } else {
            $hasil['success'] = true;
            foreach($detail as $barang) {
                //cari lokasi default
                $lokasi = $this->barang_paket->get_lokasi_default($barang->kd_produk);

                //isikan detail mutasi
                $mutasi_d = $this->barang_paket->save_mutasi_detail(array(
                  'no_mutasi_stok'        => $no_mutasi_stok,
                  'kd_produk'             => $barang->kd_produk,
                  'kd_lokasi_awal'        => $lokasi->kd_lokasi,
                  'kd_blok_awal'          => $lokasi->kd_blok,
                  'kd_sub_blok_awal'      => $lokasi->kd_sub_blok,
                  'kd_lokasi_tujuan'      => $lokasi_paket->kd_lokasi,
                  'kd_blok_tujuan'        => $lokasi_paket->kd_blok,
                  'kd_sub_blok_tujuan'    => $lokasi_paket->kd_sub_blok,
                  'qty'                   => $barang->qty * $jum_paket
                ));

                //kurangi stok di inv.t_brg_inventory
                $stok_lokasi = $this->barang_paket->get_stok_lokasi(
                  $barang->kd_produk,
                  $lokasi->kd_lokasi,
                  $lokasi->kd_blok,
                  $lokasi->kd_sub_blok
                );

                $stok_adj = intval($stok_lokasi) - ($barang->qty * $jum_paket);
//                $stok_adj = "intval($stok_lokasi) - ($barang->qty * $jum_paket)";

                $update_stok = $this->barang_paket->update_stok($stok_adj, array(
                  'kd_produk'     => $barang->kd_produk,
                  'kd_lokasi'     => $lokasi->kd_lokasi,
                  'kd_blok'       => $lokasi->kd_blok,
                  'kd_sub_blok'   => $lokasi->kd_sub_blok
                ));

                //simpan data transaksi di inv.t_trx_inventory
                $barang_out = $this->barang_paket->save_data_inventory(array(
                  'kd_produk'     => $barang->kd_produk,
                  'no_ref'        => $no_mutasi_stok,
                  'kd_lokasi'     => $lokasi->kd_lokasi,
                  'kd_blok'       => $lokasi->kd_blok,
                  'kd_sub_blok'   => $lokasi->kd_sub_blok,
                  'qty_in'        => 0,
                  'qty_out'       => $barang->qty * $jum_paket,
                  'type'          => 6,
                  'created_by'    => $created_by,
                  'created_date'  => $created_date
                ));

                //simpan data paket di mst.t_produk_paket
                $paket_d = $this->barang_paket->save_data_paket(array(
                  'kd_produk_paket'     => $kd_produk_paket,
                  'kd_produk'           => $barang->kd_produk,
                  'qty'                 => $barang->qty,
                  'tgl_berlaku_dari'    => $tgl_dari,
                  'tgl_berlaku_sampai'  => $tgl_sampai,
                  'rp_harga'            => $barang->rp_harga,
                  'rp_total'            => $barang->rp_total
                ));

                //jika gagal, batalkan transaksi
                if( !$mutasi_d || !$update_stok || !$barang_out || !$paket_d) {
                    $this->db->trans_rollback();
                    $hasil['success'] = false;
                    $hasil['errMsg'] = $pesan['db_error'];
                    break;
                }
            }

            //simpan data transaksi di inv.t_trx_inventory
            $barang_in = $this->barang_paket->save_data_inventory(array(
              'kd_produk'     => $kd_produk_paket,
              'no_ref'        => $no_mutasi_stok,
              'kd_lokasi'     => $lokasi_paket->kd_lokasi,
              'kd_blok'       => $lokasi_paket->kd_blok,
              'kd_sub_blok'   => $lokasi_paket->kd_sub_blok,
              'qty_in'        => $qty_paket,
              'qty_out'       => 0,
              'type'          => 6,
              'created_by'    => $created_by,
              'created_date'  => $created_date
            ));

            //simpan data harga paket di mst.t_produk
            $barang_paket = $this->barang_paket->update_barang_paket($kd_produk_paket,
              array(
                'rp_margin'         => 0,
                'rp_ongkos_kirim'   => 0,
                'rp_het_harga_beli' => 1.1 * $rp_total_paket,
                'rp_het_cogs'       => 1.1 * $rp_total_paket,
                'rp_cogs'           => $rp_total_paket
              )
            );

            //simpan data harga paket di mst.t_diskon_sales
            $barang_paket = $this->barang_paket->save_data_harga($kd_produk_paket,
              array(
                'hrg_beli_sup'        => $rp_total_paket,
                'rp_margin'           => 0,
                'rp_ongkos_kirim'     => 0,
                'rp_het_harga_beli'   => 1.1 * $rp_total_paket,
                'rp_jual_supermarket' => 1.1 * $rp_total_paket,
                'rp_het_cogs'         => 1.1 * $rp_total_paket,
                'rp_cogs'             => $rp_total_paket
              )
            );

            //simpan data harga beli di mst.t_supp_per_brg
            $top = $this->barang_paket->get_top_supp($this->form_data('kd_supplier'));
            $barang_paket = $this->barang_paket->save_data_harga_beli($kd_produk_paket, array(
                'kd_supplier'           => $this->form_data('kd_supplier'),
                'waktu_top'             => $top,
                'kd_produk'             => $kd_produk_paket,
                'hrg_supplier'          => 1.1 * $rp_total_paket, //+ PPN 10%
                'dpp'                   => $rp_total_paket,
                'created_by'            => $created_by,
                'created_date'          => $created_date,
                'net_hrg_supplier_sup_inc'  => 1.1 * $rp_total_paket, //+ PPN 10%
                'aktif'                 => 'true',
                'konsinyasi'            => '0',
                'net_hrg_supplier_sup'  => $rp_total_paket,
                'no_bukti'              => $no_hp,
                'keterangan'            => 'Pembentukan barang paket'
            ));

            //kurangi stok paket dari tabel inventory per lokasi
            $update_stok_paket = $this->barang_paket->update_stok($qty_paket, array(
              'kd_produk'     => $kd_produk_paket,
              'kd_lokasi'     => $lokasi_paket->kd_lokasi,
              'kd_blok'       => $lokasi_paket->kd_blok,
              'kd_sub_blok'   => $lokasi_paket->kd_sub_blok
            ));

            //jika gagal, batalkan transaksi
            if( !$barang_in || !$barang_paket|| !$update_stok_paket) {
                $this->db->trans_rollback();
                $hasil['success'] = false;
                $hasil['errMsg'] = $pesan['db_error'];
            }

        }

        if ($hasil['success']) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }
        echo json_encode($hasil);
    }

}

