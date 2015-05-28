<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faktur_pajak_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
        public function search_faktur_jual($kd_pelanggan = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select no_faktur,rp_faktur_net, sum(total) rp_potongan,rp_uang_muka,rp_dpp,rp_ppn,rp_faktur,tgl_faktur,nama_npwp,no_npwp,alamat_npwp,kd_npwp
                        from (
                        select a.no_faktur,a.tgl_faktur,a.rp_faktur_net,e.nama_npwp,e.no_npwp,e.alamat_npwp,e.kd_npwp, b.qty, b.rp_total_diskon, (b.qty * b.rp_total_diskon) as total,a.rp_uang_muka,a.rp_dpp,a.rp_ppn,a.rp_faktur
                        from sales.t_faktur_jual a 
                        join sales.t_faktur_jual_detail b on a.no_faktur = b.no_faktur
                        join mst.t_produk c on b.kd_produk = c.kd_produk 
                        join mst.t_satuan d on c.kd_satuan = d.kd_satuan
                        left join mst.t_pelanggan_npwp_dist e on e.kd_npwp = a.kd_npwp
                        where a.kd_pelanggan = '$kd_pelanggan'
                        and a.is_pajak = '0'
                        ) a
                        group by no_faktur,rp_uang_muka,rp_dpp,rp_ppn,rp_faktur,tgl_faktur,rp_faktur_net,nama_npwp,no_npwp,alamat_npwp,kd_npwp";
                        
                    
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        public function search_pelanggan_npwp($kd_pelanggan = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select a.*,b.nama_pelanggan from mst.t_pelanggan_npwp_dist a,mst.t_pelanggan_dist b
                        where a.kd_pelanggan = '$kd_pelanggan' and a.aktif=1 and a.kd_pelanggan = b.kd_pelanggan
                        ";
                        
                    
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        public function search_uang_muka($kd_pelanggan = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select * from sales.t_uang_muka 
                        where is_pajak = '0' and kd_pelanggan = '$kd_pelanggan'";
                 
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        public function search_faktur_jual_detail($no_faktur = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select no_faktur,rp_faktur_net, sum(total) rp_potongan,rp_uang_muka,rp_dpp,rp_ppn,rp_faktur,tgl_faktur
                        from (
                        select a.no_faktur,a.tgl_faktur,a.rp_faktur_net, b.qty, b.rp_total_diskon, (b.qty * b.rp_total_diskon) as total,a.rp_uang_muka,a.rp_dpp,a.rp_ppn,a.rp_faktur
                        from sales.t_faktur_jual a, sales.t_faktur_jual_detail b, mst.t_produk c, mst.t_satuan d
                        where a.no_faktur = b.no_faktur
                        and b.kd_produk = c.kd_produk 
                        and c.kd_satuan = d.kd_satuan
                        and a.no_faktur = '$no_faktur'
                        ) a
                        group by no_faktur,rp_uang_muka,rp_dpp,rp_ppn,rp_faktur,tgl_faktur,rp_faktur_net";
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
      public function search_uang_muka_detail($no_bayar = '', $search=''){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_bayar) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql="select * from sales.t_uang_muka_detail
                        where no_bayar = '$no_bayar'";
                       
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
      public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
	}
      public function query_update($sql = ""){
		return $this->db->query($sql);
	}
      public function search_no_faktur($no_faktur_pajak = ''){
		
                 $sql=" select * from sales.t_faktur_pajak
                        where no_faktur_pajak = '$no_faktur_pajak'";
                 
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		//$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $query->num_rows();
	} 
       
      public function get_data_print($no_faktur){
		
		$sql = "select 'FAKTUR PAJAK' title, a.*,b.*,c.nama_pelanggan,c.alamat_kirim,c.npwp,d.nama_npwp,d.no_npwp,d.alamat_npwp
                        from sales.t_faktur_pajak a 
                        join sales.t_faktur_jual b on a.no_faktur = b.no_faktur
                        join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan
                        left join mst.t_pelanggan_npwp_dist d on d.kd_npwp = a.kd_npwp 
                        where a.no_faktur_pajak = '". $no_faktur."'
			 ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		
		$sql = "SELECT a.*,b.*,nama_produk, nm_satuan, e.tanggal, f.rp_uang_muka
				FROM sales.t_faktur_pajak a
				JOIN sales.t_faktur_jual_detail b
					ON a.no_faktur = b.no_faktur
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
				JOIN sales.t_surat_jalan_dist e
					ON b.no_sj = e.no_sj
                                JOIN sales.t_faktur_jual f
                                        ON a.no_faktur = f.no_faktur
				WHERE a.no_faktur_pajak = '$no_faktur'";
				
		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}
     public function get_data_print_uang_muka($no_faktur){
		
		$sql = "select 'FAKTUR PAJAK' title, a.*,b.*,c.nama_pelanggan,c.alamat_kirim,c.npwp, d.nama_npwp,d.no_npwp,d.alamat_npwp
                        from sales.t_faktur_pajak a
                        join sales.t_uang_muka b on a.no_bayar_uang_muka = b.no_bayar  
                        join mst.t_pelanggan_dist c on b.kd_pelanggan = c.kd_pelanggan
                        left join mst.t_pelanggan_npwp_dist d on d.kd_npwp = a.kd_npwp 
                        where a.no_faktur_pajak = '". $no_faktur."'
			 ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		
		$sql = "select a.*,b.no_faktur_pajak
                        from sales.t_uang_muka_detail a,sales.t_faktur_pajak b
                        where a.no_bayar = b.no_bayar_uang_muka
                        and b.no_faktur_pajak = '$no_faktur'";
				
		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}
 //        public function search_pelanggan($search = "", $offset, $length){
	// 	if($search != ""){
	// 		  $sql_search = " and (lower(a.kd_pelanggan)  LIKE '%" . strtolower($search) . "%' or lower(a.nama_pelanggan) LIKE '%" . strtolower($search) . "%')";
	// 	}
 //                $sql1 = "select a.*,b.nama_sales,b.kd_sales,
 //                        CASE WHEN tipe = '1' THEN 'Toko' WHEN tipe = '2' THEN 'Modern Market' ELSE 'Agen' end nama_tipe
 //                         from mst.t_pelanggan_dist a, mst.t_sales b
 //                         where a.aktif ='1'
 //                         and a.kd_sales = b.kd_sales 
 //                         and is_pkp = 1
 //                         $sql_search
 //                        order by kd_pelanggan
 //                        limit $length offset $offset";
                
 //                $query = $this->db->query($sql1);

 //                $rows = array();
 //                if ($query->num_rows() > 0) {
 //                    $rows = $query->result();
 //                }
                
	// 	$this->db->flush_cache();
                
 //                $sql ="select count (*) AS total from mst.t_pelanggan_dist a, mst.t_sales b
 //                         where a.aktif ='1'
 //                         and a.kd_sales = b.kd_sales
 //                         $sql_search";
		
	// 	$query = $this->db->query($sql);

 //                $total = 0;
 //                if ($query->num_rows() > 0) {
 //                    $row = $query->row();
 //                    $total = $row->total;
 //                }
		
	// 	$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

 //        return $results;
	// }
}
?>
