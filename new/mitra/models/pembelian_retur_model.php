<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_retur_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_row($table = '', $data = NULL) {
        return $this->db->insert($table, $data);
    }
    public function get_all_po($no_ro = "", $search = "") {
        if ($search != '') {
            $where = " AND ((lower(no_po) LIKE '%" . $search . "%') OR (no_po LIKE '%" . $search . "%'))";
        }
        $sql = "select distinct a.no_do,a.no_po, b.tanggal_po, c.tanggal
                from purchase.t_dtl_receive_order a, purchase.t_purchase b, purchase.t_receive_order c
                where a.no_po = b.no_po 
                and a.no_do = c.no_do 
                and a.no_do = '$no_ro' " . $where . "";

        $query = $this->db->query($sql);
        //print_r($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
//         print_r($this->db->last_query());
        $results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function search_produk_by_supplier($kd_supplier = '', $kd_produk = '',$no_ro = '',$no_po ='', $search = "") {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " AND ((lower(a.nama_produk) LIKE '%" . $search . "%') 
                                                OR (a.nama_produk LIKE '%" . $search . "%') 
                                                OR (lower(a.kd_produk) LIKE '%" . $search . "%') 
                                                OR (a.kd_produk LIKE '%" . $search . "%') 
                                                OR (lower(a.kd_produk_supp) LIKE '%" . $search . "%') 
                                                OR (a.kd_produk_supp LIKE '%" . $search . "%') 
                                                OR (lower(a.kd_produk_lama) LIKE '%" . $search . "%') 
                                                OR (a.kd_produk_lama LIKE '%" . $search . "%'))";
        }

        $where = '';
        if ($kd_produk != '') {
            $where = " AND b.kd_produk = '$kd_produk' ";
        }
        if ($no_ro != '') {
            $where = " AND f.no_do = '$no_ro' ";
        }
        
        $sql ="SELECT f.kd_lokasi || f.kd_blok || f.kd_sub_blok sub, i.nama_lokasi || '-' || h.nama_blok || '-' || g.nama_sub_blok nama_sub,f.no_do, e.pkp,b.no_po, b.kd_produk, b.qty_po qty, f.qty_terima, f.qty_retur,
                b.disk_persen_supp1_po disk_persen_supp1, b.disk_persen_supp2_po disk_persen_supp2, b.disk_persen_supp3_po disk_persen_supp3, b.disk_persen_supp4_po disk_persen_supp4, 
                b.disk_amt_supp1_po disk_amt_supp1, b.disk_amt_supp2_po disk_amt_supp2, b.disk_amt_supp3_po disk_amt_supp3, b.disk_amt_supp4_po disk_amt_supp4, b.disk_amt_supp5_po disk_amt_supp5,
                b.price_supp_po harga_supplier, b.price_supp_po hrg_supplier,a.nama_produk, a.kd_produk, c.nm_satuan
                FROM purchase.t_purchase_detail b
                JOIN 
                mst.t_produk a ON b.kd_produk = a.kd_produk
                JOIN
                purchase.t_dtl_receive_order f ON f.no_po = b.no_po AND f.kd_produk =  b.kd_produk
                JOIN 
                mst.t_satuan c ON c.kd_satuan = a.kd_satuan
                JOIN 
                purchase.t_purchase d ON d.no_po = b.no_po
                JOIN 
                mst.t_supplier e ON e.kd_supplier = d.kd_suplier_po
                JOIN mst.t_sub_blok g
                        ON g.kd_sub_blok = f.kd_sub_blok AND g.kd_blok = f.kd_blok AND g.kd_lokasi = f.kd_lokasi
                JOIN mst.t_blok h
                        ON h.kd_blok = f.kd_blok AND h.kd_lokasi = f.kd_lokasi
                JOIN mst.t_lokasi i
                        ON i.kd_lokasi = f.kd_lokasi
                WHERE a.aktif = 1
                AND d.kd_suplier_po = '$kd_supplier' 
                ".$sql_search ."
                 ".$where."
                ";
        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        //print_r($this->db->last_query());
        return $rows;
    }
    
    public function get_rows_lokasi($kd_produk = '',$kd_peruntukan_supp = '',$kd_peruntukan_dist ='', $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
        }
        if ($kd_peruntukan_supp != "") {
            $kd_peruntukan = $kd_peruntukan_supp;
        }
        if ($kd_peruntukan_dist != "") {
            $kd_peruntukan = $kd_peruntukan_dist;
        }

        $sql1 = "SELECT a.kd_lokasi || a.kd_blok || a.kd_sub_blok sub, d.nama_lokasi || '-' || c.nama_blok || '-' || b.nama_sub_blok nama_sub,
					a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_sub_blok,  c.nama_blok, d.nama_lokasi, b.kapasitas,
					CASE WHEN d.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
					FROM mst.t_produk_lokasi a
					JOIN mst.t_sub_blok b
						ON b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					JOIN mst.t_blok c
						ON c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
					JOIN mst.t_lokasi d
						ON d.kd_lokasi = a.kd_lokasi
					WHERE a.kd_produk = '$kd_produk' 
                                            
					" . $sql_search . "
					LIMIT " . $length . " OFFSET " . $offset;

        $query = $this->db->query($sql1);

        //print_r($this->db->last_query());exit;

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_sub_blok a
					join mst.t_blok b ON b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					join mst.t_lokasi c ON c.kd_lokasi = b.kd_lokasi
					" . $sql_search . "";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function search_supplier($search = "", $offset, $length){
		if($search != ""){
			$this->db->where("((lower(nama_supplier) LIKE '%" . $search . "%') OR (kd_supplier LIKE '%" . $search . "%') OR (nama_supplier LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif is TRUE", NULL);
		$this->db->order_by("nama_supplier");
		$query = $this->db->get("mst.t_supplier", $length, $offset);
		
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}        
		
		$this->db->flush_cache();
		$this->db->select("count(*) AS total");
        if($search != ""){
			$this->db->where("((lower(nama_supplier) LIKE '%" . $search . "%') OR (kd_supplier LIKE '%" . $search . "%') OR (nama_supplier LIKE '%" . $search . "%'))", NULL);
		}
		$this->db->where("aktif is TRUE", NULL);
		$query = $this->db->get("mst.t_supplier");
				
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		 
		//$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
        return $rows;
        //return $results;
	}

    public function search_produk_by_no_invoice($kd_supplier = '', $kd_produk = '', $no_invoice = '') {
        $where = '';
        if ($no_invoice != '') {
            $where = " AND d.no_invoice= '$no_invoice' ";
        }

        $sql = "SELECT b.harga_supplier hrg_supplier, e.pkp,b.*, b.qty qty_terima, a.nama_produk, a.kd_produk, c.nm_satuan, d.no_faktur_pajak
					FROM purchase.t_invoice_detail b
					JOIN mst.t_produk a ON b.kd_produk = a.kd_produk
                                        JOIN mst.t_satuan c ON c.kd_satuan = a.kd_satuan
                                        JOIN purchase.t_invoice d ON d.no_invoice = b.no_invoice 
                                        JOIN mst.t_supplier e ON e.kd_supplier = d.kd_supplier
					WHERE a.aktif = 1
					AND d.kd_supplier = '$kd_supplier' 
					AND b.kd_produk= '$kd_produk' " . $where . "
					ORDER BY a.nama_produk ASC";


        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        //print_r($this->db->last_query());
        return $rows;
    }
    
    public function query_update($sql = "") {
        return $this->db->query($sql);
    }
    public function search_produk_by_no_po($kd_supplier = '', $kd_produk = '',$kd_peruntukan ='', $query = '') {
        $where = '';
        $peruntukan ='';
        if ($query != '') {

            $where = " AND (lower(a.no_do) LIKE lower('%" . $query . "%')) ";
        }
        if ($kd_peruntukan == '1' || $kd_peruntukan == '0'){
            $peruntukan = " AND c.kd_peruntukan = '$kd_peruntukan'";
        }
        $sql ="select distinct a.no_do,a.no_po, b.tanggal_po, c.tanggal
                from purchase.t_dtl_receive_order a, purchase.t_purchase b, purchase.t_receive_order c
                where a.no_po = b.no_po 
                and a.no_do = c.no_do 
                and b.kd_suplier_po = '$kd_supplier'  " . $where . " " . $peruntukan . " ORDER BY a.no_do DESC";

        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
         //print_r($this->db->last_query());
        return $rows;
    }

    public function cek_exists_brg_inv($kd_produk = null, $kd_lokasi = null, $kd_blok = null, $kd_sub_blok = null) {
        $sql = "select qty_oh from inv.t_brg_inventory 
                  where kd_produk='$kd_produk'
                  and kd_lokasi='$kd_lokasi'
                  and kd_blok='$kd_blok'
                  and kd_sub_blok='$kd_sub_blok'";

        $query = $this->db->query($sql);
        $rows = array();

        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }

    public function update_brg_inv($id = NULL, $id1 = NULL, $id2 = NULL, $id3 = NULL, $data = NULL) {
        $this->db->where('kd_produk', $id);
        $this->db->where('kd_lokasi', $id1);
        $this->db->where('kd_blok', $id2);
        $this->db->where('kd_sub_blok', $id3);
        return $this->db->update('inv.t_brg_inventory', $data);
    }

    public function get_hpp_by_kd_produk($kd_produk = '') {
        $this->db->select("a.rp_cogs,qty_stok,b.rp_ongkos_kirim,b.pct_margin");
        $this->db->join("mst.t_produk b", "a.kd_produk = b.kd_produk");
        $this->db->where("a.kd_produk", $kd_produk);
        $this->db->where("a.kd_peruntukan", '0');
        $query = $this->db->get("inv.t_hpp_inventory a");
        $row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }

        return $row;
    }

    public function get_pkp($kd_supplier = '') {
        $this->db->select("pkp");
        $this->db->where("kd_supplier", $kd_supplier);
        $query = $this->db->get("mst.t_supplier a");
        $row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }

        return $row;
    }

    public function update_row_hpp($kd_peruntukan = '', $kd_produk = '', $datau = '') {
        $this->db->where('kd_peruntukan', $kd_peruntukan);
        $this->db->where('kd_produk', $kd_produk);
        return $this->db->update('inv.t_hpp_inventory', $datau);
    }

    public function insert_row_histo($no_bukti) {
        $sql = "insert into inv.t_hpp_inventory_histo
				select a.*, '$no_bukti' from inv.t_hpp_inventory a";
        return $this->db->query($sql);
    }

    public function get_data_print($no_retur = '') {
        $sql = "select 'RETUR PEMBELIAN' title, b.pkp, a.created_by, a.no_retur, a.tgl_retur, a.remark, b.nama_supplier, a.rp_jumlah, a.pcn_ppn, a.rp_ppn, a.rp_total, a.kd_suplier, b.nama_supplier 
					from purchase.t_retur_purchase a, mst.t_supplier b
					where a.no_retur = '$no_retur'
					and a.kd_suplier = b.kd_supplier
					and a.is_konsinyasi = 0";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select 'RETUR PEMBELIAN' title, a.*,b.nama_produk, b.kd_produk_supp,c.nm_satuan ,d.no_faktur_pajak
							from purchase.t_retur_purchase_detail a, 
							mst.t_produk b, mst.t_satuan c ,purchase.t_invoice d
							where a.no_retur = '$no_retur'
							and a.kd_produk = b.kd_produk
							and b.kd_satuan = c.kd_satuan
							and a.no_invoice = d.no_invoice";

        $query_detail = $this->db->query($sql_detail);

        $data['detail'] = $query_detail->result();

        return $data;
    }
    
    public function get_data_print_ro($no_retur = '') {
        $sql = "select 'RETUR RECEIVE ORDER' title, b.pkp, a.created_by, a.no_retur, a.tgl_retur, a.remark, b.nama_supplier, a.rp_jumlah, a.pcn_ppn, a.rp_ppn, a.rp_total, a.kd_suplier, b.nama_supplier 
					from purchase.t_retur_purchase a, mst.t_supplier b
					where a.no_retur = '$no_retur'
					and a.kd_suplier = b.kd_supplier
					and a.is_konsinyasi = 0";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select 'RETUR RECEIVE ORDER' title, a.*,b.nama_produk, b.kd_produk_supp,c.nm_satuan,d.tanggal_po,e.tanggal
                        from purchase.t_retur_purchase_detail a, 
                        mst.t_produk b, mst.t_satuan c, purchase.t_purchase d, purchase.t_receive_order e
                        where a.no_retur = '$no_retur'
                        and a.kd_produk = b.kd_produk
                        and b.kd_satuan = c.kd_satuan
                        and a.no_po = d.no_po
                        and a.no_do = e.no_do
                        ";

        $query_detail = $this->db->query($sql_detail);

        $data['detail'] = $query_detail->result();

        return $data;
    }

}

?>
