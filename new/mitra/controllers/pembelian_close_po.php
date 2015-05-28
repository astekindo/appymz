<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_close_po extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_close_po_model');
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

        $result = $this->pembelian_close_po_model->get_rows($kd_supplier, $search,$start,$limit);

        echo $result;
    }

    public function get_rows_detail($no_po = '') {
        $result = $this->pembelian_close_po_model->get_rows_detail($no_po);

        echo $result;
    }

    public function update_row() {

        $no_po = isset($_POST['no_po']) ? $this->db->escape_str($this->input->post('no_po', TRUE)) : '';
        $updatedo['close_po'] = 1;
        $detail_result = $this->pembelian_close_po_model->update_closepo_detail($no_po, $updatedo);

        if ($detail_result) {
            $result = '{"success":true,"errMsg":"Data Berhasil Diupdate"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
}
