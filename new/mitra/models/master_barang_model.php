<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_barang_model extends MY_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get_rows($search = "", $offset, $length){
		$sql_search = "";
		if($search != ""){
			$sql_search = "AND (lower(f.nama_produk) LIKE '%" . strtolower($search) . "%')";
		}

		$sql1 = "SELECT d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori,
					d.nama_kategori1,c.nama_kategori2,b.nama_kategori3,a.nama_kategori4,f.*,
					CASE WHEN f.kd_peruntukkan = '0' THEN 'Supermarket' ELSE 'Distribusi' END kd_peruntukkan,
					CASE WHEN f.aktif = 1 THEN 'Ya' ELSE 'Tidak' END aktif, e.*
					FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g
					WHERE e.kd_satuan=f.kd_satuan
					AND	g.kd_ukuran = f.kd_ukuran
					AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
					AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
					AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
					AND d.kd_kategori1=c.kd_kategori1
					AND f.aktif = 1 ".$sql_search." ORDER BY kd_produk DESC LIMIT ".$length." OFFSET ".$offset;
        $query = $this->db->query($sql1);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total
					FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g
					WHERE e.kd_satuan=f.kd_satuan
					AND g.kd_ukuran = f.kd_ukuran
					AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
					AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
					AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
					AND d.kd_kategori1=c.kd_kategori1
					AND f.aktif = 1 ".$sql_search;

        $query = $this->db->query($sql2);
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_row($id = NULL){
		$sql = "SELECT d.nama_kategori1,c.nama_kategori2,b.nama_kategori3,a.nama_kategori4,f.*,
					f.kd_produk_lama,f.kd_produk_supp,e.nm_satuan, g.nama_ukuran
					CASE WHEN f.kd_peruntukkan = '1' THEN 1 ELSE 0 END kd_peruntukkan,
					CASE WHEN f.aktif = 1 THEN 1 ELSE 0 END aktif
					FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d, mst.t_satuan e, mst.t_produk f, mst.t_ukuran g
					WHERE f.kd_produk='$id'
					AND g.kd_ukuran = f.kd_ukuran
					AND e.kd_satuan=f.kd_satuan
					AND f.kd_kategori3=a.kd_kategori3 AND f.kd_kategori2=a.kd_kategori2 AND f.kd_kategori1=a.kd_kategori1 AND f.kd_kategori4=a.kd_kategori4
					AND b.kd_kategori3=f.kd_kategori3 AND b.kd_kategori2=f.kd_kategori2 AND b.kd_kategori1=f.kd_kategori1
					AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
					AND d.kd_kategori1=c.kd_kategori1
					AND f.aktif = 1";

        $query = $this->db->query($sql);

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}

	public function insert_row($data = NULL){
            $this->db->insert('mst.t_produk', $data);
           // print_r($this->db->last_query());
            return true;


	}

    public function insert_row_history($kd_produk = NULL, $koreksi_ke = NULL){
        $sql = "INSERT INTO mst.t_produk_history
        SELECT * FROM mst.t_produk
        WHERE kd_produk = '$kd_produk'
        AND koreksi_ke = '$koreksi_ke'";

        return $this->db->query($sql);
    }

    public function insert_lokasi_default($data){
        return $this->db->insert('mst.t_produk_lokasi', $data);
    }

    public function update_row($id = NULL, $data = NULL){
		$this->db->where('kd_produk', $id);
		return $this->db->update('mst.t_produk', $data);
	}

	public function delete_row($id = NULL){
		$data = array(
			'aktif' => 'FALSE'
		);
		$this->db->where('kd_produk', $id);
		return $this->db->update('mst.t_produk', $data);
	}


	public function get_satuan($search){
		$sql_search = '';
		if($search != ""){
			$sql_search = " WHERE (lower(nm_satuan) LIKE '%" . strtolower($search) . "%')";
		}
		$query = $this->db->query("SELECT kd_satuan,nm_satuan
									FROM mst.t_satuan ".$sql_search."
									ORDER BY nm_satuan ASC");
		$rows = $query->result();
			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;

	}
        public function get_satuan_berat($search){
		$sql_search = '';
		if($search != ""){
			$sql_search = " WHERE (lower(nm_satuan) LIKE '%" . strtolower($search) . "%')";
		}
		$query = $this->db->query("SELECT kd_satuan as kd_satuan_berat,nm_satuan as nama_satuan_berat
									FROM mst.t_satuan ".$sql_search."
									ORDER BY nm_satuan ASC");
		$rows = $query->result();
			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;

	}

	public function get_ukuran($search){
		$sql_search = '';
		if($search != ""){
			$sql_search = " WHERE (lower(nama_ukuran) LIKE '%" . strtolower($search) . "%')";
		}
		$query = $this->db->query("SELECT kd_ukuran,nama_ukuran
									FROM mst.t_ukuran ".$sql_search."
									ORDER BY nama_ukuran ASC");
		$rows = $query->result();
			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;

	}

	public function get_produk($search = "", $offset, $length){
		$sql_search = '';
		if($search != ""){
			$sql_search = " WHERE (lower(kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR (lower(nama_produk) LIKE '%" . strtolower($search) . "%') OR kd_produk LIKE '%".$search."%'";
		}
		$query = $this->db->query("SELECT a.kd_produk,a.kd_produk_lama,a.kd_produk_supp,nama_produk,
									(SELECT COALESCE(sum(qty_oh),0,sum(qty_oh)) jml_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk)
									FROM mst.t_produk a
									".$sql_search."
									ORDER BY nama_produk ASC LIMIT ".$length." OFFSET ".$offset);
		$rows = $query->result();

		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total
				FROM mst.t_produk
				".$sql_search;

        $query = $this->db->query($sql2);
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
		return $results;

	}

	public function get_row_kode_produk($kd_produk){
		$sql = <<<EOT
SELECT
    g.*,h.nama_ukuran, f.nm_satuan,z.nm_satuan as nm_satuan_berat, e.*, d.nama_kategori1, c.nama_kategori2, b.nama_kategori3, a.nama_kategori4,
    j.pct_alert,
    (SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_bonus) as nama_produk_bonus,
    (SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_member) as nama_produk_member,
    coalesce(kd_peruntukkan, '0', kd_peruntukkan) as kd_peruntukkan,

    coalesce(e.rp_ongkos_kirim, 0, e.rp_ongkos_kirim) as rp_ongkos_kirim,
    coalesce(e.pct_margin, 0, e.pct_margin) as pct_margin,
    coalesce(e.rp_margin, 0, e.rp_margin) as rp_margin,
    coalesce(e.rp_het_harga_beli, 0, e.rp_het_harga_beli) as rp_het_harga_beli,

    coalesce(i.net_hrg_supplier_sup_inc, 0, i.net_hrg_supplier_sup_inc) as net_hrg_supplier_sup_inc,
    coalesce(i.net_hrg_supplier_dist_inc, 0, i.net_hrg_supplier_dist_inc) as net_hrg_supplier_dist_inc,

    coalesce(g.qty_beli_bonus, 0) as qty_beli_bonus,
    coalesce(g.qty_bonus, 0) as qty_bonus,
    coalesce(g.qty_beli_member, 0) as qty_beli_member,
    coalesce(g.qty_member, 0) as qty_member,
    k.kd_lokasi, k.kd_blok, k.kd_sub_blok,k.flag_lokasi,CASE WHEN k.flag_lokasi ='G' THEN 'GUDANG' ELSE 'SUPERMARKET' END type_lokasi,
    l.nama_lokasi, m.nama_blok, n.nama_sub_blok
FROM mst.t_produk e
    JOIN mst.t_kategori4 a ON e.kd_kategori3=a.kd_kategori3 AND e.kd_kategori2=a.kd_kategori2 AND e.kd_kategori1=a.kd_kategori1 AND e.kd_kategori4=a.kd_kategori4
    JOIN mst.t_kategori3 b ON b.kd_kategori3=e.kd_kategori3 AND b.kd_kategori2=e.kd_kategori2 AND b.kd_kategori1=e.kd_kategori1
    JOIN mst.t_kategori2 c ON c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
    JOIN mst.t_kategori1 d ON d.kd_kategori1=c.kd_kategori1
    LEFT JOIN inv.t_stok_setting j ON e.kd_produk=j.kd_produk
    LEFT JOIN mst.t_satuan f ON f.kd_satuan = e.kd_satuan
    LEFT JOIN mst.t_ukuran h ON h.kd_ukuran = e.kd_ukuran
    LEFT JOIN mst.t_satuan z ON z.kd_satuan = e.kd_satuan_berat
    LEFT JOIN mst.t_diskon_sales g ON g.kd_produk = e.kd_produk
    LEFT JOIN mst.t_supp_per_brg i ON i.kd_produk = e.kd_produk
    LEFT JOIN mst.t_produk_lokasi k ON k.kd_produk = e.kd_produk and k.flag_default = 1
    LEFT JOIN mst.t_lokasi l ON k.kd_lokasi = l.kd_lokasi
    LEFT JOIN mst.t_blok m ON k.kd_lokasi = m.kd_lokasi and k.kd_blok = m.kd_blok
    LEFT JOIN mst.t_sub_blok n ON k.kd_lokasi = n.kd_lokasi and k.kd_blok = n.kd_blok and k.kd_sub_blok = n.kd_sub_blok
WHERE e.kd_produk = '$kd_produk'
EOT;

        $query = $this->db->query($sql);
         //print_r($this->db->last_query());
		// exit;
		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }

        return $row;
	}

	public function get_row_history($kd_produk = "", $koreksi_ke = ""){
		$sql = "SELECT g.*, h.nama_ukuran, f.nm_satuan, e.*, d.nama_kategori1, c.nama_kategori2, b.nama_kategori3, a.nama_kategori4,
				(SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_bonus) as nama_produk_bonus,
				(SELECT nama_produk FROM mst.t_produk h WHERE h.kd_produk = g.kd_produk_member) as nama_produk_member,
				coalesce(kd_peruntukkan, '0', kd_peruntukkan) as kd_peruntukkan,
				coalesce(hrg_beli_satuan, 0, hrg_beli_satuan) as hrg_beli_satuan,
				coalesce(e.rp_ongkos_kirim, 0, e.rp_ongkos_kirim) as rp_ongkos_kirim,
				coalesce(e.pct_margin, 0, e.pct_margin) as pct_margin,
				coalesce(e.rp_margin, 0, e.rp_margin) as rp_margin,
				coalesce(e.rp_het_harga_beli, 0, e.rp_het_harga_beli) as rp_het_harga_beli,
				coalesce(e.rp_jual_supermarket, 0, e.rp_jual_supermarket) as rp_jual_supermarket,
				coalesce(e.rp_jual_distribusi, 0, e.rp_jual_distribusi) as rp_jual_distribusi
				FROM mst.t_produk_history e
				JOIN mst.t_kategori4 a
					ON e.kd_kategori3=a.kd_kategori3 AND e.kd_kategori2=a.kd_kategori2 AND e.kd_kategori1=a.kd_kategori1 AND e.kd_kategori4=a.kd_kategori4
				JOIN mst.t_kategori3 b
					ON b.kd_kategori3=e.kd_kategori3 AND b.kd_kategori2=e.kd_kategori2 AND b.kd_kategori1=e.kd_kategori1
				JOIN mst.t_kategori2 c
					ON c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
				JOIN mst.t_kategori1 d
					ON d.kd_kategori1=c.kd_kategori1
				JOIN mst.t_satuan f
					ON f.kd_satuan = e.kd_satuan
				LEFT JOIN mst.t_ukuran h
					ON h.kd_ukuran = e.kd_ukuran
				AND
				e.kd_produk = '".$kd_produk."' AND e.koreksi_ke = '".$koreksi_ke."'
				LEFT JOIN mst.t_diskon_sales_history g
				ON g.kd_produk = e.kd_produk AND g.koreksi_ke = e.koreksi_ke
				";

        $query = $this->db->query($sql);

		$row = array();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }

        return $row;
	}

	public function get_kategori_by_name($table = "", $field ="", $condField = "", $cond = ""){
		$this->db->select($field);
		$this->db->where($condField,$cond);

		$query =  $this->db->get($table);
		$row = $query->result_array();
		foreach ($row as $value){
			foreach($value as $val){
				$kd_kategori = $val;
			}
		}
		return $kd_kategori;
	}

	public function get_history($kd_produk = ""){
		$this->db->select('*, COALESCE(updated_date, updated_date, created_date) as "tanggal"', FALSE);
		$this->db->where("kd_produk",$kd_produk);
		$this->db->order_by("koreksi_ke","asc");
		$query = $this->db->get("mst.t_produk_history");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}

	public function get_history_cogs($kd_produk = ""){
		$this->db->select(" distinct on (a.no_bukti, a.kd_produk) a.*,case a.type when 1 then 'Receive Order'
			when 2 then 'Retur Beli'
			when 3 then 'Penjualan'
			when 4 then ''
			when 5 then 'Adjust Invoice'
			end jenis_trx, COALESCE(b.rp_ajd_jumlah, 0) rp_ajd_jumlah", FALSE);
		$this->db->from('inv.t_hpp_inventory_histo a');
		$this->db->join('purchase.t_invoice_detail b', 'b.no_invoice = a.no_ref', 'left');
		$this->db->where("a.kd_produk",$kd_produk)->where("a.kd_peruntukan",'0');
		$this->db->order_by("a.no_bukti","desc");
		$query = $this->db->get();

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}

	public function get_history_cogs_dist($kd_produk = ""){
		$this->db->select("*,case type when 1 then 'Receive Order'
			when 2 then 'Retur Beli'
			when 3 then 'Penjualan'
			when 4 then ''
			when 5 then 'Adjust Invoice'
			end jenis_trx ", FALSE);
		$this->db->where("kd_produk",$kd_produk);
		$this->db->where("kd_peruntukan",'1');
		$this->db->order_by("no_bukti","desc");
		$query = $this->db->get("inv.t_hpp_inventory_histo");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}

	public function get_history_inv($kd_produk){
		$this->db->select("a.*, b.*, c.nama_supplier");
		$this->db->join("purchase.t_invoice_detail b", "b.no_invoice = a.no_invoice");
		$this->db->join("mst.t_supplier c", "c.kd_supplier = a.kd_supplier");
		$this->db->where("b.kd_produk",$kd_produk);

        $query = $this->db->get("purchase.t_invoice a");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,data:'.json_encode($rows).'}';
		return $results;
	}


    /*SUDAH DIDUPLIKASI DI barang_paket_model. HAPUS JIKA SUDAH TIDAK DIPERLUKAN DI VIEW */
	public function get_produk_paket($kd_produk = ""){
		$this->db->select("a.*, nama_produk", FALSE);
		$this->db->join("mst.t_produk b","b.kd_produk = a.kd_produk_paket");
		$this->db->where("a.kd_produk",$kd_produk);
		$query = $this->db->get("mst.t_produk_paket a");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		return $rows;
	}

	public function search_produk_paket($kd_produk = "", $search = "", $offset, $length){
		$sql_search = "";
		if($kd_produk != ""){
			$sql_search = " AND a.kd_produk <> '$kd_produk ";
		}if($search != ""){
			$sql_search = " AND (lower(nama_produk) LIKE '%" . strtolower($search) . "%') OR kd_produk LIKE '%".$search."%'";
		}
		$query = $this->db->query("SELECT a.kd_produk,nama_produk,
									(SELECT COALESCE(sum(qty_oh),0,sum(qty_oh)) jml_stok FROM inv.t_brg_inventory b WHERE b.kd_produk = a.kd_produk),
									rp_jual_supermarket, rp_jual_distribusi
									FROM mst.t_produk a
									WHERE 1=1
									".$sql_search."
									ORDER BY nama_produk ASC LIMIT ".$length." OFFSET ".$offset);
		$rows = $query->result();

		$this->db->flush_cache();
		$sql2 = "SELECT count(*) as total
				FROM mst.t_produk
				WHERE 1=1
				".$sql_search;

        $query = $this->db->query($sql2);
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}

		$results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
		return $results;

	}

	public function select_paket($where = ""){
		$this->db->where($where);

		$query = $this->db->get("mst.t_produk_paket");
		$result = FALSE;

		if($query->num_rows > 0){
			$result = TRUE;
		}

		return $result;
	}

	public function select_brg_inv($kd_produk = ""){
		$this->db->where("kd_produk",$kd_produk);

		$query = $this->db->get("inv.t_brg_inventory");
		$result = FALSE;

		if($query->num_rows > 0){
			$result = TRUE;
		}

		return $result;
	}
	public function select_qty_oh($kd_produk = "", $jum_paket = "", $qty = ""){

		$qty_input = $jum_paket*$qty;
		$this->db->where("qty_oh >=",$qty_input);
		$this->db->where("kd_produk",$kd_produk);

		$query = $this->db->get("inv.t_brg_inventory");
		$result = FALSE;

		if($query->num_rows > 0){
			$result = TRUE;
		}
		// print_r($this->db->last_query());
		// echo $query->num_rows;

		return $result;
	}

	public function insert_paket($data = NULL){
		return $this->db->insert("mst.t_produk_paket",$data);
	}

	public function insert_trx_inv($data = NULL){
		return $this->db->insert("inv.t_trx_inventory",$data);
	}

	public function insert_brg_inv($data = NULL){
		return $this->db->insert("inv.t_brg_inventory",$data);
	}

	public function query_update($sql = ""){
		return $this->db->query($sql);
	}

	public function update_brg_inv($where = '', $data = NULL){
		$this->db->where("kd_produk",$where);
		return $this->db->update("inv.t_brg_inventory",$data);
	}

	public function update_paket($where = '', $data = NULL){
		$this->db->where($where);
		return $this->db->update("mst.t_produk_paket",$data);
		// print_r($this->db->last_query());
	}

        public function get_ukuran_produk(){
		$this->db->where("aktif", '1');
		$this->db->order_by("nama_ukuran", 'asc');
		$query = $this->db->get("mst.t_ukuran");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

        public function get_satuan_produk(){
		$this->db->where("aktif", 'true');
		$this->db->order_by("kd_satuan", 'asc');
		$query = $this->db->get("mst.t_satuan");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_kategori1($cmd = '', $kd_kategori1 = NULL){
		if($cmd == 'get'){
			$sql = "SELECT kd_kategori1, nama_kategori1
				FROM mst.t_kategori1 WHERE kd_kategori1 = '$kd_kategori1'
				ORDER BY nama_kategori1 ASC";

			$query = $this->db->query($sql);

			if ($query->num_rows() != 0) {
				$row = $query->row();

				echo '{"success":true,"data":'.json_encode($row).'}';
			}
		}else{
			$sql= "SELECT kd_kategori1, nama_kategori1 FROM mst.t_kategori1 WHERE aktif=true
				ORDER BY nama_kategori1 ASC";
			$query = $this->db->query($sql);
			// $query[] = array('kd_kategori1'=> '','nama_kategori' => '');
			$rows = $query->result_array();
			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;
		}
	}

	public function get_kategori2($cmd = '', $kd_kategori1 = NULL, $kd_kategori2 = NULL){
		if($cmd == 'get'){
			$sql = "SELECT a.nama_kategori1,b.*,CASE WHEN b.aktif IS true THEN 1 ELSE 0 END aktif
					FROM mst.t_kategori1 a, mst.t_kategori2 b
					WHERE b.kd_kategori1='".$kd_kategori1."' AND a.kd_kategori1 = b.kd_kategori1 AND b.kd_kategori2 ='".$kd_kategori2."'
					ORDER BY nama_kategori2 ASC";
			$query = $this->db->query($sql);

			if ($query->num_rows() != 0) {
				$row = $query->row();

				echo '{"success":true,"data":'.json_encode($row).'}';
			}
		}else{
			$sql= "SELECT kd_kategori2, nama_kategori2 FROM mst.t_kategori2 WHERE kd_kategori1='$kd_kategori1' AND aktif=true
					ORDER BY nama_kategori2 ASC";
			$query = $this->db->query($sql);

			$rows = $query->result();
			$results = '{success:true,data:'.json_encode($rows).'}';

			return $results;
		}
	}
	public function get_kategori3($cmd = '', $kd_kategori1 = NULL, $kd_kategori2 = NULL, $kd_kategori3 = NULL){
		if($cmd == 'get'){
			$sql = "select  a.kd_kategori3, a.nama_kategori3, b.kd_kategori2, b.nama_kategori2, c.nama_kategori1, c.kd_kategori1,
					c.kd_kategori1 || b.kd_kategori2 || a.kd_kategori3 kd_kategori,
					c.nama_kategori1 || ' - ' || b.nama_kategori2 || ' - ' || a.nama_kategori3 nama_kategori ,
					CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif
					from mst.t_kategori3 a,mst.t_kategori2 b,mst.t_kategori1 c
					where a.kd_kategori1 ='$kd_kategori1'
					AND a.kd_kategori2 ='$kd_kategori2'
					AND a.kd_kategori3 ='$kd_kategori3'
					and a.aktif = true
					and a.kd_kategori2 = b.kd_kategori2 and a.kd_kategori1 = c.kd_kategori1
					and b.kd_kategori1 = c.kd_kategori1
					ORDER BY nama_kategori3 ASC";

			$query = $this->db->query($sql);
			if ($query->num_rows() != 0) {
				$row = $query->row();

				echo '{"success":true,"data":'.json_encode($row).'}';
			}
		}else{
			$sql = "SELECT a.kd_kategori3,a.nama_kategori3
									FROM mst.t_kategori3 a, mst.t_kategori2 b, mst.t_kategori1 c
									WHERE a.kd_kategori1=b.kd_kategori1  AND a.kd_kategori2=b.kd_kategori2
									AND a.kd_kategori1=c.kd_kategori1 AND b.kd_kategori1=c.kd_kategori1
									AND a.kd_kategori1='$kd_kategori1' AND a.kd_kategori2='$kd_kategori2' AND a.aktif = true
									ORDER BY a.nama_kategori3 ASC";
			$query = $this->db->query($sql);

			$rows = $query->result();
			$results = '{success:true,data:'.json_encode($rows).'}';

			return $results;
		}
	}

	public function get_kategori4($cmd = '', $kd_kategori1 = NULL, $kd_kategori2 = NULL, $kd_kategori3 = NULL, $kd_kategori4 = NULL){
		if($cmd == 'get'){
			$sql = "select d.kd_kategori1 || c.kd_kategori2 || b.kd_kategori3 || a.kd_kategori4 kd_kategori,
					d.nama_kategori1 || ' - ' || c.nama_kategori2 || ' - ' || b.nama_kategori3 || ' - ' || a.nama_kategori4 nama_kategori ,
					a.kd_kategori4, a.nama_kategori4, b.kd_kategori3, b.nama_kategori3, c.nama_kategori2,
					c.kd_kategori2, d.nama_kategori1, d.kd_kategori1,
					CASE WHEN a.aktif IS true THEN 1 ELSE 0 END aktif
					from mst.t_kategori4 a, mst.t_kategori3 b, mst.t_kategori2 c , mst.t_kategori1 d
					where
					a.kd_kategori1 = '$kd_kategori1' and a.kd_kategori2 = '$kd_kategori2' and a.kd_kategori3 = '$kd_kategori3' AND a.kd_kategori4 = '$kd_kategori4'
					AND a.kd_kategori3 = b.kd_kategori3 and a.kd_kategori2 = c.kd_kategori2 and a.kd_kategori1 = d.kd_kategori1
					and b.kd_kategori2 = c.kd_kategori2  and b.kd_kategori1 = d.kd_kategori1
					and c.kd_kategori1 = d.kd_kategori1
					ORDER BY nama_kategori4 ASC
					";
			// print_r($this->db->last_query());
			$query = $this->db->query($sql);

			if ($query->num_rows() != 0) {
				$row = $query->row();

				echo '{"success":true,"data":'.json_encode($row).'}';
			}
		}else{
			$query = $this->db->query("SELECT a.kd_kategori4,a.nama_kategori4
									FROM mst.t_kategori4 a,mst.t_kategori3 b, mst.t_kategori2 c, mst.t_kategori1 d
									WHERE a.kd_kategori1='$kd_kategori1' AND a.kd_kategori2='$kd_kategori2' AND a.kd_kategori3='$kd_kategori3'
									AND b.kd_kategori3=a.kd_kategori3 AND b.kd_kategori2=a.kd_kategori2 AND b.kd_kategori1=a.kd_kategori1
									AND c.kd_kategori2=b.kd_kategori2 AND c.kd_kategori1=b.kd_kategori1
									AND d.kd_kategori1=c.kd_kategori1
									AND a.aktif = true
									ORDER BY a.nama_kategori4 ASC");
			$rows = $query->result();

			$results = '{success:true,data:'.json_encode($rows).'}';
			return $results;
		}
	}

	public function get_parameter_margin($kd_kategori1 = NULL, $kd_kategori2 = NULL, $kd_kategori3 = NULL, $kd_kategori4 = NULL){
		$this->db->where('kd_kategori1',$kd_kategori1);
		$this->db->where('kd_kategori2',$kd_kategori2);
		$this->db->where('kd_kategori3',$kd_kategori3);
		$this->db->where('kd_kategori4',$kd_kategori4);
		$this->db->where('kd_parameter',KD_PARAMETER_MARGIN);

		$query = $this->db->get('mst.t_parameter_margin');

		if ($query->num_rows() != 0) {
			$row = $query->row();

			echo '{"success":true,"data":'.json_encode($row).'}';
		}

	}
	public function get_waktu_top($kd_produk = ''){
		$this->db->select("waktu_top");
		$this->db->join("mst.t_supp_per_brg b","a.kd_produk = b.kd_produk");
		$this->db->where("a.kd_produk",$kd_produk);
		$query = $this->db->get("mst.t_produk a");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

        return $rows;
	}
       public function get_barang_per_supplier($kd_produk = ""){
		$sql = "select * from mst.t_supp_per_brg where kd_produk ='$kd_produk' and aktif = true";
                $query = $this->db->query($sql);
//                $this->db->where("kd_produk",$kd_produk);
//                $this->db->where("aktif",true);
//		$query = $this->db->get("mst.t_supp_per_brg",false);
		 //print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result_array();
		}
		$result = $rows;

		return $result;
	}

    public function insert_barang_per_supplier($data = NULL){
		return $this->db->insert('mst.t_supp_per_brg',$data);
	}

    public function insert_data_diskon_sales($data = NULL){
		return $this->db->insert('mst.t_diskon_sales',$data);
	}

}
