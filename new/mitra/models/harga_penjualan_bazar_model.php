<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_penjualan_bazar_model extends MY_Model {
	
	public function __construct(){
		parent::__construct();
	}
    public function search_no_bukti($kd_supplier = "",$search = "", $offset, $length){
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->select("b.nama_supplier");
			$this->db->select("a.kd_diskon_sales");
			$this->db->distinct("a.kd_diskon_sales");
			if($search != ""){
				$this->db->where("a.kd_diskon_sales LIKE '%" . $search . "%'", NULL);
			}
                        if($kd_supplier != ""){
                        $this->db->where("b.kd_supplier", $kd_supplier);
                        }
			$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
			$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');
                        $this->db->where('a.status_approval',1);
			$this->db->order_by("a.kd_diskon_sales");
                        
			$query = $this->db->get("mst.t_diskon_sales_bazar a", $length, $offset);
			//print_r($this->db->last_query());
			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}        

			$this->db->flush_cache();
			$this->db->select("count(DISTINCT kd_diskon_sales) AS total");
			if($search != ""){
				$this->db->where("kd_diskon_sales LIKE '%" . $search . "%'", NULL);
			}
			$query = $this->db->get("mst.t_diskon_sales_bazar");
					
			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}
			
			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
	}
    public function search_no_bukti_filter($kd_supplier = "",$search = "", $offset, $length){
			$this->db->select("a.keterangan");
			$this->db->select("a.created_by");
			$this->db->select("b.nama_supplier");
			$this->db->select("a.kd_diskon_sales");
			$this->db->distinct("a.kd_diskon_sales");
			if($search != ""){
				$this->db->where("a.kd_diskon_sales LIKE '%" . $search . "%'", NULL);
			}
                        if($kd_supplier != ""){
                        $this->db->where("b.kd_supplier", $kd_supplier);
                        }
			$this->db->join('mst.t_supp_per_brg c','c.kd_produk=a.kd_produk');
			$this->db->join('mst.t_supplier b','b.kd_supplier=c.kd_supplier');
                        $this->db->where('a.status_approval',0);
			$this->db->order_by("a.kd_diskon_sales");
                        
			$query = $this->db->get("mst.t_diskon_sales_bazar a", $length, $offset);
			//print_r($this->db->last_query());
			$rows = array();
			if($query->num_rows() > 0){
				$rows = $query->result();
			}        

			$this->db->flush_cache();
			$this->db->select("count(DISTINCT kd_diskon_sales) AS total");
			if($search != ""){
				$this->db->where("kd_diskon_sales LIKE '%" . $search . "%'", NULL);
			}
			$query = $this->db->get("mst.t_diskon_sales_bazar");
					
			$total = 0;
			if($query->num_rows() > 0){
				$row = $query->row();
				$total = $row->total;
			}
			
			$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

			return $results;
	}
    public function search_produk_by_kategori($kd_supplier = "", $no_bukti = "", $konsinyasi = "", $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "",$kd_ukuran = "",$kd_satuan = "", $list = "", $search = '', $offset, $length){
		$where = '';
		
		if ($kd_supplier != ''){
			$where .= " AND h.kd_supplier = '$kd_supplier'";
		}
		if ($no_bukti != ''){
			$select = " i.*, ";
			$from = "JOIN mst.t_diskon_sales_bazar_temp i ON b.kd_produk = i.kd_produk  ";
			$where .= " AND i.kd_diskon_sales = '$no_bukti' ";
		}else{
			$select = " a.*,b.rp_ongkos_kirim, b.pct_margin, b.rp_het_harga_beli,b.rp_het_harga_beli_dist,";
			$from = '';
                        
		}
		if ($search != ''){
			$where .= " AND (
							(lower(nama_produk) LIKE '%" . strtolower($search) . "%') 
							OR 
							(lower(b.kd_produk) LIKE '%" . strtolower($search) . "%') 
							OR 
							(lower(b.kd_produk_lama) LIKE '%" . strtolower($search) . "%') 
						)";
		}
		if ($list != ''){
			$where .= " AND (
							a.kd_produk in(".$list.")
							OR 
							b.kd_produk_lama in(".$list.")
						)";
		}
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
                if ($kd_ukuran != ''){
			$where .= "AND b.kd_ukuran = '$kd_ukuran' ";
		}
                if ($kd_satuan != ''){
			$where .= "AND b.kd_satuan = '$kd_satuan' ";
		}
		
		$sql = "SELECT b.kd_produk kd_produk_baru, b.kd_produk_lama,b.nama_produk,b.rp_cogs p_rp_cogs, b.rp_het_cogs p_rp_het_cogs, " . $select . " h.hrg_supplier, h.net_hrg_supplier_sup_inc, h.net_hrg_supplier_dist_inc,a.koreksi_ke koreksi_diskon, b.koreksi_ke koreksi_produk, nm_satuan, 
				(SELECT nama_supplier FROM mst.t_supplier s WHERE s.kd_supplier = h.kd_supplier) nama_supplier
					FROM mst.t_produk b
					 " . $from . " 
					JOIN mst.t_supp_per_brg h 
						ON h.kd_produk = b.kd_produk
					LEFT JOIN (select * from mst.t_diskon_sales_bazar  where (tgl_start_diskon <= now() or tgl_start_diskon is null) and (tgl_end_diskon >= now() or tgl_end_diskon is null)) a
						ON b.kd_produk = a.kd_produk 
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
                                        JOIN mst.t_ukuran u
						ON b.kd_ukuran = u.kd_ukuran
					WHERE h.hrg_supplier > 0 ".$where." 
                                                ORDER BY b.nama_produk";
					// limit ".$length." offset ".$offset;
		$query = $this->db->query($sql);
		
                //print_r($this->db->last_query());
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$result['rows'] = $rows;
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total FROM mst.t_produk b
					 " . $from . " 
					JOIN mst.t_supp_per_brg h 
						ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales_bazar a
						ON b.kd_produk = a.kd_produk 
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
                                        JOIN mst.t_ukuran u
						ON b.kd_ukuran = u.kd_ukuran
					WHERE 1=1 ".$where;
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$result['total'] = $total;
        return $result;
	}    
        
     public function update_temp($kd_produk = '', $no_bukti = '', $datau = NULL){
		$this->db->where('kd_produk',$kd_produk);
		$this->db->where('kd_diskon_sales',$no_bukti);
                //return $this->db->update('mst.t_diskon_sales_bazar',$datau);
		return $this->db->update('mst.t_diskon_sales_bazar_temp',$datau);
		//print_r($this->db->last_query());exit;	
	}
    public function search_produk_history($no_bukti = '',$kd_produk = ''){
		$where = '';
		if($no_bukti != ''){
			$where .= " AND a.kd_diskon_sales = '$no_bukti' ";
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
		}		
		$sql = "SELECT a.*,b.*, d.nama_supplier, h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.tanggal, a.koreksi_ke koreksi_diskon, nm_satuan 
					FROM mst.t_produk b 
					JOIN mst.t_supp_per_brg h ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales_bazar a ON b.kd_produk = a.kd_produk 
					JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan 
					JOIN mst.t_supplier d ON d.kd_supplier = h.kd_supplier
					WHERE 1=1
					".$where." ORDER BY a.tanggal DESC";
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());exit;
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
        return $rows;
	}
    public function select_temp($kd_produk = "",$status = ""){
		$where = array(
					'kd_produk' => $kd_produk,
					'status_approval' => $status,
				);
		$this->db->where($where);
		
		$query = $this->db->get("mst.t_diskon_sales_bazar_temp");
		//$query = $this->db->get("mst.t_diskon_sales_bazar");
                return $query->result();
	}
        
    public function select_data_temp($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){
//		
		$sql = "select * from mst.t_diskon_sales_bazar
                          where kd_produk ='$kd_produk' 
                          and tgl_start_diskon <= '$tgl_start_diskon' and tgl_end_diskon >= '$tgl_start_diskon'
                          ";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
   public function select_data_temp_end($kd_produk = "",$tgl_start_diskon = "",$tgl_end_diskon = ""){
//		
		$sql = "select * from mst.t_diskon_sales_bazar
                          where kd_produk ='$kd_produk' 
                          and tgl_start_diskon <= '$tgl_end_diskon' and tgl_end_diskon >= '$tgl_end_diskon'
                          ";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
   public function insert_temp($data = NULL){
		return $this->db->insert('mst.t_diskon_sales_bazar_temp', $data);
                //return $this->db->insert('mst.t_diskon_sales_bazar', $data);
	}
   public function get_data_print($no_bukti = '', $kd_produk = ''){
		$where = '';
		$title = 'HISTORY HARGA PENJUALAN BAZAR';
		if($no_bukti != '' && $no_bukti != '0'){
			$where .= " AND a.kd_diskon_sales = '$no_bukti' ";
			$title .= " - No Bukti : $no_bukti "; 
		}
		if($kd_produk != ''){
			$where .= " AND a.kd_produk = '$kd_produk' ";
			$title .= " - Kd Produk : $kd_produk "; 
		}
		$sql_detail = "SELECT a.*,b.*, d.nama_supplier, h.hrg_supplier, h.net_hrg_supplier_sup_inc, a.tanggal, a.koreksi_ke koreksi_diskon, nm_satuan 
					FROM mst.t_produk b 
					JOIN mst.t_supp_per_brg h ON h.kd_produk = b.kd_produk
					LEFT JOIN mst.t_diskon_sales_bazar a ON b.kd_produk = a.kd_produk 
					JOIN mst.t_satuan c ON c.kd_satuan = b.kd_satuan 
					JOIN mst.t_supplier d ON d.kd_supplier = h.kd_supplier
					WHERE 1=1
					".$where." ORDER BY a.tanggal DESC";
	
		$query_detail = $this->db->query($sql_detail);
		//print_r($this->db->last_query());
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}
