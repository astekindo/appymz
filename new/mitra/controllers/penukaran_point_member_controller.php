<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penukaran_point_member_controller
 *
 * @author Yakub
 */
class penukaran_point_member_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $kdMember;
    private $pointMember;
    private $jumlahPointTukar;
    private $noBukti;
    private $tanggal;
    private $keterangan;
    private $createdBy;
    private $createdDate;
    private $updatedBy;
    private $updatedDate;
    private $data;

    function __construct() {
        parent::__construct();
        $this->load->model('penukaran_point_member_model');
        $this->load->model('setting_penukaran_point_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $this->kdMember = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member', TRUE)) : FALSE;
        $this->jumlahPointTukar = isset($_POST['jumlah_point_ditukar']) ? $this->db->escape_str($this->input->post('jumlah_point_ditukar', TRUE)) : FALSE;
        $this->pointMember = isset($_POST['point_member']) ? $this->db->escape_str($this->input->post('point_member', TRUE)) : FALSE;
        $this->tanggal = date('Y-m-d H:i:s');
        $this->keterangan = 'keterangan';
        $this->createdBy = $this->session->userdata('username');
        $this->createdDate = date('Y-m-d H:i:s');
        $this->updatedBy = $this->session->userdata('username');
        $this->updatedDate = date('Y-m-d H:i:s');
        $this->data = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        //generate no bukti
        $current_date = date('Ym');
        $no_ret = 'TP' . $current_date . '-';
        $sequence = $this->penukaran_point_member_model->get_kode_sequence($no_ret, 3);
        $this->noBukti = $no_ret . $sequence;
        //end of generate no bukti
    }

    public function finalGetDataMember() {
        echo $this->penukaran_point_member_model->getDataMember($this->limit, $this->offset, $this->search, $this->kdMember);
    }

    public function finalGetDataPenukaranPoint() {
        echo $this->setting_penukaran_point_model->getDataPenukaranPoint($this->limit, $this->offset, $this->search, "");
    }

    public function finalProcessing() {
        $this->db->trans_start();
        //insert data
        if ( $this->pointMember < $this->jumlahPointTukar ) {
            echo json_encode(array(
                'success' => false,
                'errMsg' => 'Point Tidak Mencukupi'
            ));
            return;
        }
        $insert =  $this->penukaran_point_member_model->insert_trx($this->getThrowValue());
        //kurangi point
        $sisa = $this->pointMember - $this->jumlahPointTukar;
        $update = $this->penukaran_point_member_model->updatePointMember($this->kdMember, $sisa);
        if(!$insert || !$update) {
            $this->db->trans_rollback();
            echo json_encode(array(
                'success' => false,
                'errMsg' => 'Gagal menyimpan data '. json_encode($this->db->last_query())
            ));
            return;
        } else {
            //lalalalalala
            $this->db->trans_complete();
            echo json_encode(array(
                'success' => true,
                'errMsg' => null,
                'printUrl' => site_url("penukaran_point_member_controller/print_form/" . $this->noBukti)
            ));
            return;
        }
    }

    private function getThrowValue() {
        $data = '';
        foreach ($this->data as $value => $key) {
            $arr = get_object_vars($key);
            $arr['no_bukti'] = $this->noBukti;
            $arr['kd_member'] = $this->kdMember;
            $arr['tanggal'] = $this->tanggal;
            $arr['created_by'] = $this->createdBy;
            $arr['created_date'] = $this->createdDate;
            $arr['updated_by'] = $this->updatedBy;
            $arr['updated_date'] = $this->updatedDate;
            $arr['keterangan'] = $this->keterangan;
            $arr['kd_produk'] = $arr['kd_barang'];
            $arr['qty_produk'] = $arr['qty'];
            $arr['qty_point'] = $arr['jumlah_point'];
            $arr['qty_point_tukar'] = $arr['jumlah_point_tukar'];
            unset($arr['jumlah_point_tukar']);
            unset($arr['nama_produk']);
            unset($arr['kd_barang']);
            unset($arr['qty']);
            unset($arr['jumlah_point']);
            $data[] = $arr;
        }
        return $data;
    }

    public function print_form($no_bukti = '') {
        $data = $this->penukaran_point_member_model->get_data_print($no_bukti);
        // print_r($data);die();
        if (!$data)
            show_404('page');
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/EntryPenukaranPointMember.php');
        $pdf = new EntryPenukaranPointMember(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "LETTER_MBS", true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data);
        $pdf->Output();
        exit;
    }

}
