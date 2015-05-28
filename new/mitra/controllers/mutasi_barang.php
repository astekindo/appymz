<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mutasi_barang extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('mutasi_barang_model','mbm');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        echo json_encode(array(
            'success'        => true,
            'data'           => array(
                'tgl_mutasi'     => date('d-m-Y')
            )
        ));
    }

    public function get_form_out() {
        $no_mu = 'ML' . date('Ymd') . '-';
        $sequence = $this->mbm->get_kode_sequence($no_mu, 3);
        echo json_encode(array(
            'success'        => true,
            'data'           => array(
                'no_mutasi_stok' => $no_mu . $sequence,
                'tgl_mutasi'     => date('d-m-Y')
            )
        ));
    }

    public function get_form_in() {
        $start  = $this->form_data('start', 0);
        $limit  = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        $peruntukan = $this->session->userdata('user_peruntukan');

        $this->print_result_json($this->mbm->get_form_in($search, $peruntukan, $start, $limit), true);//$this->test);
    }

    public function get_form_in_detail() {
        $search = $this->form_data('query');
        $result = $this->mbm->get_form_in_detail($search);

        echo $result;
    }

    public function search_barang() {
        $start          = $this->form_data('start', 0);
        $limit          = $this->form_data('limit', $this->config->item("length_records"));
        $search         = $this->form_data('query');
        $datablok       = $this->form_data('datablok');
        $no_mutasi_stok = $this->form_data('no_mutasi_stok', null);
        $type           = $this->form_data('type',1);
        $lokasi         = substr($datablok, 0, 2);
        $blok           = substr($datablok, 2, 2);
        $subblok        = substr($datablok, 4, 2);
        $peruntukan     = intval($this->session->userdata('user_peruntukan'));

        $result = $this->mbm->select_inv_barang(
            $peruntukan, $search, $no_mutasi_stok,
            $lokasi, $blok, $subblok,
            $type, $start, $limit
        );

        $this->test = true;
        $this->print_result_json( $result, $this->test);
    }

    public function search_lokasi() {
        $sender     = $this->form_data('sender', null);
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $lokasi     = $this->form_data('lokasi');
        $peruntukan = $this->session->userdata('user_peruntukan');
        if(!empty($lokasi)) {
            $peruntukan = array('peruntukan' => $peruntukan, 'lokasi' => $lokasi);
        }

        $result = $this->mbm->search_lokasi($search, $peruntukan, $start, $limit);
        echo $result;
    }

    public function search_lokasi_out() {
        $sender     = $this->form_data('sender', null);
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $peruntukan = 2; //untuk tampilkan semua tujuan pengiriman

        $result = $this->mbm->search_lokasi($search, $peruntukan, $start, $limit);
        echo $result;
    }

    public function get_subblok() {
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $kd_lokasi  = $this->form_data('kd_lokasi');

        $result     = $this->mbm->get_subblok($kd_lokasi, $search, $start, $limit);

        echo $result;
    }

    public function get_subblok_out() {
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $kd_lokasi  = $this->form_data('kd_lokasi');

        $result = $this->mbm->get_subblok_out($search, $start, $limit);

        echo $result;
    }

    public function search_subbloktujuan() {
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $datablok   = $this->form_data('datablok');
        $kd_lokasi  = $this->form_data('kd_lokasi');
        $kd_produk  = $this->form_data('kd_produk');
        $peruntukan = $this->session->userdata('user_peruntukan');

        $this->print_result_json(
          $this->mbm->get_subbloktujuan($kd_produk, $kd_lokasi, $search, $start, $limit, $peruntukan),
//          true
          $this->test
        );
    }

    public function search_subbloktujuan_out() {
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $datablok   = $this->form_data('datablok');

        $result = $this->mbm->get_subbloktujuan_out($search, $start, $limit, $datablok);

        echo $result;
    }

    public function search_subbloktujuan_in() {
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $search     = $this->form_data('query');
        $datablok   = $this->form_data('datablok');
        $peruntukan = $this->session->userdata('user_peruntukan');

        $result = $this->mbm->get_subbloktujuan_in($search, $peruntukan,$start, $limit, $datablok);

        echo $result;
    }

    public function update_row() {
        $tgl_mutasi     = $this->form_data('tgl_mutasi');

        $kode           = 'MS' . date('Ymd', strtotime($tgl_mutasi));
        $sequence       = $this->mbm->get_kode_sequence($kode, 3);
        $no_mutasi_stok = $kode .'-'. $sequence;

        $keterangan     = $this->form_data('keterangan');
        $no_ref         = $this->form_data('no_ref');
        $nama_pengambil = $this->form_data('nama_pengambil');
        $detail         = array_key_exists('detail',$_POST) ? json_decode($this->input->post('detail',TRUE)) : array();

        $detail_result  = 0;
        $trx_result     = 0;
        $inv_result     = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tgl_mutasi) {
            $tgl_mutasi = date('Y-m-d', strtotime($tgl_mutasi));
        }

        $this->db->trans_start();
        $header_ms = null;
        unset($header_ms);
        $header_ms['no_mutasi_stok'] = $no_mutasi_stok;
        $header_ms['tgl_mutasi'] = $tgl_mutasi;
        $header_ms['keterangan'] = $keterangan;
        $header_ms['no_ref'] = $no_ref;
        $header_ms['nama_pengambil'] = $nama_pengambil;
        $header_ms['userid'] = $this->session->userdata('username');
        $header_ms['created_by'] = $this->session->userdata('username');
        $header_ms['created_date'] = date('Y-m-d H:i:s');
        $header_ms['updated_by'] = $this->session->userdata('username');
        $header_ms['updated_date'] = date('Y-m-d H:i:s');
        $header_ms['status'] = 1;
        $header_ms['approval_out'] = $this->session->userdata('username');
        $header_ms['approval_in'] = $this->session->userdata('username');
        $header_ms['tgl_approval_out'] = date('Y-m-d H:i:s');
        $header_ms['tgl_approval_in'] = date('Y-m-d H:i:s');

        $header_result = $this->mbm->insert_row('inv.t_mutasi_barang', $header_ms);

        if ($header_result) {
            foreach ($detail as $obj) {
                $detail_ms = null;
                $kd_lokasi = substr($obj->sub_asal, 0, 2);
                $kd_blok = substr($obj->sub_asal, 2, 2);
                $kd_sub_blok = substr($obj->sub_asal, 4, 2);

                $kd_lokasi_t = substr($obj->sub_tujuan, 0, 2);
                $kd_blok_t = substr($obj->sub_tujuan, 2, 2);
                $kd_sub_blok_t = substr($obj->sub_tujuan, 4, 2);

                if($obj->qty <= 0){
                    echo '{"success":false,"errMsg":"Qty harus lebih besar dari 0"}';
                    $this->db->trans_rollback();
                    exit;
                }

                $detail_ms['no_mutasi_stok'] = $no_mutasi_stok;
                $detail_ms['kd_produk'] = $obj->kd_produk;
                $detail_ms['kd_lokasi_awal'] = $kd_lokasi;
                $detail_ms['kd_blok_awal'] = $kd_blok;
                $detail_ms['kd_sub_blok_awal'] = $kd_sub_blok;
                $detail_ms['kd_lokasi_tujuan'] = $kd_lokasi_t;
                $detail_ms['kd_blok_tujuan'] = $kd_blok_t;
                $detail_ms['kd_sub_blok_tujuan'] = $kd_sub_blok_t;
                $detail_ms['qty'] = $obj->qty;

                $detail_result = $this->mbm->insert_row('inv.t_mutasi_barang_detail', $detail_ms);

                if ($detail_result) {
                    $trx = null;
                    unset($trx);
                    $trx['kd_produk'] = $obj->kd_produk;
                    $trx['no_ref'] = $no_mutasi_stok;
                    $trx['kd_lokasi'] = $kd_lokasi;
                    $trx['kd_blok'] = $kd_blok;
                    $trx['kd_sub_blok'] = $kd_sub_blok;
                    $trx['qty_in'] = 0;
                    $trx['qty_out'] = $obj->qty;
                    $trx['type'] = 6;
                    $trx['created_by'] = $this->session->userdata('username');
                    $trx['created_date'] = date('Y-m-d H:i:s');
                    $trx['tgl_trx'] = date('Y-m-d H:i:s');

                    $trx_result = $this->mbm->insert_row('inv.t_trx_inventory', $trx);

                    unset($trx['kd_lokasi']);
                    unset($trx['kd_blok']);
                    unset($trx['kd_sub_blok']);
                    unset($trx['qty_in']);
                    unset($trx['qty_out']);
                    $trx['qty_in'] = $obj->qty;
                    $trx['qty_out'] = 0;
                    $trx['kd_lokasi'] = $kd_lokasi_t;
                    $trx['kd_blok'] = $kd_blok_t;
                    $trx['kd_sub_blok'] = $kd_sub_blok_t;

                    $trx_result = $this->mbm->insert_row('inv.t_trx_inventory', $trx);

                    //out stok
                    $set = 'qty_oh';
                    $field = $obj->qty;
                    $where = array(
                        'kd_produk' => $obj->kd_produk,
                        'kd_lokasi' => $kd_lokasi,
                        'kd_blok' => $kd_blok,
                        'kd_sub_blok' => $kd_sub_blok
                    );

                    $is_found = $this->mbm->select_inventory($where);

                    if ($is_found) {
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh - " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi' AND kd_blok = '$kd_blok' AND kd_sub_blok = '$kd_sub_blok'";
                        if ($this->mbm->query_update($sql)) {
                            $inv_result++;
                        }
                    } else {
                        unset($inv);
                        $inv['kd_produk'] = $obj->kd_produk;
                        $inv['kd_lokasi'] = $kd_lokasi;
                        $inv['kd_blok'] = $kd_blok;
                        $inv['kd_sub_blok'] = $kd_sub_blok;
                        $inv['qty_oh'] = $obj->qty * -1;
                        $inv['created_by'] = $this->session->userdata('username');
                        $inv['created_date'] = date('Y-m-d H:i:s');

                        if ($this->mbm->insert_row('inv.t_brg_inventory', $inv)) {
                            $inv_result++;
                        }
                    }

                    //in stok
                    $set = 'qty_oh';
                    $field = $obj->qty;
                    $where = array(
                        'kd_produk' => $obj->kd_produk,
                        'kd_lokasi' => $kd_lokasi_t,
                        'kd_blok' => $kd_blok_t,
                        'kd_sub_blok' => $kd_sub_blok_t
                    );

                    $is_found = $this->mbm->select_inventory($where);
                    if ($is_found) {
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi_t' AND kd_blok = '$kd_blok_t' AND kd_sub_blok = '$kd_sub_blok_t'";
                        if ($this->mbm->query_update($sql)) {
                            $inv_result++;
                        }
                    } else {
                        unset($inv);
                        $inv['kd_produk'] = $obj->kd_produk;
                        $inv['kd_lokasi'] = $kd_lokasi_t;
                        $inv['kd_blok'] = $kd_blok_t;
                        $inv['kd_sub_blok'] = $kd_sub_blok_t;
                        $inv['qty_oh'] = $obj->qty;
                        $inv['created_by'] = $this->session->userdata('username');
                        $inv['created_date'] = date('Y-m-d H:i:s');

                        if ($this->mbm->insert_row('inv.t_brg_inventory', $inv)) {
                            $inv_result++;
                        }
                    }

                }
            }
        }

        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0 && $trx_result > 0 && $inv_result > 0) {
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("mutasi_barang/print_form_mutasi/" . $no_mutasi_stok) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function update_row_out() {

        $kode = 'ML' . substr($_POST['tgl_mutasi'], -4) . substr($_POST['tgl_mutasi'], 3, 2) . substr($_POST['tgl_mutasi'], 0, 2) . '-';
        $sequence = $this->mbm->get_kode_sequence($kode, 3);
        $no_mutasi_stok = $kode . $sequence;

        $tgl_mutasi = $this->form_data('tgl_mutasi');
        $keterangan = $this->form_data('keterangan');
        $no_ref = $this->form_data('no_ref');
        $nama_pengambil = $this->form_data('nama_pengambil');
        $detail = json_decode( $this->input->post('detail',TRUE) );
        $kd_lokasi_tujuan = $this->form_data('kd_lokasi_tujuan');

        $header_result = 0;
        $detail_result = 0;
        $trx_result = 0;
        $inv_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tgl_mutasi) {
            $tgl_mutasi = date('Y-m-d', strtotime($tgl_mutasi));
        }

        $this->db->trans_start();
        $header_ms = array(
            'no_mutasi_stok'    => $no_mutasi_stok,
            'tgl_mutasi'        => $tgl_mutasi,
            'keterangan'        => $keterangan,
            'no_ref'            => $no_ref,
            'userid'            => $this->session->userdata('username'),
            'created_by'        => $this->session->userdata('username'),
            'created_date'      => date('Y-m-d H:i:s'),
            'updated_by'        => $this->session->userdata('username'),
            'updated_date'      => date('Y-m-d H:i:s'),
            'status'            => 0,
            'approval_out'      => $this->session->userdata('username'),
            'tgl_approval_out'  => date('Y-m-d H:i:s'),
            'nama_pengambil'    => $nama_pengambil,
            'tujuan'            => $this->mbm->get_kd_peruntukan($kd_lokasi_tujuan)
        );
        if ($this->mbm->insert_row('inv.t_mutasi_barang', $header_ms)) {
            $header_result++;
            if ($header_result > 0) {
                foreach ($detail as $obj) {
                    unset($detail_ms);
                    $kd_lokasi = substr($obj->sub_asal, 0, 2);
                    $kd_blok = substr($obj->sub_asal, 2, 2);
                    $kd_sub_blok = substr($obj->sub_asal, 4, 2);

                    if($obj->qty <= 0){
                        echo '{"success":false,"errMsg":"Qty harus lebih besar dari 0"}';
                        $this->db->trans_rollback();
                        exit;
                    }

                    $detail_ms['no_mutasi_stok'] = $no_mutasi_stok;
                    $detail_ms['kd_produk'] = $obj->kd_produk;
                    $detail_ms['kd_lokasi_awal'] = $kd_lokasi;
                    $detail_ms['kd_blok_awal'] = $kd_blok;
                    $detail_ms['kd_sub_blok_awal'] = $kd_sub_blok;
                    $detail_ms['kd_lokasi_tujuan'] = $kd_lokasi_tujuan;
                    $detail_ms['kd_blok_tujuan'] = 0;
                    $detail_ms['kd_sub_blok_tujuan'] = 0;
                    $detail_ms['qty'] = $obj->qty;

                    if ($this->mbm->insert_row('inv.t_mutasi_barang_detail', $detail_ms)) {
                        unset($trx);
                        $trx['kd_produk'] = $obj->kd_produk;
                        $trx['no_ref'] = $no_mutasi_stok;
                        $trx['kd_lokasi'] = $kd_lokasi;
                        $trx['kd_blok'] = $kd_blok;
                        $trx['kd_sub_blok'] = $kd_sub_blok;
                        $trx['qty_in'] = 0;
                        $trx['qty_out'] = $obj->qty;
                        $trx['type'] = 6;
                        $trx['created_by'] = $this->session->userdata('username');
                        $trx['created_date'] = date('Y-m-d H:i:s');
                        $trx['tgl_trx'] = date('Y-m-d H:i:s');

                        if ($this->mbm->insert_row('inv.t_trx_inventory', $trx)) {
                            $trx_result++;
                        }


                        $where = array(
                            'kd_produk' => $obj->kd_produk,
                            'kd_lokasi' => $kd_lokasi,
                            'kd_blok' => $kd_blok,
                            'kd_sub_blok' => $kd_sub_blok
                        );
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh - " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi' AND kd_blok = '$kd_blok' AND kd_sub_blok = '$kd_sub_blok'";

                        if ($this->mbm->select_inventory($where)) {
                            if ($this->mbm->query_update($sql)) {
                                $inv_result++;
                            }
                        } else {
                            unset($inv);
                            $inv['kd_produk'] = $obj->kd_produk;
                            $inv['kd_lokasi'] = $kd_lokasi;
                            $inv['kd_blok'] = $kd_blok;
                            $inv['kd_sub_blok'] = $kd_sub_blok;
                            $inv['qty_oh'] = $obj->qty * -1;
                            $inv['created_by'] = $this->session->userdata('username');
                            $inv['created_date'] = date('Y-m-d H:i:s');

                            if ($this->mbm->insert_row('inv.t_brg_inventory', $inv)) {
                                $inv_result++;
                            }
                        }

                        $detail_result++;
                    }
                }
            }
        }
        $this->db->trans_complete();


        if ($header_result && $detail_result  && $trx_result > 0 && $inv_result > 0) {
            $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("mutasi_barang/print_form_mlo/" . $no_mutasi_stok) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed..'. "$header_result && $detail_result  && $trx_result && $inv_result".'"}';
        }
        echo $result;
    }

    public function update_row_in() {
        $no_mutasi_stok = $this->form_data('no_mutasi_stok');
        $tgl_mutasi = $this->form_data('tgl_mutasi_in',null);
        $keterangan = $this->form_data('keterangan');
        $detail = json_decode( $this->input->post('detail',TRUE) );

        $header_result = 0;
        $detail_result = 0;
        $trx_result = 0;
        $inv_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal, data kosong"}';
            exit;
        }

        $tgl_mutasi = empty($tgl_mutasi) ? date('Y-m-d') : date('Y-m-d', strtotime($tgl_mutasi));
        $err_lokasi = array();
        //todo: validasi blok-sub blok tujuan
        foreach ($detail as $obj) {
            $kd_produk      = $obj->kd_produk;
            $lokasi_asal    = substr($obj->sub_asal, 0, 2);
            $blok_asal      = substr($obj->sub_asal, 2, 2);
            $sub_blok_asal  = substr($obj->sub_asal, 4, 2);
            $lokasi_tujuan  = substr($obj->sub_tujuan, 0, 2);
            $blok_tujuan    = substr($obj->sub_tujuan, 2, 2);
            $sub_blok_tujuan= substr($obj->sub_tujuan, 4, 2);
        }

        $this->db->trans_start();
        $header_ms = array(
          'updated_by'        => $this->session->userdata('username'),
          'updated_date'      => $tgl_mutasi,
          'status'            => 1,
          'approval_in'       => $this->session->userdata('username'),
          'tgl_approval_in'   => $tgl_mutasi,
          'keterangan'        => $keterangan
        );

        if ($this->mbm->update_mutasi($no_mutasi_stok, $header_ms)) {
            $header_result++;
            if ($header_result > 0) {
                foreach ($detail as $obj) {
                    $kd_produk = $obj->kd_produk;
                    $kd_lokasi_t = substr($obj->sub_tujuan, 0, 2);
                    $kd_blok_t = substr($obj->sub_tujuan, 2, 2);
                    $kd_sub_blok_t = substr($obj->sub_tujuan, 4, 2);
                    $qty = $obj->qty;
                    $trx = array(
                      'kd_produk'     => $kd_produk,
                      'no_ref'        => $no_mutasi_stok,
                      'kd_lokasi'     => $kd_lokasi_t,
                      'kd_blok'       => $kd_blok_t,
                      'kd_sub_blok'   => $kd_sub_blok_t,
                      'qty_in'        => $qty,
                      'qty_out'       => 0,
                      'type'          => 6,
                      'created_by'    => $this->session->userdata('username'),
                      'created_date'  => date('Y-m-d H:i:s'),
                      'tgl_trx'       => $tgl_mutasi
                    );

                    $trx1['kd_lokasi_tujuan'] = $kd_lokasi_t;
                    $trx1['kd_blok_tujuan'] = $kd_blok_t;
                    $trx1['kd_sub_blok_tujuan'] = $kd_sub_blok_t;

                    if (
                            $this->mbm->insert_row('inv.t_trx_inventory', $trx) &&
                            $this->mbm->update_mutasi_in($no_mutasi_stok, $trx1)
                    )
                        $trx_result++;

                    $where = array('kd_produk' => $kd_produk, 'kd_lokasi' => $kd_lokasi_t, 'kd_blok' => $kd_blok_t, 'kd_sub_blok' => $kd_sub_blok_t);

                    $updated_by = $this->session->userdata('username');
                    $updated_date = date('Y-m-d H:i:s');
                    $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $qty . ",updated_by ='$updated_by',updated_date='$updated_date'
                                WHERE kd_produk = '$kd_produk' AND kd_lokasi = '$kd_lokasi_t' AND kd_blok = '$kd_blok_t'
                                    AND kd_sub_blok = '$kd_sub_blok_t'";

                    if ($this->mbm->select_inventory($where)) {
                        if ($this->mbm->query_update($sql)) {
                            $inv_result++;
                        }
                    } else {
                        unset($inv);
                        $inv['kd_produk'] = $kd_produk;
                        $inv['kd_lokasi'] = $kd_lokasi_t;
                        $inv['kd_blok'] = $kd_blok_t;
                        $inv['kd_sub_blok'] = $kd_sub_blok_t;
                        $inv['qty_oh'] = $qty;
                        $inv['created_by'] = $this->session->userdata('username');
                        $inv['created_date'] = date('Y-m-d H:i:s');

                        if ($this->mbm->insert_row('inv.t_brg_inventory', $inv)) {
                            $inv_result++;
                        }
                    }

                    $detail_result++;
                }
            }
        }
        $this->db->trans_complete();
        if ($header_result > 0 && $detail_result > 0 && $trx_result > 0 && $inv_result > 0) {
            $result = array(
                'success'   => true,
                'printUrl'  => site_url("mutasi_barang/print_form_mli/" . $no_mutasi_stok)
            );
        } else {
            $result = array(
              'success'   => false,
              'errMsg'  => 'Process Failed..'
            );
        }
        echo json_encode($result);
    }

    public function search_mutasi() {
        $start = $this->form_data('start', 0);
        $limit = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');

        $result = $this->mbm->search_mutasi($search, $start, $limit);


        echo $result;
    }

    public function outstanding_mutasi() {
        echo $this->mbm->outstanding_mutasi();
    }

    public function print_form_mlo($no_mutasi_stok) {
        if (!$no_mutasi_stok)
            show_404('page');

        $data = array(
            'header' => $this->mbm->get_summary_print($no_mutasi_stok),
            'detail' => $this->mbm->get_detail_print_out($no_mutasi_stok),
        );

        $data['header']->title = 'Form Mutasi Out';
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/Mutasi_out_print.php');
        $pdf = new Mutasi_out_print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function print_form_mli($no_mutasi_stok) {
        if (!$no_mutasi_stok)
            show_404('page');

        $data = array(
            'header' => $this->mbm->get_summary_print($no_mutasi_stok),
            'detail' => $this->mbm->get_detail_print_in($no_mutasi_stok),
        );

        $data['header']->title = 'Form Mutasi In';
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/Mutasi_in_print.php');
        $pdf = new Mutasi_in_print(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function print_form_mutasi($no_mutasi_stok) {
        if (!$no_mutasi_stok)
            show_404('page');

        $data = array(
            'header' => $this->mbm->get_summary_print($no_mutasi_stok),
            'detail' => $this->mbm->get_detail_print_in($no_mutasi_stok),
        );

//        var_dump($data);
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/InvMutasiBarang.php');
        $pdf = new InvMutasiBarang(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    // public function get_kd_peruntukan($kd_lokasi) {
    //     var_dump($this->mbm->get_kd_peruntukan($kd_lokasi));
    // }
}
