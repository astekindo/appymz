<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of collection_pelanggan_model
 *
 * @author Yakub
 */
class collection_pelanggan_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataCollection($limit, $offset, $search = "", $kdCollector = "") {
        $result = '';
        $total = 0;
        $queryTotal = '';
        $queryData = '';
        if (!empty($kdCollector)) {
            $queryData = "SELECT * FROM mst.t_collection a WHERE a.kd_collector = $kdCollector";
            $queryTotal = "SELECT COUNT(*) as total FROM mst.t_collection a WHERE a.kd_collector = $kdCollector";
        } else {
            $sqlSearch = "";
            if ($search) {
                $sqlSearch = "WHERE ((lower(a.kd_collector) LIKE '%" . strtolower($search) . "%') OR (a.nama_collector LIKE '%$search%'))";
            }
            $queryData = "SELECT * FROM mst.t_collection a $sqlSearch limit $limit offset $offset";
            $queryTotal = "SELECT COUNT(*) as total FROM mst.t_collection a $sqlSearch";
        }
        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataCollectionPelanggan($limit, $offset, $search = "", $kdCollector = "") {
        $result = '';
        $total = 0;
        $sqlSearch = "";
        $sqlColecctor = "";
        if ($search) {
            $sqlSearch = "AND (c.nama_area LIKE '%$search%') OR (c.nama_area LIKE '%" . strtoupper($search) . "%')";
        }
        if ($kdCollector){
            $sqlColecctor = "AND a.kd_collector='$kdCollector'";
        }
        $queryData = "SELECT a.*,b.nama_collector,c.nama_area FROM
                      mst.t_collection_area a
                      LEFT JOIN mst.t_collection b
                      ON b.kd_collector=a.kd_collector
                      LEFT JOIN mst.t_area c
                      ON c.kd_area=a.kd_area
                      WHERE 1=1 $sqlColecctor $sqlSearch limit $limit offset $offset";

        $queryTotal = "SELECT COUNT(*) FROM 
                      (SELECT a.*,b.nama_collector,c.nama_area FROM
                      mst.t_collection_area a
                      LEFT JOIN mst.t_collection b
                      ON b.kd_collector=a.kd_collector
                      LEFT JOIN mst.t_area c
                      ON c.kd_area=a.kd_area
                      WHERE 1=1 $sqlColecctor $sqlSearch limit $limit offset $offset)a";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function getDataArea($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = "";
        if ($search) {
            $sqlSearch = "WHERE ((lower(a.nama_area) LIKE '%" . strtolower($search) . "%') OR (a.nama_area LIKE '%" . strtolower($search) . "%'))";
        }
        $queryData = "SELECT * FROM mst.t_area a where a.kd_area not in (select kd_area from mst.t_collection_area) and status = 1
                      $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) FROM mst.t_area a where a.kd_area not in (select kd_area from mst.t_collection_area) and status = 1
                      $sqlSearch";

        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            $result = $getData->result();
            $total = $getTotal->row()->total;
        }

        $success = array(
            'success' => true,
            'record' => total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function insert($data) {
        $success = '';
            if ($this->isDataExist($data['kd_collector'], $data['kd_area'])) {
                $success = array(
                    'success' => false,
                    'errMsg' => 'Data Telah Ada'
                );
            } else {
                $this->db->insert('mst.t_collection_area', $data);
                $success = array(
                    'success' => true
                );
            }
            return json_encode($success);
    }

    public function update($data, $kdCollector, $kdArea) {
        $success = '';
        if ($this->isDataExist($data['kd_collector'], $data['kd_area'])) {
            $success = array(
                'success' => false,
                'errMsg' => 'Data Telah Ada'
            );
        } else {
            $this->db->where('kd_collector', $kdCollector);
            $this->db->where('kd_area', $kdArea);
            $this->db->update('mst.t_collection_area', $data);
            $success = array(
                'success' => true
            );
        }
        return json_encode($success);
    }

    public function delete($kdCollector, $kdArea) {
        $this->db->where('kd_collector', $kdCollector);
        $this->db->where('kd_area', $kdArea);
        $this->db->delete('mst.t_collection_area');
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    private function isDataExist($kdCollector, $kdArea) {
        $exist = $this->db->query("SELECT * FROM mst.t_collection_area a WHERE a.kd_collector = '$kdCollector' AND a.kd_area='$kdArea'");
        if ($exist->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
