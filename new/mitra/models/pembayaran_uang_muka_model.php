<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Pembayaran_uang_muka_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function get_noso($kd_pelanggan="", $search = "", $offset, $length) {
               
//            $sql ="select a.*,(rp_total - rp_uang_muka)sisa_bayar
//                    from (
//                    select a.no_so,a.rp_total,a.rp_dpp,a.rp_ppn,sum(b.rp_uang_muka) rp_uang_muka
//                    from sales.t_sales_order_dist a, sales.t_uang_muka_detail b
//                    where a.no_so = b.no_so
//                    and a.kd_member = '$kd_pelanggan'
//                    group by a.kd_member,a.no_so,a.rp_total,a.rp_dpp,a.rp_ppn
//                    ) a";
          $sql = "select tgl_so,no_so,rp_total,rp_dpp,rp_ppn,rp_uang_muka,(rp_total - rp_uang_muka)sisa_bayar
                  from sales.t_sales_order_dist
                  where  kd_member ='$kd_pelanggan'";
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
    public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
		
		// print_r($this->db->last_query());
	}
     public function query_update($sql = "") {
           return $this->db->query($sql);
        }
    public function update_sales_order_dist($no_so, $rp_uang_muka){
               
		$sql = "UPDATE sales.t_sales_order_dist SET rp_uang_muka=rp_uang_muka+" . $rp_uang_muka . " WHERE no_so='" . $no_so . "'";
		
		$this->db->flush_cache();
	
		return $this->db->query($sql);
		
		// print_r($this->db->last_query());
	}
   public function get_data_print($no_bayar = ''){	
		$sql = "select 'PEMBAYARAN UANG MUKA FORM' title,a.*
                        from sales.t_uang_muka a
                        where no_bayar = '$no_bayar'
                        ";

		$query = $this->db->query($sql);
		
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = " select a.*,b.*,a.rp_uang_muka uang_muka
                                from sales.t_uang_muka_detail a, sales.t_sales_order_dist b
                                where a.no_so = b.no_so
                                and a.no_bayar = '$no_bayar'
                                ";
		
		$query_detail = $this->db->query($sql_detail);
		
		$data['detail'] = $query_detail->result();
                
                $this->db->flush_cache();
		$sql_detail_bayar = "select a.*,b.nm_pembayaran 
                                    from sales.t_uang_muka_bayar a ,mst.t_jns_pembayaran b
                                    where a.kd_jenis_bayar = b.kd_jenis_bayar
                                    and a.no_bayar = '$no_bayar'
                                    ";
		
		$query_detail_bayar = $this->db->query($sql_detail_bayar);
		
		$data['detail_bayar'] = $query_detail_bayar->result();
		
		return $data;
	}
}