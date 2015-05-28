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
class cetak_penjualan_distribusi_controller extends MY_Controller {

    private $limit;
    private $offset;
    private $search;
    private $tanggalSo;
    private $noSo;
    private $kdMember;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_penjualan_distribusi_model');
        $this->load->model('barterbarang_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->tanggalSo = isset($_POST['tanggal_so']) ? $this->db->escape_str($this->input->post('tanggal_so', TRUE)) : '';
        $this->noSo = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $this->kdMember = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member', TRUE)) : '';
    }

    public function finalGetDataSO() {
        echo $this->cetak_penjualan_distribusi_model->getDataSalesOrder($this->limit, $this->offset, $this->search, $this->kdMember, $this->tanggalSo);
    }

    public function finalGetDataSODetail() {
        echo $this->cetak_penjualan_distribusi_model->getDataSalesOrderDetail($this->limit, $this->offset, $this->noSo, $this->search);
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
