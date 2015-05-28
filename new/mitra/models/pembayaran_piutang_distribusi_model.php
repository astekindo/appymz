<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_piutang_distribusi_model extends MY_Model { 
	 
	public function __construct(){
		parent::__construct();
	}
	
	public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
		
		// print_r($this->db->last_query());
	}
        public function search_bstt($kd_colector = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(no_bstt) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * from sales.t_bstt where status = 0
                         " . $sql_search . "  order by no_bstt desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                                 from sales.t_bstt where kd_collector ='$kd_colector'";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
        public function get_all_faktur($kd_pelanggan = "",$no_bstt ="", $search = ""){
		$where ="";
                if ($search != '') {
                   $where .= " AND ((lower(a.no_faktur) LIKE '%" . $search . "%') OR (a.no_faktur LIKE '%" . $search . "%'))";
                }if ($no_bstt !=''){
                   $where .= " and b.no_bstt = '$no_bstt' ";
                }
                           $sql ="select a.* from sales.t_faktur_jual a
                            join sales.t_bstt_detail b on a.kd_pelanggan = b.kd_pelanggan
                            where a.kd_pelanggan ='$kd_pelanggan' and a.rp_kurang_bayar > 0 " . $where . "";
              
                $query = $this->db->query($sql);
		//print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        public function get_rows($kd_pelanggan = "",$no_bstt ="", $search = ""){
		$where ="";
                if ($search != '') {
                 $where .= " AND ((lower(no_faktur) LIKE '%" . $search . "%') OR (no_faktur LIKE '%" . $search . "%'))";
                }
                
                    $sql ="select a.*,b.tanggal from sales.t_bstt_detail a
                            join sales.t_bstt b on a.no_bstt = b.no_bstt
                            where a.kd_pelanggan ='$kd_pelanggan' and a.rp_kurang_bayar > 0 
                            AND a.no_bstt = '$no_bstt' " . $where . "";
               
                $query = $this->db->query($sql);
		//print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}
        
        public function update_faktur_jual($no_faktur, $rp_kurang_bayar,$rp_total_bayar){
                if($rp_kurang_bayar <= 0){
			$sql = "UPDATE sales.t_faktur_jual SET status=2, rp_kurang_bayar = $rp_kurang_bayar,rp_bayar=rp_bayar+" . $rp_total_bayar . " WHERE no_faktur='" . $no_faktur . "'";
		}else{
			$sql = "UPDATE sales.t_faktur_jual SET rp_kurang_bayar = $rp_kurang_bayar,rp_bayar=rp_bayar+" . $rp_total_bayar . " WHERE no_faktur='" . $no_faktur . "'";;
		}
		$this->db->flush_cache();
	
		return $this->db->query($sql);
		
		// print_r($this->db->last_query());
	}
        
        public function get_data_print($no_bukti = ''){	
		$sql = "select 'PEMBAYARAN PIUTANG (DISTRIBUSI)' title,a.*
                        from sales.t_piutang_pembayaran a
                        where no_pembayaran_piutang = '$no_bukti'
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = " select a.no_pembayaran_piutang,a.tgl_faktur,a.rp_bayar,a.no_faktur,a.rp_faktur,a.rp_potongan,a.rp_dibayar,b.rp_uang_muka,b.cash_diskon,a.rp_piutang,a.rp_bayar, b.rp_kurang_bayar,b.rp_bayar total_bayar
                                from sales.t_piutang_dist_detail a, sales.t_faktur_jual b
                                where a.no_faktur = b.no_faktur
                                and a.no_pembayaran_piutang = '$no_bukti'
                                ";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
                
                $this->db->flush_cache();
		$sql_detail_bayar = "select a.*,b.nm_pembayaran 
                                    from sales.t_piutang_dist_bayar a ,mst.t_jns_pembayaran b
                                    where a.kd_jns_bayar = b.kd_jenis_bayar
                                    and a.no_pembayaran_piutang = '$no_bukti'
                                    ";
		
		$query_detail_bayar = $this->db->query($sql_detail_bayar);
		
		$data['detail_bayar'] = $query_detail_bayar->result();
		
		return $data;
	}
}
?>
