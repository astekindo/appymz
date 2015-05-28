<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_po_model extends MY_Model {
	
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
	 
	public function get_rows($kdSupplier = "", $tglAwal = "", $tglAkhir = "", $approval_po = "", $close_po = "", $konsinyasi = "",$peruntukan_sup ="",$peruntukan_dist ="", $search = "", $offset, $length){
		$sql_search = "";
		$where = "";
		if($kdSupplier != ""){
			$where .=  " AND b.kd_suplier_po = '$kdSupplier' ";
		}
		if($approval_po != "" && $approval_po != "A" ){
			$where .=  " AND b.approval_po = '$approval_po' ";
		}

		if($close_po != "" && $close_po != "A"){
			$where .=  " AND b.close_po = '$close_po' ";
		}
                if($peruntukan_sup != ""){
			$where .=  " AND b.kd_peruntukan = '$peruntukan_sup' ";
                }
                if($peruntukan_dist != ""){
			$where .=  " AND b.kd_peruntukan = '$peruntukan_dist' ";
                }
        switch($konsinyasi) {
            case '0':
            case '1':
                $where .=  " AND b.konsinyasi = '$konsinyasi' ";
                break;
            case '2':
                $where .=  " AND b.no_po like '" . GET_PB_REQUEST . "%'";
                break;
            case '3':
                $where .=  " AND b.no_po like '" . GET_ASSET_REQUEST . "%'";
                break;
        }

		if($tglAwal != "" && $tglAkhir != ""){
			$where .=  " AND b.tanggal_po between '$tglAwal' AND '$tglAkhir' ";
		}
		if($search != ""){
			$sql_search =  " AND ((lower(b.no_po) LIKE '%" . strtolower($search) . "%') OR (lower(f.no_ro) LIKE '%" . strtolower($search) . "%'))";
		}
		$sql = "select distinct coalesce(f.no_ro, 'NON-PR') no_ro,
					b.no_po,b.tgl_berlaku_po, b.tanggal_po, b.kd_suplier_po kd_supplier, c.nama_supplier,
					case b.konsinyasi when '0' then 'NORMAL' when '1' then 'KONSINYASI' end type_purchase,
					case b.approval_po when '0' then 'Belum Approve' when '1' then 'Approve' when '9' then 'Reject' end status_po,  
					case b.close_po when '0' then 'Open' when '1' then 'Close' else '-' end is_close_po,
					d.no_do, e.tanggal_terima tanggal_do,
                                        CASE WHEN b.kd_peruntukan ='1' THEN 'Distribusi' ELSE 'Supermarket' END peruntukan
					from purchase.t_purchase b
					join purchase.t_purchase_detail f on b.no_po = f.no_po
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_dtl_receive_order d on d.no_po = b.no_po
					left join purchase.t_receive_order e on e.no_do = d.no_do
					where 1=1 $sql_search $where
					order by b.tanggal_po desc limit $length offset $offset";
      //return $sql;
        $query = $this->db->query($sql);

		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
		$sql2 = "select count(*) as total from (select distinct coalesce(f.no_ro, 'NON-PR') no_ro
					from purchase.t_purchase b
					join purchase.t_purchase_detail f on b.no_po = f.no_po
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_dtl_receive_order d on d.no_po = b.no_po
					left join purchase.t_receive_order e on e.no_do = d.no_do
					where 1=1 $sql_search $where) as tabel limit 1";
        
        $query = $this->db->query($sql2);
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
				
		$results = '{"success":true, "record":'.$total.', "data":'.json_encode($rows).'}';
        return $results;
	}

    public function get_data_po($no_po = ""){
		$sql = "select distinct coalesce(f.no_ro, 'NON-PR') no_ro,
					b.no_po,b.tgl_berlaku_po, b.tanggal_po, b.kd_suplier_po kd_supplier, c.nama_supplier,
					case b.konsinyasi when '0' then 'NORMAL' when '1' then 'KONSINYASI' end type_purchase,
					case b.approval_po when '0' then 'Belum Approve' when '1' then 'Approve' when '9' then 'Reject' end status_po,  
					case b.close_po when '0' then 'Open' when '1' then 'Close' else '-' end is_close_po,
					d.no_do, e.tanggal tanggal_do  
					from purchase.t_purchase b
					join purchase.t_purchase_detail f on b.no_po = f.no_po
					left join mst.t_supplier c on b.kd_suplier_po = c.kd_supplier
					left join purchase.t_dtl_receive_order d on d.no_po = b.no_po
					left join purchase.t_receive_order e on e.no_do = d.no_do
					where b.no_po = $no_po";
        $query = $this->db->query($sql);
		
		$rows = array();
        $total = $query->num_rows();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		
		$this->db->flush_cache();
				
		$results = '{"success":true,"record":'.$total.',"data":'.json_encode($rows).'}';
        return $results;
	}
        
        public function get_data_html($no_po = ''){
		$sql = "select b.pkp, CASE WHEN a.konsinyasi='1' THEN 'PURCHASE ORDER FORM (KONSINYASI)' ELSE 'PURCHASE ORDER FORM' END title, a.no_po, a.tanggal_po, a.order_by_po, a.top,b.nama_supplier, a.alamat_kirim_po, a.remark,
					a.rp_jumlah_po, a.rp_diskon_po, a.ppn_percent_po, a.rp_ppn_po, a.rp_total_po, b.nama_supplier, b.fax, b.pic, b.telpon,
					b.npwp, b.email,
					a.tgl_berlaku_po, a.rp_dp, a.kirim_po, a.cetak_ke, a.cetak_ke_non_harga, a.approval_by
					from purchase.t_purchase a, mst.t_supplier b
					where a.no_po = '$no_po'
					and a.kd_suplier_po = b.kd_supplier";

		$query = $this->db->query($sql);
		// print_r($this->db->last_query());exit;
		if($query->num_rows() == 0) return FALSE;
		
		$data['header'] = $query->row();
		
		$this->db->flush_cache();
		$sql_detail = "select a.*, b.kd_produk_lama, b.nama_produk, c.nm_satuan,b.kd_produk_supp
						from purchase.t_purchase_detail a, mst.t_produk b, mst.t_satuan c
						where a.no_po = '$no_po'
						and a.kd_produk = b.kd_produk
						and b.kd_satuan = c.kd_satuan";
		
		$query_detail = $this->db->query($sql_detail);
		$data['detail'] = $query_detail->result();
		
		return $data;
	}
}