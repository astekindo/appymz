<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_receive_order extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_receive_order_model', 'pro_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        // $no_do = 'RO' . date('Ymd') . '-';
        // $sequence = $this->pro_model->get_kode_sequence($no_do, 3);
        echo '{"success":true,
				"data":{
					"no_do":"",
					"tanggal":"' . date('d-M-Y') . '",
                                        "user_peruntukan":"' . $this->session->userdata('user_peruntukan') . '",
					"tanggal_terima":"' . date('d-m-Y') . '"
				}
			}';
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $tanggal_terima = isset($_POST['tanggal_terima']) ? $this->db->escape_str($this->input->post('tanggal_terima', TRUE)) : '';
        $tanggal_terima = date('Ymd', strtotime($tanggal_terima));
        $no_do = 'RO' . $tanggal_terima . '-';
        $sequence = $this->pro_model->get_kode_sequence($no_do, 3);
        $no_do = $no_do . $sequence;

        //$no_do = isset($_POST['no_do']) ? $this->db->escape_str($this->input->post('no_do',TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';

        $tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $tanggal_bukti = isset($_POST['tanggal_bukti']) ? $this->db->escape_str($this->input->post('tanggal_bukti', TRUE)) : '';
        $bukti_supplier = isset($_POST['bukti_supplier']) ? $this->db->escape_str($this->input->post('bukti_supplier', TRUE)) : '';
        $kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi', TRUE)) : '';
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : '';
        $header_result = FALSE;
        $detail_result = 0;

        if (count($detail) > 0) {

            if ($tanggal_terima) {
                $tanggal_terima = date('Y-m-d', strtotime($tanggal_terima));
            }
            if ($tanggal) {
                $tanggal = date('Y-m-d', strtotime($tanggal));
            }
            if ($tanggal_bukti) {
                $tanggal_bukti = date('Y-m-d', strtotime($tanggal_bukti));
            }
            $this->db->trans_begin();

            $tgl_ro = strtotime($tanggal_terima);
            foreach ($detail as $obj) {
               $tgl_po = strtotime($obj->tgl_po);
               if($tgl_ro < $tgl_po){
                    echo '{"success":false,"errMsg":"Tanggal Terima Tidak Boleh Lebih Kecil dari Tanggal PO"}';
                    $this->db->trans_rollback();
                    exit;
               }
             }

            $header_do['no_do'] = $no_do;
            $header_do['kd_supplier'] = $kd_supplier;
            $header_do['tanggal'] = $tanggal;
            $header_do['tgl_bukti_supplier'] = $tanggal_bukti;
            $header_do['tanggal_terima'] = $tanggal_terima;
            $header_do['no_bukti_supplier'] = $bukti_supplier;
            $header_do['konsinyasi'] = '0';
            $header_do['created_by'] = $this->session->userdata('username');
            $header_do['created_date'] = date('Y-m-d H:i:s');
            $header_do['updated_by'] = $this->session->userdata('username');
            $header_do['updated_date'] = date('Y-m-d H:i:s');
            $header_do['kd_peruntukan'] = $kd_peruntukan;

            $header_result = $this->pro_model->insert_row('purchase.t_receive_order', $header_do);
//			$is_close_po = TRUE;
            foreach ($detail as $obj) {

                if ($obj->qty_terima + $obj->qty_do - $obj->qty_retur > $obj->qty_po) {
                    $result = '{"success":false,"errMsg":"Qty RO + Qty tidak boleh lebih besar dari Qty PO"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                if ($obj->qty_do == '') {
                    $result = '{"success":false,"errMsg":"Qty tidak boleh Kosong"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                if ($obj->sub == '') {
                    $result = '{"success":false,"errMsg":"Sub Blok tidak boleh Kosong"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                if ($kd_ekspedisi == '') {
                    $result = '{"success":false,"errMsg":"Nama Ekspedisi tidak boleh Kosong"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                if ($obj->kd_satuan_ekspedisi == '') {
                    $result = '{"success":false,"errMsg":"Satuan Ekspedisi tidak boleh Kosong"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                if ($obj->berat_ekspedisi == '') {
                    $result = '{"success":false,"errMsg":"Berat Ekspedisi tidak boleh Kosong. Minimal isi angka 1"}';
                    $this->db->trans_rollback();
                    echo $result;
                    exit;
                }
                $kd_lokasi = substr($obj->sub, 0, 2);
                $kd_blok = substr($obj->sub, 2, 2);
                $kd_sub_blok = substr($obj->sub, 4, 2);


                unset($detail_do);
//                if ($obj->tonality != '' and $obj->tonality != null){
//                    $kd_produk = $obj->tonality;
//                }else {
//                    $kd_produk = $obj->kd_produk;
//                }
                $kd_produk = $obj->kd_produk;

                $detail_do['no_do'] = $no_do;
                $detail_do['no_po'] = $obj->no_po;
                $detail_do['kd_produk'] = $kd_produk;
                $detail_do['qty_beli'] = $obj->qty_po;
                $detail_do['qty_terima'] = $obj->qty_do;
                $detail_do['kd_ekspedisi'] = $kd_ekspedisi;
                $detail_do['kd_satuan_ekspedisi'] = $obj->kd_satuan_ekspedisi;
                $detail_do['berat_ekspedisi'] = $obj->berat_ekspedisi;
                $detail_do['jumlah_barcode'] = $obj->qty_do;
                $detail_do['kd_lokasi'] = $kd_lokasi;
                $detail_do['kd_blok'] = $kd_blok;
                $detail_do['kd_sub_blok'] = $kd_sub_blok;
                $detail_do['created_by'] = $this->session->userdata('username');
                $detail_do['created_date'] = date('Y-m-d H:i:s');
                $detail_do['updated_by'] = $this->session->userdata('username');
                $detail_do['updated_date'] = date('Y-m-d H:i:s');
                $detail_do['kd_produk_tonality'] = $obj->tonality;

                if ($this->pro_model->insert_row('purchase.t_dtl_receive_order', $detail_do)) {
                    $detail_result++;

                        $data_ro = $this->pro_model->get_data_ro($obj->no_po);
                        if($data_ro == 0){
                            $sql = "UPDATE purchase.t_purchase SET close_po = 1 WHERE no_po = '" . $obj->no_po . "'";
                            $this->pro_model->query_update($sql);
                        }

                    unset($trx_inventory);
                    $trx_inventory['kd_produk'] = $kd_produk;
                    $trx_inventory['no_ref'] = $no_do;
                    $trx_inventory['kd_lokasi'] = $kd_lokasi;
                    $trx_inventory['kd_blok'] = $kd_blok;
                    $trx_inventory['kd_sub_blok'] = $kd_sub_blok;
                    $trx_inventory['qty_in'] = $obj->qty_do;
                    $trx_inventory['qty_out'] = 0;
                    $trx_inventory['type'] = 1;
                    $trx_inventory['tgl_trx'] = $tanggal_terima;
                    $trx_inventory['created_by'] = $this->session->userdata('username');
                    $trx_inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->pro_model->insert_row('inv.t_trx_inventory', $trx_inventory);

                    if ($this->pro_model->get_stok_inventory($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)) {
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty_do . " WHERE kd_produk = '" . $obj->kd_produk . "' and kd_lokasi = '" . $kd_lokasi . "' and kd_blok = '" . $kd_blok . "' and kd_sub_blok = '" . $kd_sub_blok . "'";
                        $this->pro_model->query_update($sql);
                    } else {
                        unset($brg_inventory);
                        $brg_inventory['kd_produk'] = $kd_produk;
                        $brg_inventory['kd_lokasi'] = $kd_lokasi;
                        $brg_inventory['kd_blok'] = $kd_blok;
                        $brg_inventory['kd_sub_blok'] = $kd_sub_blok;
                        $brg_inventory['qty_oh'] = $obj->qty_do;
                        $brg_inventory['created_by'] = $this->session->userdata('username');
                        $brg_inventory['created_date'] = date('Y-m-d H:i:s');

                        $this->pro_model->insert_row("inv.t_brg_inventory", $brg_inventory);
                    }

                    $hpp_result = $this->pro_model->get_hpp_by_kd_produk($kd_produk, $obj->no_po);

                    $hpp_pct_ppn = 0;
                    $hpp_rp_ppn = 0;
                    $hpp_pkp = 1;
                    $hpp_rp_angkut = $hpp_result->rp_ongkos_kirim;
                    $hpp_hrg_beli_satuan = $hpp_result->dpp_po;
                    $hpp_qty_in = $obj->qty_do;

                    $cogs_result = $this->pro_model->select_cogs($kd_produk);

                    if ($cogs_result->rp_cogs == '') {
                        $hpp_cogs = $hpp_result->dpp_po;
                    } else {
                        // $hpp_cogs = $cogs_result->rp_cogs;
                        $hpp_cogs = ((($hpp_result->qty_stok - $hpp_qty_in) * $cogs_result->rp_cogs) + ($hpp_hrg_beli_satuan * $hpp_qty_in)) / ($hpp_result->qty_stok);
                    }

                    $hpp_rp_margin = ($hpp_result->pct_margin / 100) * $hpp_cogs;

                    if ($hpp_result->pkp == '1') {
                        $hpp_pct_ppn = 10;
                        $hpp_rp_ppn = ($hpp_pct_ppn / 100) * ($hpp_cogs + $hpp_rp_margin + $hpp_rp_angkut);
                        $hpp_pkp = 1.1;
                    }

                    $hpp_het = ($hpp_cogs + $hpp_rp_angkut + $hpp_rp_margin) * $hpp_pkp;
                    $hpp_het_hrg_beli = ($hpp_hrg_beli_satuan + $hpp_rp_angkut + (($hpp_result->pct_margin / 100) * $hpp_hrg_beli_satuan)) * $hpp_pkp;

                    if ($this->pro_model->get_hpp_inventory($kd_produk)) {
                        unset($hpp_inventory);
                        $hpp_inventory['no_ref'] = $no_do;
                        $hpp_inventory['type'] = '1';
                        $hpp_inventory['qty_in'] = $hpp_qty_in;
                        $hpp_inventory['qty_out'] = 0;
                        $hpp_inventory['qty_stok'] = $hpp_result->qty_stok;
                        $hpp_inventory['hrg_beli_satuan'] = $hpp_result->dpp_po;
                        $hpp_inventory['rp_cogs'] = $hpp_cogs;
                        $hpp_inventory['rp_nilai_stok'] = $hpp_result->qty_stok * $hpp_cogs;
                        $hpp_inventory['rp_angkut'] = $hpp_rp_angkut;
                        $hpp_inventory['pct_margin'] = $hpp_result->pct_margin;
                        $hpp_inventory['rp_margin'] = $hpp_rp_margin;
                        $hpp_inventory['pct_ppn'] = $hpp_pct_ppn;
                        $hpp_inventory['rp_ppn'] = $hpp_rp_ppn;
                        $hpp_inventory['rp_het'] = $hpp_het;
                        $hpp_inventory['rp_het_hrg_beli'] = $hpp_het_hrg_beli;
                        $hpp_inventory['tanggal'] = date('Y-m-d H:i:s');


                        $this->pro_model->update_row_hpp($hpp_result->kd_peruntukan, $kd_produk, $hpp_inventory);
                    } else {
                        unset($hpp_inventory);
                        $hpp_inventory['kd_peruntukan'] = $hpp_result->kd_peruntukan;
                        $hpp_inventory['kd_produk'] = $kd_produk;
                        $hpp_inventory['no_ref'] = $no_do;
                        $hpp_inventory['type'] = '1';
                        $hpp_inventory['qty_in'] = $hpp_qty_in;
                        $hpp_inventory['qty_out'] = 0;
                        $hpp_inventory['qty_stok'] = $hpp_result->qty_stok;
                        $hpp_inventory['hrg_beli_satuan'] = $hpp_result->dpp_po;
                        $hpp_inventory['rp_cogs'] = $hpp_cogs;
                        $hpp_inventory['rp_nilai_stok'] = $hpp_result->qty_stok * $hpp_cogs;
                        $hpp_inventory['rp_angkut'] = $hpp_rp_angkut;
                        $hpp_inventory['pct_margin'] = $hpp_result->pct_margin;
                        $hpp_inventory['rp_margin'] = $hpp_rp_margin;
                        $hpp_inventory['pct_ppn'] = $hpp_pct_ppn;
                        $hpp_inventory['rp_ppn'] = $hpp_rp_ppn;
                        $hpp_inventory['rp_het'] = $hpp_het;
                        $hpp_inventory['rp_het_hrg_beli'] = $hpp_het_hrg_beli;
                        $hpp_inventory['tanggal'] = date('Y-m-d H:i:s');
                        $this->pro_model->insert_row("inv.t_hpp_inventory", $hpp_inventory);
                    }


                    unset($hpp_inventory_histo);
                    $hpp_inventory_histo['no_bukti'] = "MB" . date('Ym') . '-' . $this->pro_model->get_kode_sequence("MB" . date('Ym'), 5);
                    $hpp_inventory_histo['kd_produk'] = $kd_produk;
                    $hpp_inventory_histo['kd_peruntukan'] = $hpp_result->kd_peruntukan;
                    $hpp_inventory_histo['no_ref'] = $no_do;
                    $hpp_inventory_histo['type'] = '1';
                    $hpp_inventory_histo['qty_in'] = $hpp_qty_in;
                    $hpp_inventory_histo['qty_out'] = 0;
                    $hpp_inventory_histo['qty_stok'] = $hpp_result->qty_stok;
                    $hpp_inventory_histo['hrg_beli_satuan'] = $hpp_result->dpp_po;
                    $hpp_inventory_histo['rp_cogs'] = $hpp_cogs;
                    $hpp_inventory_histo['rp_nilai_stok'] = $hpp_result->qty_stok * $hpp_cogs;
                    $hpp_inventory_histo['rp_angkut'] = $hpp_rp_angkut;
                    $hpp_inventory_histo['pct_margin'] = $hpp_result->pct_margin;
                    $hpp_inventory_histo['rp_margin'] = $hpp_rp_margin;
                    $hpp_inventory_histo['pct_ppn'] = $hpp_pct_ppn;
                    $hpp_inventory_histo['rp_ppn'] = $hpp_rp_ppn;
                    $hpp_inventory_histo['rp_het'] = $hpp_het;
                    $hpp_inventory_histo['rp_het_hrg_beli'] = $hpp_het_hrg_beli;
                    $hpp_inventory_histo['tanggal'] = date('Y-m-d H:i:s');
                    $this->pro_model->insert_row("inv.t_hpp_inventory_histo", $hpp_inventory_histo);

                    $this->pro_model->update_row_produk($hpp_result->kd_peruntukan, $kd_produk, $hpp_cogs, $hpp_het);
                }
            }
        }

        $title = 'RECEIVE ORDER';
        if ($header_result && $detail_result > 0) {
            $this->db->trans_commit();
            $result = '{"success":true,"errMsg":"Pembuatan RO berhasil, NO RO: ' . $no_do . ' ","printUrl":"' . site_url("pembelian_receive_order/print_form/" . $no_do . "/" . $title) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
            $this->db->trans_rollback();
            echo $result;
            exit;
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_po() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : "";
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : "";
        $tgl = date('Y-m-d');
        $result = $this->pro_model->get_all_po($kd_supplier, $search, $this->session->userdata('user_peruntukan'),$tgl);

        echo $result;
    }
    public function search_po_bonus($no_po) {

        echo $this->pro_model->search_po_bonus($no_po);


    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_po_detail() {
        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : "";
        $result = $this->pro_model->get_po_detail($no_po);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_sub_blok() {
        $result = '{"success" : true, record : 0, "data" : ""} ';


        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pro_model->search_supplier($search, $start, $limit);

        echo $result;
    }

    public function search_ekspedisi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pro_model->search_ekspedisi($search, $start, $limit);

        echo $result;
    }

    public function search_satuan() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi', TRUE)) : '';

        $result = $this->pro_model->search_satuan($search, $start, $limit, $kd_ekspedisi);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk_by_no_po() {
        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $sender = isset($_POST['sender']) ? $this->db->escape_str($this->input->post('sender', TRUE)) : '';

        $result = $this->pro_model->get_po_detail($no_po, $search, $sender);

        echo $result;
    }

    public function search_tonaliti() {
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $sender = isset($_POST['sender']) ? $this->db->escape_str($this->input->post('sender', TRUE)) : '';

        $result = $this->pro_model->get_tonaliti($kd_produk, $search, $sender);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($no_do = '', $title = '') {
        $title = str_replace('%20', ' ', $title);
        $data = $this->pro_model->get_data_print($no_do, $title);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianReceiveOrderPrint.php');
        $pdf = new PembelianReceiveOrderPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_rows_lokasi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $kd_peruntukan_dist = isset($_POST['kd_peruntukan_dist']) ? $this->db->escape_str($this->input->post('kd_peruntukan_dist', TRUE)) : '';
        $kd_peruntukan_supp = isset($_POST['kd_peruntukan_supp']) ? $this->db->escape_str($this->input->post('kd_peruntukan_supp', TRUE)) : '';
        if ($kd_peruntukan_supp === 'true'){
            $kd_peruntukan_supp = '0';
        }else {
           $kd_peruntukan_supp = '';
        }
        if ($kd_peruntukan_dist === 'true'){
            $kd_peruntukan_dist = '1';
        }else {
            $kd_peruntukan_dist = '';
        }
        $result = $this->pro_model->get_rows_lokasi($kd_produk,$kd_peruntukan_supp,$kd_peruntukan_dist, $search, $start, $limit);

        echo $result;
    }

}
