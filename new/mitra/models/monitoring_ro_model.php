<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_ro_model extends MY_Model {

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

    public function get_rows($kdSupplier = "", $tglAwal = "", $tglAkhir = "", $lokasi = "", $bloklokasi = "", $subbloklokasi = "",$invoice ="",$bayar ="", $peruntukan_sup ="", $peruntukan_dist ="",$search = "", $offset, $length){
        $sql_search = "";
        $where = "";
        $left = " left ";
        if($kdSupplier != ""){
            $where .=  " AND a.kd_supplier = '$kdSupplier' ";
            $left = " ";
        }

        if($lokasi != ""){
            $where .=  " AND a.kd_lokasi = '$lokasi' ";
            $left = " ";
        }

        if($bloklokasi != ""){
            $where .=  " AND a.kd_blok = '$bloklokasi' ";
            $left = " ";
        }

        if($subbloklokasi != ""){
            $where .=  " AND a.kd_subblok = '$subbloklokasi' ";
            $left = " ";
        }
        if($invoice == "1"){
            $where .=  " AND d.no_invoice is null";
            $left = " ";
        }elseif($invoice == "2"){
            $where .=  " AND d.no_invoice <> '' ";
            $left = " ";
        }else{
            $where .=  "";
            $left = " ";
        }
        if($bayar == "1"){
            $where .=  " AND g.no_bukti is null ";
            $left = " ";
        }elseif($bayar == "2"){
            $where .=  " AND g.no_bukti <> '' ";
            $left = " ";
        }else{
            $where .=  "";
            $left = " ";
        }
        
        if($tglAwal != "" && $tglAkhir != ""){
            $where .=  " AND a.tanggal between '$tglAwal' AND '$tglAkhir' ";
        }
        if($search != ""){
            $sql_search =  " AND (lower(a.no_do) LIKE '%" . strtolower($search) . "%')  OR (lower(d.no_invoice) LIKE '%" . strtolower($search) . "%')  OR (lower(g.no_bukti) LIKE '%" . strtolower($search) . "%') ";
            $this->db->where($sql_search);
        }
        if($peruntukan_sup != ""){
			$where .=  " AND a.kd_peruntukan = '$peruntukan_sup' ";
                }
        if($peruntukan_dist != ""){
                $where .=  " AND a.kd_peruntukan = '$peruntukan_dist' ";
        }
        $sql = "select  distinct on (a.no_do, d.no_invoice, d.kd_produk)
                a.*, c.nama_supplier, d.no_invoice, e.tgl_invoice,e.tgl_terima_invoice, g.no_bukti, h.tanggal,
                CASE WHEN a.kd_peruntukan ='1' THEN 'Distribusi' ELSE 'Supermarket' END peruntukan
                from purchase.t_receive_order a
                join purchase.t_dtl_receive_order f on a.no_do = f.no_do
                left join mst.t_supplier c on a.kd_supplier = c.kd_supplier
                left join purchase.t_invoice_detail d on d.no_do = a.no_do
                left join purchase.t_invoice e on e.no_invoice = d.no_invoice
                left join purchase.t_pelunasan_detail g on g.no_invoice = d.no_invoice
                left join purchase.t_pelunasan_hutang h on h.no_bukti = g.no_bukti
                where 1=1 $sql_search $where and a.kd_supplier = c.kd_supplier
                order by a.no_do desc, d.no_invoice desc, d.kd_produk desc, a.tanggal desc
                limit $length offset $offset";
        $query = $this->db->query($sql);
        //print_r($this->db->last_query());
        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(no_do) as total from (select  distinct on (a.no_do, d.no_invoice, d.kd_produk)
                a.no_do from purchase.t_receive_order a
                join purchase.t_dtl_receive_order f on a.no_do = f.no_do
                left join mst.t_supplier c on a.kd_supplier = c.kd_supplier
                left join purchase.t_invoice_detail d on d.no_do = a.no_do
                left join purchase.t_invoice e on e.no_invoice = d.no_invoice
                left join purchase.t_pelunasan_detail g on g.no_invoice = d.no_invoice
                left join purchase.t_pelunasan_hutang h on h.no_bukti = g.no_bukti
                where 1=1 $sql_search $where and a.kd_supplier = c.kd_supplier
                order by a.no_do, d.no_invoice, d.kd_produk, a.tanggal desc) as tabel";

        $query = $this->db->query($sql2);
        // print_r($this->db->last_query());exit;
        $total = 0;
        if($query->num_rows() > 0){
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $results;
    }

    public function get_data_html($no_do = ''){
        $sql = "select 'RECEIVE ORDER FORM' title, a.kd_supplier, b.nama_supplier, a.no_bukti_supplier, a.no_do, a.tanggal,
				a.tanggal_terima, a.created_by
				from purchase.t_receive_order a, mst.t_supplier b
				where a.no_do = '$no_do'
				and a.kd_supplier = b.kd_supplier";

        $query = $this->db->query($sql);

        if($query->num_rows() == 0) return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();

        $sql_detail = "select a.no_po, a.kd_produk, b.kd_produk_lama, b.kd_produk_supp, b.nama_produk, a.qty_terima,
						c.nm_satuan, d.nama_lokasi || ' - ' || e.nama_blok || ' - ' || f.nama_sub_blok gudang,
						nama_ekspedisi, 
						(SELECT h.nm_satuan as nm_satuan_ekspedisi 
							FROM mst.t_satuan h 
							WHERE h.kd_satuan = a.kd_satuan_ekspedisi
						),
						berat_ekspedisi
						from purchase.t_dtl_receive_order a
						JOIN mst.t_produk b
							ON a.kd_produk = b.kd_produk
						JOIN mst.t_satuan c
							ON b.kd_satuan = c.kd_satuan
						JOIN mst.t_lokasi d
							ON a.kd_lokasi = d.kd_lokasi
						JOIN mst.t_blok e 
							ON a.kd_blok = e.kd_blok
						JOIN mst.t_sub_blok f 
							ON a.kd_sub_blok = f.kd_sub_blok
						LEFT JOIN mst.t_ekpedisi g
							ON  g.kd_ekspedisi = a.kd_ekspedisi
						where a.no_do = '$no_do'
						and d.kd_lokasi = e.kd_lokasi
						and d.kd_lokasi = f.kd_lokasi 
						and e.kd_blok = f.kd_blok ";

        $query_detail = $this->db->query($sql_detail);
        $data['detail'] = $query_detail->result();

        // print_r($this->db->last_query());exit;
        return $data;
    }

}
