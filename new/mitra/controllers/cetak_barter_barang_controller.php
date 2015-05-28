<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_barter_barang_controller
 *
 * @author Yakub
 */
class cetak_barter_barang_controller extends MY_Controller {

    private $limit;
    private $offset;
    private $search;
    private $tanggalBarter;
    private $kdSupplier;
    private $noTransfer;
    private $noSurat;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_barter_barang_model');
        $this->load->model('barterbarang_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->tanggalBarter = isset($_POST['tanggal_barter']) ? $this->db->escape_str($this->input->post('tanggal_barter', TRUE)) : '';
        $this->kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $this->noTransfer = isset($_POST['no_transfer']) ? $this->db->escape_str($this->input->post('no_transfer', TRUE)) : '';
        $this->noSurat = isset($_POST['no_sb']) ? $this->db->escape_str($this->input->post('no_sb', TRUE)) : '';
    }

    public function finalGetDataBarter() {
        echo $this->cetak_barter_barang_model->getDataBarter($this->limit, $this->offset, $this->search, $this->kdSupplier, $this->tanggalBarter);
    }

    public function finalGetDataSuratBarter() {
        echo $this->cetak_barter_barang_model->getDataSuratBarter($this->limit, $this->offset, $this->noTransfer, $this->search);
    }

    public function finalPrint($noSB) {
        $data = $this->barterbarang_model->get_data_print($noSB);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/InvBarterPrint.php');
        $pdf = new InvBarterPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}
