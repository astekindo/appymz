<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class master_perusahaan_controller extends MY_Controller {

    private $kdPerusahaan;
    private $noPerusahaan;
    private $namaPerusahaan;
    private $tglBerdiri;
    private $noAktaPendirian;
    private $noSiup;
    private $noTelp;
    private $direktur;
    private $noFax;
    private $aktif;
    private $deskripsi;
    private $pkp;
    private $npwp;
    private $namaNpwp;
    private $alamatNpwp;
    private $alamat1;
    private $alamat2;
    private $alamat3;


    function __construct() {
        parent::__construct();
        $this->load->model('master_perusahaan_model');

        $this->kdPerusahaan    = $this->form_data('kd_perusahaan');
        $this->noPerusahaan    = $this->form_data('no_perusahaan');
        $this->namaPerusahaan  = $this->form_data('nama_perusahaan');
        $this->tglBerdiri      = $this->form_data('tgl_berdiri');
        $this->noAktaPendirian = $this->form_data('no_akta_pendirian');
        $this->noSiup          = $this->form_data('no_siup');
        $this->noTelp          = $this->form_data('no_telp_perusahaan');
        $this->direktur        = $this->form_data('direktur_perusahaan');
        $this->noFax           = $this->form_data('no_fax_perusahaan');
        $this->aktif           = $this->form_data('aktif');
        $this->deskripsi       = $this->form_data('deskripsi_perusahaan');
        $this->pkp             = $this->form_data('radio_pkp');
        $this->npwp            = $this->form_data('no_npwp');
        $this->namaNpwp        = $this->form_data('nama_npwp');
        $this->alamatNpwp      = $this->form_data('alamat_npwp');
        $this->alamat1         = $this->form_data('alamat1');
        $this->alamat2         = $this->form_data('alamat2');
        $this->alamat3         = $this->form_data('alamat3');
    }

    public function finalGetRows() {
        $offset         = $this->form_data('start', 0);
        $limit          = $this->form_data('limit', $this->config->item("length_records"));
        $search         = $this->form_data('query');
        $kdPerusahaan   = $this->form_data('kd_perusahaan');
        echo $this->master_perusahaan_model->getRows($limit, $offset, $search, $kdPerusahaan);
    }

    public function finalInsertUpdate() {
        $command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : '';
        $data = array(
            'kd_perusahaan'     => $this->kdPerusahaan,
            'no_perusahaan'     => $this->noPerusahaan,
            'nama_perusahaan'   => $this->namaPerusahaan,
            'tgl_berdiri'       => $this->tglBerdiri,
            'no_akta_pendirian' => $this->noAktaPendirian,
            'no_siup'           => $this->noSiup,
            'no_telp'           => $this->noTelp,
            'direktur'          => $this->direktur,
            'no_fax'            => $this->noFax,
            'aktif'             => $this->aktif,
            'deskripsi'         => $this->deskripsi,
            'pkp'               => $this->pkp,
            'npwp'              => $this->npwp,
            'nama_npwp'         => $this->namaNpwp,
            'alamat_npwp'       => $this->alamatNpwp,
            'alamat1'           => $this->alamat1,
            'alamat2'           => $this->alamat2,
            'alamat3'           => $this->alamat3
        );

        if ($command == 'save') {
            echo $this->master_perusahaan_model->insert($data);
        } else if ($command == 'update') {
            echo $this->master_perusahaan_model->update($data, $this->kdPerusahaan);
        }
    }

}
