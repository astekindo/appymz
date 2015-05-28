<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of setting_penukaran_point_model
 *
 * @author Yakub
 */
class setting_penukaran_point_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getDataProduk($limit, $offset, $search = "", $kdProduk = "") {
        $result = '';
        $total = 0;
        $queryTotal = '';
        $queryData = '';
        if (!empty($kdProduk)) {
            $queryData = "SELECT a.kd_produk,a.nama_produk,b.nm_satuan "
                    . "FROM mst.t_produk a,mst.t_satuan b "
                    . "WHERE a.kd_satuan=b.kd_satuan AND a.kd_produk = $kdProduk";
            $queryTotal = "SELECT COUNT(*) as total FROM "
                    . "(SELECT a.kd_produk,a.nama_produk,b.nm_satuan "
                    . "FROM mst.t_produk a,mst.t_satuan b "
                    . "WHERE a.kd_satuan=b.kd_satuan AND a.kd_produk = '$kdProduk' ) a";
        } else {
            $sqlSearch = "";
            if ($search) {
                $sqlSearch = "AND ((lower(a.kd_produk) LIKE '%" . strtolower($search) . "%') OR (a.nama_produk LIKE '%$search%') OR (b.nm_satuan LIKE '%$search%'))";
            }
            $queryData = "SELECT a.kd_produk,a.nama_produk,b.nm_satuan "
                    . "FROM mst.t_produk a,mst.t_satuan b "
                    . "WHERE a.kd_satuan=b.kd_satuan $sqlSearch limit $limit offset $offset";
            $queryTotal = "SELECT COUNT(*) as total FROM "
                    . "(SELECT a.kd_produk,a.nama_produk,b.nm_satuan "
                    . "FROM mst.t_produk a,mst.t_satuan b "
                    . "WHERE a.kd_satuan=b.kd_satuan $sqlSearch limit $limit offset $offset) a";
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

    public function getDataPenukaranPoint($limit, $offset, $search = "", $kdProduk = "") {
        $result = '';
        $total = 0;
        $queryTotal = '';
        $queryData = '';
  
        if (!empty($kdProduk)) {
            $queryData = "SELECT a.*,b.nama_produk,c.nm_satuan 
                          FROM mst.t_point_setting_penukaran a    
                          LEFT JOIN mst.t_produk b on b.kd_produk=a.kd_barang 
                          ,mst.t_satuan c
                          WHERE b.kd_satuan=c.kd_satuan AND a.aktif=1 AND a.kd_barang='$kdProduk'";
            $queryTotal = "SELECT COUNT(*) AS total FROM(SELECT a.*,b.nama_produk,c.nm_satuan 
                           FROM mst.t_point_setting_penukaran a 
                           LEFT JOIN mst.t_produk b ON b.kd_produk=a.kd_barang 
                           ,mst.t_satuan c
                           WHERE b.kd_satuan=c.kd_satuan AND a.aktif=1 AND a.kd_barang='$kdProduk')a";
        } else {
            $sqlSearch = "";
            if ($search) {
                $sqlSearch = "AND ((lower(a.kd_barang) LIKE '%" . strtolower($search) . "%') OR (b.nama_produk LIKE '%$search%'))";
            }
            $queryData = "SELECT a.*,b.nama_produk,c.nm_satuan 
                          FROM mst.t_point_setting_penukaran a    
                          LEFT JOIN mst.t_produk b on b.kd_produk=a.kd_barang 
                          ,mst.t_satuan c
                          WHERE b.kd_satuan=c.kd_satuan AND a.aktif=1 $sqlSearch limit $limit offset $offset";
            $queryTotal = "SELECT COUNT(*) as total FROM 
                          (SELECT a.*,b.nama_produk,c.nm_satuan 
                          FROM mst.t_point_setting_penukaran a    
                          LEFT JOIN mst.t_produk b on b.kd_produk=a.kd_barang 
                          ,mst.t_satuan c
                          WHERE b.kd_satuan=c.kd_satuan AND a.aktif=1 $sqlSearch limit $limit offset $offset)a";
        }
        $getData = $this->db->query($queryData);
        $getTotal = $this->db->query($queryTotal);

        if ($getData->num_rows() > 0) {
            foreach ($getData->result_array() as $data) {
                $data['qty_tukar'] = 1;
                $data['jumlah_point_tukar'] = $data['qty_tukar'] * $data['jumlah_point'];
                $result[] = $data;
            }
            $total = $getTotal->row()->total;
        }
        
        $success = array(
            'success' => true,
            'record' => $total,
            'data' => $result
        );

        return json_encode($success);
    }

    public function insert($data) {
        $success = '';
        if ($this->isDataExist($data['kd_barang'], $data['qty'])) {
            $success = array(
                'success' => false,
                'errMsg' => 'Data Telah Ada'
            );
        } else {
            $this->db->insert('mst.t_point_setting_penukaran', $data);
            $success = array(
                'success' => true
            );
        }
        return json_encode($success);
    }

    public function update($data, $kdBarang, $qty) {
        $this->db->where('kd_barang', $kdBarang);
        $this->db->where('qty', $qty);
        $this->db->update('mst.t_point_setting_penukaran', $data);
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    public function delete($kdBarang, $qty) {
        $this->db->where('kd_barang', $kdBarang);
        $this->db->where('qty', $qty);
        $this->db->delete('mst.t_point_setting_penukaran');
        $success = array(
            'success' => true
        );
        return json_encode($success);
    }

    private function isDataExist($kdBarang, $qty) {
        $exist = $this->db->query("SELECT * FROM mst.t_point_setting_penukaran a WHERE a.kd_barang = '$kdBarang' AND a.qty ='$qty'");
        if ($exist->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
