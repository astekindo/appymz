<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_surat_pesanan_controller
 *
 * @author Yakub
 */
class cetak_surat_pesanan_controller extends MY_Controller {

    //put your code here
    private $limit;
    private $offset;
    private $search;
    private $kdSupplier;
    private $noSurat;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_surat_pesanan_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $this->noSurat = isset($_POST['no_surat']) ? $this->db->escape_str($this->input->post('no_surat', TRUE)) : '';
    }

    public function finalGetDataSuratPesanan() {
        echo $this->cetak_surat_pesanan_model->getSuratPesanan($this->limit, $this->offset, $this->kdSupplier, $this->search);
        //echo $this->kdSupplier;
    }

    public function finalGetDataSuratPesananDetail() {
        echo $this->cetak_surat_pesanan_model->getDataSuratPesananDetail($this->limit, $this->offset, $this->noSurat, $this->search);
    }

    public function finalPrint($no_sp = '') {
        echo $this->cetak_surat_pesanan_model->setCetakKe($no_sp);
        
        $data = $this->cetak_surat_pesanan_model->getDataPrint($no_sp);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/cetak_surat_pesanan_print.php');
        $pdf = new cetak_surat_pesanan_print(PDF_PAGE_ORIENTATION_PORTRAIT, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPkp($data['header']);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}
