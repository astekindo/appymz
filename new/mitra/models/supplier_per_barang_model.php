<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_per_barang_model extends MY_Model {
	
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
	public function supplier_($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search =  " AND(lower(nama_supplier) LIKE '%" . strtolower($search) . "%' OR lower(kd_supplier) LIKE '%" . strtolower($search) . "%')  ";
		}

		$sql1 = "SELECT kd_supplier, CASE WHEN pkp= '1' THEN 'Ya' ELSE 'Tidak' END pkp,nama_supplier,alias_supplier,alamat, CASE WHEN top is NULL THEN 0 ELSE top END waktu_top
					FROM mst.t_supplier
					WHERE aktif is true ".$sql_search." 
					limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT nama_supplier
					FROM mst.t_supplier) as tabel limit 1";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
	}
	
	
	public function get_rows($kd_supplier = "", $search = "", $offset, $length){
		$sql_search = "";
		if($kd_supplier != ""){
			$sql_search .=  " AND lower(a.kd_supplier) = '" . strtolower($kd_supplier) . "'";
		}
		
		if($search != ""){
			$sql_search .=  " AND (lower(c.nama_produk) LIKE '%" . strtolower($search) . "%' OR lower(c.kd_produk_lama) LIKE '%" . strtolower($search) . "%' OR lower(a.kd_produk) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT nama_supplier,nama_produk,b.pkp,c.kd_produk_lama,a.*, CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif, 
					CASE WHEN waktu_top is NULL THEN 0 ELSE waktu_top END waktu_top,
					CASE WHEN hrg_supplier_dist is NULL THEN 0 ELSE hrg_supplier_dist END hrg_supplier_dist
					FROM mst.t_supp_per_brg a, mst.t_supplier b, mst.t_produk c
					WHERE 1=1 AND b.kd_supplier=a.kd_supplier AND c.kd_produk=a.kd_produk 
					".$sql_search." 
					ORDER BY a.kd_supplier, a.kd_produk DESC
					limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
                
                //if($kd_supplier != ""){
		//	 print_r($this->db->last_query());
		//}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (SELECT nama_supplier,nama_produk,a.* 
					FROM mst.t_supp_per_brg a, mst.t_supplier b, mst.t_produk c
					WHERE b.kd_supplier=a.kd_supplier AND c.kd_produk=a.kd_produk
					".$sql_search." 
					ORDER BY a.kd_supplier , a.kd_produk DESC) as tabel";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_row($id = NULL, $id1 = NULL){
        $sql = "SELECT nama_supplier, nama_produk, a.*, CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif ,
					CASE WHEN hrg_supplier_dist is NULL THEN 0 ELSE hrg_supplier_dist END hrg_supplier_dist,
					CASE WHEN pkp = '1' THEN 'Ya' ELSE 'Tidak' END pkp
					FROM mst.t_supp_per_brg a, mst.t_supplier b, mst.t_produk c
					WHERE a.kd_supplier='$id' AND a.kd_produk='$id1' AND
					b.kd_supplier=a.kd_supplier AND c.kd_produk=a.kd_produk
					";
        $query = $this->db->query($sql);
		
        if ($query->num_rows() != 0) {
            $row = $query->row();
			return $row;
        }
	}
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_supp_per_brg', $data);
		
	}
	
	public function update_row($kd1 = NULL, $kd2 = NULL, $data = NULL){
		$this->db->where("kd_supplier",$kd1);
		$this->db->where("kd_produk",$kd2);
		return $this->db->update('mst.t_supp_per_brg', $data);
	}
        public function update_aktif($kd_supplier = NULL,$kd_barang = NULL,$data = NULL){
		$this->db->where("kd_supplier",$kd_supplier);
                $this->db->where("kd_produk",$kd_barang);
		return $this->db->update('mst.t_supp_per_brg', $data);
	}
	
	public function delete_row($kd1 = NULL, $kd2 = NULL){
		$data = array(
			'aktif' => '0'
		);
		$this->db->where("kd_supplier",$kd1);
		$this->db->where("kd_produk",$kd2);
		
		return $this->db->update('mst.t_supp_per_brg', $data);
	}

	public function get_supplier(){
		$sql= "SELECT kd_supplier, nama_supplier FROM mst.t_supplier WHERE aktif=true";
		$query = $this->db->query($sql);
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}
	
	public function get_produk($search = "", $offset, $length, $kd_supplier = ''){
		$sql_search = '';
		if($search != ""){
			$sql_search = "  AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR kd_produk LIKE '%".$search."%'";
		}
		$query = $this->db->query("SELECT a.kd_produk,a.kd_produk_lama,nama_produk
									FROM mst.t_produk a 
									WHERE a.aktif = 1 ".$sql_search." 
									ORDER BY nama_produk ASC LIMIT ".$length." OFFSET ".$offset);
		$rows = $query->result();
		
		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total
				FROM mst.t_produk
				WHERE 1=1 ".$sql_search;
        
        $query = $this->db->query($sql2);
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
		return $results;
		
	}
	public function search_produk_by_kategori($kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $offset, $length){
		$where = '';
		
		
		if ($kd_kategori1 != ''){
			$where .= "AND b.kd_kategori1 = '$kd_kategori1' ";
		}
		
		if ($kd_kategori2 != ''){
			$where .= "AND b.kd_kategori2 = '$kd_kategori2' ";
		}
		
		if ($kd_kategori3 != ''){
			$where .= "AND b.kd_kategori3 = '$kd_kategori3' ";
		}
		
		if ($kd_kategori4 != ''){
			$where .= "AND b.kd_kategori4 = '$kd_kategori4' ";
		}
		if ($konsinyasi != ''){
			$where .= "AND b.is_konsinyasi = '$konsinyasi' ";
		}
		
		$sql = "SELECT '1' as add, b.kd_produk, b.kd_produk_lama,b.nama_produk, nm_satuan
					FROM mst.t_produk b
					JOIN mst.t_satuan c 
						ON c.kd_satuan = b.kd_satuan 
					JOIN mst.t_kategori1 d
						ON b.kd_kategori1 = d.kd_kategori1
					JOIN mst.t_kategori2 e
						ON b.kd_kategori2 = e.kd_kategori2 
						AND b.kd_kategori1 = e.kd_kategori1
					JOIN mst.t_kategori3 f
						ON b.kd_kategori3 = f.kd_kategori3
						AND b.kd_kategori2 = f.kd_kategori2
						AND b.kd_kategori1 = f.kd_kategori1
					JOIN mst.t_kategori4 g
						ON b.kd_kategori4 = g.kd_kategori4
						AND b.kd_kategori3 = g.kd_kategori3
						AND b.kd_kategori2 = g.kd_kategori2
						AND b.kd_kategori1 = g.kd_kategori1
					WHERE 1=1 ".$where."
						ORDER BY b.nama_produk";
					// limit ".$length." offset ".$offset;
		$query = $this->db->query($sql);
		
		// print_r($this->db->last_query());exit;
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		return $rows;
	}
}
