<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_member_model extends MY_Model {
	
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
	public function get_rows($search = "", $offset, $length){
		/*$this->db->select("*,CASE WHEN aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif,CASE WHEN idtype = '1' THEN 'KTP' ELSE CASE WHEN idtype = '2' THEN 'SIM' ELSE CASE WHEN idtype = '3' THEN 'PASSPORT' ELSE CASE WHEN idtype = '4' THEN 'KARTU PELAJAR' END  END END END idtype,
		CASE WHEN jenis = '1' THEN 'GOLD' ELSE CASE WHEN jenis = '2' THEN 'SILVER' ELSE CASE WHEN jenis = '3' THEN 'PLATINUM' END END END jenis",FALSE);
		if($search != ""){
			$sql_search = "(lower(nmmember) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$this->db->where('aktif', 'true');
		$this->db->order_by("kd_member", "desc");
		$query = $this->db->get("mst.t_member", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(lower(nmmember) LIKE '%" . strtolower($search) . "%')";
			$this->db->where($sql_search, NULL);
		}
		$query = $this->db->get("mst.t_member");
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		} 11/05/2013 */
		
		$sql_search = "";
		if($search != ""){
			$sql_search = "and (lower(nmmember) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT distinct a.kd_member, a.nmmember, a.alamat_penagihan, a.telepon, a.hp, a.jenis, a.sdtgl, 
					   a.tgljoin, a.tgllahir, a.idno, 
					   CASE WHEN a.status = '0' THEN 'Single' ELSE CASE WHEN a.status = '1' THEN 'Menikah' ELSE CASE WHEN a.status = '2' THEN 'Janda/Duda' END END END as status, 
					   a.tmplahir, a.agama, a.jenis_kelamin, a.total_point,a.top_dist,a.limit_dist,
					   b.nama_propinsi,a. kd_propinsi,e.nama_kalurahan,a.kd_kelurahan, d.nama_kecamatan,a. kd_kecamatan, c.nama_kota, a.kd_kota, a.kodepos, a.fax, a.email, a.profesi, 
					   a.nmpersh, a.alamat_pengiriman, a.total_point, a.teleponkantor, a.created_by, a.created_date, 
					   a.updated_by, a.updated_date, a.aktif, a.kd_cabang, a.idtype, a.npwp, a.alamat_npwp,
					   CASE WHEN a.is_pelanggan_dist ='0' THEN 'Tidak' ELSE 'Ya' end is_pelanggan_dist,
					   CASE WHEN aktif is true THEN 'Ya' ELSE 'Tidak' end aktif
								FROM mst.t_member a, mst.t_propinsi b, mst.t_kota c, mst.t_kecamatan d, mst.t_kalurahan e
								where a.kd_propinsi = b.kd_propinsi
								and a.kd_kota = c.kd_kota
								and a.kd_kecamatan = d.kd_kecamatan
								and a.kd_kelurahan = e.kd_kalurahan ".$sql_search."
								order by kd_member
								limit ".$length." offset ".$offset;
        
        $query = $this->db->query($sql1);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_member a, mst.t_propinsi b, mst.t_kota c, mst.t_kecamatan d, mst.t_kalurahan e
								where a.kd_propinsi = b.kd_propinsi
								and a.kd_kota = c.kd_kota
								and a.kd_kecamatan = d.kd_kecamatan
								and a.kd_kelurahan = e.kd_kalurahan ".$sql_search;
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        
        return $results;
	}
	
	public function get_histo1($kd_member = NULL){
		$sql= "select no_so, tgl_so, rp_total_bayar from sales.t_sales_order
					where kd_member = '$kd_member'";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
	
	public function get_histo2($no_so=NULL){
		$sql = "select a.no_so, a.kd_produk,  b.nama_produk, a.qty, c.nm_satuan, a.rp_harga, a.rp_diskon, a.rp_ekstra_diskon, a.rp_total
						from sales.t_sales_order_detail a, mst.t_produk b, mst.t_satuan c
						where a.no_so = '$no_so'
						and a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}
	public function get_histo2_by_filter($no_so = NULL, $kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '', $kd_kategori4 = '', $dari = '', $sampai = ''){
		$where = '';
		if ($kd_kategori1 != '')
			$where .= " AND b.kd_kategori1 = '$kd_kategori1' ";
			
		if ($kd_kategori2 != '')
			$where .= " AND b.kd_kategori2 = '$kd_kategori2' ";
			
		if ($kd_kategori3 != '')
			$where .= " AND b.kd_kategori3 = '$kd_kategori3' ";
		
		if ($kd_kategori4 != '')
			$where .= " AND b.kd_kategori4 = '$kd_kategori4' ";
		
		if ($dari != '' && $sampai != '')
			$where .= " AND d.tgl_so BETWEEN '$dari' AND '$sampai' ";
		
		$sql = "SELECT a.no_so, d.tgl_so, a.kd_produk,  b.nama_produk, a.qty, c.nm_satuan, a.rp_harga, a.rp_diskon, a.rp_ekstra_diskon, a.rp_total
						FROM sales.t_sales_order_detail a
						JOIN mst.t_produk b
								ON a.kd_produk = b.kd_produk
						JOIN mst.t_satuan c
								ON b.kd_satuan = c.kd_satuan
						JOIN sales.t_sales_order d
								ON d.no_so = a.no_so
						WHERE a.no_so = '$no_so' ".$where;
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		// print_r($this->db->last_query());
		return $results;
		
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
		$this->db->select("*,CASE WHEN aktif IS true THEN 1 ELSE 0 END aktif",FALSE);
        $this->db->where("kd_member", $id);
        $query = $this->db->get('mst.t_member');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_member', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_member', $id);
		return $this->db->update('mst.t_member', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL){		
		$data = array(
			'aktif' => '0'
		);
		$this->db->where('kd_member', $id);
		return $this->db->update('mst.t_member', $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_last_records(){
		$query = $this->db->query("SELECT to_number(kd_member,'99') kd_member FROM mst.t_member WHERE kd_member = (SELECT MAX(kd_member) FROM mst.t_member)");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_member;
                }
        return $return_value;
	}
	
	public function get_Cab(){
		$sql = "SELECT kd_cabang as kd, nama_cabang as nama FROM mst.t_cabang;";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}
	
	public function get_prop(){
		$sql = "select a. nama_propinsi, a.kd_propinsi from mst.t_propinsi a;";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
		
	}
	public function get_kota($prop = NULL){
		$sql= "select a. nama_propinsi,a. kd_propinsi, b.nama_kota, b.kd_kota from mst.t_propinsi a,mst.t_kota b
				where a.kd_propinsi = b.kd_propinsi
				and a.kd_propinsi = '$prop'";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
	
	public function get_kec($prop = NULL, $kota=NULL){
		$sql= "select distinct a.nama_propinsi, b.nama_kota, c.nama_kecamatan, c.kd_kecamatan from mst.t_propinsi a,mst.t_kota b,mst.t_kecamatan c
				where a.kd_propinsi = b.kd_propinsi
				and b.kd_kota = c.kd_kota
				and a.kd_propinsi = '$prop'
				and b.kd_kota = '$kota'";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
	
	public function get_kel($prop = NULL, $kota=NULL, $kec = NULL){
		$sql= "select distinct a. nama_propinsi, b.nama_kota, c.nama_kecamatan, d.kd_kalurahan, d.nama_kalurahan from mst.t_propinsi a,mst.t_kota b,mst.t_kecamatan c,mst.t_kalurahan d where a.kd_propinsi = b.kd_propinsi
				and b.kd_kota = c.kd_kota
				and c.kd_kecamatan = d.kd_kecamatan
				and a.kd_propinsi = '$prop'
				and b.kd_kota = '$kota'
				and c.kd_kecamatan = '$kec'";
		$query = $this->db->query($sql);
		
		$rows = $query->result();
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
}
