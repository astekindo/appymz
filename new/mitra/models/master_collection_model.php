<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_collection_model extends MY_Model {

    protected $table = 'mst.t_collection';
    protected $pk = 'kd_collector';

    public function __construct(){
        parent::__construct();
    }

    public function get_rows($search = "", $start, $limit) {
        $this->db->select("t_collection.kd_collector, t_collection.kd_cabang::text, t_collection.nama_collector,
            t_collection.alamat, t_collection.no_telp, t_collection.no_telp2, t_collection.email,, t_collection.pin_bb,
            CASE WHEN t_collection.status = 1 THEN 'aktif' ELSE 'tidak aktif' END status,
            t_cabang.nama_cabang", false)
          ->from('mst.t_collection')
          ->join('mst.t_cabang', 't_collection.kd_cabang = t_cabang.kd_cabang')
          //->join('mst.t_area', 't_collection.kd_area = t_area.kd_area')
          ->limit($limit, $start)
          ->order_by('kd_collector','ASC');
        if($search != "") {
            $this->db->like('t_collection.kd_collector', $search);
            $this->db->or_like('t_collection.nama_collector', $search);
        }

        $query = $this->db->get();
        $data['total'] = $query->num_rows();
        if($query->num_rows() > 0) {
//            $data['rows'] = $this->db->last_query();
            $data['rows'] = $query->result();
        }

        return $data;
    }

    public function get_row($kd_collector) {
        $this->db->select("t_collection.kd_collector, t_collection.kd_cabang::text, t_collection.nama_collector,
            t_collection.alamat, t_collection.no_telp, t_collection.no_telp2,t_collection.status,t_collection.email,t_collection.pin_bb,
            t_cabang.nama_cabang", false)
          ->from('mst.t_collection')
          ->join('mst.t_cabang', 't_collection.kd_cabang = t_cabang.kd_cabang')
          //->join('mst.t_area', 't_collection.kd_area = t_area.kd_area')
          ->where('t_collection.kd_collector', $kd_collector)
          ->order_by('kd_collector','ASC');
        $query = $this->db->get();
        $data['total'] = $query->num_rows();
        if($query->num_rows() > 0) {
            $data['rows'] = $query->row();
        }

        return $data;
    }

    public function insert_row($data) {
        return $this->db->insert($this->table,$data);
    }

    public function update_row($kd_collector,$data) {
        $this->db->where($this->pk, $kd_collector);
        return $this->db->update($this->table, $data);
    }

    public function delete_row($kd_collector) {
        $this->db->where('kd_collector',$kd_collector);
        return $this->db->update('mst.t_collection', array('status' => 0));
    }

    public function get_cabang(){
        $results = $this->db->get_where('mst.t_cabang', array('status' => 1));

        return $results->result();
    }

    public function get_area() {
        $results = $this->db->get_where('mst.t_area', array('status' => 1));
        return $results->result();
    }

}
?>