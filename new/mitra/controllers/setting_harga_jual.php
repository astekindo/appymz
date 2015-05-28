<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_harga_jual extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('setting_harga_jual_model', 'model');
    }


    public function get_produk(){
        $start = $this->form_data('start', 0);
        $limit = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');
        $this->print_result_json($this->model->get_produk($search,$start,$limit), $this->test);
    }

    public function get_row_kode_produk()
    {
        header('Content-Type:application/json');
        $kd_produk = $this->form_data('kd_produk');

        $result = $this->model->get_row_kode_produk($kd_produk);
        if(!$result['success']) {
            echo json_encode(array(
                'success'   => false,
                'errMsg'    => 'Gagal mengambil data harga',
                //'last_query'=> $result['lq']
              ));
            return;
        }

        echo json_encode(array(
            'success'  => true,
            'data'     => $result['data']
        ));
    }

    public function get_row_grid_approval()
    {
        $kd_bonus_sales     = $this->form_data('no_bukti');
        $tgl_start_bonus    = $this->form_data('tgl_start_bonus');
        $tgl_end_bonus      = $this->form_data('tgl_end_bonus');
        $this->print_result_json($this->model->get_approval_data($kd_bonus_sales, $tgl_start_bonus, $tgl_end_bonus), $this->test);
    }

    public function get_kd_bonus_sales()
    {
        $start = $this->form_data('start', 0);
        $limit = $this->form_data('limit', $this->config->item("length_records"));
        $search = $this->form_data('query');

        $result = $this->model->get_kd_bonus_sales($search, $start, $limit);
        $this->print_result_json($result, $this->test);
    }

    public function update_row()
    {
        $data = array(
            'kd_bonus_sales'        => $this->form_data('kd_bonus_sales'),
            'kd_produk'             => $this->form_data('kd_produk'),
            'tanggal'               => date('Y-m-d'),
            'koreksi_ke'            => $this->form_data('koreksi_ke', 0),
            'tgl_start_bonus'       => $this->form_data('tgl_start_bonus'),
            'tgl_end_bonus'         => $this->form_data('tgl_end_bonus'),
            'is_bonus'              => $this->form_data('is_bonus'),
            // 'is_bonus_paket'        => $this->form_data('is_bonus_paket'),
            // 'bonus_type'            => ,
            'kd_produk_bonus'       => $this->form_data('kd_produk_bonus'),
            'kd_produk_member'      => $this->form_data('kd_produk_member'),

            'kd_kategori1_bonus'    => $this->form_data('kd_kategori1_kons'),
            'kd_kategori2_bonus'    => $this->form_data('kd_kategori2_kons'),
            'kd_kategori3_bonus'    => $this->form_data('kd_kategori3_kons'),
            'kd_kategori4_bonus'    => $this->form_data('kd_kategori4_kons'),
            'kd_kategori1_member'   => $this->form_data('kd_kategori1_member'),
            'kd_kategori2_member'   => $this->form_data('kd_kategori2_member'),
            'kd_kategori3_member'   => $this->form_data('kd_kategori3_member'),
            'kd_kategori4_member'   => $this->form_data('kd_kategori4_member'),

            'qty_beli_bonus'        => $this->form_data('qty_beli_bonus'),
            'is_bonus_kelipatan'    => $this->form_data('is_bonus_kelipatan'),
            'qty_bonus'             => $this->form_data('qty_bonus'),
            'qty_beli_member'       => $this->form_data('qty_beli_member'),
            'is_member_kelipatan'   => $this->form_data('is_member_kelipatan'),
            'qty_member'            => $this->form_data('qty_member'),
            // 'keterangan'            =>
            'created_by'            => $this->session->userdata('username'),
            'created_date'          => date('Y-m-d'),
            'status'                => 0
        );

        if('is_sbp_fs_produk-checkbox' === 'on') {
            $data['kd_kategori1_bonus'] = null;
            $data['kd_kategori2_bonus'] = null;
            $data['kd_kategori3_bonus'] = null;
            $data['kd_kategori4_bonus'] = null;
            $data['kd_kategori1_member']= null;
            $data['kd_kategori2_member']= null;
            $data['kd_kategori3_member']= null;
            $data['kd_kategori4_member']= null;
        } elseif('is_sbp_fs_kategori-checkbox' === 'on') {
            $data['kd_produk_bonus']       = null;
            $data['kd_produk_member']      = null;
        }

        if(empty($data['kd_bonus_sales']) && (!$data['is_bonus'] || $data['qty_bonus'] < 1 || $data['qty_member'] < 1))
            $this->returnError("Tidak ada data yang bisa disimpan.");

        if(empty( $data['tgl_start_bonus']) || empty( $data['tgl_end_bonus']) )
            $this->returnError("Periode salah.");

        $this->db->trans_start();
        if($this->model->get_outstanding_bonus($data['kd_produk'], $data['tgl_start_bonus'], $data['tgl_end_bonus']) ) {
            $this->returnError("Sudah ada data untuk produk '$data[kd_produk]' untuk periode <br/>
                $data[tgl_start_bonus] s.d $data[tgl_end_bonus].");
        } else {
            // XBYYYYMM-0001
            $kode                   = 'XB' . date('Ym', strtotime(date('Y-m-d')));
            $sequence               = $this->model->get_kode_sequence($kode, 4);
            $data['kd_bonus_sales'] = $kode .'-'. $sequence;
            $proses = $this->model->insert_row_temp($data);
        }


        if(!$proses['success']) {
            $this->returnError("Penyimpanan data gagal.", true, $proses['lq']);
        } else {
            $this->db->trans_complete();
            $result = array(
                'success'       => true,
                'successMsg'    => 'Data tersimpan. No bukti: '. $data['kd_bonus_sales'],
            );
            if($this->test) $result['lq'] = $proses['lq'];
            echo json_encode($result);
            return;
        }
    }

    public function proses_approval()
    {
        $detail           = json_decode($_POST['detail']);
        $updated_by       = $this->session->userdata('username');
        $updated_date     = date('Y-m-d H:i:s');

        if(empty($detail)) $this->returnError("Data kosong.");
        $this->db->trans_start();
        foreach ($detail as $key => $data) {
            if($data->is_bonus === 'Ya') {
                $data->is_bonus = 1;
            }
            if($data->is_bonus === 'Tidak') {
                $data->is_bonus = 0;
            }
            if($data->is_bonus_kelipatan === 'Ya') {
                $data->is_bonus_kelipatan = 1;
            }
            if($data->is_bonus_kelipatan === 'Tidak') {
                $data->is_bonus_kelipatan = 0;
            }
            if($data->is_bonus_paket === 'Ya')      {
                $data->is_bonus_paket = 1;
            }
            if($data->is_bonus_paket === 'Tidak') {
                $data->is_bonus_paket = 0;
            }
            if($data->is_member_kelipatan === 'Ya') {
                $data->is_member_kelipatan = 1;
            }
            if($data->is_member_kelipatan === 'Tidak') {
                $data->is_member_kelipatan = 0;
            }
            //update t_bonus_produk_temp
            $datau = array(
                'updated_by'    => $updated_by,
                'updated_date'  => $updated_date
            );
            if($data->status_approval === 'Approve') {
                $datau['status'] = 1;
            } elseif($data->status_approval === 'Reject') {
                $datau['status'] = 9;
            } else $this->returnError("Data salah.");

            $proses = $this->model->proses_approval($data->kd_bonus_sales, $data->kd_produk, $datau);
            if(!$proses['success']) {
                $this->returnError("Gagal mengupdate data approval.", true, $proses['lq']);
            } else {
                unset($proses['success']);
            }

            //update t_bonus_produk
            // $proses = $this->model->update_bonus_produk($data->kd_bonus_sales, $data->kd_produk, $datau);
            // if(!$proses['success']) {
            //     $this->returnError("Gagal mengupdate data diskon dan bonus.", true, $proses['lq']);
            // } else {
            //     unset($proses['success']);
            // }

            //update t_diskon_sales
            $proses = $this->update_diskon_sales($data);
            if(!$proses['success']) {
                $this->returnError("Gagal mengupdate data diskon dan bonus.", true, $proses['lq']);
            } else {
                $this->db->trans_complete();
                $result = array(
                    'success'       => true,
                    'successMsg'    => 'Data tersimpan.'
                );
            }
        }

    }

    public function update_diskon_sales($data)
    {
        $datau = array(
            'is_bonus'              => $data->is_bonus,
            'is_bonus_paket'        => $data->is_bonus_paket,
            'tgl_start_bonus'       => $data->tgl_start_bonus,
            'tgl_end_bonus'         => $data->tgl_end_bonus,
            'qty_beli_bonus'        => $data->qty_beli_bonus,
            'kd_produk_bonus'       => $data->kd_produk_bonus,
            'qty_bonus'             => $data->qty_bonus,
            'is_bonus_kelipatan'    => $data->is_bonus_kelipatan,
            'qty_beli_member'       => $data->qty_beli_member,
            'kd_produk_member'      => $data->kd_produk_member,
            'qty_member'            => $data->qty_member,
            'is_member_kelipatan'   => $data->is_member_kelipatan,
            'kd_kategori1_bonus'    => $data->kd_kategori1_bonus,
            'kd_kategori2_bonus'    => $data->kd_kategori2_bonus,
            'kd_kategori3_bonus'    => $data->kd_kategori3_bonus,
            'kd_kategori4_bonus'    => $data->kd_kategori4_bonus,
            'kd_kategori1_member'   => $data->kd_kategori1_member,
            'kd_kategori2_member'   => $data->kd_kategori2_member,
            'kd_kategori3_member'   => $data->kd_kategori3_member,
            'kd_kategori4_member'   => $data->kd_kategori4_member
        );
        return $this->model->update_diskon_sales($data->kd_produk, $datau);
    }

    public function hitung_diskon($data, $target, $persen, $amount, $teks_persen = 'persen', $teks_amount = 'amount')
    {
        $target_teks = $target .'_op';
        if(!empty($persen) && empty($amount)) {
            $data->$target_teks = $teks_persen;
            $data->$target = $persen;
        }else if(empty($persen) && !empty($amount)) {
            $data->$target_teks = $teks_amount;
            $data->$target = $amount;
        }else {
            $data->$target_teks = $teks_persen;
            $data->$target = 0;
        }
        return $data;
    }

    public function bersihkan_data($data)
    {
        foreach ($data as $key => $value) {
            if($value === null || $value === '') unset($data[$key]);
        }
        return $data;
    }

}
