<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjualan_sj_model
 *
 * @author faroq
 */
class Retur_penjualan_non_struk_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
   public function search_produk($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%') OR (a.kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif", 1);
                $this->db->where("b.tgl_start_diskon <= now()  and b.tgl_end_diskon >= now()");
		$this->db->order_by("nama_produk");
                $this->db->join("mst.t_diskon_sales b","b.kd_produk = a.kd_produk","inner");
		$query = $this->db->get("mst.t_produk a", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%') OR (a.kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif", 1);
                $this->db->where("b.tgl_start_diskon <= now()  and b.tgl_end_diskon >= now() ");
                $this->db->join("mst.t_diskon_sales b","b.kd_produk = a.kd_produk","inner");
                
		$query = $this->db->get("mst.t_produk a");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
        
    public function get_row_produk($search_by = "", $id = NULL){
		$this->db->select("a.nama_produk,a.kd_produk_supp,c.*,b.nm_satuan,d.nama_produk AS nama_produk_bonus");
		if($search_by == "nama"){
			$this->db->where("(lower(a.nama_produk) = '" . strtolower($id) . "')", NULL);
		}else{
			$this->db->where("a.kd_produk", $id);
		}
        
		$this->db->join("mst.t_satuan b", "a.kd_satuan = b.kd_satuan");
		$this->db->join("mst.t_diskon_sales c", "c.kd_produk = a.kd_produk", "left");	
		$this->db->join("mst.t_produk d", "d.kd_produk = c.kd_produk_bonus", "left");
                $this->db->where("c.tgl_start_diskon <= now()  and c.tgl_end_diskon >= now() ");
                $query = $this->db->get('mst.t_produk a');
                //print_r($this->db->last_query());
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
        return $row;
    }
    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }
    public function search_lokasi ($kd_lokasi='',$kd_blok='',$kd_sub_blok='',$kd_produk=''){
        $sql="select * from inv.t_brg_inventory 
             where kd_produk ='$kd_produk' and kd_lokasi ='$kd_lokasi' and kd_blok ='$kd_blok'
             and kd_sub_blok = '$kd_sub_blok'";
        
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        //print_r($this->db->last_query());
        return $rows;
    }
    public function query_update($sql = ""){
		return $this->db->query($sql);
	}
   public function get_data_print($no_retur = ''){	
		$sql = "select 'RETUR PENJUALAN' title,a.*
                        from sales.t_retur_sales a
                        where no_retur = '$no_retur'
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = " select 'RETUR PENJUALAN' title, a.*,b.nama_lokasi2 || '-' ||  c.nama_blok2 || '-' || d.nama_sub_blok2 lokasi, e.nama_produk, e.kd_produk_supp, f.nm_satuan 
                                from sales.t_retur_sales_detail a, mst.t_produk e, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d, mst.t_satuan f
                                where a.no_retur = '$no_retur'
                                and a.kd_produk = e.kd_produk
                                and e.kd_satuan = f.kd_satuan
                                and a.kd_lokasi = b.kd_lokasi
                                and a.kd_blok = c.kd_blok
                                and a.kd_lokasi = c.kd_lokasi
                                and a.kd_blok = d.kd_blok
                                and a.kd_lokasi = d.kd_lokasi
                                and a.kd_sub_blok = d.kd_sub_blok";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}
