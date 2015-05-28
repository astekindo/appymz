<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_retur_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */

    public function get_kode_sequence($kode_proses, $digit){
		$query = $this->db->query("SELECT mst.get_sequence('". $kode_proses . "', " . $digit . ") id");
		$kode = "";
        if($query->num_rows() > 0){
        	$kode = $query->row()
						->id;
        }

        return $kode;
	}

    public function search_salesorder($search = "", $offset, $length) {
        $sql_search = " ";
        if ($search != "") {
            $sql_search = "where (lower(no_so) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select no_so, tgl_so, rp_total, rp_diskon, rp_diskon_tambahan, rp_grand_total, keterangan, kirim_so, userid
                 from sales.t_sales_order " . $sql_search . "  order by tgl_so desc
		limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total
			from sales.t_sales_order";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function search_do($no_so = "",$kd_produk ="", $search = "", $kd_peruntukan = "") {
        if ($search != '') {
            $where = " AND ((lower(a.no_do) LIKE '%" . $search . "%') OR (a.no_do LIKE '%" . $search . "%'))";
        }
        $sql = "select a.no_do,a.tanggal,b.qty - b.qty_retur_do as qty
                from sales.t_sales_delivery_order a,sales.t_sales_delivery_order_detail b
                where a.no_do = b.no_do
                AND b.kd_barang ='$kd_produk' AND b.qty - b.qty_retur_do > 0
                AND a.no_so = '" . $no_so . "'  " . $where . "
                order by a.no_do desc";

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

    public function search_produk_by_salesorder($no_so = "") {
        $sql = "select b.qty qty_so, (b.qty_retur_so + coalesce(f.qty_retur_do, 0) + b.qty_retur) as tot_qty_retur_so, (b.qty - qty_retur_so - coalesce(f.qty_retur_do, 0) - b.qty_retur) as qty_efektif_so,
                coalesce(f.qty_do, 0) qty_do,(coalesce(f.qty_retur_do, 0) + b.qty_retur) tot_qty_retur_do,(coalesce(f.qty_do, 0) - coalesce(f.qty_retur_do, 0) - b.qty_retur) qty_efektif_do,
                case b.is_kirim
                    when 0 THEN
                        b.qty
                    else
                        coalesce(f.qty_sj, 0)
                end qty_sj,
                b.qty_retur,
                case b.is_kirim
                    when 0 THEN
                        b.qty - b.qty_retur
                    else
                        coalesce(f.qty_sj, 0) - b.qty_retur
                end qty_efektif_sj,

                e.no_so, e.tgl_so, e.kirim_so, e.kirim_alamat_so,e.rp_diskon_tambahan, e.kirim_telp_so, e.rp_total, e.rp_diskon, e.rp_bank_charge, e.rp_ongkos_kirim,
                e.rp_ongkos_pasang, e.rp_total_nett, e.rp_grand_total,
                b.kd_produk, c.nama_produk,c.kd_produk_supp,
                d.nm_satuan, b.rp_harga, b.rp_diskon, b.rp_total, b.rp_ekstra_diskon, b.keterangan
                from  mst.t_produk c, mst.t_satuan d, sales.t_sales_order e, sales.t_sales_order_detail b
                left join
                (select x.no_so, y.kd_barang, sum(y.qty_sj) qty_sj ,sum(y.qty) qty_do, sum(qty_retur_do) qty_retur_do
                from sales.t_sales_delivery_order x, sales.t_sales_delivery_order_detail y
                where x.no_do = y.no_do group by x.no_so, y.kd_barang) f
                on f.no_so = b.no_so and f.kd_barang = b.kd_produk
                where e.no_so = '$no_so'
                and e.no_so = b.no_so
                and b.kd_produk = c.kd_produk
                and c.kd_satuan = d.kd_satuan
                ";

        $query = $this->db->query($sql);
        //print_r($this->db->last_query());
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }
    public function search_produk_bonus_by_so($no_so = "") {
        $sql = "select * from sales.t_sales_order_bonus where no_so ='$no_so'";
        $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }

    public function search_lokasi($kd_lokasi = "", $kd_blok = "",$kd_sub_blok = "",$kd_produk = "") {
        $sql = "select * from inv.t_brg_inventory where kd_lokasi = '$kd_lokasi' and kd_blok = '$kd_blok'
                and kd_sub_blok = '$kd_sub_blok' and kd_produk = '$kd_produk' ";
       $query = $this->db->query($sql);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }

    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nama_kategori2) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select a.kd_kategori2, a.kd_kategori1, b.nama_kategori1, a.nama_kategori2,
					a.kd_kategori1 || a.kd_kategori2 kd_kategori,
					b.nama_kategori1 || ' - ' || a.nama_kategori2 nama_kategori ,
					CASE WHEN a.aktif IS true THEN 'Ya' ELSE 'Tidak' END aktif
					from mst.t_kategori2 a
					join mst.t_kategori1 b on a.kd_kategori1 = b.kd_kategori1
					 " . $sql_search . "
					order by nama_kategori1
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total
			from mst.t_kategori2 a
			join mst.t_kategori1 b on a.kd_kategori1 = b.kd_kategori1
			 " . $sql_search;

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

     public function get_rows_lokasi($kd_produk = '', $search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " WHERE (lower(a.nama_sub_blok) LIKE '%" . strtolower($search) . "%') ";
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

        // print_r($this->db->last_query());exit;

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "SELECT count(*) as total FROM mst.t_produk_lokasi a
					JOIN mst.t_sub_blok b
						ON b.kd_sub_blok = a.kd_sub_blok AND b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi
					JOIN mst.t_blok c
						ON c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi
					JOIN mst.t_lokasi d
						ON d.kd_lokasi = a.kd_lokasi
					WHERE a.kd_produk = '$kd_produk'
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
    public function get_data_print($no_retur = ''){
		$sql = "select 'RETUR PENJUALAN' title,a.*
                        from sales.t_retur_sales a
                        where no_retur = '$no_retur'
                        ";

		$query = $this->db->query($sql);

		if($query->num_rows() == 0) return FALSE;

		$data['header'] = $query->row();

		$this->db->flush_cache();
		$sql_detail = " SELECT 'RETUR PENJUALAN' title, a.*,b.nama_lokasi2 || '-' ||  c.nama_blok2 || '-' || d.nama_sub_blok2 lokasi, e.nama_produk, e.kd_produk_supp, f.nm_satuan
                                FROM sales.t_retur_sales_detail a
                                JOIN mst.t_produk e ON a.kd_produk = e.kd_produk
                                LEFT JOIN mst.t_lokasi b ON a.kd_lokasi = b.kd_lokasi
                                LEFT JOIN mst.t_blok c ON a.kd_blok = c.kd_blok  AND a.kd_lokasi = c.kd_lokasi
                                LEFT JOIN mst.t_sub_blok d ON a.kd_blok = d.kd_blok AND a.kd_lokasi = d.kd_lokasi AND a.kd_sub_blok = d.kd_sub_blok
                                LEFT JOIN mst.t_satuan f ON e.kd_satuan = f.kd_satuan
                                WHERE a.no_retur = '$no_retur'";

		$query_detail = $this->db->query($sql_detail);

		$data['detail'] = $query_detail->result();

		return $data;
	}

    public function get_nama_kategori2($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = "where (lower(a.nama_kategori2) LIKE '%" . strtolower($search) . "%' )";
        }

        $sql1 = "select distinct(a.nama_kategori2)
					from mst.t_kategori2 a
					 " . $sql_search . "
					order by nama_kategori2
					limit " . $length . " offset " . $offset;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_row($id = NULL, $id1 = NULL) {
        $sql = "SELECT a.nama_kategori1,b.*,CASE WHEN b.aktif IS true THEN 1 ELSE 0 END aktif FROM mst.t_kategori1 a, mst.t_kategori2 b WHERE b.kd_kategori1='" . $id1 . "' AND a.kd_kategori1 = b.kd_kategori1 AND b.kd_kategori2 ='" . $id . "'";
        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($table = '', $data = NULL) {
        $result = $this->db->insert($table, $data);
        //print_r($this->db->last_query());
        return $result;
    }

    public function query_update($sql = "") {
        return $this->db->query($sql);
    }
    public function query_insert($sql = "") {
        return $this->db->query($sql);
    }
    public function update_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

    public function delete_row($kd2 = NULL, $kd1 = NULL, $data = NULL) {
        $this->db->where("kd_kategori2", $kd2);
        $this->db->where("kd_kategori1", $kd1);
        return $this->db->update('mst.t_kategori2', $data);
    }

    public function get_kategori1() {
        $sql = "SELECT kd_kategori1, nama_kategori1 FROM mst.t_kategori1 WHERE aktif=true ORDER BY nama_kategori1";
        $query = $this->db->query($sql);
        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

    public function get_all_faktur($search = "", $offset, $length)
    {
        $where = null;
		if ($search != '') {
            $where = " AND ((lower(no_so) LIKE '%" . $search . "%') OR (no_so LIKE '%" . $search . "%'))";
        }
        $sql ="select *,
        case when rp_kembali > 0 then (rp_total_bayar - rp_kembali) else rp_total_bayar end rp_total_bayar,
        case when rp_kembali > 0 then rp_grand_total - rp_retur else rp_total_bayar  - rp_retur end efektif_retur
        from sales.t_sales_order where 1=1 $where LIMIT $length OFFSET $offset";

        $query = $this->db->query($sql);
		//print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$this->db->flush_cache();
                $sql2 ="select count(*) AS total from sales.t_sales_order where 1=1
                        $where";
                $query2 = $this->db->query($sql2);
                $total = 0;
                if ($query2->num_rows() > 0) {
                    $row = $query2->row();
                    $total = $row->total;
                }
		$results = '{success:true,record:' . $total . ',data:'.json_encode($rows).'}';

        return $results;
	}
}
