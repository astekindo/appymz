<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_area_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows($search = "", $offset, $length) {
        if ($search != "") {
            $sql_search = " and (lower(a.kd_area) LIKE '%" . strtolower($search) . "%' or lower(a.nama_area) LIKE '%" . strtolower($search) . "%') ";
        }

        $sql = "select a.kd_perusahaan,a.kd_cabang,a.kd_area,a.nama_area,a.kd_propinsi, b.nama_propinsi,
                case when status = 1 then 'aktif' else 'non aktif' end status
                from mst.t_area a, mst.t_propinsi b where a.kd_propinsi = b.kd_propinsi $sql_search
                order by kd_area desc limit $length offset $offset";
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(kd_area) as total from mst.t_area $sql_search ";
        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row($id = NULL) {
        $sql = "SELECT * FROM mst.t_area WHERE kd_area = '$id'";

        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_area', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_area', $id);
        return $this->db->update('mst.t_area', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $this->db->where('kd_area', $id);
        return $this->db->update('mst.t_area', array('status' => 0));
    }

    public function getCustomers($limit, $offset, $search = "", $kdArea) {
        $total = 0;
        $queryResults = '';
        $sqlSearch = "";
        if ($search != "") {
            $sqlSearch = "AND ((lower(mst.t_pelanggan_dist.nama_pelanggan) LIKE '%" . strtolower($search) . "%') OR (mst.t_pelanggan_dist.kd_pelanggan LIKE '%" . strtolower($search) . "%'))";
        }

        $getDataQuery = "SELECT a.*,b.nama_propinsi,c.nama_kota,d.nama_kecamatan,e.nama_kalurahan,f.nama_area,g.nama_cabang,
                         CASE WHEN a.aktif = 1 THEN 'aktif' 
                         ELSE 'tidak aktif' 
                         END status,
                         CASE 
                         WHEN a.tipe = 0 THEN 'TOKO' 
                         WHEN a.tipe = 1 THEN 'AGEN'
                         WHEN a.tipe = 2 THEN 'MODERN MARKET'
                         ELSE 'UNKNOWN' 
                         END nama_tipe,
                         CASE
                         WHEN a.is_pkp = 0 THEN 'tidak' 
                         ELSE 'ya' 
                         END pkp
                         FROM mst.t_pelanggan_dist a 
                         LEFT JOIN mst.t_propinsi b on a.kd_propinsi=b.kd_propinsi
                         LEFT JOIN mst.t_kota c on a.kd_kota=c.kd_kota
                         LEFT JOIN mst.t_kecamatan d on a.kd_kecamatan=d.kd_kecamatan
                         LEFT JOIN mst.t_kalurahan e on a.kd_kalurahan=e.kd_kalurahan
                         LEFT JOIN mst.t_area f on a.kd_area=f.kd_area
                         LEFT JOIN mst.t_cabang g on a.kd_cabang=g.kd_cabang
                         WHERE a.kd_area='$kdArea'
                         $sqlSearch LIMIT $limit OFFSET $offset";
        $getTotalQuery = "SELECT COUNT(*) AS total FROM 
                         (SELECT a.*,b.nama_propinsi,c.nama_kota,d.nama_kecamatan,e.nama_kalurahan,f.nama_area,g.nama_cabang,
                         CASE WHEN a.aktif = 1 THEN 'aktif' 
                         ELSE 'tidak aktif' 
                         END status
                         FROM mst.t_pelanggan_dist a 
                         LEFT JOIN mst.t_propinsi b on a.kd_propinsi=b.kd_propinsi
                         LEFT JOIN mst.t_kota c on a.kd_kota=c.kd_kota
                         LEFT JOIN mst.t_kecamatan d on a.kd_kecamatan=d.kd_kecamatan
                         LEFT JOIN mst.t_kalurahan e on a.kd_kalurahan=e.kd_kalurahan
                         LEFT JOIN mst.t_area f on a.kd_area=f.kd_area
                         LEFT JOIN mst.t_cabang g on a.kd_cabang=g.kd_cabang
                         WHERE a.kd_area='$kdArea'
                         )a";
        $queryGetTotal = $this->db->query($getTotalQuery);
        $queryGetData = $this->db->query($getDataQuery);

        if ($queryGetData->num_rows() > 0) {
            $queryResults = $queryGetData->result();
            $total = $queryGetTotal->row()->total;
        }


        $results = json_encode(array(
            'success' => true,
            'record' => $total,
            'data' => $queryResults
        ));
        return $results;
    }

}
