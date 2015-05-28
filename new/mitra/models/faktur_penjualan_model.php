<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faktur_penjualan_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}
        
        public function search_no_sj_by_pelanggan($kd_pelanggan = '', $no_so ='', $search='', $kd_peruntukan = 1){
		if ($search != "") {
                    
                    $sql_search =  "AND (lower(a.no_sj) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);   
                }
                 $sql=" select distinct (b.no_sj),a.*,d.no_so
                        from sales.t_surat_jalan_dist a
                        join sales.t_surat_jalan_dist_detail b on a.no_sj = b.no_sj
                        join sales.t_sales_order_dist d on a.no_so = d.no_so
                        where a.is_faktur = '0' and a.kd_pelanggan = '$kd_pelanggan' and d.no_so ='$no_so'";
                    
                 $query = $this->db->query($sql);
		$rows = array();
		
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
	
	
		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
    public function search_no_do_by_pelanggan_no_sj($kd_pelanggan = '', $no_sj = ''){
		if ($no_sj != ''){
			$no_sj_in_1 = '';
			$no_sj = explode(';',$no_sj);
			foreach ($no_sj as $no_sj_in){
				$no_sj_in_1 = $no_sj_in_1."'".$no_sj_in."',"; 
			}
			$no_sj = substr($no_sj_in_1,0,-1);
		}
		$sql = "select b.qty qty_sj,a.no_sj,a.tanggal,a.no_do,b.*,d.no_so,e.*,f.nama_produk,g.nm_satuan
                        from sales.t_surat_jalan_dist a
                        join sales.t_surat_jalan_dist_detail b on a.no_sj = b.no_sj 
                        join sales.t_sales_order_dist d on a.no_so = d.no_so
                        join sales.t_sales_order_dist_detail e on d.no_so = e.no_so
                        join mst.t_produk f on b.kd_produk = f.kd_produk
                        left join mst.t_satuan g on f.kd_satuan = g.kd_satuan
                        where e.kd_produk = b.kd_produk
                        and a.no_sj in (".$no_sj.")";
				
		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		// $results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}
    public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
	}
    public function query_update($sql = ""){
		return $this->db->query($sql);
	}
     public function update_surat_jalan_dist($no_sj, $data){
		$this->db->where("no_sj",$no_sj);
		$this->db->update("sales.t_surat_jalan_dist",$data);
	}
    public function get_data_print($no_faktur){
		
		$sql = "select 'FAKTUR PENJUALAN' title, a.*,b.nama_pelanggan,b.alamat_tagih,c.no_ref,d.nama_sales,e.nama_npwp,e.alamat_npwp
                        from sales.t_faktur_jual a
                        join mst.t_pelanggan_dist b on a.kd_pelanggan = b.kd_pelanggan 
                        join sales.t_sales_order_dist c on a.no_so = c.no_so 
                        join mst.t_sales_area f on f.kd_area = b.kd_area
                        join mst.t_sales d on f.kd_sales = d.kd_sales 
                        left join mst.t_pelanggan_npwp_dist e on  e.kd_npwp = a.kd_npwp
                        where a.no_faktur = '". $no_faktur."'
			 ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		
		$sql = "SELECT a.*,b.*,nama_produk, nm_satuan, e.tanggal
				FROM sales.t_faktur_jual a
				JOIN sales.t_faktur_jual_detail b
					ON a.no_faktur = b.no_faktur
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
				JOIN sales.t_surat_jalan_dist e
					ON b.no_sj = e.no_sj
				WHERE a.no_faktur = '$no_faktur'";
				
		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}
    public function search_do($kd_pelanggan="", $search = "", $offset, $length) {
               
        $sql = "select distinct a.no_so, a.tgl_so, a.kirim_so, a.kirim_alamat_so, a.kirim_telp_so, a.userid, a.keterangan
                from sales.t_sales_order_dist a, sales.t_surat_jalan_dist b
                left join sales.t_faktur_jual_detail c on b.no_sj = c.no_sj
                where a.no_so = b.no_so
                and a.kd_member = '$kd_pelanggan' 
                and c.no_sj is null
             ";

            $query = $this->db->query($sql .  " LIMIT ". $length . " OFFSET ".$offset);
            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }
        
            //print_r($this->db->last_query());
        
        
        $this->db->flush_cache();
        /**$this->db->select("count(distinct a.*) AS total");
        $this->db->join('sales.t_sales_order_detail  b', 'b.no_so=a.no_so');
        if ($search != "") {
            $this->db->where("((lower(a.no_so) LIKE '%" . $search . "%') OR (a.no_so LIKE '%" . $search . "%'))", NULL);
        }

        $this->db->where("a.status", '1');
        $this->db->where("b.is_kirim", '1');
        $query = $this->db->get("sales.t_sales_order a");**/

        $query = $this->db->query("select count(*) AS total from (".$sql.") tabel");
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function search_uang_muka($no_so="", $search = "", $offset, $length) {
      
        $sql = "select a.*,b.no_so,b.rp_jumlah,b.rp_uang_muka,coalesce(b.rp_uang_muka_terpakai,0) rp_uang_muka_terpakai,(b.rp_uang_muka - coalesce (b.rp_uang_muka_terpakai,0)) uang_muka_sisa
                from sales.t_uang_muka a,sales.t_uang_muka_detail b, sales.t_sales_order_dist c
                where b.no_so = c.no_so
                and a.no_bayar = b.no_bayar
                and b.no_so = '$no_so'
                ";
       $query = $this->db->query($sql .  " LIMIT ". $length . " OFFSET ".$offset);
            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }
        //print_r($this->db->last_query());
        $this->db->flush_cache();
        $query = $this->db->query("select count(*) AS total from (".$sql.") tabel");
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
}
