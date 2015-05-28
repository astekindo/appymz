<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of collection_pelanggan_controller
 *
 * @author Yakub
 */
class collection_pelanggan_controller extends MY_Controller {

    //put your code here
    private $offset;
    private $limit;
    private $search;
    private $kdArea;
    private $kdCollector;
    private $createdDate;
    private $createdBy;
    private $updatedDate;
    private $updatedBy;
    private $command;

    function __construct() {
        parent::__construct();
        $this->load->model('collection_pelanggan_model');
        $this->offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $this->limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $this->search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : FALSE;
        $this->kdArea = isset($_POST['combo_kd_area_cp']) ? $this->db->escape_str($this->input->post('combo_kd_area_cp', TRUE)) : FALSE;
        $this->kdCollector = isset($_POST['txt_kd_collector_cp']) ? $this->db->escape_str($this->input->post('txt_kd_collector_cp', TRUE)) : FALSE;
        $this->command = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd', TRUE)) : FALSE;
        $this->createdBy = $this->session->userdata('username');
        $this->createdDate = date('Y-m-d H:i:s');
        $this->updatedByd = $this->session->userdata('username');
        $this->updatedDate = date('Y-m-d H:i:s');
    }

    public function finalGetDataCollection() {
        echo $this->collection_pelanggan_model->getDataCollection($this->limit, $this->offset, $this->search, $this->kdCollector);
    }

    public function finalGetDataCollectionPelanggan() {
        echo $this->collection_pelanggan_model->getDataCollectionPelanggan($this->limit, $this->offset, $this->search, $this->kdCollector);
    }

    public function finalGetDataArea() {
        echo $this->collection_pelanggan_model->getDataArea($this->limit, $this->offset, $this->search);
    }

    private function finalInsert() {
        $data = array(
            'kd_collector' => $this->kdCollector,
            'kd_area' => $this->kdArea,
            'created_by' => $this->createdBy,
            'created_date' => $this->createdDate,
            'updated_by' => $this->updatedBy,
            'updated_date' => $this->updatedDate
        );
        echo $this->collection_pelanggan_model->insert($data);
    }

    private function finalUpdate() {
        $data = array(
            'kd_collector' => $this->kdCollector,
            'kd_area' => $this->kdArea,
            'created_by' => $this->createdBy,
            'created_date' => $this->createdDate,
            'updated_by' => $this->updatedBy,
            'updated_date' => $this->updatedDate
        );
        $this->kdArea = isset($_POST['txt_kd_pelanggan_lama_cp']) ? $this->db->escape_str($this->input->post('txt_kd_pelanggan_lama_cp', TRUE)) : FALSE;
        echo $this->collection_pelanggan_model->update($data, $this->kdCollector, $this->kdArea);
    }

    private function finalDelete() {
        echo $this->collection_pelanggan_model->delete($this->kdCollector, $this->kdArea);
    }

    public function finalProcessing() {
        switch ($this->command) {
            case 'save':
                $this->finalInsert();
                break;
            case 'update':
                $this->finalUpdate();
                break;
            case 'delete':
                $this->finalDelete();
                break;
            default :
                $error = array('success' => false);
                echo json_encode($error);
                break;
        }
    }

}
