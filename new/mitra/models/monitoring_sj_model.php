<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_sj_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	 
	public function get_rows($no_so = "", $search = "", $offset, $length){
		$sql_search = "";
		$where = "";
		 
		if($no_so != ""){
			$where .=  " AND b.no_so = '$no_so' ";
		}
		
                
                if($search != ""){
			$sql_search =  " AND ((lower(c.kirim_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.no_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.userid) LIKE '%" . strtolower($search) . "%') OR (lower(b.no_kendaraan) LIKE '%". strtolower($search) . "%') OR (lower(b.sopir) LIKE '%". strtolower($search) . "%'))";
			$this->db->where($sql_search);
		}
		
        // $this->db->where('status','0');
       
                $sql ="select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                                ".$where." 
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                order by a.tanggal desc
                                limit ".$length." offset ".$offset."";
        $query = $this->db->query($sql);
        //print_r('--------'.$no_so);
		//print_r($this->db->last_query());exit;
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                                ".$where." 
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                
                             order by a.tanggal desc) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
        
        public function get_sj_rows($no_do = "", $search = "", $offset, $length){
		$sql_search = "";
		$where = "";
		 
		if($no_do != ""){
			$where .=  " AND c.no_do = '$no_do' ";
		}
		
        if($search != ""){
			$sql_search =  " AND ((lower(c.kirim_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.no_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.userid) LIKE '%" . strtolower($search) . "%') OR (lower(b.no_kendaraan) LIKE '%". strtolower($search) . "%') OR (lower(b.sopir) LIKE '%". strtolower($search) . "%'))";
			$this->db->where($sql_search);
		}
		
        // $this->db->where('status','0');
       
                $sql ="select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                                ".$where." 
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                order by a.tanggal desc
                                limit ".$length." offset ".$offset."";
        $query = $this->db->query($sql);
		
		//print_r($this->db->last_query());exit;
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                                ".$where." 
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                
                             order by a.tanggal desc) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
        
        
        public function get_no_so($search = "", $offset, $length){
		
                
        if($search != ""){
			$sql_search =  " AND ((lower(c.kirim_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.no_so) LIKE '%" . strtolower($search) . "%') OR (lower(c.userid) LIKE '%" . strtolower($search) . "%'))";
			$this->db->where($sql_search);
		}
		
        // $this->db->where('status','0');
       
                $sql ="select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                               
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                order by a.tanggal desc
                                limit ".$length." offset ".$offset."";
        $query = $this->db->query($sql);
		
		//print_r($this->db->last_query());exit;
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select c.no_so, c.rp_total_bayar, c.rp_kurang_bayar, b.kd_produk, a.nama_produk, b.qty, d.nm_satuan, 
                                b.rp_harga, b.rp_diskon, b.rp_total, b.is_kirim, b.rp_ekstra_diskon, b.is_do, 
                                c.tgl_so, c.kd_member, c.kirim_so, c.kirim_alamat_so, c.kirim_telp_so, c.userid kasir, c.keterangan
                                from  sales.t_sales_order c,  sales.t_sales_order_detail b, mst.t_produk a, mst.t_satuan d
                                WHERE 1=1
                                ".$sql_search."
                               
                                and c.no_so = b.no_so
                                and a.kd_produk = b.kd_produk
                                and a.kd_satuan = d.kd_satuan
                                
                             order by a.tanggal desc) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}


}