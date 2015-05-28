<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Generate_po extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generate_po_model','model');
    }

    public function get_rows()
    {
        $bulan    = $this->form_data('tgl_cari');
        $supplier = $this->form_data('kd_supplier');
        
        $tgl_min  = date('Y-m-d', strtotime(substr($bulan,0,4) . '-' . substr($bulan,5,2) . '-01') );
        $tgl_max  = date('Y-m-t', strtotime($tgl_min) );
        
        $this->print_result_json($this->model->get_rows($supplier, $tgl_min, $tgl_max), $this->test);
    }

    public function update_row()
    {
        $bulan    = $this->form_data('tgl_cari');
        $supplier = $this->form_data('kd_supplier');
        
        $tgl_min  = date('Y-m-d', strtotime(substr($bulan,6,4) . '-' . substr($bulan,3,2) . '-01') );
        $tgl_max  = date('Y-m-t', strtotime($tgl_min) );

        if(empty($tgl_min) || empty($tgl_max)) $this->returnError("Proses gagal. Periode salah!");

        $this->db->trans_start();
        $proses = $this->model->get_rows($supplier, $tgl_min, $tgl_max, true);
        if(!$proses['success']) {
            $this->returnError("Proses gagal. Gagal mengambil data SO!", true, $proses['lq']);
        } else {
            unset($proses['success']);
        }

        $tanggal  = date('Y-m-d');
        $user     = $this->session->userdata('username');

        $prefix   = 'GSO' . date('Ym') . '-';
        $seq      = $this->model->get_kode_sequence($prefix, 3);

        $no_bukti = $prefix . $seq;

        $update_po = 0;
        $total_barang = count($proses['data']);
        foreach ($proses['data'] as $detail) {
            $qty = $this->model->get_references($detail->kd_supplier, $detail->kd_produk, $detail->blth, 0);
            if( intval($qty) > 0 ) {
                $proses = $this->model->update_qty_po($detail->kd_supplier, $detail->kd_produk, $detail->blth, array(
                  'updated_by'   => $user,
                  'updated_date' => $tanggal,
                  'qty'          => $qty + $detail->qty
                ) );
                $update_po += 1;
            } else {
                //ambil diskon per barang
                $proses     = $this->model->get_data_harga($detail->kd_produk);
                if(!$proses['success'] && isset($proses['data'])) {
                    $this->returnError("Proses gagal. Gagal mengambil data harga!", true, $proses['lq']);
                } elseif(empty($proses['data']->hrg_supplier)) {
                    $this->returnError("Proses gagal. Data harga beli kosong!", true, $proses['lq']);
                } else {
                    $harga = $proses['data'];
                    unset($proses['success']);
                }

                $net_price  = $harga->hrg_supplier; 
                $net_price  = empty($harga->disk_persen_supp1) ? $net_price - $harga->disk_amt_supp1 
                  : $net_price  - ($net_price * $harga->disk_persen_supp1 / 100);
                $net_price  = empty($harga->disk_persen_supp2) ? $net_price - $harga->disk_amt_supp2 
                  : $net_price  - ($net_price * $harga->disk_persen_supp2 / 100);
                $net_price  = empty($harga->disk_persen_supp3) ? $net_price - $harga->disk_amt_supp3 
                  : $net_price  - ($net_price * $harga->disk_persen_supp3 / 100);
                $net_price  = empty($harga->disk_persen_supp4) ? $net_price - $harga->disk_amt_supp4 
                  : $net_price  - ($net_price * $harga->disk_persen_supp4 / 100);
                $net_price  = $net_price - $harga->disk_amt_supp5;

                $data_po[]     = array(
                    'no_bukti'          => $no_bukti,
                    'kd_supplier'       => $harga->kd_supplier,
                    'blth'              => $detail->blth,
                    'kd_produk'         => $detail->kd_produk,
                    'qty'               => $detail->qty,
                    'rp_jual'           => $harga->hrg_supplier,
                    'disk_persen_supp1' => intval($harga->disk_persen_supp1),
                    'disk_persen_supp2' => intval($harga->disk_persen_supp2),
                    'disk_persen_supp3' => intval($harga->disk_persen_supp3),
                    'disk_persen_supp4' => intval($harga->disk_persen_supp4),
                    'disk_amt_supp1'    => intval($harga->disk_amt_supp1),
                    'disk_amt_supp2'    => intval($harga->disk_amt_supp2),
                    'disk_amt_supp3'    => intval($harga->disk_amt_supp3),
                    'disk_amt_supp4'    => intval($harga->disk_amt_supp4),
                    'disk_amt_supp5'    => intval($harga->disk_amt_supp5),
                    'net_price'         => $net_price,
                    'status'            => 0,
                    'created_by'        => $user,
                    'created_date'      => $tanggal
                );
                $proses = $this->model->update_data_so($detail->blth, $detail->kd_produk, array(
                    'flag_gen_konsinyasi' => 1,
                    'tgl_gen_konsinyasi'  => date('Y-m-d'),
                    'no_gen_konsinyasi'   => $no_bukti,
                ));
                if(!$proses['success']) {
                    $this->returnError("Proses gagal. Gagal memperbarui data SO!", true, $proses['lq']);
                } else {
                    unset($proses['success']);
                }
            }
        }

        if($update_po == $total_barang) {
            $this->db->trans_complete();
            echo json_encode(array(
                'success'   => $proses['success'],
                'successMsg'=> 'Data berhasil diupdate'
            ));

        } elseif(isset($data_po)) {
            $proses = $this->model->insert_data_po_bulk($data_po);
            if(!$proses['success']) {
                $this->returnError("Proses gagal. Gagal menyimpan data PO!", true, $proses['lq']);

            } else {
                $this->db->trans_complete();

                echo json_encode(array(
                    'success'   => $proses['success'],
                    'successMsg'=> 'Data berhasil disimpan<br/> No. Bukti: ' .$no_bukti
                ));
            }
        } else {
            $this->returnError("Proses gagal.", true);
        }
    }
}
