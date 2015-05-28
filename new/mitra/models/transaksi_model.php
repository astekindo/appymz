<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_model extends MY_Model {
	
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
        public function get_rows($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN t.aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif", FALSE);
        if ($search != "") {
            $sql_search = "(lower(t.kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(t.nama_transaksi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('t.aktif', '1');
        $this->db->order_by("t.kd_transaksi", "desc");
        $this->db->join('acc.t_jenis_voucher jv', 'jv.kd_jenis_voucher = t.kd_jenis_voucher');
        $query = $this->db->get("acc.t_transaksi t", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(nama_transaksi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("acc.t_transaksi");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
//	public function get_rows($search = "", $offset, $length) {
//        $this->db->select("*,CASE WHEN aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif", FALSE);
//        if ($search != "") {
//            $sql_search = "(lower(kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(nama_transaksi) LIKE '%" . strtolower($search) . "%')";
//            $this->db->where($sql_search, NULL);
//        }
//        $this->db->where('aktif', '1');
//        $this->db->order_by("kd_transaksi", "desc");
//        $query = $this->db->get("acc.t_transaksi", $length, $offset);
//
//        $rows = array();
//        if ($query->num_rows() > 0) {
//            $rows = $query->result();
//        }
//
//        $this->db->flush_cache();
//        $this->db->select('count(*) as total');
//        if ($search != "") {
//            $sql_search = "(lower(kd_transaksi) LIKE '%" . strtolower($search) . "%' or lower(nama_transaksi) LIKE '%" . strtolower($search) . "%')";
//            $this->db->where($sql_search, NULL);
//        }
//        $query = $this->db->get("acc.t_transaksi");
//
//        $total = 0;
//        if ($query->num_rows() > 0) {
//            $row = $query->row();
//            $total = $row->total;
//        }
//        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
//
//        return $results;
//    }
    
    public function get_rows_akun($search = "") {
        
        $this->db->select("td.kd_transaksi, 
                td.kd_akun, 
                ta.nama, 
                upper(ta.dk) as dk_akun, 
                td.dk_transaksi,
                td.kd_costcenter,
                tc.nama_costcenter
                ", FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');
        $this->db->join('acc.t_costcenter tc', 'tc.kd_costcenter=td.kd_costcenter','left');
        $this->db->where("td.kd_transaksi",$search);        
        $this->db->order_by("td.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_transaksi_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_transaksi",$search);      
        $query = $this->db->get("acc.t_transaksi_detail");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_jenisvoucher(){
        $this->db->select("kd_jenis_voucher,title,dk,auto_posting_voucher");        
        $query = $this->db->get("acc.t_jenis_voucher");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }
        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_jenisvoucher_akun($search = "") {
        
        $this->db->select("td.kd_jenis_voucher, 
                td.kd_akun, 
                ta.nama", FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=td.kd_akun');
        $this->db->where("td.kd_jenis_voucher",$search);        
        $this->db->order_by("td.kd_akun", "asc");
        $query = $this->db->get("acc.t_jenis_voucher_detail td");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("kd_jenis_voucher",$search);      
        $query = $this->db->get("acc.t_jenis_voucher_detail");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row($id = NULL){
		$this->db->select("*",FALSE);
        $this->db->where("kd_transaksi", $id);
        $query = $this->db->get('acc.t_transaksi');
        
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
            echo '{"success":true,"data":'.json_encode($row).'}';
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($dbname='',$id = NULL, $data = NULL){
		$this->db->where('kd_transaksi', $id);
		return $this->db->update($dbname, $data);
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row($id = NULL, $data = NULL){		
		$this->db->where('kd_transaksi', $id);
		return $this->db->update('acc.t_transaksi', $data);
	}
        
        public function delete_rowAll($id = NULL){				
                $sql="delete from acc.t_transaksi_detail where kd_transaksi='$id'";
                return $this->db->query($sql);  		
	}
        
         public function cek_exists_rowakun($id = NULL){
            $sql="select * from acc.t_transaksi_detail 
                where kd_transaksi='$id'";            
            $query = $this->db->query($sql);            
            return $query->num_rows();
        }
        
        public function cek_exists_akunvoucher($id = NULL,$kdakun=''){
            $sql="select a.kd_jenis_voucher,b.kd_akun,a.dk 
                from acc.t_jenis_voucher a join 
                acc.t_jenis_voucher_detail b 
                on a.kd_jenis_voucher=b.kd_jenis_voucher
                where a.kd_jenis_voucher='$id' and b.kd_akun='$kdakun'";            
            $query = $this->db->query($sql);            
            $dk='d';
            $tf='false';
            if($query->num_rows()>0){
                $tf='true';
                $row = $query->row();
                $dk=$row->dk;
            }
            return '{success:' . $tf . ',dk:"'.$dk.'"}';
        }
        
        public function cek_exists_costcenter($id = NULL,$kdakun=''){
            $sql="select a.kd_costcenter,a.nama_costcenter,b.kd_akun
                from acc.t_costcenter a inner join 
                acc.t_costcenter_akun b 
                on a.kd_costcenter=b.kd_costcenter
                where a.kd_costcenter='$id' and b.kd_akun='$kdakun'";            
            $query = $this->db->query($sql);            
            $kdcc='';
            $nmcc='';
            $tf='false';
            if($query->num_rows()>0){
                $tf='true';
                $row = $query->row();
                $kdcc=$row->kd_costcenter;
                $nmcc=$row->nama_costcenter;
            }
            return '{success:' . $tf . ',kdcc:"'.$kdcc.'",nmcc:"'.$nmcc.'"}';
        }
	
}
