<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_purchase_order_bonus_controller
 *
 * @author Yakub
 */
class monitoring_purchase_order_bonus_controller extends MY_Controller {

    //put your code here
    private $limit;
    private $offset;
    private $search;
    private $kdSupplierPO;
    private $tglAwal;
    private $tglAkhir;
    private $approvalPo;
    private $closePo;
    private $konsinyasi;
    private $peruntukanSup;
    private $peruntukanDist;
    private $noPoInduk;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('monitoring_purchase_order_bonus_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->kdSupplierPO = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $this->tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $this->tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $this->approvalPo = isset($_POST['approval_po']) ? $this->db->escape_str($this->input->post('approval_po', TRUE)) : '';
        $this->closePo = isset($_POST['close_po']) ? $this->db->escape_str($this->input->post('close_po', TRUE)) : '';
        $this->konsinyasi = isset($_POST['konsinyasi']) ? $this->db->escape_str($this->input->post('konsinyasi', TRUE)) : '';
        $this->peruntukanSup = isset($_POST['peruntukan_sup']) ? $this->db->escape_str($this->input->post('peruntukan_sup', TRUE)) : '';
        $this->peruntukanDist = isset($_POST['peruntukan_dist']) ? $this->db->escape_str($this->input->post('peruntukan_dist', TRUE)) : '';
        $this->noPoInduk = isset($_POST['no_po_induk']) ? $this->db->escape_str($this->input->post('no_po_induk', TRUE)) : '';
    }

    public function finalGetDataSupplierPO() {
        echo $this->monitoring_purchase_order_bonus_model->getDataSupplier($this->limit, $this->offset, $this->search);
    }

    public function finalGetDataNoPoInduk() {
        echo $this->monitoring_purchase_order_bonus_model->getDataNOPOInduk($this->limit, $this->offset, $this->kdSupplierPO, $this->search);
    }

    public function finalGetRows() {
        if ($this->peruntukanDist == 'true') {
            $this->peruntukanDist = '1';
        } else {
            $this->peruntukanDist = '';
        }
        if ($this->peruntukanSup == 'true') {
            $this->peruntukanSup = '0';
        } else {
            $this->peruntukanSup = '';
        }
        echo $this->monitoring_purchase_order_bonus_model->get_rows($this->kdSupplierPO, $this->tglAwal, $this->tglAkhir, $this->approvalPo, $this->closePo, $this->konsinyasi, $this->peruntukanSup, $this->peruntukanDist, $this->noPoInduk, $this->search, $this->offset, $this->limit);
    }

}
