<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_pelunasan_piutang_model extends MY_Model {

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
	public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);

		// print_r($this->db->last_query());
	}


	public function get_rows($no_faktur){

//		$sql = "SELECT a.kd_produk, c.nama_produk, d.nm_satuan, a.qty, COALESCE(b.rp_piutang,a.rp_total,b.rp_piutang) rp_piutang,
//					COALESCE(b.rp_piutang,a.rp_total,b.rp_piutang) rp_bayar, a.rp_total
//					FROM sales.t_sales_order_detail a
//					LEFT JOIN sales.t_piutang_detail b
//						ON a.kd_produk = b.kd_produk
//							AND a.no_so = b.no_faktur
//					JOIN mst.t_produk c
//						ON c.kd_produk = a.kd_produk
//					JOIN mst.t_satuan d
//						ON d.kd_satuan = c.kd_satuan
//					WHERE no_so = '$no_faktur'";

            $sql = "SELECT * from sales.t_sales_order WHERE no_so = '$no_faktur'";
            $query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function sisa_faktur($no_faktur, $rp_sisa_piutang){
		$sql = "UPDATE sales.t_piutang_pelunasan SET rp_sisa_piutang = " . $rp_sisa_piutang . " WHERE no_faktur='" . $no_faktur . "'";;


		$this->db->flush_cache();

		return $this->db->query($sql);
	}

	public function update_pelunasan($no_faktur, $rp_pelunasan){
		$sql = "UPDATE sales.t_piutang_pelunasan SET rp_pelunasan=COALESCE(rp_pelunasan,0,rp_pelunasan)+" . $rp_pelunasan . " WHERE no_faktur='" . $no_faktur . "'";;

		$this->db->flush_cache();

		return $this->db->query($sql);
		// print_r($this->db->last_query());
	}
        public function update_sales_order($no_faktur, $rp_kurang_bayar,$rp_total_bayar){
        if($rp_kurang_bayar <= 0) {
			$sql = "UPDATE sales.t_sales_order SET status=2, rp_kurang_bayar = $rp_kurang_bayar,rp_total_bayar=rp_total_bayar+$rp_total_bayar WHERE no_so='$no_faktur'";
		}else{
			$sql = "UPDATE sales.t_sales_order SET rp_kurang_bayar = $rp_kurang_bayar,rp_total_bayar=rp_total_bayar+$rp_total_bayar WHERE no_so='$no_faktur'";;
		}
		$this->db->flush_cache();

		return $this->db->query($sql);

		// print_r($this->db->last_query());
	}

	public function select_detail($no_faktur, $kd_produk){
		$where = array(
						'no_faktur' => $no_faktur,
						'kd_produk' => $kd_produk,
				);
		$this->db->where($where);
		$query = $this->db->get('sales.t_piutang_detail');

		$results = FALSE;

		if($query->num_rows() > 0 ){
			$results = TRUE;
		}

		return $results;
	}

	public function update_detail($no_faktur = NULL, $kd_produk = NULL, $data = NULL) {
        $this->db->where('no_faktur', $no_faktur);
        $this->db->where('kd_produk', $kd_produk);
        return $this->db->update('sales.t_piutang_detail', $data);
		// print_r($this->db->last_query());
    }


	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_faktur($search = ""){
		if ($search != '') {
            $search = " AND ((lower(no_so) LIKE '%" . $search . "%') OR (no_so LIKE '%" . $search . "%'))";
        }
        $sql ="select * from sales.t_sales_order where status = '0' or rp_kurang_bayar > 0  " . $search . "";
        $query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_data_print($no_bukti = ''){
		$sql = "select 'PEMBAYARAN PIUTANG FORM' title,a.*
                        from sales.t_piutang_pelunasan a
                        where no_pelunasan_piutang = '$no_bukti'
                        ";

		$query = $this->db->query($sql);

		if($query->num_rows() == 0) return FALSE;

		$data['header'] = $query->row();

		$this->db->flush_cache();
		$sql_detail = " select a.*,b.*
                                from sales.t_piutang_detail a, sales.t_sales_order b
                                where a.no_faktur = b.no_so
                                and a.no_pelunasan_piutang = '$no_bukti'
                                ";

		$query_detail = $this->db->query($sql_detail);

		$data['detail'] = $query_detail->result();

                $this->db->flush_cache();
		$sql_detail_bayar = "select a.*,b.nm_pembayaran
                                    from sales.t_piutang_bayar a ,mst.t_jns_pembayaran b
                                    where a.kd_jns_bayar = b.kd_jenis_bayar
                                    and a.no_pelunasan_piutang = '$no_bukti'
                                    ";

		$query_detail_bayar = $this->db->query($sql_detail_bayar);

		$data['detail_bayar'] = $query_detail_bayar->result();

		return $data;
	}

	public function search_pelanggan(){
		$this->db->select('kd_pelanggan, nama_pelanggan');
		$query = $this->db->get('mst.t_pelanggan_dist');

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

		return $results;
	}
        public function query_update($sql = "") {
           return $this->db->query($sql);
        }
}
