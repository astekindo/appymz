<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_penukaran_point_controller
 *
 * @author Yakub
 */
class cetak_penukaran_point_controller extends MY_Controller {

    private $limit;
    private $offset;
    private $search;
    private $tanggal;
    private $kdMember;

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('cetak_penukaran_point_model');
        $this->load->model('barterbarang_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->tanggal = isset($_POST['tanggal']) ? $this->db->escape_str($this->input->post('tanggal', TRUE)) : '';
        $this->kdMember = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member', TRUE)) : '';
    }

    public function finalGetDataPenukaranPoint() {
        echo $this->cetak_penukaran_point_model->getDataPenukaranPoint($this->limit, $this->offset, $this->search, $this->kdMember, $this->tanggal);
    }

}
