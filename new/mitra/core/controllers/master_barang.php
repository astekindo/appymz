<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_barang extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('master_barang_model');
        $this->load->model('setting_harga_jual_model');
        // $this->load->model('kategori2_model');
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

        $result = $this->master_barang_model->get_rows($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->master_barang_model->get_row($id);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        // $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : FALSE;
        $koreksi_ke = isset($_POST['koreksi_ke']) ? $this->db->escape_str($this->input->post('koreksi_ke', TRUE)) : FALSE;
        $kd_kategori1 = isset($_POST['nama_kategori1']) ? $this->db->escape_str($this->input->post('nama_kategori1', TRUE)) : FALSE;
        $kd_kategori2 = isset($_POST['nama_kategori2']) ? $this->db->escape_str($this->input->post('nama_kategori2', TRUE)) : FALSE;
        $kd_kategori3 = isset($_POST['nama_kategori3']) ? $this->db->escape_str($this->input->post('nama_kategori3', TRUE)) : FALSE;
        $kd_kategori4 = isset($_POST['nama_kategori4']) ? $this->db->escape_str($this->input->post('nama_kategori4', TRUE)) : FALSE;

        $thn_reg = date('y');

        $nama_produk = isset($_POST['nama_produk']) ? $this->db->escape_str($this->input->post('nama_produk', TRUE)) : FALSE;
        $nama_produk = strtoupper($nama_produk);
        $kd_produk_lama = isset($_POST['kd_produk_lama']) ? $this->db->escape_str($this->input->post('kd_produk_lama', TRUE)) : FALSE;
        $kd_produk_supp = isset($_POST['kd_produk_supp']) ? $this->db->escape_str($this->input->post('kd_produk_supp', TRUE)) : FALSE;
        $kd_peruntukkan = isset($_POST['kd_peruntukkan']) ? $this->db->escape_str($this->input->post('kd_peruntukkan', TRUE)) : FALSE;

        $hrg_beli_sup = isset($_POST['hrg_beli_sup']) ? $this->db->escape_str($this->input->post('hrg_beli_sup', TRUE)) : FALSE;
        $hrg_beli_dist = isset($_POST['hrg_beli_dist']) ? $this->db->escape_str($this->input->post('hrg_beli_dist', TRUE)) : FALSE;
        $min_stok = isset($_POST['min_stok']) ? $this->db->escape_str($this->input->post('min_stok', TRUE)) : FALSE;
        $max_stok = isset($_POST['max_stok']) ? $this->db->escape_str($this->input->post('max_stok', TRUE)) : FALSE;
        $min_order = isset($_POST['min_order']) ? $this->db->escape_str($this->input->post('min_order', TRUE)) : FALSE;

        $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan', TRUE)) : FALSE;
        $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran', TRUE)) : FALSE;
        $is_konsinyasi = isset($_POST['is_konsinyasi']) ? $this->db->escape_str($this->input->post('is_konsinyasi', TRUE)) : FALSE;
        $aktif_purchase = isset($_POST['aktif_purchase']) ? $this->db->escape_str($this->input->post('aktif_purchase', TRUE)) : FALSE;
        $is_harga_lepas = isset($_POST['is_harga_lepas']) ? $this->db->escape_str($this->input->post('is_harga_lepas', TRUE)) : FALSE;
        $ket_perubahan = isset($_POST['ket_perubahan']) ? $this->db->escape_str($this->input->post('ket_perubahan', TRUE)) : FALSE;
        $is_barang_paket = isset($_POST['is_barang_paket']) ? $this->db->escape_str($this->input->post('is_barang_paket', TRUE)) : FALSE;
        $jum_paket = isset($_POST['jum_paket']) ? $this->db->escape_str($this->input->post('jum_paket', TRUE)) : FALSE;

        $tgl_berlaku_promo1 = isset($_POST['tgl_berlaku_promo1']) ? $this->db->escape_str($this->input->post('tgl_berlaku_promo1', TRUE)) : FALSE;
        $tgl_berlaku_promo2 = isset($_POST['tgl_berlaku_promo1']) ? $this->db->escape_str($this->input->post('tgl_berlaku_promo1', TRUE)) : FALSE;


        $rp_jual_supermarket = isset($_POST['rp_jual_supermarket']) ? $this->db->escape_str($this->input->post('rp_jual_supermarket', TRUE)) : FALSE;
        $rp_jual_distribusi = isset($_POST['rp_jual_distribusi']) ? $this->db->escape_str($this->input->post('rp_jual_distribusi', TRUE)) : FALSE;
        $rp_het_harga_beli = isset($_POST['rp_het_harga_beli']) ? $this->db->escape_str($this->input->post('rp_het_harga_beli', TRUE)) : FALSE;
        $rp_ongkos_kirim = isset($_POST['rp_ongkos_kirim']) ? $this->db->escape_str($this->input->post('rp_ongkos_kirim', TRUE)) : FALSE;

        $kd_kategori1_bonus = isset($_POST['nama_kategori1_bonus']) ? $this->db->escape_str($this->input->post('nama_kategori1_bonus', TRUE)) : FALSE;
        $kd_kategori2_bonus = isset($_POST['nama_kategori2_bonus']) ? $this->db->escape_str($this->input->post('nama_kategori2_bonus', TRUE)) : FALSE;
        $kd_kategori3_bonus = isset($_POST['nama_kategori3_bonus']) ? $this->db->escape_str($this->input->post('nama_kategori3_bonus', TRUE)) : FALSE;
        $kd_kategori4_bonus = isset($_POST['nama_kategori4_bonus']) ? $this->db->escape_str($this->input->post('nama_kategori4_bonus', TRUE)) : FALSE;

        $kd_kategori1_member = isset($_POST['nama_kategori1_member']) ? $this->db->escape_str($this->input->post('nama_kategori1_member', TRUE)) : FALSE;
        $kd_kategori2_member = isset($_POST['nama_kategori2_member']) ? $this->db->escape_str($this->input->post('nama_kategori2_member', TRUE)) : FALSE;
        $kd_kategori3_member = isset($_POST['nama_kategori3_member']) ? $this->db->escape_str($this->input->post('nama_kategori3_member', TRUE)) : FALSE;
        $kd_kategori4_member = isset($_POST['nama_kategori4_member']) ? $this->db->escape_str($this->input->post('nama_kategori4_member', TRUE)) : FALSE;

        $margin_op = isset($_POST['margin_op']) ? $this->db->escape_str($this->input->post('margin_op', TRUE)) : FALSE;
        $pct_margin = isset($_POST['margin']) ? $this->db->escape_str($this->input->post('margin', TRUE)) : FALSE;
        $rp_margin = isset($_POST['rp_margin']) ? $this->db->escape_str($this->input->post('rp_margin', TRUE)) : FALSE;
        $rp_ongkos_kirim_dist = isset($_POST['rp_ongkos_kirim_dist']) ? $this->db->escape_str($this->input->post('rp_ongkos_kirim_dist', TRUE)) : FALSE;

        $margin_op_dist = isset($_POST['margin_op_dist']) ? $this->db->escape_str($this->input->post('margin_op_dist', TRUE)) : FALSE;
        $pct_margin_dist = isset($_POST['margin_dist']) ? $this->db->escape_str($this->input->post('margin_dist', TRUE)) : FALSE;
        $rp_margin_dist = isset($_POST['rp_margin_dist']) ? $this->db->escape_str($this->input->post('rp_margin_dist', TRUE)) : FALSE;
        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : FALSE;

        // if($margin_op === "persen"){
        // $pct_margin = $margin;
        // }else{
        // $pct_margin = ($rp_margin*100)/$hrg_beli_sup;
        // }
        // if($margin_op_dist === "persen"){
        // $pct_margin_dist = $margin_dist;
        // }else{
        // $pct_margin_dist = ($rp_margin_dist*100)/$hrg_beli_sup;
        // }
        $kd_diskon_sales = isset($_POST['kd_diskon_sales']) ? $this->db->escape_str($this->input->post('kd_diskon_sales', TRUE)) : FALSE;
        $qty_beli_bonus = isset($_POST['qty_beli_bonus']) ? $this->db->escape_str($this->input->post('qty_beli_bonus', TRUE)) : FALSE;
        $kd_produk_bonus = isset($_POST['kd_produk_bonus']) ? $this->db->escape_str($this->input->post('kd_produk_bonus', TRUE)) : FALSE;
        $qty_bonus = isset($_POST['qty_bonus']) ? $this->db->escape_str($this->input->post('qty_bonus', TRUE)) : FALSE;
        $is_bonus_kelipatan = isset($_POST['is_bonus_kelipatan']) ? $this->db->escape_str($this->input->post('is_bonus_kelipatan', TRUE)) : '0';
        $qty_beli_member = isset($_POST['qty_beli_member']) ? $this->db->escape_str($this->input->post('qty_beli_member', TRUE)) : FALSE;
        $kd_produk_member = isset($_POST['kd_produk_member']) ? $this->db->escape_str($this->input->post('kd_produk_member', TRUE)) : FALSE;
        $qty_member = isset($_POST['qty_member']) ? $this->db->escape_str($this->input->post('qty_member', TRUE)) : FALSE;
        $is_member_kelipatan = isset($_POST['is_member_kelipatan']) ? $this->db->escape_str($this->input->post('is_member_kelipatan', TRUE)) : FALSE;

        $tgl_berlaku_promo1 = isset($_POST['tgl_berlaku_promo1']) ? $this->db->escape_str($this->input->post('tgl_berlaku_promo1', TRUE)) : FALSE;
        $tgl_berlaku_promo2 = isset($_POST['tgl_berlaku_promo2']) ? $this->db->escape_str($this->input->post('tgl_berlaku_promo2', TRUE)) : FALSE;

        if ($qty_bonus > 0 || $qty_member > 0) {
            $is_bonus = 1;
        }else
            $is_bonus = 0;


        $disk_amt_kons5 = isset($_POST['disk_kons5']) ? $this->db->escape_str($this->input->post('disk_kons5', TRUE)) : FALSE;
        $disk_amt_member5 = isset($_POST['disk_memb5']) ? $this->db->escape_str($this->input->post('disk_memb5', TRUE)) : FALSE;

        $disk_kons1_op = isset($_POST['disk_kons1_op']) ? $this->db->escape_str($this->input->post('disk_kons1_op', TRUE)) : FALSE;
        $disk_kons2_op = isset($_POST['disk_kons2_op']) ? $this->db->escape_str($this->input->post('disk_kons2_op', TRUE)) : FALSE;
        $disk_kons3_op = isset($_POST['disk_kons3_op']) ? $this->db->escape_str($this->input->post('disk_kons3_op', TRUE)) : FALSE;
        $disk_kons4_op = isset($_POST['disk_kons4_op']) ? $this->db->escape_str($this->input->post('disk_kons4_op', TRUE)) : FALSE;
        $disk_kons1 = isset($_POST['disk_kons1']) ? $this->db->escape_str($this->input->post('disk_kons1', TRUE)) : FALSE;
        $disk_kons2 = isset($_POST['disk_kons2']) ? $this->db->escape_str($this->input->post('disk_kons2', TRUE)) : FALSE;
        $disk_kons3 = isset($_POST['disk_kons3']) ? $this->db->escape_str($this->input->post('disk_kons3', TRUE)) : FALSE;
        $disk_kons4 = isset($_POST['disk_kons4']) ? $this->db->escape_str($this->input->post('disk_kons4', TRUE)) : FALSE;
        if ($disk_kons1_op === "persen") {
            $disk_persen_kons1 = $disk_kons1;
            $disk_amt_kons1 = 0;
        } else {
            $disk_persen_kons1 = 0;
            $disk_amt_kons1 = $disk_kons1;
        }
        if ($disk_kons2_op === "persen") {
            $disk_persen_kons2 = $disk_kons2;
            $disk_amt_kons2 = 0;
        } else {
            $disk_persen_kons2 = 0;
            $disk_amt_kons2 = $disk_kons2;
        }
        if ($disk_kons3_op === "persen") {
            $disk_persen_kons3 = $disk_kons3;
            $disk_amt_kons3 = 0;
        } else {
            $disk_persen_kons3 = 0;
            $disk_amt_kons3 = $disk_kons3;
        }
        if ($disk_kons4_op === "persen") {
            $disk_persen_kons4 = $disk_kons4;
            $disk_amt_kons4 = 0;
        } else {
            $disk_persen_kons4 = 0;
            $disk_amt_kons4 = $disk_kons4;
        }

        $disk_memb1_op = isset($_POST['disk_memb1_op']) ? $this->db->escape_str($this->input->post('disk_memb1_op', TRUE)) : FALSE;
        $disk_memb2_op = isset($_POST['disk_memb2_op']) ? $this->db->escape_str($this->input->post('disk_memb2_op', TRUE)) : FALSE;
        $disk_memb3_op = isset($_POST['disk_memb3_op']) ? $this->db->escape_str($this->input->post('disk_memb3_op', TRUE)) : FALSE;
        $disk_memb4_op = isset($_POST['disk_memb4_op']) ? $this->db->escape_str($this->input->post('disk_memb4_op', TRUE)) : FALSE;
        $disk_memb1 = isset($_POST['disk_memb1']) ? $this->db->escape_str($this->input->post('disk_memb1', TRUE)) : FALSE;
        $disk_memb2 = isset($_POST['disk_memb2']) ? $this->db->escape_str($this->input->post('disk_memb2', TRUE)) : FALSE;
        $disk_memb3 = isset($_POST['disk_memb3']) ? $this->db->escape_str($this->input->post('disk_memb3', TRUE)) : FALSE;
        $disk_memb4 = isset($_POST['disk_memb4']) ? $this->db->escape_str($this->input->post('disk_memb4', TRUE)) : FALSE;
        if ($disk_memb1_op === "persen") {
            $disk_persen_member1 = $disk_memb1;
            $disk_amt_member1 = 0;
        } else {
            $disk_persen_member1 = 0;
            $disk_amt_member1 = $disk_memb1;
        }
        if ($disk_memb2_op === "persen") {
            $disk_persen_member2 = $disk_memb2;
            $disk_amt_member2 = 0;
        } else {
            $disk_persen_member2 = 0;
            $disk_amt_member2 = $disk_memb2;
        }
        if ($disk_memb3_op === "persen") {
            $disk_persen_member3 = $disk_memb3;
            $disk_amt_member3 = 0;
        } else {
            $disk_persen_member3 = 0;
            $disk_amt_member3 = $disk_memb3;
        }
        if ($disk_memb4_op === "persen") {
            $disk_persen_member4 = $disk_memb4;
            $disk_amt_member4 = 0;
        } else {
            $disk_persen_member4 = 0;
            $disk_amt_member4 = $disk_memb4;
        }

        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d H:i:s');

        $no_urut_diskon = $this->setting_harga_jual_model->get_kode_sequence('HJUAL', 3);
        if ($aktif == 0)
            $aktif = 0;
        else
            $aktif = 1;

        $check_result = $this->master_barang_model->check_data('nama_produk', $nama_produk, 'mst.t_produk');

        if ($kd_produk) {
            $field_result = $this->master_barang_model->get_data_field('nama_produk', 'kd_produk', $kd_produk, 'mst.t_produk');
            if ($field_result->nama_produk == $nama_produk) {
                $check_result = FALSE;
            }
        }

        if ($check_result) {
            $nama_produk = str_replace('"', "''", $nama_produk);
            $errMsg = "Data dengan Nama Produk: " . $nama_produk . " Sudah Ada di dalam Database. Silahkan Input Ulang";
            $result = '{"success":false,"errMsg":"' . $errMsg . '"}';
            echo $result;
            exit;
        }

        if ($kd_produk_lama != '-') {
            $kd_produk_lama_result = $this->master_barang_model->check_data('kd_produk_lama', $kd_produk_lama, 'mst.t_produk');

            if ($kd_produk) {
                $field_result = $this->master_barang_model->get_data_field('kd_produk_lama', 'kd_produk', $kd_produk, 'mst.t_produk');
                if ($field_result->kd_produk_lama == $kd_produk_lama) {
                    $kd_produk_lama_result = FALSE;
                }
            }

            if ($kd_produk_lama_result) {
                $errMsg = "Data dengan Kode Produk Lama: " . $kd_produk_lama . " Sudah Ada di dalam Database. Silahkan Input Ulang";
                $result = '{"success":false,"errMsg":"' . $errMsg . '"}';
                echo $result;
                exit;
            }
        }
        $success = 0;
        $this->db->trans_begin();

        if (!$kd_produk) { //save  
            $no_urut_produk = $this->master_barang_model->get_kode_sequence("BRG" . $thn_reg . $kd_kategori1 . $kd_kategori2 . $kd_kategori3 . $kd_kategori4, 3);
            $no_urut = $this->master_barang_model->get_kode_sequence("NO_URUT", 6);
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');
            $kd_produk = $thn_reg . $kd_kategori1 . $kd_kategori2 . $kd_kategori3 . $kd_kategori4 . $no_urut_produk;
            $data = array(
                'kd_produk' => $kd_produk,
                'kd_kategori1' => $kd_kategori1,
                'kd_kategori2' => $kd_kategori2,
                'kd_kategori3' => $kd_kategori3,
                'kd_kategori4' => $kd_kategori4,
                'thn_reg' => $thn_reg,
                'no_urut' => $no_urut,
                'no_urut_produk' => $no_urut_produk,
                'nama_produk' => $nama_produk,
                'kd_produk_lama' => $kd_produk_lama,
                'kd_produk_supp' => $kd_produk_supp,
                'kd_peruntukkan' => $kd_peruntukkan,
                'kd_satuan' => $kd_satuan,
                'kd_ukuran' => $kd_ukuran,
                'is_konsinyasi' => $is_konsinyasi,
                'hrg_beli_dist' => $hrg_beli_dist,
                'hrg_beli_sup' => $hrg_beli_sup,
                'rp_jual_supermarket' => $rp_jual_supermarket,
                'rp_jual_distribusi' => $rp_jual_distribusi,
                'rp_het_harga_beli' => $rp_het_harga_beli,
                'rp_ongkos_kirim' => $rp_ongkos_kirim,
                'rp_margin' => $rp_margin,
                'pct_margin' => $pct_margin,
                'rp_ongkos_kirim_dist' => $rp_ongkos_kirim_dist,
                'rp_margin_dist' => $rp_margin_dist,
                'pct_margin_dist' => $pct_margin_dist,
                'tanggal' => $created_date,
                'koreksi_ke' => 0,
                'aktif' => $aktif,
                'aktif_purchase' => $aktif_purchase,
                'is_harga_lepas' => $is_harga_lepas,
                'ket_perubahan' => $ket_perubahan,
                'is_barang_paket' => $is_barang_paket,
                'created_by' => $created_by,
                'created_date' => $created_date,
                'tgl_awal_promo' => $tgl_berlaku_promo1,
                'tgl_akhir_promo' => $tgl_berlaku_promo2,
            );
            if (!($is_barang_paket)) {
                unset($data['tgl_awal_promo']);
                unset($data['tgl_akhir_promo']);
            }
            if ($this->master_barang_model->insert_row($data)) {
                if ($this->master_barang_model->insert_row_history($kd_produk, 0)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                }
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                exit;
            }
        } else { //edit            
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');
            if (!is_numeric($kd_kategori1)) {
                $kd_kategori1 = $this->master_barang_model->get_kategori_by_name('mst.t_kategori1', 'kd_kategori1', 'nama_kategori1', $kd_kategori1);
            }
            if (!is_numeric($kd_kategori2)) {
                $kd_kategori2 = $this->master_barang_model->get_kategori_by_name('mst.t_kategori2', 'kd_kategori2', 'nama_kategori2', $kd_kategori2);
            }
            if (!is_numeric($kd_kategori3)) {
                $kd_kategori3 = $this->master_barang_model->get_kategori_by_name('mst.t_kategori3', 'kd_kategori3', 'nama_kategori3', $kd_kategori3);
            }
            if (!is_numeric($kd_kategori4)) {
                $kd_kategori4 = $this->master_barang_model->get_kategori_by_name('mst.t_kategori4', 'kd_kategori4', 'nama_kategori4', $kd_kategori4);
            }

            if (!is_numeric($kd_satuan)) {
                $kd_satuan = $this->master_barang_model->get_kategori_by_name('mst.t_satuan', 'kd_satuan', 'nm_satuan', $kd_satuan);
            }
            if (!is_numeric($kd_ukuran)) {
                $kd_ukuran = $this->master_barang_model->get_kategori_by_name('mst.t_ukuran', 'kd_ukuran', 'nama_ukuran', $kd_ukuran);
            }

            $datau = array(
                'nama_produk' => $nama_produk,
                'kd_produk_lama' => $kd_produk_lama,
                'kd_produk_supp' => $kd_produk_supp,
                'kd_peruntukkan' => $kd_peruntukkan,
                // KATEGORI TIDAK BOLEH DIUPDATE
                // 'kd_kategori1' => $kd_kategori1,
                // 'kd_kategori2' => $kd_kategori2,
                // 'kd_kategori3' => $kd_kategori3,
                // 'kd_kategori4' => $kd_kategori4,
                'kd_satuan' => $kd_satuan,
                'kd_ukuran' => $kd_ukuran,
                'is_konsinyasi' => $is_konsinyasi,
                // 'hrg_beli_sup' => $hrg_beli_sup,
                // 'hrg_beli_dist' => $hrg_beli_dist,
                // 'rp_jual_supermarket' => $rp_jual_supermarket,
                // 'rp_jual_distribusi' => $rp_jual_distribusi,
                // 'rp_het_harga_beli' => $rp_het_harga_beli,
                // 'rp_ongkos_kirim' => $rp_ongkos_kirim,
                // 'rp_margin' => $rp_margin,
                // 'pct_margin' => $pct_margin,
                // 'rp_ongkos_kirim_dist' => $rp_ongkos_kirim_dist,
                // 'rp_margin_dist' => $rp_margin_dist,
                // 'pct_margin_dist' => $pct_margin_dist,
                'is_konsinyasi' => $is_konsinyasi,
                'koreksi_ke' => $koreksi_ke + 1,
                'aktif' => $aktif,
                'aktif_purchase' => $aktif_purchase,
                'is_harga_lepas' => $is_harga_lepas,
                'ket_perubahan' => $ket_perubahan,
                'is_barang_paket' => $is_barang_paket,
                'tanggal' => $updated_date,
                'updated_by' => $updated_by,
                'updated_date' => $updated_date,
                'tgl_awal_promo' => $tgl_berlaku_promo1,
                'tgl_akhir_promo' => $tgl_berlaku_promo2,
            );

            if ($this->master_barang_model->update_row($kd_produk, $datau)) {
                if ($this->master_barang_model->insert_row_history($kd_produk, $koreksi_ke + 1)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                }
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                exit;
            }
        }

        if (!$kd_diskon_sales) { //save  
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');
            $kd_diskon_sales = date('Ym') . '-' . $no_urut_diskon;
            $data_diskon = array(
                'kd_produk' => $kd_produk,
                'kd_diskon_sales' => $kd_diskon_sales,
                'disk_persen_kons1' => $disk_persen_kons1,
                'disk_persen_kons2' => $disk_persen_kons2,
                'disk_persen_kons3' => $disk_persen_kons3,
                'disk_persen_kons4' => $disk_persen_kons4,
                'disk_amt_kons1' => $disk_amt_kons1,
                'disk_amt_kons2' => $disk_amt_kons2,
                'disk_amt_kons3' => $disk_amt_kons3,
                'disk_amt_kons4' => $disk_amt_kons4,
                'disk_amt_kons5' => $disk_amt_kons5,
                'disk_persen_member1' => $disk_persen_member1,
                'disk_persen_member2' => $disk_persen_member2,
                'disk_persen_member3' => $disk_persen_member3,
                'disk_persen_member4' => $disk_persen_member4,
                'disk_amt_member1' => $disk_amt_member1,
                'disk_amt_member2' => $disk_amt_member2,
                'disk_amt_member3' => $disk_amt_member3,
                'disk_amt_member4' => $disk_amt_member4,
                'disk_amt_member5' => $disk_amt_member5,
                'created_by' => $created_by,
                'created_date' => $created_date,
                'tanggal' => $created_date,
                'qty_beli_bonus' => $qty_beli_bonus,
                'kd_produk_bonus' => $kd_produk_bonus,
                'qty_bonus' => $qty_bonus,
                'is_bonus_kelipatan' => $is_bonus_kelipatan,
                'qty_beli_member' => $qty_beli_member,
                'kd_produk_member' => $kd_produk_member,
                'qty_member' => $qty_member,
                'is_member_kelipatan' => $is_member_kelipatan,
                'is_bonus' => $is_bonus,
                'koreksi_ke' => 0,
                'kd_kategori1_bonus' => $kd_kategori1_bonus,
                'kd_kategori2_bonus' => $kd_kategori2_bonus,
                'kd_kategori3_bonus' => $kd_kategori3_bonus,
                'kd_kategori4_bonus' => $kd_kategori4_bonus,
                'kd_kategori1_member' => $kd_kategori1_member,
                'kd_kategori2_member' => $kd_kategori2_member,
                'kd_kategori3_member' => $kd_kategori3_member,
                'kd_kategori4_member' => $kd_kategori4_member,
            );

            if ($this->setting_harga_jual_model->insert_row($data_diskon)) {
                if ($this->setting_harga_jual_model->insert_row_sales($kd_produk, $kd_diskon_sales, 0)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                }
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                exit;
            }
        } else { //edit            
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau_diskon = array(
                // 'disk_persen_kons1' => $disk_persen_kons1,
                // 'disk_persen_kons2' => $disk_persen_kons2,
                // 'disk_persen_kons3' => $disk_persen_kons3,
                // 'disk_persen_kons4' => $disk_persen_kons4,
                // 'disk_amt_kons1' => $disk_amt_kons1,
                // 'disk_amt_kons2' => $disk_amt_kons2,
                // 'disk_amt_kons3' => $disk_amt_kons3,
                // 'disk_amt_kons4' => $disk_amt_kons4,
                // 'disk_amt_kons5' => $disk_amt_kons5,
                // 'disk_persen_member1'=>	$disk_persen_member1,
                // 'disk_persen_member2'=>	$disk_persen_member2,
                // 'disk_persen_member3'=> $disk_persen_member3,
                // 'disk_persen_member4'=>	$disk_persen_member4,
                // 'disk_amt_member1'=>	$disk_amt_member1,
                // 'disk_amt_member2'=>	$disk_amt_member2,
                // 'disk_amt_member3'=> 	$disk_amt_member3,
                // 'disk_amt_member4'=>	$disk_amt_member4,
                // 'disk_amt_member5'=>	$disk_amt_member5,
                'updated_by' => $updated_by,
                'updated_date' => $updated_date,
                'qty_beli_bonus' => $qty_beli_bonus,
                'kd_produk_bonus' => $kd_produk_bonus,
                'qty_bonus' => $qty_bonus,
                'is_bonus_kelipatan' => $is_bonus_kelipatan,
                'qty_beli_member' => $qty_beli_member,
                'kd_produk_member' => $kd_produk_member,
                'qty_member' => $qty_member,
                'is_member_kelipatan' => $is_member_kelipatan,
                'is_bonus' => $is_bonus,
                'koreksi_ke' => $koreksi_ke + 1,
                'kd_kategori1_bonus' => $kd_kategori1_bonus,
                'kd_kategori2_bonus' => $kd_kategori2_bonus,
                'kd_kategori3_bonus' => $kd_kategori3_bonus,
                'kd_kategori4_bonus' => $kd_kategori4_bonus,
                'kd_kategori1_member' => $kd_kategori1_member,
                'kd_kategori2_member' => $kd_kategori2_member,
                'kd_kategori3_member' => $kd_kategori3_member,
                'kd_kategori4_member' => $kd_kategori4_member,
            );

            if ($this->setting_harga_jual_model->update_row($kd_produk, $datau_diskon)) {
                if ($this->setting_harga_jual_model->insert_row_sales($kd_produk, $kd_diskon_sales, $koreksi_ke + 1)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                }
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                exit;
            }
        }
        
        /**
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        foreach ($detail as $obj) {
            $where = array(
                'kd_produk' => $kd_produk,
                'kd_produk_paket' => $obj->kd_produk_paket
            );
            if ($this->master_barang_model->select_paket($where)) {
                unset($detail_mb);
                $detail_mb['qty'] = $obj->qty;
                $detail_mb['rp_harga'] = $obj->rp_harga;
                $detail_mb['rp_total'] = $obj->rp_total;

                if ($this->master_barang_model->update_paket($where, $detail_mb)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                    $this->db->trans_rollback();
                }
            } else {
                unset($detail_mb);
                $detail_mb['kd_produk'] = $kd_produk;
                $detail_mb['kd_produk_paket'] = $obj->kd_produk_paket;
                $detail_mb['qty'] = $obj->qty;
                $detail_mb['rp_harga'] = $obj->rp_harga;
                $detail_mb['rp_total'] = $obj->rp_total;

                if ($this->master_barang_model->insert_paket($detail_mb)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                    $this->db->trans_rollback();
                }
            }

            unset($trx_inv);
            $trx_inv['kd_produk'] = $obj->kd_produk_paket;
            $trx_inv['kd_lokasi'] = '10';
            $trx_inv['kd_blok'] = '09';
            $trx_inv['kd_sub_blok'] = '01';
            $trx_inv['no_ref'] = 'MB' . $obj->kd_produk_paket;
            $trx_inv['qty_in'] = 0;
            $trx_inv['qty_out'] = $jum_paket * $obj->qty;
            $trx_inv['type'] = '6';
            $trx_inv['created_by'] = $created_by;
            $trx_inv['created_date'] = $created_date;
            $trx_inv['tgl_trx'] = $created_date;
            if ($this->master_barang_model->insert_trx_inv($trx_inv)) {
                $result = '{"success":true,"errMsg":""}';
                $success = 1;
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                exit;
            }

            if ($this->master_barang_model->select_qty_oh($obj->kd_produk_paket, $jum_paket, $obj->qty)) {
                $result = '{"success":true,"errMsg":""}';
                $success = 1;
            } else {
                $result = '{"success":false,"errMsg":"Qty Item Barang Paket Tidak Mencukupi"}';
                echo $result;
                $this->db->trans_rollback();
                exit;
            }

            if ($this->master_barang_model->select_brg_inv($obj->kd_produk_paket)) {
                $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh - " . $jum_paket * $obj->qty . ",updated_by = '$updated_by', updated_date = '$updated_date' WHERE kd_produk = '" . $obj->kd_produk_paket . "'";
                if ($this->master_barang_model->query_update($sql)) {
                    $result = '{"success":true,"errMsg":""}';
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                    $this->db->trans_rollback();
                    $success = 1;
                }
            } else {
                unset($brg_inv);
                $brg_inv['kd_produk'] = $obj->kd_produk_paket;
                $brg_inv['kd_lokasi'] = '10';
                $brg_inv['kd_blok'] = '09';
                $brg_inv['kd_sub_blok'] = '01';
                $brg_inv['qty_oh'] = $jum_paket * $obj->qty;
                $brg_inv['created_by'] = $created_by;
                $brg_inv['created_date'] = $created_date;
                $brg_inv['is_bonus'] = 0;
                if ($this->master_barang_model->insert_brg_inv($brg_inv)) {
                    $result = '{"success":true,"errMsg":""}';
                    $success = 1;
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.."}';
                    $this->db->trans_rollback();
                }
            }
        }
        if ($this->master_barang_model->select_brg_inv($kd_produk)) {
            $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $jum_paket . ",updated_by = '$updated_by', updated_date = '$updated_date' WHERE kd_produk = '" . $obj->kd_produk_paket . "'";
            if ($this->master_barang_model->query_update($sql)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                $success = 1;
            }
        } else {
            unset($brg_inv);
            $brg_inv['kd_produk'] = $kd_produk;
            $brg_inv['kd_lokasi'] = '10';
            $brg_inv['kd_blok'] = '09';
            $brg_inv['kd_sub_blok'] = '01';
            $brg_inv['qty_oh'] = $jum_paket * $obj->qty;
            $brg_inv['created_by'] = $created_by;
            $brg_inv['created_date'] = $created_date;
            $brg_inv['is_bonus'] = 0;
            if ($this->master_barang_model->insert_brg_inv($brg_inv)) {
                $result = '{"success":true,"errMsg":""}';
                $success = 1;
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
            }
        }
        unset($trx_inv);
        $trx_inv['kd_produk'] = $kd_produk;
        $trx_inv['kd_lokasi'] = '10';
        $trx_inv['kd_blok'] = '09';
        $trx_inv['kd_sub_blok'] = '01';
        $trx_inv['no_ref'] = 'MB' . $kd_produk;
        $trx_inv['qty_in'] = $jum_paket;
        $trx_inv['qty_out'] = 0;
        $trx_inv['type'] = '6';
        $trx_inv['created_by'] = $created_by;
        $trx_inv['created_date'] = $created_date;
        $trx_inv['tgl_trx'] = $created_date;
        if ($this->master_barang_model->insert_trx_inv($trx_inv)) {
            $result = '{"success":true,"errMsg":""}';
            $success = 1;
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
            $this->db->trans_rollback();
        }
        **/
        
        if ($success == 1) {
            $this->db->trans_commit();
        } else if ($success == 0) {
            $this->db->trans_rollback();
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_rows() {
        $postdata = isset($_POST['postdata']) ? $this->input->post('postdata', TRUE) : array();

        if (count($postdata) > 0) {
            $records = explode(';', $this->input->post('postdata'));
            $i = 0;
            foreach ($records as $id) {
                if ($id != '') {

                    $this->db->trans_start();
                    if ($this->master_barang_model->delete_row($id)) {
                        $i++;
                    }
                    $this->db->trans_complete();
                }
            }
            if ($i > 0) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            echo $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : FALSE;

        if ($this->master_barang_model->delete_row($kd_produk)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_satuan() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->master_barang_model->get_satuan($search);
        echo $result;
    }

    public function get_ukuran() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->master_barang_model->get_ukuran($search);
        echo $result;
    }

    public function get_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->master_barang_model->get_produk($search, $start, $limit);
        echo $result;
    }

    public function get_row_kode_produk() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";
        $result = $this->master_barang_model->get_row_kode_produk($kd_produk);
        $top_result = $this->master_barang_model->get_waktu_top($kd_produk);
        $waktu_top = '';
        foreach ($top_result as $obj) {
            $waktu_top .= $obj->waktu_top . " / ";
        }
        $waktu_top = substr($waktu_top, 0, -2);
        $result->waktu_top = $waktu_top;

        $rp_jual_supermarket = $result->rp_jual_supermarket;
        $rp_jual_supermarket_net = 0;
        $rp_jual_supermarket_member_net = 0;

        if ($result->kd_peruntukkan == '0') {
            $result->mb_kd_peruntukkanS = TRUE;
            $result->mb_kd_peruntukkanD = FALSE;
        } else {
            $result->mb_kd_peruntukkanS = FALSE;
            $result->mb_kd_peruntukkanD = TRUE;
        }
        if ($result->kd_produk_lama == "") {
            $result->kd_produk_lama = '-';
        }
        if ($result->kd_produk_supp == "") {
            $result->kd_produk_supp = '-';
        }
        if ($result->disk_persen_kons1 != 0) {
            $result->disk_kons1_op = 'persen';
            $result->disk_kons1 = $result->disk_persen_kons1;
            $rp_jual_supermarket_net = $rp_jual_supermarket - ($rp_jual_supermarket * ($result->disk_persen_kons1 / 100));
        } else if ($result->disk_amt_kons1 != 0) {
            $result->disk_kons1_op = 'amount';
            $result->disk_kons1 = $result->disk_amt_kons1;
            $rp_jual_supermarket_net = $rp_jual_supermarket - $result->disk_amt_kons1;
        } else {
            $result->disk_kons1_op = 'persen';
            $result->disk_kons1 = 0;
            $rp_jual_supermarket_net = $rp_jual_supermarket;
        }

        if ($result->disk_persen_kons2 != 0 ) {
            $result->disk_kons2_op = 'persen';
            $result->disk_kons2 = $result->disk_persen_kons2;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - ($rp_jual_supermarket_net * ($result->disk_persen_kons2 / 100));
        } else if ($result->disk_amt_kons2 != 0) {
            $result->disk_kons2_op = 'amount';
            $result->disk_kons2 = $result->disk_amt_kons2;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - $result->disk_amt_kons2;
        } else {
            $result->disk_kons2_op = 'persen';
            $result->disk_kons2 = 0;
        }

        if ($result->disk_persen_kons3 != 0 ) {
            $result->disk_kons3_op = 'persen';
            $result->disk_kons3 = $result->disk_persen_kons3;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - ($rp_jual_supermarket_net * ($result->disk_persen_kons3 / 100));
        } else if ($result->disk_amt_kons3 != 0) {
            $result->disk_kons3_op = 'amount';
            $result->disk_kons3 = $result->disk_amt_kons3;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - $result->disk_amt_kons3;
        } else {
            $result->disk_kons3_op = 'persen';
            $result->disk_kons3 = 0;
        }

        if ($result->disk_persen_kons4 != 0) {
            $result->disk_kons4_op = 'persen';
            $result->disk_kons4 = $result->disk_persen_kons4;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - ($rp_jual_supermarket_net * ($result->disk_persen_kons4 / 100));
        } else if ($result->disk_amt_kons4 != 0) {
            $result->disk_kons4_op = 'amount';
            $result->disk_kons4 = $result->disk_amt_kons4;
            $rp_jual_supermarket_net = $rp_jual_supermarket_net - $result->disk_amt_kons4;
        } else {
            $result->disk_kons4_op = 'persen';
            $result->disk_kons4 = 0;
        }
        $rp_jual_supermarket_net = $rp_jual_supermarket_net - $result->disk_amt_kons5;
        $result->rp_jual_supermarket_net = $rp_jual_supermarket_net;

        if ($result->disk_persen_member1 != 0) {
            $result->disk_memb1_op = 'persen';
            $result->disk_memb1 = $result->disk_persen_member1;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket - ($rp_jual_supermarket * ($result->disk_persen_member1 / 100));
        } else if ($result->disk_amt_member1 != 0) {
            $result->disk_memb1_op = 'amount';
            $result->disk_memb1 = $result->disk_amt_member1;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket - $result->disk_amt_member1;
        } else {
            $result->disk_memb1_op = 'persen';
            $result->disk_memb1 = 0;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket;
        }

        if ($result->disk_persen_member2 != 0) {
            $result->disk_memb2_op = 'persen';
            $result->disk_memb2 = $result->disk_persen_member2;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket_member_net - ($rp_jual_supermarket_member_net * ($result->disk_persen_member2 / 100));
        } else if ($result->disk_amt_member2 != 0) {
            $result->disk_memb2_op = 'amount';
            $result->disk_memb2 = $result->disk_amt_member2;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket - $result->disk_amt_member2;
        } else {
            $result->disk_memb2_op = 'persen';
            $result->disk_memb2 = 0;
        }

        if ($result->disk_persen_member3 != 0) {
            $result->disk_memb3_op = 'persen';
            $result->disk_memb3 = $result->disk_persen_member3;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket_member_net - ($rp_jual_supermarket_member_net * ($result->disk_persen_member3 / 100));
        } else if ( $result->disk_amt_member3 != 0) {
            $result->disk_memb3_op = 'amount';
            $result->disk_memb3 = $result->disk_amt_member3;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket - $result->disk_amt_member3;
        } else {
            $result->disk_memb3_op = 'persen';
            $result->disk_memb3 = 0;
        }

        if ($result->disk_persen_member4 != 0 ) {
            $result->disk_memb4_op = 'persen';
            $result->disk_memb4 = $result->disk_persen_member4;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket_member_net - ($rp_jual_supermarket_member_net * ($result->disk_persen_member4 / 100));
        } else if ($result->disk_amt_member4 != 0) {
            $result->disk_memb4_op = 'amount';
            $result->disk_memb4 = $result->disk_amt_member4;
            $rp_jual_supermarket_member_net = $rp_jual_supermarket - $result->disk_amt_member4;
        } else {
            $result->disk_memb4_op = 'persen';
            $result->disk_memb4 = 0;
        }
        $rp_jual_supermarket_member_net = $rp_jual_supermarket_member_net - $result->disk_amt_kons5;
        $result->rp_jual_supermarket_member_net = $rp_jual_supermarket_member_net;

        $result->rp_margin = ($result->pct_margin * $result->hrg_beli_sup) / 100;

        if ($result->pkp_update === '0') {
            $result->net_hrg_supplier_sup_exc = $result->net_hrg_supplier_sup_inc;
        } else {
            $result->net_hrg_supplier_sup_exc = $result->net_hrg_supplier_sup_inc / 1.1;
        }
        $result->rp_het_harga_beli = round($result->net_hrg_supplier_sup_exc + $result->rp_margin + $result->rp_ongkos_kirim, 3);
        $result->rp_het_harga_beli_inc = round($result->rp_het_harga_beli * 1.1, 3);

        $result->rp_margin_dist = ($result->pct_margin_dist * $result->hrg_beli_dist) / 100;
        $result->rp_het_harga_beli_dist = round($result->hrg_beli_dist + $result->rp_margin_dist + $result->rp_ongkos_kirim_dist, 3);
        $result->rp_het_harga_beli_dist_inc = round($result->rp_het_harga_beli_dist * 1.1, 3);
        if (!$result->rp_cogs) {
            $result->rp_cogs = 0;
            $result->rp_het_cogs = 0;
            $result->rp_het_cogs_inc = 0;
        } else {
            $result->margin_cogs = $result->pct_margin;
            $result->rp_margin_cogs = $result->rp_cogs * ($result->pct_margin / 100);
            $result->rp_ongkos_kirim_cogs = $result->rp_ongkos_kirim;
            $result->rp_het_cogs = round($result->rp_cogs + $result->rp_margin_cogs + $result->rp_ongkos_kirim, 3);
            $result->rp_het_cogs_inc = round($result->rp_het_cogs * 1.1, 3);
        }

        echo '{success:true,data:' . json_encode($result) . '}';
    }

    public function get_row_history() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";
        $koreksi_ke = isset($_POST['koreksi_ke']) ? $this->db->escape_str($this->input->post('koreksi_ke', TRUE)) : "";

        $result = $this->master_barang_model->get_row_history($kd_produk, $koreksi_ke);

        if ($result->kd_peruntukkan == '0') {
            $result->mb_kd_peruntukkanS = TRUE;
            $result->mb_kd_peruntukkanD = FALSE;
        } else {
            $result->mb_kd_peruntukkanS = FALSE;
            $result->mb_kd_peruntukkanD = TRUE;
        }
        if ($result->kd_produk_lama == "") {
            $result->kd_produk_lama = '-';
        }
        if ($result->kd_produk_supp == "") {
            $result->kd_produk_supp = '-';
        }
        if ($result->disk_persen_kons1 != 0 AND $result->disk_amt_kons1 == 0) {
            $result->disk_kons1_op = 'persen';
            $result->disk_kons1 = $result->disk_persen_kons1;
        } else if ($result->disk_persen_kons1 == 0 AND $result->disk_amt_kons1 != 0) {
            $result->disk_kons1_op = 'amount';
            $result->disk_kons1 = $result->disk_amt_kons1;
        } else {
            $result->disk_kons1_op = 'persen';
            $result->disk_kons1 = 0;
        }

        if ($result->disk_persen_kons2 != 0 AND $result->disk_amt_kons2 == 0) {
            $result->disk_kons2_op = 'persen';
            $result->disk_kons2 = $result->disk_persen_kons2;
        } else if ($result->disk_persen_kons2 == 0 AND $result->disk_amt_kons2 != 0) {
            $result->disk_kons2_op = 'amount';
            $result->disk_kons2 = $result->disk_amt_kons2;
        } else {
            $result->disk_kons2_op = 'persen';
            $result->disk_kons2 = 0;
        }

        if ($result->disk_persen_kons3 != 0 AND $result->disk_amt_kons3 == 0) {
            $result->disk_kons3_op = 'persen';
            $result->disk_kons3 = $result->disk_persen_kons3;
        } else if ($result->disk_persen_kons3 == 0 AND $result->disk_amt_kons3 != 0) {
            $result->disk_kons3_op = 'amount';
            $result->disk_kons3 = $result->disk_amt_kons3;
        } else {
            $result->disk_kons3_op = 'persen';
            $result->disk_kons3 = 0;
        }

        if ($result->disk_persen_kons4 != 0 AND $result->disk_amt_kons4 == 0) {
            $result->disk_kons4_op = 'persen';
            $result->disk_kons4 = $result->disk_persen_kons4;
        } else if ($result->disk_persen_kons4 == 0 AND $result->disk_amt_kons4 != 0) {
            $result->disk_kons4_op = 'amount';
            $result->disk_kons4 = $result->disk_amt_kons4;
        } else {
            $result->disk_kons4_op = 'persen';
            $result->disk_kons4 = 0;
        }

        if ($result->disk_persen_member1 != 0 AND $result->disk_amt_member1 == 0) {
            $result->disk_memb1_op = 'persen';
            $result->disk_memb1 = $result->disk_persen_member1;
        } else if ($result->disk_persen_member1 == 0 AND $result->disk_amt_member1 != 0) {
            $result->disk_memb1_op = 'amount';
            $result->disk_memb1 = $result->disk_amt_member1;
        } else {
            $result->disk_memb1_op = 'persen';
            $result->disk_memb1 = 0;
        }

        if ($result->disk_persen_member2 != 0 AND $result->disk_amt_member2 == 0) {
            $result->disk_memb2_op = 'persen';
            $result->disk_memb2 = $result->disk_persen_member2;
        } else if ($result->disk_persen_member2 == 0 AND $result->disk_amt_member2 != 0) {
            $result->disk_memb2_op = 'amount';
            $result->disk_memb2 = $result->disk_amt_member2;
        } else {
            $result->disk_memb2_op = 'persen';
            $result->disk_memb2 = 0;
        }

        if ($result->disk_persen_member3 != 0 AND $result->disk_amt_member3 == 0) {
            $result->disk_memb3_op = 'persen';
            $result->disk_memb3 = $result->disk_persen_member3;
        } else if ($result->disk_persen_member3 == 0 AND $result->disk_amt_member3 != 0) {
            $result->disk_memb3_op = 'amount';
            $result->disk_memb3 = $result->disk_amt_member3;
        } else {
            $result->disk_memb3_op = 'persen';
            $result->disk_memb3 = 0;
        }

        if ($result->disk_persen_member4 != 0 AND $result->disk_amt_member4 == 0) {
            $result->disk_memb4_op = 'persen';
            $result->disk_memb4 = $result->disk_persen_member4;
        } else if ($result->disk_persen_member4 == 0 AND $result->disk_amt_member4 != 0) {
            $result->disk_memb4_op = 'amount';
            $result->disk_memb4 = $result->disk_amt_member4;
        } else {
            $result->disk_memb4_op = 'persen';
            $result->disk_memb4 = 0;
        }
        echo '{success:true,data:' . json_encode($result) . '}';
    }

    public function get_history() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";

        $result = $this->master_barang_model->get_history($kd_produk);

        echo $result;
    }

    public function get_history_cogs() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";

        $result = $this->master_barang_model->get_history_cogs($kd_produk);

        echo $result;
    }

    public function get_history_cogs_dist() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";

        $result = $this->master_barang_model->get_history_cogs_dist($kd_produk);

        echo $result;
    }

    public function get_history_inv() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";

        $result = $this->master_barang_model->get_history_inv($kd_produk);

        echo $result;
    }

    public function search_produk_paket($kd_produk = '') {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->master_barang_model->search_produk_paket($kd_produk, $search, $start, $limit);

        echo $result;
    }

    public function get_produk_paket() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";

        $hasil = $this->master_barang_model->get_produk_paket($kd_produk);
        $results = array();
        foreach ($hasil as $result) {
            if (count($result) > 0) {
                $result->is_barang_paket = '1';
            }else
                $result->is_barang_paket = '0';

            $results[] = $result;
        }

        echo '{success:true,data:' . json_encode($results) . '}';
    }
    
   public function get_ukuran_produk(){
		$result = $this->master_barang_model->get_ukuran_produk();
        
        echo $result;
	}
  public function get_satuan_produk(){
		$result = $this->master_barang_model->get_satuan_produk();
        
        echo $result;
	}

    public function get_kategori1() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {

            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->master_barang_model->get_kategori1('get', $id);

            return $result;
        } else {
            $result = $this->master_barang_model->get_kategori1();
            echo $result;
        }
    }

    public function get_kategori2($kd_kategori1 = '') {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1', TRUE)) : NULL;
            $result = $this->master_barang_model->get_kategori2('get', $id, $id1);

            return $result;
        } else {
            $result = $this->master_barang_model->get_kategori2('', $kd_kategori1);
            echo $result;
        }
    }

    public function get_kategori3($kd_kategori1 = '', $kd_kategori2 = '') {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1', TRUE)) : NULL;
            $id2 = isset($_POST['id2']) ? $this->db->escape_str($this->input->post('id2', TRUE)) : NULL;
            $result = $this->master_barang_model->get_kategori3('get', $id, $id1, $id2);

            return $result;
        } else {
            $result = $this->master_barang_model->get_kategori3('', $kd_kategori1, $kd_kategori2);
            echo $result;
        }
    }

    public function get_kategori4($kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '') {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1', TRUE)) : NULL;
            $id2 = isset($_POST['id2']) ? $this->db->escape_str($this->input->post('id2', TRUE)) : NULL;
            $id3 = isset($_POST['id3']) ? $this->db->escape_str($this->input->post('id3', TRUE)) : NULL;
            $result = $this->master_barang_model->get_kategori4('get', $id, $id1, $id2, $id3);

            return $result;
        } else {
            $result = $this->master_barang_model->get_kategori4('', $kd_kategori1, $kd_kategori2, $kd_kategori3);
            echo $result;
        }
    }

    public function get_parameter_margin() {
        $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1', TRUE)) : NULL;
        $kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2', TRUE)) : NULL;
        $kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3', TRUE)) : NULL;
        $kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4', TRUE)) : NULL;

        $result = $this->master_barang_model->get_parameter_margin($kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4);

        return $result;
    }

}
