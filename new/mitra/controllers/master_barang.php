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

        $kd_produk              = $this->form_data('kd_produk', FALSE);
        $koreksi_ke             = $this->form_data('koreksi_ke', FALSE);
        $kd_kategori1           = $this->form_data('nama_kategori1', FALSE);
        $kd_kategori2           = $this->form_data('nama_kategori2', FALSE);
        $kd_kategori3           = $this->form_data('nama_kategori3', FALSE);
        $kd_kategori4           = $this->form_data('nama_kategori4', FALSE);
        $thn_reg                = date('y');

        $nama_produk            = strtoupper($this->form_data('nama_produk', FALSE));
        $tonaliti               = $this->form_data('tonaliti', FALSE);
        $kd_produk_lama         = $this->form_data('kd_produk_lama', FALSE);
        $kd_produk_supp         = $this->form_data('kd_produk_supp', FALSE);
        $kd_peruntukkan         = $this->form_data('kd_peruntukkan', FALSE);

        $min_stok               = $this->form_data('min_stok', false);
        $max_stok               = $this->form_data('max_stok', false);
        $min_order              = $this->form_data('min_order', false);

        $kd_satuan              = $this->form_data('kd_satuan', false);
        $kd_ukuran              = $this->form_data('kd_ukuran', false);
        $is_konsinyasi          = $this->form_data('is_konsinyasi', 0);
        $aktif_purchase         = $this->form_data('aktif_purchase', false);
        $is_harga_lepas         = $this->form_data('is_harga_lepas', false);
        $ket_perubahan          = $this->form_data('ket_perubahan', false);
        $is_barang_paket        = $this->form_data('is_barang_paket', false);
        $nilai_berat            = $this->form_data('nilai_berat', false);
        $satuan_berat           = $this->form_data('kd_satuan_berat', false);
        $aktif                  = $this->form_data('aktif', false);

        $kd_lokasi              = $this->form_data('kd_lokasi', false);
        $kd_blok                = $this->form_data('kd_blok', false);
        $kd_sub_blok            = $this->form_data('kd_sub_blok', false);
        $flag_lokasi            = $this->form_data('flag_lokasi', false);

        if($flag_lokasi ='Supermarket'){
            $flag_lokasi ='S';
        }else if($flag_lokasi ='Gudang'){
            $flag_lokasi ='G';
        }


        $no_urut_diskon = $this->master_barang_model->get_kode_sequence('HJUAL', 3);
        $aktif = empty($aktif) ? 0 : 1;


        if($tonaliti !='' or $tonaliti != null){
            $tonaliti = strtoupper($tonaliti);
            $nama_produk_tonaliti = $nama_produk.'-'.$tonaliti;//Tonaliti Ada
            $kd_produk_tonaliti = $kd_produk.'-'.$tonaliti;

            $check_kode_produk = $this->master_barang_model->check_data('kd_produk', $kd_produk_tonaliti, 'mst.t_produk');

            if ($check_kode_produk && strlen($kd_produk) > 13){
                    $result = "{'success': false,'errMsg': 'Data dengan Kode Produk: $kd_produk_tonaliti Sudah Ada di dalam Database. Silahkan Input Ulang'}";
                    echo $result;
                    exit;
            }elseif ($check_kode_produk) {
                    $kd_produk = '';
                    $nama_produk = $nama_produk_tonaliti;
            }else{
                    $kd_produk = '';
                    $nama_produk = $nama_produk_tonaliti;
            }
        }


        $check_result = $this->master_barang_model->check_data('nama_produk', $nama_produk, 'mst.t_produk');

        if($tonaliti =='' or $tonaliti == null) {
            if ($kd_produk) {
                $field_result = $this->master_barang_model->get_data_field('nama_produk', 'kd_produk', $kd_produk, 'mst.t_produk');
                if ($field_result->nama_produk == $nama_produk) {
                    $check_result = FALSE;
                } else {
                    $nama_produk = str_replace('"', "''", $nama_produk);
                    $errMsg = "Data dengan Nama Produk: $nama_produk Sudah Ada di dalam Database. Silahkan Input Ulang";
                    $result = '{"success": false,"errMsg":"' . $errMsg . '"}';
                    echo $result;
                    exit;
                }
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
        }

        $success = 0;
        $this->db->trans_begin();

        if (!$kd_produk) { //save
            $no_urut_produk = $this->master_barang_model->get_kode_sequence("BRG" . $thn_reg . $kd_kategori1 . $kd_kategori2 . $kd_kategori3 . $kd_kategori4, 3);
            $no_urut = $this->master_barang_model->get_kode_sequence("NO_URUT", 6);
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            if($tonaliti !='' or $tonaliti != null){
                    $kode_produk = substr($kd_produk_tonaliti,0,13);
                    $result = $this->master_barang_model->get_barang_per_supplier($kode_produk);
                    foreach ($result as $data) {
                        $data['kd_produk']= $kd_produk_tonaliti;
                        $data['created_by']= $created_by;
                        $data['created_date']= $created_date;
                        $data['keterangan'] = $ket_perubahan;
                        if ($this->master_barang_model->insert_barang_per_supplier($data)){
                            $result = '{"success":true,"errMsg":""}';
                            $success = 1;
                        }
                    }
                $kd_produk = $kd_produk_tonaliti;
                $tonaliti = $tonaliti;

            }else{
                $kd_produk = $thn_reg . $kd_kategori1 . $kd_kategori2 . $kd_kategori3 . $kd_kategori4 . $no_urut_produk;
                $tonaliti = '';
            }

            $data = array(
                'kd_produk'             => $kd_produk,
                'kd_kategori1'          => $kd_kategori1,
                'kd_kategori2'          => $kd_kategori2,
                'kd_kategori3'          => $kd_kategori3,
                'kd_kategori4'          => $kd_kategori4,
                'thn_reg'               => $thn_reg,
                'no_urut'               => $no_urut,
                'no_urut_produk'        => $no_urut_produk,
                'nama_produk'           => $nama_produk,
                'kd_produk_lama'        => $kd_produk_lama,
                'kd_produk_supp'        => $kd_produk_supp,
                'kd_peruntukkan'        => $kd_peruntukkan,
                'kd_satuan'             => $kd_satuan,
                'kd_ukuran'             => $kd_ukuran,
                'is_konsinyasi'         => $is_konsinyasi,
                'tanggal'               => $created_date,
                'koreksi_ke'            => 0,
                'aktif'                 => $aktif,
                'aktif_purchase'        => $aktif_purchase,
                'is_harga_lepas'        => $is_harga_lepas,
                'ket_perubahan'         => $ket_perubahan,
                'is_barang_paket'       => $is_barang_paket,
                'created_by'            => $created_by,
                'created_date'          => $created_date,
                'min_stok'              => $min_stok,
                'max_stok'              => $max_stok,
                'min_order'             => $min_order,
                'tonality'              => $tonaliti,
                'nilai_berat'           => $nilai_berat,
                'kd_satuan_berat'       => $satuan_berat,
            );
            $data_lokasi = array(
                'kd_produk'         => $kd_produk,
                'kd_lokasi'         => $kd_lokasi,
                'kd_blok'           => $kd_blok,
                'kd_sub_blok'       => $kd_sub_blok,
                'kd_peruntukan'     => $kd_peruntukkan,
                'flag_default'      => 1,
                'flag_lokasi'       => $flag_lokasi,
            );

           $created_by = $this->session->userdata('username');
           $created_date = date('Y-m-d H:i:s');
           $kd_diskon_sales = date('Ym') . '-' . $no_urut_diskon;
           $data_diskon = array(
               'kd_produk'              => $kd_produk,
               'kd_diskon_sales'        => $kd_diskon_sales,
               'created_by'             => $created_by,
               'created_date'           => $created_date,
               'tanggal'                => $created_date,
               'disk_persen_kons1'      => 0,
               'disk_persen_kons2'      => 0,
               'disk_persen_kons3'      => 0,
               'disk_persen_kons4'      => 0,
               'disk_amt_kons1'         => 0,
               'disk_amt_kons2'         => 0,
               'disk_amt_kons3'         => 0,
               'disk_amt_kons4'         => 0,
               'disk_amt_kons5'         => 0,
               'disk_persen_member1'    => 0,
               'disk_persen_member2'    => 0,
               'disk_persen_member3'    => 0,
               'disk_persen_member4'    => 0,
               'disk_amt_member1'       => 0,
               'disk_amt_member2'       => 0,
               'disk_amt_member3'       => 0,
               'disk_amt_member4'       => 0,
               'disk_amt_member5'       => 0,
               'koreksi_ke'             => 0,
               'keterangan'             => $ket_perubahan
           );

            $hasil_data           = $this->master_barang_model->insert_row($data) ;
            $hasil_kd_produk      = $this->master_barang_model->insert_row_history($kd_produk, 0) ;
            $hasil_data_lokasi    = $this->master_barang_model->insert_lokasi_default($data_lokasi);
            $hasil_data_diskon    = $this->master_barang_model->insert_data_diskon_sales($data_diskon);

            if ($hasil_data && $hasil_kd_produk && $hasil_data_lokasi && $hasil_data_diskon) {
                $result = '{"success":true,"errMsg":""}';
                $success = 1;
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
                $this->db->trans_rollback();
                echo $result;
                echo json_encode($test);
                exit;
            }
        } else { //edit
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            if (!is_numeric($kd_satuan)) {
                $kd_satuan = $this->master_barang_model->get_kategori_by_name('mst.t_satuan', 'kd_satuan', 'nm_satuan', $kd_satuan);
            }
            if (!is_numeric($kd_ukuran)) {
                $kd_ukuran = $this->master_barang_model->get_kategori_by_name('mst.t_ukuran', 'kd_ukuran', 'nama_ukuran', $kd_ukuran);
            }

            $datau = array(
                'nama_produk'       => $nama_produk,
                'kd_produk_lama'    => $kd_produk_lama,
                'kd_produk_supp'    => $kd_produk_supp,
                'kd_peruntukkan'    => $kd_peruntukkan,
                'kd_satuan'         => $kd_satuan,
                'kd_ukuran'         => $kd_ukuran,
                'is_konsinyasi'     => $is_konsinyasi,
                'is_konsinyasi'     => $is_konsinyasi,
                'koreksi_ke'        => $koreksi_ke + 1,
                'aktif'             => $aktif,
                'aktif_purchase'    => $aktif_purchase,
                'is_harga_lepas'    => $is_harga_lepas,
                'ket_perubahan'     => $ket_perubahan,
                'is_barang_paket'   => $is_barang_paket,
                'tanggal'           => $updated_date,
                'nilai_berat'       => $nilai_berat,
                'kd_satuan_berat'   => $satuan_berat,
                'updated_by'        => $updated_by,
                'updated_date'      => $updated_date
            );
            //var_dump($datau);
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

    public function get_satuan_berat() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->master_barang_model->get_satuan_berat($search);
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
        header('Content-Type: application/json');
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

        if(!empty($result->hrg_beli_dist)) {
            $result->rp_margin_dist = ($result->pct_margin_dist * $result->hrg_beli_dist) / 100;
            $result->rp_het_harga_beli_dist = round($result->hrg_beli_dist + $result->rp_margin_dist + $result->rp_ongkos_kirim_dist, 3);
            $result->rp_het_harga_beli_dist_inc = round($result->rp_het_harga_beli_dist * 1.1, 3);
        } else {
            $result->rp_margin_dist = 0;
            $result->rp_het_harga_beli_dist = 0;
            $result->rp_het_harga_beli_dist_inc = 0;
        }
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

        header('Content-Type: application/json');
        echo json_encode(array(
            'success'   => true,
            'data'      => $result
        ));
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
        header('Content-Type: application/json');
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

    public function get_kategori() {
        $cmd = $this->form_data('cmd');
        $kd_kategori1 = $this->form_data('kd_kategori1');
        $kd_kategori2 = $this->form_data('kd_kategori2');
        $kd_kategori3 = $this->form_data('kd_kategori3');
        $kd_kategori4 = $this->form_data('kd_kategori4');
        if(!empty($kd_kategori4)) {
             $this->print_result_json($this->model->get_kategori4($cmd, $kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4), $this->test);
        } elseif(!empty($kd_kategori3)) {
             $this->print_result_json($this->model->get_kategori3($cmd, $kd_kategori1, $kd_kategori2, $kd_kategori3), $this->test);
        } elseif(!empty($kd_kategori2)) {
             $this->print_result_json($this->model->get_kategori2($cmd, $kd_kategori1, $kd_kategori2), $this->test);
        } elseif(!empty($kd_kategori1)) {
             $this->print_result_json($this->model->get_kategori1($cmd, $kd_kategori1), $this->test);
        } else {
            echo json_encode(array('success' => true, data => array()));
        }
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
        header('Content-Type: application/json');
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
