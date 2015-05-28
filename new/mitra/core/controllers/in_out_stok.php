<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class In_out_stok extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();

        $this->load->model('in_out_stok_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        $no_hp = 'HP' . date('Ymd') . '-';
        $sequence = $this->hp_model->get_kode_sequence($no_hp, 3);
        echo '{"success":true,
				"data":{
					"no_hp":"' . $no_hp . $sequence . '",
					"tanggal":"' . date('d-m-Y') . '"
				}
			}';
    }

    public function search_lokasi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';


        $result = $this->in_out_stok_model->search_lokasi($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $no_buk = 'IO' . date('Ym') . '-';
        $sequence = $this->in_out_stok_model->get_kode_sequence($no_buk, 3);
        $no_bukti = $no_buk . $sequence;

        $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();
        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        }
        $status = '1';
        $this->db->trans_begin();
        foreach ($detail as $obj) {
            if($obj->nama_lokasi === ''){
                $obj->nama_lokasi = $obj->kd_lokasi_asal;
            }
            
            $lokasi = $obj->nama_lokasi;
            $lokasi1 = substr($lokasi, 0, 2);
            $lokasi2 = substr($lokasi, 2, 2);
            $lokasi3 = substr($lokasi, 4, 2);
            $kd_produk = $obj->kd_produk;
            $qty_in = $obj->qty_in;
            $qty_out = $obj->qty_out;
            $keterangan = $obj->keterangan;

            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d');
            //print_r($tanggal);

            if ($obj->edited == 'Y') {
                unset($detail_ios);
                $detail_ios['no_bukti'] = $no_bukti;
                $detail_ios['kd_produk'] = $kd_produk;
                $detail_ios['tanggal'] = date('Y-m-d');
                $detail_ios['qty_in'] = $qty_in;
                $detail_ios['qty_out'] = $qty_out;
                $detail_ios['keterangan'] = $keterangan;
                $detail_ios['status'] = $status;
                $detail_ios['created_by'] = $created_by;
                $detail_ios['created_date'] = date('Y-m-d');
                $detail_ios['kd_lokasi'] = $lokasi1;
                $detail_ios['kd_blok'] = $lokasi2;
                $detail_ios['kd_sub_blok'] = $lokasi3;
                $detail_ios['approve_by'] = '';
                //$detail_ios['approve_date']=     '';
                //$detail_ios['keterangan_approve']= $tanggal;
                $detailresult = $this->in_out_stok_model->insert_row('inv.t_inout_stok', $detail_ios);

                if ($detailresult) {
                    $detail_result++;
                }
                
                unset($trxinventory);
                $trxinventory['kd_produk'] = $kd_produk;
                $trxinventory['no_ref'] = $no_bukti;
                $trxinventory['kd_lokasi'] = $lokasi1;
                $trxinventory['kd_blok'] = $lokasi2;
                $trxinventory['kd_sub_blok'] = $lokasi3;
                $trxinventory['qty_in'] = $qty_in;
                $trxinventory['qty_out'] = $qty_out;
                $trxinventory['type'] = '9';
                $trxinventory['created_by'] = $created_by;
                $trxinventory['created_date'] = $created_date;
                $trxinventory['tgl_trx'] = date('Y-m-d');

                $stok = 0;
                $stokexists = FALSE;
                $rowstok = $this->in_out_stok_model->cek_exists_brg_inv($kd_produk, $lokasi1, $lokasi2, $lokasi3);

                unset($brg_inventory);
                if (count($rowstok) > 0) {
                    $stokexists = true;
                    foreach ($rowstok as $objstok) {
                        $stok = $objstok->qty_oh;
                    }
                    $brg_inventory['qty_oh'] = $stok - $qty_out + $qty_in;
                    $brg_inventory['updated_by'] = $created_by;
                    $brg_inventory['updated_date'] = $created_date;
                } else {
                    $brg_inventory['kd_produk'] = $kd_produk;
                    $brg_inventory['kd_lokasi'] = $lokasi1;
                    $brg_inventory['kd_blok'] = $lokasi2;
                    $brg_inventory['kd_sub_blok'] = $lokasi3;
                    $brg_inventory['qty_oh'] = $stok - $qty_out + $qty_in;
                    $brg_inventory['created_by'] = $created_by;
                    $brg_inventory['created_date'] = $created_date;
                }
                
                if ($this->in_out_stok_model->insert_row('inv.t_trx_inventory', $trxinventory)) {
                    if (!$stokexists) {
                        if ($this->in_out_stok_model->insert_row('inv.t_brg_inventory', $brg_inventory)) {
                            $detail_result++;
                        }
                    } else {
                        if ($this->in_out_stok_model->update_brg_inv($kd_produk, $lokasi1, $lokasi2, $lokasi3, $brg_inventory)) {
                            $detail_result++;
                        }
                    }
                }
            }
        }
        $this->db->trans_commit();
        if ($detailresult) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Tidak Ada Data yang Disimpan"}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_stok_produk() {
        $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1', TRUE)) : '';
        $kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2', TRUE)) : '';
        $kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3', TRUE)) : '';
        $kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4', TRUE)) : '';
        $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran', TRUE)) : '';
        $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan', TRUE)) : '';
        $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $list = isset($_POST['list']) ? $this->db->escape_str($this->input->post('list', TRUE)) : '';
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        }
        if ($list != '') {
            $list_exp = explode(',', $list);
            $list_imp = implode("','", $list_exp);
            $list = strtoupper("'" . $list_imp . "'");
        }

        $data_result = $this->in_out_stok_model->search_stok_produk($kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $kd_ukuran, $kd_satuan, $tanggal, $list, $search, $start, $limit);

        $hasil = $data_result['rows'];
        //$results = array();

        echo '{success:true,record:' . $data_result['total'] . ',data:' . json_encode($hasil) . '}';
    }

    public function search_kd_produk($search_by) {
        $keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        echo $this->hp_model->get_produk($keyword);
    }

    public function print_form($no_bukti = '', $kd_produk = '') {
        $data = $this->hp_model->get_data_print($no_bukti, $kd_produk);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/HargaPembelianPrint.php');
        $pdf = new HargaPembelianPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'F4', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_no_bukti_filter() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->hp_model->get_no_bukti_filter($search, $start, $limit);

        echo $result;
    }

}
