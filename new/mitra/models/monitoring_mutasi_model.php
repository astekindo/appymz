<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_mutasi_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search, $peruntukan, $start, $limit, $filter)
    {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if (!empty($search)) {
            $this->db->like('lower(no_mutasi_stok)',strtolower($search));
        }
        if( !empty($filter) ) {
            if( isset($filter['no_mutasi']) )       $this->db->where('b.no_mutasi_stok', $filter['no_mutasi']);
            if( isset($filter['kd_produk']) )       $this->db->where('a.kd_produk', $filter['kd_produk']);
            if( isset($filter['tgl_awal']) )        $this->db->where('b.tgl_mutasi', $filter['tgl_awal']);
            if( isset($filter['tgl_akhir']) )       $this->db->where('b.tgl_mutasi', $filter['tgl_akhir']);
            if( isset($filter['lokasi_awal']) )     $this->db->where('c.kd_lokasi', $filter['lokasi_awal']);
            if( isset($filter['lokasi_tujuan']) )   $this->db->where('d.kd_lokasi', $filter['lokasi_tujuan']);
        }
        if(intval($peruntukan) != 2) {
            $this->db->where('tujuan', "$peruntukan");
        }
        $sql = "distinct on(a.no_mutasi_stok) b.no_mutasi_stok, b.tgl_mutasi, b.userid created_by, b.no_ref,
        CASE b.status WHEN 0 THEN 'Mutasi Out' WHEN 1 THEN 'Mutasi In' END status,
        b.keterangan, b.nama_pengambil, coalesce(c.kd_lokasi, '-') lokasi_awal, coalesce(d.kd_lokasi, '-') lokasi_tujuan,
        coalesce(c.nama_lokasi, '-') nama_lokasi_awal, coalesce(d.nama_lokasi, '-') nama_lokasi_tujuan";

        $this->db->select($sql, FALSE)->from('inv.t_mutasi_barang_detail a')
        ->join('inv.t_mutasi_barang b', 'a.no_mutasi_stok = b.no_mutasi_stok')
        ->join('mst.t_lokasi c', 'a.kd_lokasi_awal = c.kd_lokasi', 'left')
        ->join('mst.t_lokasi d', 'a.kd_lokasi_tujuan = d.kd_lokasi', 'left')
        ->stop_cache();
        $results['total'] = $this->db->count_all_results();

        $this->db->order_by('a.no_mutasi_stok desc')->limit($limit, $start);
        $query = $this->db->get();
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_no_mutasi($search, $peruntukan, $start, $limit, $all = FALSE)
    {
        $results= array('data' => array(), 'total' => 0);
        $this->db->start_cache();
        if (!empty($search)) {
            $this->db->like('no_mutasi_stok',"$search");
        }
        if(!$all) {
            $this->db->where('status', 0);
        }
        if(intval($peruntukan) != 2) {
            $this->db->where('tujuan', "$peruntukan");
        }

        $this->db->select('no_mutasi_stok,tgl_mutasi,no_ref,nama_pengambil,keterangan,DATE(now()) as tgl_mutasi_in', FALSE);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('inv.t_mutasi_barang');

        $this->db->order_by('no_mutasi_stok desc');
        $query = $this->db->get('inv.t_mutasi_barang', $limit, $start);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

}