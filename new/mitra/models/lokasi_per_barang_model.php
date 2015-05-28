<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lokasi_per_barang_model extends MY_Model {

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
    public function get_lokasi_barang($kd_peruntukan, $search, $params,$offset, $length) {
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->where("(lower(a.kd_produk) LIKE '%" . strtolower($search) .
             "%' OR lower(a.nama_produk) LIKE '%" . strtolower($search) . "%')", NULL, false);
        }
        if($kd_peruntukan != 2) {
            $this->db->where('c.kd_peruntukan', "$kd_peruntukan");
        }

        if(array_key_exists('kdLokasi', $params) && !empty($params['kdLokasi'])){
            $this->db->where('b.kd_lokasi', $params['kdLokasi']);
        }

        if(array_key_exists('kdBlok', $params) && !empty($params['kdBlok'])){
            $this->db->where('b.kd_blok', $params['kdBlok']);
        }

        if(array_key_exists('kdSubBlok', $params) && !empty($params['kdSubBlok'])){
            $this->db->where('b.kd_sub_blok', $params['kdSubBlok']);
        }
        if(array_key_exists('kdSatuan', $params) && !empty($params['kdSatuan'])){
            $this->db->where('a.kd_satuan', $params['kdSatuan']);
        }
        if(array_key_exists('kdUkuran', $params) && !empty($params['kdUkuran'])){
            $this->db->where('a.kd_ukuran', $params['kdUkuran']);
        }
        if(array_key_exists('kdKategori1', $params) && !empty($params['kdKategori1'])){
            $this->db->where('a.kd_kategori1', $params['kdKategori1']);
        }
        if(array_key_exists('kdKategori2', $params) && !empty($params['kdKategori2'])){
            $this->db->where('a.kd_kategori2', $params['kdKategori2']);
        }
        if(array_key_exists('kdKategori3', $params) && !empty($params['kdKategori3'])){
            $this->db->where('a.kd_kategori3', $params['kdKategori3']);
        }
        if(array_key_exists('kdKategori4', $params) && !empty($params['kdKategori4'])){
            $this->db->where('a.kd_kategori4', $params['kdKategori4']);
        }
        if(array_key_exists('kdSuplier', $params) && !empty($params['kdSuplier'])){
            $this->db->where('g.kd_supplier', $params['kdSuplier'])->where('g.aktif', "true")
            ->join('mst.t_supp_per_brg g', 'a.kd_produk = g.kd_produk');
        }

        $this->db->select("c.nama_lokasi || ' - ' || d.nama_blok || ' - ' || e.nama_sub_blok lokasi,
	CASE c.kd_peruntukan::numeric WHEN 0 THEN 'Supermarket' ELSE 'Distribusi' END peruntukan,
	a.kd_produk, a.nama_produk, b.qty_oh,f.nm_satuan", false)
        ->join('inv.t_brg_inventory b', 'a.kd_produk = b.kd_produk')
        ->join('mst.t_lokasi c', 'b.kd_lokasi = c.kd_lokasi')
        ->join('mst.t_blok d', 'b.kd_blok = d.kd_blok and c.kd_lokasi = d.kd_lokasi')
        ->join('mst.t_sub_blok e', 'b.kd_sub_blok = e.kd_sub_blok and d.kd_blok = e.kd_blok and c.kd_lokasi = e.kd_lokasi')
        ->join('mst.t_satuan f', 'a.kd_satuan = f.kd_satuan');

        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_produk a');

        $this->db->order_by('a.kd_produk desc, a.kd_kategori1 asc, a.kd_kategori2 asc, a.kd_kategori3 asc, a.kd_kategori4 asc');
        $query = $this->db->get('mst.t_produk a', $length, $offset);
        $results['lq'] = $this->db->last_query();
        $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
    }

    public function get_barang_per_lokasi($kdLokasi = "", $kdBlok = "", $kdSubBlok = "", $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND (lower(subject) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = "SELECT a.kd_produk, e.nama_produk, b.nama_lokasi, nm_satuan,
					c.nama_blok, d.nama_sub_blok, a.qty_oh
					FROM inv.t_brg_inventory a
					INNER JOIN mst.t_lokasi b ON a.kd_lokasi = b.kd_lokasi
					INNER JOIN mst.t_blok c ON a.kd_blok = c.kd_blok
					INNER JOIN mst.t_sub_blok d ON a.kd_sub_blok = d.kd_sub_blok
					INNER JOIN mst.t_produk e ON a.kd_produk = e.kd_produk
					INNER JOIN mst.t_satuan f ON e.kd_satuan = f.kd_satuan
					WHERE c.kd_lokasi = '$kdLokasi' and d.kd_blok = '$kdBlok' and d.kd_sub_blok = '$kdSubBlok'
					and b.kd_lokasi = c.kd_lokasi AND
						c.kd_blok = d.kd_blok AND
						b.kd_lokasi = d.kd_lokasi";

        $query = $this->db->query($sql1);


        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) AS total FROM (SELECT inv.t_brg_inventory.kd_produk, mst.t_produk.nama_produk, mst.t_lokasi.nama_lokasi,
					mst.t_blok.nama_blok, mst.t_sub_blok.nama_sub_blok, inv.t_brg_inventory.qty_oh
					FROM inv.t_brg_inventory
					INNER JOIN mst.t_lokasi ON inv.t_brg_inventory.kd_lokasi = mst.t_lokasi.kd_lokasi
					INNER JOIN mst.t_blok ON inv.t_brg_inventory.kd_blok = mst.t_blok.kd_blok
					INNER JOIN mst.t_sub_blok ON inv.t_brg_inventory.kd_sub_blok = mst.t_sub_blok.kd_sub_blok
					INNER JOIN mst.t_produk ON inv.t_brg_inventory.kd_produk = mst.t_produk.kd_produk
					WHERE mst.t_lokasi.kd_lokasi = mst.t_blok.kd_lokasi AND
						mst.t_blok.kd_blok = mst.t_sub_blok.kd_blok AND
						mst.t_lokasi.kd_lokasi = mst.t_sub_blok.kd_lokasi) as tabel";

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
        $this->db->where("id_ro", $id);
        $query = $this->db->get('tt_receive_order');

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
        return $this->db->insert('tt_receive_order', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('id_ro', $id);
        return $this->db->update('tt_receive_order', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $this->db->where('id_ro', $id);
        return $this->db->delete('tt_receive_order');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all() {
        $this->db->where("aktif is true", NULL);
        $this->db->order_by("id_ro", 'asc');
        $query = $this->db->get("tt_receive_order");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_kategori1() {
        $results= array('data' => array(), 'total' => 0);

        $this->db->select('kd_kategori1,nama_kategori1')->where('aktif','true');
        $this->db->order_by('nama_kategori1', 'asc');
        $query = $this->db->get('mst.t_kategori1');

        $results['data'] = $query->result();
        $results['total'] = $query->num_rows();
        $results['lq'] = $this->db->last_query();

        return $results;
    }
    public function get_kategori2($kategori1 = null) {
        $results= array('data' => array(), 'total' => 0);

        $this->db->select('distinct on(kd_kategori2_p, nama_kategori2_p) kd_kategori2_p as kd_kategori2,nama_kategori2_p as nama_kategori2', false)
          ->where('kategori1_aktif','true')
          ->where('kategori2_aktif','true');
        if(!empty($kategori1)) $this->db->where('kd_kategori1',$kategori1);
        $this->db->order_by('kd_kategori2_p asc');
        $query = $this->db->get('report.v_list_kategori');

        $results['data'] = $query->result();
        $results['total'] = $query->num_rows();
        $results['lq'] = $this->db->last_query();

        return $results;
    }
    public function get_kategori3($kategori1, $kategori2) {
        $results= array('data' => array(), 'total' => 0);

        $this->db->select('distinct on(kd_kategori3_p) kd_kategori3_p as kd_kategori3,nama_kategori3_p as nama_kategori3', false)
          ->where('kategori1_aktif','true')
          ->where('kategori2_aktif','true')
          ->where('kategori3_aktif','true');
        if(!empty($kategori1)) $this->db->where('kd_kategori1',$kategori1);
        if(!empty($kategori2)) $this->db->where('kd_kategori2_p',$kategori2);
        $this->db->order_by('kd_kategori3_p asc');
        $query = $this->db->get('report.v_list_kategori');

        $results['data'] = $query->result();
        $results['total'] = $query->num_rows();
        $results['lq'] = $this->db->last_query();

        return $results;
    }
    public function get_kategori4($kategori1, $kategori2, $kategori3) {
        $results= array('data' => array(), 'total' => 0);
        $this->db->select('distinct on(kd_kategori4_p) kd_kategori4_p as kd_kategori4,nama_kategori4_p as nama_kategori4', false)
          ->where('kategori1_aktif','true')
          ->where('kategori2_aktif','true')
          ->where('kategori3_aktif','true')
          ->where('kategori4_aktif','true');
        if(!empty($kategori1)) $this->db->where('kd_kategori1',$kategori1);
        if(!empty($kategori2)) $this->db->where('kd_kategori2_p',$kategori2);
        if(!empty($kategori3)) $this->db->where('kd_kategori3_p',$kategori3);
        $this->db->order_by('kd_kategori4_p asc');
        $query = $this->db->get('report.v_list_kategori');

        $results['data'] = $query->result();
        $results['total'] = $query->num_rows();
        $results['lq'] = $this->db->last_query();

        return $results;
    }
}
