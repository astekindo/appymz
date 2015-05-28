<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class outstanding_struk_bintang2_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function getRows($length, $offset, $search = "") {
        
        /**
         * query untuk retrieve record dari database
         */
        
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND ((lower(z.nama_produk) LIKE '%" . strtolower($search) . "%') OR (y.kd_produk LIKE '%" . strtolower($search) . "%'))";
            
        }

        $queryData = "select y.no_so, y.tgl_so, y.kd_produk,z.nama_produk, y.qty_kirim, coalesce(x.qty_dikirim, 0) qty_dikirim 
        from
        (select a.no_so, a.tgl_so, b.kd_produk, b.qty qty_kirim
        from 
        sales.t_sales_order_detail b, sales.t_sales_order a 
        where
        a.no_so = b.no_so
        and b.is_kirim = 1) y
        LEFT JOIN
        (select c.no_so, e.kd_produk, sum(e.qty) qty_dikirim from sales.t_sales_delivery_order c, sales.t_surat_jalan d, sales.t_surat_jalan_detail e
        where c.no_do = d.no_do
        and d.no_sj = e.no_sj group by c.no_so, e.kd_produk
        ) x on x.no_so = y.no_so and y.kd_produk = x.kd_produk
        JOIN mst.t_produk z on y.kd_produk = z.kd_produk
        where qty_kirim <> qty_dikirim $sql_search
        order by no_so, tgl_so desc limit $length offset $offset";


        /**
         * query untuk get total row
         */
        
        $queryTotal = "select count(*) as total from (select y.no_so, y.tgl_so, y.kd_produk,z.nama_produk, y.qty_kirim, coalesce(x.qty_dikirim, 0) qty_dikirim 
        from
        (select a.no_so, a.tgl_so, b.kd_produk, b.qty qty_kirim
        from 
        sales.t_sales_order_detail b, sales.t_sales_order a 
        where
        a.no_so = b.no_so
        and b.is_kirim = 1) y
        LEFT JOIN
        (select c.no_so, e.kd_produk, sum(e.qty) qty_dikirim from sales.t_sales_delivery_order c, sales.t_surat_jalan d, sales.t_surat_jalan_detail e
        where c.no_do = d.no_do
        and d.no_sj = e.no_sj group by c.no_so, e.kd_produk
        ) x on x.no_so = y.no_so and y.kd_produk = x.kd_produk
        JOIN mst.t_produk z on y.kd_produk = z.kd_produk
        where qty_kirim <> qty_dikirim $sql_search
        order by no_so, tgl_so desc) as tabel limit 1";

        $queryProcess1 = $this->db->query($queryData);
       
        $result = $queryProcess1->result();
        $jsonresults = json_encode($result);

        $queryProcess2 = $this->db->query($queryTotal);
        $total = $queryProcess2->row()->total;
        $results = '{success:true,record:' . $total . ',data:' . $jsonresults . '}';
        return $results;
    }

}
