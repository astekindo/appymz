<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of setting_sales_pelanggan_model
 *
 * @author Yakub
 */
class setting_sales_pelanggan_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataSales($limit, $offset, $search = "", $kdSales = "") {
        $result = '';
        $total = 0;
        $queryTotal = '';
        $queryData = '';
        if (!empty($kdSales)) {
            $queryData = "SELECT * FROM mst.t_sales a WHERE a.kd_sales = $kdSales";
            $queryTotal = "SELECT COUNT(*) as total FROM mst.t_sales a WHERE a.ks_sales = $kdSales";
        } else {
            $sqlSearch = "";
            if ($search) {
                $sqlSearch = "WHERE ((lower(a.kd_sales) LIKE '%" . strtolower($search) . "%') OR (a.nama_sales LIKE '%$search%'))";
            }
            $queryData = "SELECT * FROM mst.t_sales a $sqlSearch limit $limit offset $offset";
            $queryTotal = "SELECT COUNT(*) as total FROM mst.t_sales a $sqlSearch";
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

    public function getDataSalesPelanggan($limit, $offset, $search = "", $kdSales = "") {
        $result = '';
        $total = 0;
        $sqlSearch = "";
        $sqlSales ="";
        if ($search) {
            $sqlSearch = "AND (c.nama_area LIKE '%$search%') OR (c.nama_area LIKE '%" . strtoupper($search) . "%')";
        }
        if($kdSales){
            $sqlSales=" AND a.kd_sales='$kdSales'";
        }
        $queryData = "SELECT a.*,b.nama_sales,c.nama_area FROM
                    mst.t_sales_area a
                    LEFT JOIN mst.t_sales b
                    ON b.kd_sales=a.kd_sales
                    LEFT JOIN mst.t_area c
                    ON c.kd_area=a.kd_area
                    WHERE 1=1 $sqlSales $sqlSearch limit $limit offset $offset";
        
        $queryTotal = "SELECT COUNT(*) FROM 
                      (SELECT a.*,b.nama_sales,c.nama_area FROM
                        mst.t_sales_area a
                        LEFT JOIN mst.t_sales b
                        ON b.kd_sales=a.kd_sales
                        LEFT JOIN mst.t_area c
                        ON c.kd_area=a.kd_area
                        WHERE 1=1 $sqlSales $sqlSearch limit $limit offset $offset)a";

        $getData = $this->db->query($queryData);
        //print_r($this->db->last_query());
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

    public function getDataAreaDist($limit, $offset, $search = "") {
        $result = '';
        $total = 0;
        $sqlSearch = "";
        if ($search) {
            $sqlSearch = "AND ((lower(a.kd_area) LIKE '%" . strtolower($search) . "%') OR (a.nama_area LIKE '%" . strtolower($search) . "%'))";
        }
        $queryData = "SELECT * FROM mst.t_area a where a.kd_area not in (select kd_area from mst.t_sales_area) and status = 1
                      $sqlSearch limit $limit offset $offset";
        $queryTotal = "SELECT COUNT(*) FROM mst.t_area a where a.kd_area not in (select kd_area from mst.t_sales_area) and status = 1
                      $sqlSearch ";

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
        if ($this->isDataExist($data['kd_sales'], $data['kd_area'])) {
            $success = array(
                'success' => false,
                'errMsg' => 'Data Telah Ada'
            );
        } else {
            $this->db->insert('mst.t_sales_area', $data);
            $success = array(
                'success' => true
            );
        }
        return json_encode($success);
    }

    public function update($data, $kdSales, $kdArea) {
        $success = '';
        if ($this->isDataExist($data['kd_sales'], $data['kd_area'])) {
            $success = array(
                'success' => false,
                'errMsg' => 'Data Telah Ada'
            );
        } else {
            $this->db->where('kd_sales', $kdSales);
            //$this->db->where('kd_area', $kdArea);
            $this->db->update('mst.t_sales_area', $data);
            $success = array(
                'success' => true
            );
        }
        return json_encode($success);
    }

    public function delete($kdSales, $kdArea) {
        $this->db->where('kd_sales', $kdSales);
        $this->db->where('kd_area', $kdArea);
        $this->db->delete('mst.t_sales_area');
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    private function isDataExist($kdSales, $kdArea) {
        $exist = $this->db->query("SELECT * FROM mst.t_sales_area a WHERE a.kd_sales = '$kdSales' AND a.kd_area='$kdArea'");
        if ($exist->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
