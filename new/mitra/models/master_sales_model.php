<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_sales_model extends MY_Model {

    protected $table = 'mst.t_sales';
    protected $pk = 'kd_sales';

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $start, $limit) {
        $this->db->select("t_sales.kd_sales, t_sales.kd_cabang::text, t_sales.nama_sales, t_sales.alamat, t_sales.no_telp, t_sales.no_telp2,t_sales.pin_bb,t_sales.email,
                           CASE WHEN t_sales.status = 1 THEN 'aktif' 
                           ELSE 'tidak aktif' 
                           END status, t_cabang.nama_cabang", false)
                ->from('mst.t_sales')
                ->join('mst.t_cabang', 't_sales.kd_cabang = t_cabang.kd_cabang')
                //->join('mst.t_area', 't_sales.kd_area = t_area.kd_area')
                ->limit($limit, $start)
                ->order_by('kd_sales', 'ASC');
        if ($search != "") {
            $this->db->like('t_sales.kd_sales', $search);
            $this->db->or_like('t_sales.nama_sales', $search);
        }

        $query = $this->db->get();
        $data['total'] = $query->num_rows();
        if ($query->num_rows() > 0) {
            //$data['rows'] = $this->db->last_query();
            $data['rows'] = $query->result();
        }

        return $data;
    }

    public function get_row($kd_sales) {
        $this->db->select("t_sales.kd_sales, t_sales.kd_cabang::text, t_sales.nama_sales, "
                . "t_sales.alamat, t_sales.no_telp,pin_bb, t_sales.no_telp, t_sales.email, "
                . "t_sales.no_telp2, t_sales.status, t_cabang.nama_cabang", false)
                ->from('mst.t_sales')
                ->join('mst.t_cabang', 't_sales.kd_cabang = t_cabang.kd_cabang')
                //->join('mst.t_area', 't_sales.kd_area = t_area.kd_area')
                ->where('t_sales.kd_sales', $kd_sales)
                ->order_by('kd_sales', 'ASC');
        $query = $this->db->get();
        $data['total'] = $query->num_rows();
        if ($query->num_rows() > 0) {
            //$data['rows'] = $this->db->last_query();
            $data['rows'] = $query->row();
        }

        return $data;
    }

    public function insert_row($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update_row($kd_sales, $data) {
        $this->db->where($this->pk, $kd_sales);
        return $this->db->update($this->table, $data);
    }

    public function delete_row($kd_sales) {
        $this->db->where('kd_sales', $kd_sales);
        return $this->db->update('mst.t_sales', array('status' => 0));
    }

    public function get_cabang() {
        $results = $this->db->get_where('mst.t_cabang', array('status' => 1));

        return $results->result();
    }

    public function get_area() {
        $results = $this->db->get_where('mst.t_area', array('status' => 1));
        return $results->result();
    }

}

?>