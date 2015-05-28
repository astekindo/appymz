<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class setting_npwp_pelanggan_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAll($length, $offset, $search = "", $kd_pelanggan) {
        $total = 0;
        $queryResults = '';
        if (!empty($kd_pelanggan)) {
            $query = $this->db->query("select * from mst.t_pelanggan_dist where kd_pelanggan = '$kd_pelanggan'");
            if ($query->num_rows() === 1) {
                $total = 1;
                $queryResults = $query->result();
            }
        } else {
            $sqlSearch = "";
            if ($search != "") {
                $sqlSearch = "WHERE ((lower(mst.t_pelanggan_dist.nama_pelanggan) LIKE '%" . strtolower($search) . "%') OR (mst.t_pelanggan_dist.kd_pelanggan LIKE '%" . strtolower($search) . "%'))";
            }
            $getDataQuery = "select * from mst.t_pelanggan_dist $sqlSearch limit $length offset $offset";
            $getTotalQuery = "select count(*) as total from mst.t_pelanggan_dist $sqlSearch";
            $queryGetTotal = $this->db->query($getTotalQuery);
            $queryGetData = $this->db->query($getDataQuery);
            $total = $queryGetTotal->row()->total;

            if ($queryGetData->num_rows() > 0) {
                $queryResults = $queryGetData->result();
            }
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

    public function getAllNpwp($length, $offset, $search = "", $kd_pelanggan) {
        $total = 0;
        $queryResults = '';
        if (!empty($kd_pelanggan)) {
            $query = $this->db->query("select * from mst.t_pelanggan_npwp_dist where kd_pelanggan = '$kd_pelanggan'");
            $query1 = $this->db->query("select count(*) as total from mst.t_pelanggan_npwp_dist where kd_pelanggan = '$kd_pelanggan'");
            if ($query->num_rows() > 0) {
                $total = $query1->row()->total;
                $queryResults = $query->result();
            }
        } else {
            $sqlSearch = "";
            if ($search != "") {
                $sqlSearch = "WHERE lower(mst.t_pelanggan_npwp_dist.kd_pelanggan) LIKE '%" . strtolower($search) . "%' OR (mst.t_pelanggan_npwp_dist.kd_npwp) LIKE '%" . strtolower($search) . "%'";
            }
            $getDataQuery = "select * from mst.t_pelanggan_npwp_dist $sqlSearch limit $length offset $offset";
            $getTotalQuery = "select count(*) as total from mst.t_pelanggan_npwp_dist $sqlSearch";
            $queryGetTotal = $this->db->query($getTotalQuery);
            $queryGetData = $this->db->query($getDataQuery);
            $total = $queryGetTotal->row()->total;
            if ($queryGetData->num_rows() > 0) {
                $queryResults = $queryGetData->result();
            }
        }

        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
//        '{"success":true, "record":' . $total . ', "data":' . $queryResults . '}';
        return $results;
    }

    public function update($data, $kdPelanggan, $kdNpwp) {
        $this->db->where('kd_pelanggan', $kdPelanggan);
        $this->db->where('kd_npwp', $kdNpwp);
        $this->db->update('mst.t_pelanggan_npwp_dist', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function insert($data) {
        $this->db->insert('mst.t_pelanggan_npwp_dist', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    //unused
    public function isDataExist($kdPelanggan, $kdNpwp) {
        $query = $this->db->query("select * from mst.t_pelanggan_npwp_dist where kd_pelanggan = '$kdPelanggan' and kd_npwp='$kdNpwp'");
        $results = $query->result();
        if ($results != null) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($kdPelanggan, $kdNpwp) {
        $this->db->where('kd_pelanggan', $kdPelanggan);
        $this->db->where('kd_npwp', $kdNpwp);
        $this->db->delete("mst.t_pelanggan_npwp_dist");
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

//    public function getDetailPelangganNPWP($kdPelanggan) {
//        $total = 0;
//        $queryResults = '';
//        $query = $this->db->query("select * from mst.t_pelanggan_npwp_dist where kd_pelanggan = '$kdPelanggan'");
//        $queryTotal = $this->db->query("select count(*) as total from mst.t_pelanggan_npwp_dist where kd_pelanggan ='$kdPelanggan'");
//        if ($query->num_rows() > 0) {
//            $total = $queryTotal->row()->total;
//            $queryResults = json_encode($query->row());
//        }
////        $results = '{"success":true, "record":' . $total . ', "data":' . $queryResults . '}';
////        return $results;
//        $results = '{success:true,record:' . $total . ',data:' . $queryResults . '}';
//        return $results;
//    }
//
//    public function getAllPelangganNPWP($length, $offset, $search = "") {
//        $total = 0;
//        $queryResults = '';
//        $sqlSearch = "";
//        if ($search != "") {
//            $sqlSearch = "WHERE ((lower(mst.t_pelanggan_npwp_dist.kd_pelanggan) LIKE '%" . strtolower($search) . "%') OR (mst.t_pelanggan_npwp_dist.kd_npwp) LIKE '%" . strtolower($search) . "%'))";
//        }
//        $getDataQuery = "select * from mst.t_pelanggan_npwp_dist $sqlSearch limit $length offset $offset";
//        $getTotalQuery = "select count(*) as total from mst.t_pelanggan_npwp_dist $sqlSearch";
//        $queryGetTotal = $this->db->query($getTotalQuery);
//        $queryGetData = $this->db->query($getDataQuery);
//        $total = $queryGetTotal->row()->total;
//
//        if ($queryGetData->num_rows() > 0) {
//            $data = $queryGetData->result();
//        }
//        $queryResults = json_encode($data);
//        $results = '{"success":true, "record":' . $total . ', "data":' . $queryResults . '}';
//        return $results;
//    }
//
//     public function getNPWP($kodePelanggan) {
//        $sql = "SELECT a.* FROM mst.t_pelanggan_dist a, mst.t_pelanggan_npwp_dist b
//                WHERE a.kd_pelanggan='$kodePelanggan' AND
//                b.kd_pelanggan=a.kd_pelanggan
//					";
//        $query = $this->db->query($sql);
//
//        if ($query->num_rows() != 0) {
//            $row = $query->row();
//            return $row;
//        }
//    }
}
