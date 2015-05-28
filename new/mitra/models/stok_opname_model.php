<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stok_opname_model extends MY_Model {

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
    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012

     */
    public function get_barang($kdLokasi = NULL, $kdBlok = NULL, $kdSubBlok = NULL) {
        $query = $this->db->query(" SELECT a.kd_produk, g.nama_produk, f.nm_satuan, a.qty_oh, a.qty_oh qty_adjust,
							a.qty_oh-a.qty_oh penyesuaian, 
							nama_lokasi, nama_blok, nama_sub_blok
							FROM inv.t_brg_inventory a, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d,
							mst.t_produk g, mst.t_satuan f
							WHERE a.kd_lokasi = '$kdLokasi' AND a.kd_blok = '$kdBlok' AND a.kd_sub_blok = '$kdSubBlok' 
							AND b.kd_lokasi = a.kd_lokasi
							AND c.kd_blok = a.kd_blok AND c.kd_lokasi = b.kd_lokasi
							AND d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = c.kd_blok AND d.kd_lokasi = b.kd_lokasi
							AND g.kd_produk = a.kd_produk 
							AND f.kd_satuan = g.kd_satuan
							ORDER BY a.kd_produk ");
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_barang_entry($noopname = NULL) {
        $query = $this->db->query(" SELECT   
  t_stok_opname_detail.kd_produk, 
  t_produk.nama_produk, 
  t_satuan.nm_satuan, 
  t_stok_opname_detail.qty, 
  t_stok_opname_detail.qty_adjust, 
  t_stok_opname_detail.qty_penyesuaian
FROM 
  inv.t_stok_opname_detail, 
  mst.t_produk, 
  mst.t_satuan
WHERE 
  t_stok_opname_detail.kd_produk = t_produk.kd_produk AND
  t_produk.kd_satuan = t_satuan.kd_satuan and t_stok_opname_detail.no_opname='$noopname' order by t_stok_opname_detail.kd_produk ");
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_initstok($kdLokasi = "", $kdBlok = "", $kdSubBlok = "", $kdKat1 = "", $kdKat2 = "", $kdKat3 = "", $kdKat4 = "") {
        
        $where = "";
        
        if($kdLokasi != ""){
                $where .=  " AND a.kd_lokasi = '$kdLokasi' ";
        }
        if($kdBlok != ""){
                $where .=  " AND a.kd_blok = '$kdBlok' ";
        }

        if($kdSubBlok != ""){
                $where .=  " AND a.kd_sub_blok = '$kdSubBlok'  ";
        }

        if($kdKat1 != ""){
                $where .=  " AND g.kd_kategori1 = '$kdKat1' ";
        }
        
        if($kdKat2 != ""){
                $where .=  " AND g.kd_kategori2 = '$kdKat2' ";
        }
        
        if($kdKat3 != ""){
                $where .=  " AND g.kd_kategori3 = '$kdKat3' ";
        }
        
        if($kdKat4 != ""){
                $where .=  " AND g.kd_kategori4 = '$kdKat4' ";
        }
        
        $query = $this->db->query(" SELECT a.kd_produk, g.nama_produk, f.nm_satuan, a.qty_oh,
							nama_lokasi, nama_blok, nama_sub_blok
							FROM inv.t_brg_inventory a, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d,
							mst.t_produk g, mst.t_satuan f
							WHERE 1=1 ".$where." 
							AND b.kd_lokasi = a.kd_lokasi
							AND c.kd_blok = a.kd_blok AND c.kd_lokasi = b.kd_lokasi
							AND d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = c.kd_blok AND d.kd_lokasi = b.kd_lokasi
							AND g.kd_produk = a.kd_produk 
							AND f.kd_satuan = g.kd_satuan
							ORDER BY a.kd_produk ");
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_head_entrystok($search = "", $offset, $length) {
$sql_search = "";
		if($search != ""){
			$sql_search = " AND (lower(t_stok_opname.no_opname) LIKE '%" . strtolower($search) . "%' OR lower(t_sub_blok.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
		}
        $query = $this->db->query(" SELECT 
  t_stok_opname.no_opname, 
  t_stok_opname.tgl_opname, 
  t_lokasi.nama_lokasi, 
  t_blok.nama_blok, 
  t_sub_blok.nama_sub_blok, 
  t_stok_opname.keterangan
FROM 
  inv.t_stok_opname, 
  mst.t_lokasi, 
  mst.t_blok, 
  mst.t_sub_blok
WHERE 
  t_stok_opname.kd_lokasi = t_lokasi.kd_lokasi AND
  t_stok_opname.kd_blok = t_blok.kd_blok AND
  t_stok_opname.kd_lokasi = t_blok.kd_lokasi AND
  t_stok_opname.kd_lokasi = t_sub_blok.kd_lokasi AND
  t_stok_opname.kd_blok = t_sub_blok.kd_blok AND
  t_stok_opname.kd_sub_blok = t_sub_blok.kd_sub_blok AND t_stok_opname.status=0 
  ".$sql_search." LIMIT ".$length." OFFSET ".$offset);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_head_approvalstok($search = "", $offset, $length) {
$sql_search = "";
		if($search != ""){
			$sql_search = " AND (lower(t_stok_opname.no_opname) LIKE '%" . strtolower($search) . "%' OR lower(t_sub_blok.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
		}
        $query = $this->db->query(" SELECT 
  t_stok_opname.no_opname, 
  t_stok_opname.tgl_opname, 
  t_stok_opname.kd_lokasi,
  t_stok_opname.kd_blok,
  t_stok_opname.kd_sub_blok,
  t_lokasi.nama_lokasi, 
  t_blok.nama_blok, 
  t_sub_blok.nama_sub_blok, 
  t_stok_opname.keterangan
FROM 
  inv.t_stok_opname, 
  mst.t_lokasi, 
  mst.t_blok, 
  mst.t_sub_blok
WHERE 
  t_stok_opname.kd_lokasi = t_lokasi.kd_lokasi AND
  t_stok_opname.kd_blok = t_blok.kd_blok AND
  t_stok_opname.kd_lokasi = t_blok.kd_lokasi AND
  t_stok_opname.kd_lokasi = t_sub_blok.kd_lokasi AND
  t_stok_opname.kd_blok = t_sub_blok.kd_blok AND
  t_stok_opname.kd_sub_blok = t_sub_blok.kd_sub_blok AND t_stok_opname.status=1 
  ".$sql_search." LIMIT ".$length." OFFSET ".$offset);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function update_head_entrystok($id1 = '', $data = NULL){
                $this->db->where('no_opname', $id1);		                	
		return $this->db->update('inv.t_stok_opname', $data);
	}
    public function update_detail_entrystok($id1 = NULL,$id2 = NULL, $data = NULL){
                $this->db->where('no_opname', $id1);	
                $this->db->where('kd_produk', $id2);	
		return $this->db->update('inv.t_stok_opname_detail', $data);
	}
    public function get_sub_blok($kdLokasi, $kdBlok) {
        $this->db->select('kd_sub_blok,nama_sub_blok');
        $this->db->where('kd_lokasi', $kdLokasi);
        $this->db->where('kd_blok', $kdBlok);
        $query = $this->db->get('mst.t_sub_blok');
        // print_r($this->db->last_query());
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    
    public function cek_exists_brg_inv($kd_produk=null,$kd_lokasi=null,$kd_blok=null,$kd_sub_blok=null){
            $sql="select qty_oh from inv.t_brg_inventory 
                  where kd_produk='$kd_produk'
                  and kd_lokasi='$kd_lokasi'
                  and kd_blok='$kd_blok'
                  and kd_sub_blok='$kd_sub_blok'";
            
            $query = $this->db->query($sql);
            $rows = array();
            
            if($query->num_rows() > 0){
                $rows = $query->result();
            }
            
            return $rows;
        }
        
        public function update_brg_inv($id = NULL, $id1 = NULL, $id2 = NULL,$id3 = NULL, $data = NULL){
                $this->db->where('kd_produk', $id);
		$this->db->where('kd_lokasi', $id1);
		$this->db->where('kd_blok', $id2);
		$this->db->where('kd_sub_blok', $id3);
		return $this->db->update('inv.t_brg_inventory', $data);
	}

}