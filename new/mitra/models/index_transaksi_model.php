<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class index_transaksi_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert($data) {
        $this->db->insert('mst.t_index_transaksi', $data);
    }

    public function update($data, $where) {
        $this->db->where('kd_index', $where);
        $this->db->update('mst.t_index_transaksi', $data);
    }

    public function delete($kd_index) {
        $this->db->where('kd_index', $kd_index);
        if($this->db->delete("mst.t_index_transaksi")){
            return json_encode(array(
                'success'=>TRUE
            ));
        }else{
            return json_encode(array(
                'success'=>FALSE
            )); 
        }
    }

    public function get($kd_index) {
        $query = "select * from mst.t_index_transaksi where kd_index=$kd_index";
        $data = $this->db->query($query);
        $result = $data->result();
        $jsonresult = json_encode($result);
        return $jsonresult;
    }

    public function isExist($kdIndex) {
        $query = "select * from mst.t_index_transaksi where kd_index='$kdIndex'";
        $data = $this->db->query($query);
        $result = $data->result();
        if ($result != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getAll($length, $offset, $search = "", $kd_index) {
        $total = 0;
        $queryResults = '';
        if(!empty($kd_index)) {
            $query = $this->db->query("select * from mst.t_index_transaksi where kd_index = '$kd_index'");
            if($query->num_rows()===1) {
                $total = 1;
                $queryResults = json_encode($query->row());
            }
        } else {
            $sqlSearch = "";
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(mst.t_index_transaksi.nama_index) LIKE '%" . strtolower($search) . "%') OR (mst.t_index_transaksi.kd_index LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "select * from mst.t_index_transaksi $sqlSearch limit $length offset $offset";
            $getTotalQuery = "select count(*) as total from mst.t_index_transaksi $sqlSearch";
            $queryGetTotal = $this->db->query($getTotalQuery);
            $queryGetData = $this->db->query($getDataQuery);
            $total = $queryGetTotal->row()->total;

            if ($queryGetData->num_rows() > 0) {
                $data = $queryGetData->result();
            }
            $queryResults = json_encode($data);
            //$finaResults=  chop($queryResults, ']');
        }
        $results = '{"success":true, "record":' . $total . ', "data":' . $queryResults . '}';
        return $results;
    }

//    public function getAll() {
//        //$query = $this->db->get('mst.t_index_transaksi');
//        //this->db->select("*, CASE WHEN aktif is true THEN 'Ya' ELSE 'Tidak' end aktif", FALSE);
//        $query = '';
//        $getTotalQuery = 'select count(*) as total from mst.t_index_transaksi as tabel limit 1';
//        $dbResult = $this->db->get("mst.t_index_transaksi");
//        $query = $this->db->query($getTotalQuery);
//        $total = $query->row()->total;
//        $results = '{success:true,record:' . $total . ',data:' . json_encode($dbResult->result()) . '}';
//        return $results;
//    }
}
