<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!class_exists('CI_Model')) { class CI_Model extends Model {} }

class MY_Model extends CI_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_kode_sequence($kode_proses, $digit){
		$query = $this->db->query("SELECT mst.get_sequence('". $kode_proses . "', " . $digit . ") id");
		$kode = "";
        if($query->num_rows() > 0){
        	$kode = $query->row()
						->id;
        }
		
        return $kode;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_produk($search_by = "", $keyword = ""){
		$this->db->select("kd_produk,nama_produk");
		if($search_by == "nama"){
			$this->db->where("(lower(nama_produk) LIKE '%" . strtolower($keyword) . "%')", NULL);
			$this->db->order_by("nama_produk", 'asc');
		}elseif($search_by == "kode"){
			$this->db->where("kd_produk LIKE '" . $keyword . "%'", NULL);
			$this->db->order_by("kd_produk", 'asc');
		}else{
			$this->db->order_by("nama_produk", 'asc');
		}
		$this->db->where("aktif",1);		
		$query = $this->db->get("mst.t_produk");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row_produk($search_by = "", $id = NULL){
		$this->db->select("a.*,c.*,b.nm_satuan,d.nama_produk AS nama_produk_bonus");
		if($search_by == "nama"){
			$this->db->where("(lower(a.nama_produk) = '" . strtolower($id) . "')", NULL);
		}else{
			$this->db->where("a.kd_produk", $id);
		}
        
		$this->db->join("mst.t_satuan b", "a.kd_satuan = b.kd_satuan");
		$this->db->join("mst.t_diskon_sales_dist c", "c.kd_produk = a.kd_produk", "left");	
		$this->db->join("mst.t_produk d", "d.kd_produk = c.kd_produk_bonus", "left");
                //$this->db->where("c.tgl_start_diskon <= current_date  and c.tgl_end_diskon >= current_date ");
                $query = $this->db->get('mst.t_produk a');
        
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
		 //print_r($this->db->last_query());
        return $row;
	}
	
	public function get_bonus($kd_produk = NULL){
		$this->db->select('kd_produk_bonus,kd_kategori1_bonus,kd_kategori2_bonus,kd_kategori3_bonus,kd_kategori4_bonus,qty_bonus');
		$this->db->where('kd_produk',$kd_produk);
		$query = $this->db->get('mst.t_diskon_sales');
		
		$row = array();
		if($query->num_rows() > 0 ){
			$row = $query->row();
		}
		
		return $row;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran($is_pelunasan_hutang = FALSE){
		//if($is_pelunasan_hutang) $this->db->where("is_pelunasan_hutang", 1);
		$this->db->select("*");
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("nm_pembayaran", 'asc');
		$query = $this->db->get("mst.t_jns_pembayaran");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_member(){
		$this->db->select("kd_member,nmmember,aktif");
		$this->db->where("aktif is true", NULL);
		$this->db->order_by("nmmember", 'asc');
		$query = $this->db->get("mst.t_member");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_supplier($search_by = "", $keyword = ""){
		$this->db->select("kd_supplier,nama_supplier");
		if($search_by == "nama"){
			$this->db->where("(lower(nama_supplier) LIKE '%" . $keyword . "%')", NULL);
			$this->db->order_by("nama_supplier", 'asc');
		}elseif($search_by == "kode"){
			$this->db->where("kd_supplier LIKE '" . $keyword . "%'", NULL);
			$this->db->order_by("kd_supplier", 'asc');
		}else{
			$this->db->order_by("nama_supplier", 'asc');
		}
		$this->db->where("aktif is TRUE", NULL);
		$query = $this->db->get("mst.t_supplier");
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_supplier($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(nama_supplier) LIKE '%" . $search . "%') OR (kd_supplier LIKE '%" . $search . "%') OR (nama_supplier LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif is TRUE", NULL);
		$this->db->order_by("nama_supplier");
		$query = $this->db->get("mst.t_supplier", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_supplier) LIKE '%" . $search . "%') OR (kd_supplier LIKE '%" . $search . "%') OR (nama_supplier LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif is TRUE", NULL);
		$query = $this->db->get("mst.t_supplier");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function search_ekspedisi($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(nama_ekspedisi) LIKE '%" . $search . "%') OR (kd_ekspedisi LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif", 1);
		$this->db->order_by("nama_ekspedisi");
		$query = $this->db->get("mst.t_ekpedisi", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_ekspedisi) LIKE '%" . $search . "%') OR (kd_ekspedisi LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif", 1);
		$query = $this->db->get("mst.t_ekpedisi");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function search_satuan($search = "", $offset, $length, $kd_ekspedisi = ""){
		if($search != ""){
			$this->db->where("((lower(nm_satuan) LIKE '%" . $search . "%') OR (kd_satuan LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->select("a.*,b.*,nm_satuan as nm_satuan_eksp");
		$this->db->join("mst.t_satuan b","b.kd_satuan = a.kd_satuan");
		$this->db->order_by("nm_satuan");
		$this->db->where("a.kd_ekspedisi", $kd_ekspedisi);

		$query = $this->db->get("mst.t_ekspedisi_price a", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		// print_r($this->db->last_query());exit;
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
		if($kd_ekspedisi != ""){
			$this->db->where("a.kd_ekspedisi", $kd_ekspedisi);
		}
        if($search != ""){
			$this->db->where("((lower(nm_satuan) LIKE '%" . $search . "%') OR (kd_satuan LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->join("mst.t_satuan b","b.kd_satuan = a.kd_satuan");
		$query = $this->db->get("mst.t_ekspedisi_price a");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_pelanggan($search = "", $offset, $length){
		if($search != ""){
			  $sql_search = " and (lower(a.kd_pelanggan)  LIKE '%" . strtolower($search) . "%' or lower(a.nama_pelanggan) LIKE '%" . strtolower($search) . "%')";
		}
                $sql1 = "select a.*,b.nama_sales,b.kd_sales,
                         CASE WHEN tipe = '1' THEN 'Toko' WHEN tipe = '2' THEN 'Modern Market' ELSE 'Agen' end nama_tipe
                         from mst.t_pelanggan_dist a, mst.t_sales b, mst.t_sales_area c 
                         where a.aktif ='1'
                         and a.kd_area = c.kd_area
                         and b.kd_sales = c.kd_sales
                         $sql_search
                         order by kd_pelanggan
                         limit $length offset $offset";
                
                $query = $this->db->query($sql1);

                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }
                
		$this->db->flush_cache();
                
                $sql ="select count (*) AS total from mst.t_pelanggan_dist a, mst.t_sales b, mst.t_sales_area c 
                         where a.aktif ='1'
                         and a.kd_area = c.kd_area
                         and b.kd_sales = c.kd_sales
                         $sql_search";
		
		$query = $this->db->query($sql);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(mst.t_produk.nama_produk) LIKE '%" . strtolower($search) . "%') OR (mst.t_produk.kd_produk LIKE '%" . $search . "%') OR (mst.t_produk.kd_produk_supp LIKE '%" . $search . "%') OR (mst.t_produk.kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("mst.t_produk.aktif", 1);
                $this->db->join("mst.t_satuan", "mst.t_produk.kd_satuan = mst.t_satuan.kd_satuan", "inner");
		$this->db->order_by("nama_produk");
		$query = $this->db->get("mst.t_produk", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (kd_produk LIKE '%" . $search . "%') OR (kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif", 1);
		$query = $this->db->get("mst.t_produk");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function search_produk_distribusi($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%') OR (a.kd_produk_supp LIKE '%" . $search . "%') OR (kd_produk_lama LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("a.aktif", 1);
                //$this->db->where("b.tgl_start_diskon <= current_date  and b.tgl_end_diskon >= current_date");
		$this->db->order_by("nama_produk");
                $this->db->join("mst.t_diskon_sales_dist b","b.kd_produk = a.kd_produk","inner");
		$query = $this->db->get("mst.t_produk a", $length, $offset);
		//print_r($this->db->last_query());
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
                //$this->db->where("b.tgl_start_diskon <= current_date  and b.tgl_end_diskon >= current_date");
                $this->db->join("mst.t_diskon_sales_dist b","b.kd_produk = a.kd_produk","inner");
                
		$query = $this->db->get("mst.t_produk a");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function search_bonus_distribusi($kd_produk = "", $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $search = "", $offset, $length){
		$this->db->select("a.kd_produk,a.nama_produk,b.qty_bonus,b.qty_beli_bonus,b.is_bonus_kelipatan");
		
		if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))", NULL);
		}		
		if($kd_produk != ""){
			$this->db->where('a.kd_produk',$kd_produk);
		}else{
			if($kd_kategori1 != ""){
				$this->db->where('kd_kategori1',$kd_kategori1);
			}
			if($kd_kategori2 != ""){
				$this->db->where('kd_kategori2',$kd_kategori2);
			}
			if($kd_kategori3 != ""){
				$this->db->where('kd_kategori3',$kd_kategori3);
			}
			if($kd_kategori4 != ""){
				$this->db->where('kd_kategori4',$kd_kategori4);
			}
		}
		
		// $this->db->where("kd_peruntukkan", "1");
		
		$this->db->where("aktif", 1);
		$this->db->join("mst.t_diskon_sales b", "b.kd_produk = a.kd_produk", "left");
		$this->db->order_by("nama_produk");
		$query = $this->db->get("mst.t_produk a", $length, $offset);
		
		// print_r($this->db->last_query());
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (kd_produk LIKE '%" . $search . "%'))", NULL);
		}		
		if($kd_produk != ""){
			$this->db->where('kd_produk',$kd_produk);
		}else{
			if($kd_kategori1 != ""){
				$this->db->where('kd_kategori1',$kd_kategori1);
			}
			if($kd_kategori2 != ""){
				$this->db->where('kd_kategori2',$kd_kategori2);
			}
			if($kd_kategori3 != ""){
				$this->db->where('kd_kategori3',$kd_kategori3);
			}
			if($kd_kategori4 != ""){
				$this->db->where('kd_kategori4',$kd_kategori4);
			}
		}
		$this->db->where("aktif", 1);
		$query = $this->db->get("mst.t_produk");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}
	
	public function get_nilai_parameter($parameter = ''){
		$this->db->select('nilai_parameter');
		$this->db->where('kd_parameter',$parameter);
		$query = $this->db->get('mst.t_parameter');
		
		$row = '';
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
		echo '{"success":true,"data":'.json_encode($row).'}';
	}
	
	public function check_data($field = '', $value = '', $table = ''){
		$this->db->where($field,$value);
		$query = $this->db->get($table);
		
		$results = FALSE;
		
		if($query->num_rows() > 0 ){
			$results = TRUE;
		}
		// print_r($this->db->last_query());exit;
		return $results;
	}
	
	public function check_data_array($where = '', $table = ''){
		$this->db->where($where);
		$query = $this->db->get($table);
		
		$results = FALSE;
		
		if($query->num_rows() > 0 ){
			$results = TRUE;
		}
		// print_r($this->db->last_query());exit;
		return $results;
	}
	
	public function get_data_field($select = '', $field = '', $value = '', $table = ''){
		$this->db->select($select);
		$this->db->where($field,$value);
		$query = $this->db->get($table);
		
		$row = array();
		if($query->num_rows() > 0 ){
			$row = $query->row();
		}
		// print_r($this->db->last_query());exit;
		return $row;
	}
	
	public function get_data_field_array($select = '', $where = '', $table = ''){
		$this->db->select($select);
		$this->db->where($where);
		$query = $this->db->get($table);
		
		$row = array();
		if($query->num_rows() > 0 ){
			$row = $query->row();
		}
		// print_r($this->db->last_query());exit;
		return $row;
	}
	
}