<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_purchase_order_print extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_purchase_order_print_model', 'ppop_model');
        $this->load->model('pembelian_create_po_model', 'pcp_model');
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
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->ppop_model->get_rows($kd_supplier,$kd_peruntukan, $search, $start, $limit);

        echo $result;
    }

    public function get_rows_detail($no_po = '') {
        $result = $this->ppop_model->get_rows_detail($no_po);

        echo $result;
    }

    public function print_form($no_po) {
        $this->pcp_model->setCetakKe($no_po);
        $data = $this->pcp_model->get_data_print($no_po);
        $this->output->set_content_type("application/pdf");
        if (!$data)
            show_404('page');
        $this->load->library('FPDF_Form_Template');
        $this->load->library('CreatePOPrint_pdf');
        $pdf = new CreatePOPrint_pdf();
        $pdf->create_pdf($data);
    }
    
    public function print_form_surat_pesanan($no_po) {
        $this->pcp_model->setCetakKe($no_po);
        $data = $this->pcp_model->get_data_print($no_po);
        $this->output->set_content_type("application/pdf");
        if (!$data)
            show_404('page');
        $this->load->library('FPDF_Form_Template');
        $this->load->library('CreateSPPrint_pdf');
        $pdf = new CreateSPPrint_pdf();
        $pdf->create_pdf($data);
    }
  
}
