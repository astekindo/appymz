<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pricetag_print extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pricetag_print_model', 'ptp_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->ptp_model->search_produk_pricetag($search, $start, $limit);


        echo $result;
    }

    public function submit() {
        $chk_rp_coret = isset($_POST['chk_rp_coret']) ? $this->db->escape_str($this->input->post('chk_rp_coret', TRUE)) : '0';
        $kd_produk1 = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $kd_produk2 = isset($_POST['kd_produk2']) ? $this->db->escape_str($this->input->post('kd_produk2', TRUE)) : '';
        $kd_produk3 = isset($_POST['kd_produk3']) ? $this->db->escape_str($this->input->post('kd_produk3', TRUE)) : '';
        $kd_produk4 = isset($_POST['kd_produk4']) ? $this->db->escape_str($this->input->post('kd_produk4', TRUE)) : '';
        $kd_produk5 = isset($_POST['kd_produk5']) ? $this->db->escape_str($this->input->post('kd_produk5', TRUE)) : '';
        $kd_produk6 = isset($_POST['kd_produk6']) ? $this->db->escape_str($this->input->post('kd_produk6', TRUE)) : '';
        $kd_produk7 = isset($_POST['kd_produk7']) ? $this->db->escape_str($this->input->post('kd_produk7', TRUE)) : '';
        $kd_produk8 = isset($_POST['kd_produk8']) ? $this->db->escape_str($this->input->post('kd_produk8', TRUE)) : '';

        $rp_coret1 = isset($_POST['rp_coret']) ? $this->db->escape_str($this->input->post('rp_coret', TRUE)) : '';
        $rp_coret2 = isset($_POST['rp_coret2']) ? $this->db->escape_str($this->input->post('rp_coret2', TRUE)) : '';
        $rp_coret3 = isset($_POST['rp_coret3']) ? $this->db->escape_str($this->input->post('rp_coret3', TRUE)) : '';
        $rp_coret4 = isset($_POST['rp_coret4']) ? $this->db->escape_str($this->input->post('rp_coret4', TRUE)) : '';
        $rp_coret5 = isset($_POST['rp_coret5']) ? $this->db->escape_str($this->input->post('rp_coret5', TRUE)) : '';
        $rp_coret6 = isset($_POST['rp_coret6']) ? $this->db->escape_str($this->input->post('rp_coret6', TRUE)) : '';
        $rp_coret7 = isset($_POST['rp_coret7']) ? $this->db->escape_str($this->input->post('rp_coret7', TRUE)) : '';
        $rp_coret8 = isset($_POST['rp_coret8']) ? $this->db->escape_str($this->input->post('rp_coret8', TRUE)) : '';

        $harga_jual1 = isset($_POST['harga_jual']) ? $this->db->escape_str($this->input->post('harga_jual', TRUE)) : '';
        $harga_jual2 = isset($_POST['harga_jual2']) ? $this->db->escape_str($this->input->post('harga_jual2', TRUE)) : '';
        $harga_jual3 = isset($_POST['harga_jual3']) ? $this->db->escape_str($this->input->post('harga_jual3', TRUE)) : '';
        $harga_jual4 = isset($_POST['harga_jual4']) ? $this->db->escape_str($this->input->post('harga_jual4', TRUE)) : '';
        $harga_jual5 = isset($_POST['harga_jual5']) ? $this->db->escape_str($this->input->post('harga_jual5', TRUE)) : '';
        $harga_jual6 = isset($_POST['harga_jual6']) ? $this->db->escape_str($this->input->post('harga_jual6', TRUE)) : '';
        $harga_jual7 = isset($_POST['harga_jual7']) ? $this->db->escape_str($this->input->post('harga_jual7', TRUE)) : '';
        $harga_jual8 = isset($_POST['harga_jual8']) ? $this->db->escape_str($this->input->post('harga_jual8', TRUE)) : '';
        
        $nama_produk1 = isset($_POST['nama_produk1']) ? $this->db->escape_str($this->input->post('nama_produk1', TRUE)) : '';
        $nama_produk2 = isset($_POST['nama_produk2']) ? $this->db->escape_str($this->input->post('nama_produk2', TRUE)) : '';
        $nama_produk3 = isset($_POST['nama_produk3']) ? $this->db->escape_str($this->input->post('nama_produk3', TRUE)) : '';
        $nama_produk4 = isset($_POST['nama_produk4']) ? $this->db->escape_str($this->input->post('nama_produk4', TRUE)) : '';
        $nama_produk5 = isset($_POST['nama_produk5']) ? $this->db->escape_str($this->input->post('nama_produk5', TRUE)) : '';
        $nama_produk6 = isset($_POST['nama_produk6']) ? $this->db->escape_str($this->input->post('nama_produk6', TRUE)) : '';
        $nama_produk7 = isset($_POST['nama_produk7']) ? $this->db->escape_str($this->input->post('nama_produk7', TRUE)) : '';
        $nama_produk8 = isset($_POST['nama_produk8']) ? $this->db->escape_str($this->input->post('nama_produk8', TRUE)) : '';
        
        $nm_satuan1 = isset($_POST['nm_satuan1']) ? $this->db->escape_str($this->input->post('nm_satuan1', TRUE)) : '';
        $nm_satuan2 = isset($_POST['nm_satuan2']) ? $this->db->escape_str($this->input->post('nm_satuan2', TRUE)) : '';
        $nm_satuan3 = isset($_POST['nm_satuan3']) ? $this->db->escape_str($this->input->post('nm_satuan3', TRUE)) : '';
        $nm_satuan4 = isset($_POST['nm_satuan4']) ? $this->db->escape_str($this->input->post('nm_satuan4', TRUE)) : '';
        $nm_satuan5 = isset($_POST['nm_satuan5']) ? $this->db->escape_str($this->input->post('nm_satuan5', TRUE)) : '';
        $nm_satuan6 = isset($_POST['nm_satuan6']) ? $this->db->escape_str($this->input->post('nm_satuan6', TRUE)) : '';
        $nm_satuan7 = isset($_POST['nm_satuan7']) ? $this->db->escape_str($this->input->post('nm_satuan7', TRUE)) : '';
        $nm_satuan8 = isset($_POST['nm_satuan8']) ? $this->db->escape_str($this->input->post('nm_satuan8', TRUE)) : '';
        
        $cetak_besar = isset($_POST['cetak_besar']) ? $this->db->escape_str($this->input->post('cetak_besar', TRUE)) : '';
        $cetak_kecil = isset($_POST['cetak_kecil']) ? $this->db->escape_str($this->input->post('cetak_kecil', TRUE)) : '';
        
        $produk1 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan1,'kd_produk' => $kd_produk1, 'nama_produk' => $nama_produk1, 'rp_coret' => $rp_coret1, 'harga_jual' => $harga_jual1, 'chk_rp_coret' => $chk_rp_coret );
        $produk2 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan2,'kd_produk' => $kd_produk2, 'nama_produk' => $nama_produk2, 'rp_coret' => $rp_coret2, 'harga_jual' => $harga_jual2, 'chk_rp_coret' => $chk_rp_coret );
        $produk3 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan3,'kd_produk' => $kd_produk3, 'nama_produk' => $nama_produk3, 'rp_coret' => $rp_coret3, 'harga_jual' => $harga_jual3, 'chk_rp_coret' => $chk_rp_coret );
        $produk4 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan4,'kd_produk' => $kd_produk4, 'nama_produk' => $nama_produk4, 'rp_coret' => $rp_coret4, 'harga_jual' => $harga_jual4, 'chk_rp_coret' => $chk_rp_coret );
        $produk5 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan5,'kd_produk' => $kd_produk5, 'nama_produk' => $nama_produk5, 'rp_coret' => $rp_coret5, 'harga_jual' => $harga_jual5, 'chk_rp_coret' => $chk_rp_coret );
        $produk6 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan6,'kd_produk' => $kd_produk6, 'nama_produk' => $nama_produk6, 'rp_coret' => $rp_coret6, 'harga_jual' => $harga_jual6, 'chk_rp_coret' => $chk_rp_coret );
        $produk7 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan7,'kd_produk' => $kd_produk7, 'nama_produk' => $nama_produk7, 'rp_coret' => $rp_coret7, 'harga_jual' => $harga_jual7, 'chk_rp_coret' => $chk_rp_coret );
        $produk8 = array('cetak_besar' => $cetak_besar, 'cetak_kecil' => $cetak_kecil, 'nm_satuan' => $nm_satuan8,'kd_produk' => $kd_produk8, 'nama_produk' => $nama_produk8, 'rp_coret' => $rp_coret8, 'harga_jual' => $harga_jual8, 'chk_rp_coret' => $chk_rp_coret );
        
        $pricetag = array('produk1' =>  $produk1,
                            'produk2' =>  $produk2,
                            'produk3' =>  $produk3,
                            'produk4' =>  $produk4,
                            'produk5' =>  $produk5,
                            'produk6' =>  $produk6,
                            'produk7' =>  $produk7,
                            'produk8' =>  $produk8);
        
        $param = array('pricetag'  => $pricetag);
        
        $kd_cetak = 'PT' . date('Ymd') . '-';
        $sequence = $this->ptp_model->get_kode_sequence($kd_cetak, 3);
        $kd_cetak = $kd_cetak . $sequence;
        
        $data['kd_cetak'] = $kd_cetak;
        $data['pricetag'] = json_encode($param);
        $data['tanggal'] = date('Y-m-d H:i:s');
        
        $this->ptp_model->insert_row($data);
        
        $result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pricetag_print/print_form/" . $kd_cetak) . '"}';

        echo $result;
    }

    public function print_form($kd_cetak = '') {
        $title = str_replace('%20', ' ', $title);
        $data = $this->ptp_model->get_data_print($kd_cetak);
//        print_r($data->pricetag->produk1->kd_produk); die();
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/MasterPriceTagPrint.php');
        $pdf = new MasterPriceTagPrint(PDF_PAGE_ORIENTATION_PORTRAIT, PDF_UNIT, "PRICETAG", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data);
        $pdf->Output();
        exit;
    }

}
