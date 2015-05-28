<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_piutang_distribusi_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function search_faktur($search = '', $start, $limit) {
        if ($search != "") {
            $sql_search = "AND (lower(no_faktur) LIKE '%" . strtolower($search) . "%') ";
        }
        $sql = "select no_faktur,tgl_faktur
				from sales.t_faktur_jual 
				where 1=1
				" . $sql_search . "
                                order by no_faktur desc";

        $query = $this->db->query($sql);
        $rows = array();

        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        //print_r($this->db->last_query());
        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
     public function search_no_bayar($search = '', $start, $limit) {
        if ($search != "") {
            $sql_search = "AND (lower(no_bayar) LIKE '%" . strtolower($search) . "%') ";
        }
        $sql = "select no_pembayaran_piutang,tgl_bayar,rp_bayar
				from sales.t_piutang_pembayaran 
				where 1=1
				" . $sql_search . "
                                order by no_faktur desc";

        $query = $this->db->query($sql);
        $rows = array();

        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        //print_r($this->db->last_query());
        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_rows($search, $no_faktur, $pelanggan, $status, $tgl_min, $tgl_max,$no_bayar, $start, $limit) {
        $sql_search = "";
		$where = "";
		if($pelanggan != ""){
			$where .=  " AND a.kd_pelanggan = '$pelanggan' ";
		}
                if($no_faktur != ""){
			$where .=  " AND a.no_faktur = '$no_faktur' ";
		}
		if($status != "" && $status != "1" ){
			$where .=  " AND a.status = '$status' ";
		}
	
       		if($tgl_min != "" && $tgl_max != ""){
			$where .=  " AND a.tgl_faktur between '$tgl_min' AND '$tgl_max' ";
		}
		if($search != ""){
			$sql_search =  " AND ((lower(a.no_faktur) LIKE '%" . strtolower($search) . "%') OR (lower(b.nama_pelanggan) LIKE '%" . strtolower($search) . "%'))";
		}
                if($no_bayar != ""){
                    $sql = "select distinct on (a.no_faktur) a.tgl_faktur,c.rp_bayar as pembayaran,a.no_faktur,a.kd_pelanggan,b.nama_pelanggan,a.rp_faktur,a.rp_uang_muka,a.cash_diskon,a.rp_bayar,a.rp_kurang_bayar,CASE WHEN a.status='2' THEN 'LUNAS' ELSE 'BELUM LUNAS' END status
                            from sales.t_faktur_jual a,mst.t_pelanggan_dist b, sales.t_piutang_dist_detail c
                            where a.kd_pelanggan = b.kd_pelanggan 
                            and a.no_faktur = c.no_faktur
                            and c.no_pembayaran_piutang = '$no_bayar'
                            ".$where." ".$sql_search." ";
                }else{
                    $sql = "select a.no_faktur,a.tgl_faktur,a.kd_pelanggan,b.nama_pelanggan,a.rp_faktur,a.rp_uang_muka,a.cash_diskon,a.rp_bayar,a.rp_kurang_bayar,CASE WHEN a.status='2' THEN 'LUNAS' ELSE 'BELUM LUNAS' END status
                            from sales.t_faktur_jual a,mst.t_pelanggan_dist b
                            where a.kd_pelanggan = b.kd_pelanggan 
                            ".$where." ".$sql_search." ";
                }
//        return $sql;
        $query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (".$sql.") as tabel limit 1";
        
            $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{"success":true, "record":'.$total.', "data":'.json_encode($rows).'}';
        return $results;
	}
    public function get_data_per_faktur($no_faktur) {
        $sql_header = <<<EOT
                    select a.no_faktur,a.tgl_faktur,a.kd_pelanggan,b.nama_pelanggan,a.rp_faktur,a.rp_uang_muka,a.cash_diskon,a.rp_bayar,a.rp_kurang_bayar,CASE WHEN a.status='2' THEN 'LUNAS' ELSE 'BELUM LUNAS' END status,b.no_telp,b.alamat_kirim
                    from sales.t_faktur_jual a,mst.t_pelanggan_dist b
                    where a.kd_pelanggan = b.kd_pelanggan  and no_faktur = '$no_faktur'
EOT;
        $sql_bayar = <<<EOT
                    select b.no_faktur,c.tgl_bayar,a.*,d.nm_pembayaran
                    from sales.t_piutang_dist_bayar a, sales.t_piutang_dist_detail b, sales.t_piutang_pembayaran c, mst.t_jns_pembayaran d
                    where a.no_pembayaran_piutang = b.no_pembayaran_piutang
                    and b.no_pembayaran_piutang = c.no_pembayaran_piutang
                    and a.kd_jns_bayar = d.kd_jenis_bayar
                    and b.no_faktur = '$no_faktur'
EOT;
        $sql_kirim = <<<EOT
                    select a.*,b.nama_produk,b.kd_produk_supp,c.nm_satuan,d.rp_uang_muka,d.cash_diskon 
                    from sales.t_faktur_jual_detail a,mst.t_produk b, mst.t_satuan c,sales.t_faktur_jual d
                    where a.kd_produk = b.kd_produk
                    and b.kd_satuan = c.kd_satuan
                    and a.no_faktur = d.no_faktur
                    and a.no_faktur ='$no_faktur'
EOT;
$sql_retur = <<<EOT
select b.no_so
    , a.no_retur
    , a.kd_produk
    , d.kd_produk_lama
    , d.nama_produk
    , a.qty
    , e.nm_satuan
    , a.rp_jumlah
    , a.rp_disk
    , a.rp_potongan
    , a.rp_total
from sales.t_retur_sales_detail a
join sales.t_retur_sales b on a.no_retur = b.no_retur
join mst.t_produk d on d.kd_produk = a.kd_produk
join mst.t_satuan e on e.kd_satuan = d.kd_satuan
where b.no_so_retur = '$no_so'
EOT;

        $query = $this->db->query($sql_header);
        if ($query->num_rows() > 0) {
            $result['header'] = $query->row();
        }
        $query = $this->db->query($sql_kirim);
        if ($query->num_rows() > 0) {
            $result['detail_penjualan'] = $query->result();
        }
       
        $query = $this->db->query($sql_bayar);
        if ($query->num_rows() > 0) {
            $bayar_cicil = $query->result();
        }
        foreach ($bayar_cicil as $bayar) {
            $result['detail_pembayaran'][] = $bayar;
        }
//        $query = $this->db->query($sql_retur);
//        if ($query->num_rows() > 0) {
//            $result['detail_retur'] = $query->result();
//        }

        unset($bayar_cicil);
        $this->db->flush_cache();

        return $result;
    }
}

?>
