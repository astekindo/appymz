<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_create_invoice_model extends MY_Model {

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
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_po(){
		$this->db->select("no_po");
		$this->db->order_by("no_po", 'asc');
		$this->db->where("konsinyasi", '0');
		$query = $this->db->get("purchase.t_purchase");

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_po_detail($no_po=''){
		$sql = "select c.no_do, a.no_po, d.kd_produk, d.nama_produk, e.qty_terima qty, nm_satuan, b.price_supp_po price_list,
				disk_amt_supp1_po, disk_amt_supp2_po, disk_amt_supp3_po, disk_amt_supp4_po,rp_disk_po, dpp_po, b.rp_total_po,
				b.rp_total_po as adjust,c.kd_supplier, f.nama_supplier
				from purchase.t_purchase a, purchase.t_purchase_detail b, purchase.t_receive_order c, mst.t_produk d,
				purchase.t_dtl_receive_order e, mst.t_supplier f, mst.t_satuan g
				where a.no_po = b.no_po
				and a.no_po = c.no_po
				and b.kd_produk = d.kd_produk
				and c.no_do = e.no_do
				and f.kd_supplier = c.kd_supplier
				and g.kd_satuan = d.kd_satuan
				and a.no_po = '".$no_po."'";
		$query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function get_no_do(){
		$this->db->select("no_do,no_po");

		$query = $this->db->get("purchase.t_receive_order");

		$rows = array();
		if($query->num_rows() > 0) $rows = $query->result();

		$result = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';
		return $result;
	}

	public function search_no_do_by_supplier($kd_supplier = '', $no_do ='', $search='', $kd_peruntukan = ''){
		
                if ($search != "") {
                    // $sql_search  = " AND a.no_do in (".$search.")";
                  $sql_search =  "AND (lower(a.no_do) LIKE '%" . strtolower($search) . "%') ";
                     $this->db->where($sql_search);
                }
                if ($kd_peruntukan != ""){
                    $sql_search = "AND a.kd_peruntukan = '$kd_peruntukan'";
                }
                 $sql=" select distinct a.* from purchase.t_receive_order a
                        join purchase.t_dtl_receive_order b on a.no_do = b.no_do and b.qty_terima > b.qty_invoice
                        join purchase.t_purchase c on c.no_po = b.no_po
                        where a.kd_supplier = '$kd_supplier' and c.konsinyasi = '0' 
                        and a.no_do like 'RO%' $sql_search order by a.no_do asc";
                 $query = $this->db->query($sql);
		$rows = array();

		if($query->num_rows() > 0){
			$rows = $query->result();
		}


		//print_r($this->db->last_query());
		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	public function search_no_do_by_supplier_no_do($kd_supplier = '', $no_do = ''){
		if ($no_do != ''){
			$no_do_in_1 = '';
			$no_do = explode(';',$no_do);
			foreach ($no_do as $no_do_in){
				$no_do_in_1 = $no_do_in_1."'".$no_do_in."',";
			}
			$no_do = substr($no_do_in_1,0,-1);
		}
		$sql = "select distinct on(a.no_do,b.no_po,b.kd_produk) a.no_do, a.tanggal, a.tanggal_terima, b.no_po, b.kd_produk, d.kd_produk, e.nm_satuan, (b.qty_terima-b.qty_retur) qty_terima,
				c.disk_persen_supp1_po, c.disk_persen_supp2_po, c.disk_persen_supp3_po, c.disk_persen_supp4_po,
				c.disk_amt_supp1_po, c.disk_amt_supp2_po, c.disk_amt_supp3_po, c.disk_amt_supp4_po, c.disk_amt_supp5_po,
				c.price_supp_po as pricelist, nama_produk, c.dpp_po,
				c.rp_disk_po
				from purchase.t_receive_order a, purchase.t_dtl_receive_order b,
				purchase.t_purchase_detail c, mst.t_produk d, mst.t_satuan e
				where a.kd_supplier = '$kd_supplier'
				and a.no_do in (".$no_do.")
                                and b.qty_terima > b.qty_invoice
				and a.no_do = b.no_do
				and b.no_po = c.no_po
				and b.kd_produk = c.kd_produk
				and c.kd_produk = d.kd_produk
				and d.kd_satuan = e.kd_satuan";

		$query = $this->db->query($sql);
                //print_r($this->db->last_query());
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		// $results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $rows;
	}

	public function search_produk_by_supplier($kd_supplier = ''){
		$sql = "SELECT
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan, coalesce(sum(c.qty_oh), 0,sum(c.qty_oh)) jml_stok
  				FROM
					mst.t_supp_per_brg s
				JOIN
					mst.t_produk a ON s.kd_produk = a.kd_produk
  				JOIN
					mst.t_satuan b ON b.kd_satuan = a.kd_satuan
  				LEFT JOIN
					inv.t_brg_inventory c ON c.kd_produk = a.kd_produk
				WHERE
					a.aktif is true
					AND
					s.kd_supplier = '$kd_supplier'
				GROUP BY
					a.nama_produk, a.kd_produk, a.min_stok, a.max_stok, b.nm_satuan
				ORDER BY
					a.nama_produk ASC";

		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

		$results = '{success:true,record:'.$query->num_rows().',data:'.json_encode($rows).'}';

        return $results;
	}

	// update hpp inventory | set | rp_nilai_stok = rp_nilai_stok + adjust |
	// cogs = (rp_nilai_stok + adjust)/qty_stok
	// rp_het_cogs = (((rp_nilai_stok + adjust)/qty_stok)+ongkos+((rp_nilai_stok + adjust)/qty_stok)*(pctmargin/100))*1.1

	public function update_row_hpp($adjust = '', $kd_produk = '',$kd_peruntukan = '',$no_invoice = ''){
		$sql = "UPDATE inv.t_hpp_inventory SET type = '5',
				no_ref = '$no_invoice',
				qty_in = 0,
				qty_out = 0,
				rp_nilai_stok = rp_nilai_stok + ".$adjust.",
				rp_cogs = (rp_nilai_stok + ".$adjust.")/qty_stok,
				rp_het = (((rp_nilai_stok + ".$adjust.")/qty_stok)+rp_angkut+((rp_nilai_stok + ".$adjust.")/qty_stok)*(pct_margin/100))*1.1
				WHERE kd_produk = '$kd_produk' AND kd_peruntukan = '0'";
		return $this->db->query($sql);
	}

	public function insert_row_histo($no_bukti){
		$sql = "insert into inv.t_hpp_inventory_histo
				select a.*, '$no_bukti' from inv.t_hpp_inventory a";
		return $this->db->query($sql);
	}

	public function get_data_print($no_inv){

		$sql = "select 'FORM INVOICE' title, a.*, b.nama_supplier,b.pkp from purchase.t_invoice a, mst.t_supplier b
			where a.no_invoice = '". $no_inv.
			"' and a.kd_supplier = b.kd_supplier";

		$query = $this->db->query($sql);

		if($query->num_rows() == 0) return FALSE;

		$data['header'] = $query->row();

		$this->db->flush_cache();

		$sql = "SELECT a.*,b.*,nama_produk, nm_satuan, e.tanggal,e.tanggal_terima ,f.pkp
				FROM purchase.t_invoice a
				JOIN purchase.t_invoice_detail b
					ON a.no_invoice = b.no_invoice
				JOIN mst.t_produk c
					ON b.kd_produk = c.kd_produk
				JOIN mst.t_satuan d
					ON c.kd_satuan = d.kd_satuan
				JOIN purchase.t_receive_order e
					ON b.no_do = e.no_do
				JOIN mst.t_supplier f
					ON a.kd_supplier = f.kd_supplier
				WHERE a.no_invoice = '$no_inv'";

		$query_detail = $this->db->query($sql);
		$data['detail'] = $query_detail->result();
		return $data;
	}

	public function get_cogs_on_hpp($kd_produk){
		$this->db->select("rp_cogs,rp_het");
		$this->db->where("kd_produk", $kd_produk);
		$query = $this->db->get("inv.t_hpp_inventory");

		$rows = array();
		if($query->num_rows() > 0){
			$row = $query->row();
		}

        return $row;
	}

	public function update_row_produk($kd_produk,$data){
		$this->db->where("kd_produk",$kd_produk);
		$this->db->update("mst.t_produk",$data);
	}

        public function update_receive_order($no_ro, $data){
		$this->db->where("no_do",$no_ro);
		$this->db->update("purchase.t_receive_order",$data);
	}
        public function query_update($sql = ""){
		return $this->db->query($sql);
	}

}
