<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_app_voucher_model
 *
 * @author faroq
 */
class account_app_voucher_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function get_rows($search = "", $offset, $length,$kd_cabang="") {
        $this->db->select("CASE WHEN tv.aktif=1 THEN 0 ELSE 1 END as approval, 
  tv.kd_voucher, 
  tv.tgl_transaksi, 
  tv.kd_transaksi, 
  tt.nama_transaksi, 
  tv.keterangan, 
  tv.referensi,tv.no_giro_cheque", FALSE);
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 1);
        $this->db->order_by("tv.kd_voucher", "asc");
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 1);
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_rows_approval1($search = "", $offset, $length,$kd_cabang="") {
        $this->db->select("CASE WHEN tv.aktif=1 THEN 0 ELSE 1 END as approval, 
  tv.kd_voucher, 
  tv.tgl_transaksi, 
  tv.kd_transaksi, 
  tt.nama_transaksi, 
  tv.keterangan, 
  tv.referensi,tv.no_giro_cheque,
  tv.kd_jenis_voucher,
            tv.approval1,
            CASE WHEN tv.aktif=2 THEN 1 ELSE 0 END as status_apv1,
            tv.approval_by,
            tv.approval_date,
            tv.approval2,
            tv.status_apv2,
            tv.approval2_by,
            tv.approval2_date,
            tv.approval3,
            tv.status_apv3,
            tv.approval3_by,
            tv.approval3_date,
            tv.kd_cabang,
	    rj.count_reject
  ", FALSE);
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->join("(select kd_voucher,count(*) count_reject from acc.t_histo_voucher GROUP BY kd_voucher) rj","rj.kd_voucher= tv.kd_voucher","left");
        $this->db->where("tv.aktif", 1);
        $this->db->where("tv.approval1", 1);
        $this->db->where("tv.status_close is false");
        $this->db->order_by("tv.kd_voucher", "asc");
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 1);
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_rows_approval2($search = "", $offset, $length,$kd_cabang="") {
        $this->db->select("CASE WHEN tv.aktif=2 THEN 0 ELSE 1 END as approval, 
            tv.kd_voucher, 
            tv.tgl_transaksi, 
            tv.kd_transaksi, 
            tt.nama_transaksi, 
            tv.keterangan, 
            tv.referensi,
            tv.no_giro_cheque,
            tv.kd_jenis_voucher,
            tv.approval1,
            CASE WHEN tv.aktif=2 THEN 1 ELSE 0 END as status_apv1,
            tv.approval_by,
            tv.approval_date,
            tv.approval2,
            tv.status_apv2,
            tv.approval2_by,
            tv.approval2_date,
            tv.approval3,
            tv.status_apv3,
            tv.approval3_by,
            tv.approval3_date,
            tv.kd_cabang,
	    rj.count_reject
            ", FALSE);
       
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->join("(select kd_voucher,count(*) count_reject from acc.t_histo_voucher GROUP BY kd_voucher) rj","rj.kd_voucher= tv.kd_voucher","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.approval2", 1);
        $this->db->where("tv.status_apv2 is null", NULL);
         if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->order_by("tv.kd_voucher", "asc");
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.approval2", 1);
        $this->db->where("tv.status_apv2 is null", NULL);
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_approval4($search = "", $offset, $length,$kd_cabang="") {
        $this->db->select("
            CASE WHEN tv.aktif=2 THEN 0 ELSE 1 END as approval, 
            tv.kd_voucher, 
            tv.tgl_transaksi, 
            tv.kd_transaksi, 
            tt.nama_transaksi, 
            tv.keterangan, 
            tv.referensi,
            tv.no_giro_cheque,
            tv.kd_jenis_voucher,
            tv.approval1,
            CASE WHEN tv.aktif=2 THEN 1 ELSE 0 END as status_apv1,
            tv.approval_by,
            tv.approval_date,
            tv.approval2,
            tv.status_apv2,
            tv.approval2_by,
            tv.approval2_date,
            tv.approval3,
            tv.status_apv3,
            tv.approval3_by,
            tv.approval3_date,
            tv.kd_cabang
            ", FALSE);
       
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.approval3", 1);
        $this->db->where("(CASE when tv.approval2=1 THEN tv.status_apv2 ELSE 1 END) =1");
        $this->db->where("tv.status_apv3 is null", NULL);
         if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->order_by("tv.kd_voucher", "asc");
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.approval3", 1);
        $this->db->where("(CASE when tv.approval2=1 THEN tv.status_apv2 ELSE 1 END) =1");
        $this->db->where("tv.status_apv3 is null", NULL);
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    
    public function get_rows_approval3($search = "", $offset, $length,$kd_cabang="") {
        $this->db->select("CASE WHEN tv.aktif=2 THEN 0 ELSE 1 END as approval, 
  tv.kd_voucher, 
  tv.tgl_transaksi, 
  tv.kd_transaksi, 
  tt.nama_transaksi, 
  tv.keterangan, 
  tv.referensi,tv.no_giro_cheque,,
	case when tv.approval3=1 THEN tv.approval3_date else 
		case when tv.approval2=1 THEN tv.approval2_date else tv.approval_date end 
	END
	as lastapproval_date", FALSE);
       
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.status_apv3 is not null", NULL);
        $this->db->where("tv.status_posting is null", NULL);
         if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->order_by("tv.kd_voucher", "asc");
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_voucher) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        if ($kd_cabang != "") {
           $this->db->where("tv.kd_cabang", $kd_cabang);             
        }
        $this->db->join("acc.t_transaksi tt","tv.kd_transaksi=tt.kd_transaksi","left");
        $this->db->where("tv.aktif", 2);
        $this->db->where("tv.status_posting is null", NULL);
//        $this->db->where("tv.approval2", 1);
        $this->db->where("tv.status_apv3 is not null", NULL);
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_approval_level($kdvoucher = ""){
        $this->db->select("approval1,approval2,approval3,auto_posting_voucher");
        $this->db->where("tv.kd_voucher", $kdvoucher);
        $query = $this->db->get("acc.t_voucher tv");
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }
        return $rows;
    }
    public function get_rows_akun($search = "") {
        $this->db->select("tv.kd_voucher, 
  tv.kd_akun, 
  ta.nama, 
  tv.dk_akun, 
  tv.dk_transaksi, 
  tc.nama_costcenter as costcenter,tv.keterangan_detail,tv.ref_detail,
  tv.debet,tv.kredit", FALSE);        
        $this->db->join("acc.t_akun ta","tv.kd_akun=ta.kd_akun");
        $this->db->join("acc.t_costcenter tc","tv.kd_costcenter=tc.kd_costcenter","left");
        $this->db->where("tv.kd_voucher", $search);
        $this->db->order_by("tv.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_voucher_detail tv");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_akun_loop($search = "") {
        $this->db->select("tv.kd_voucher, 
  tv.kd_akun, 
  ta.nama, 
  tv.dk_akun, 
  trim(tv.dk_transaksi) as dk_transaksi, 
  tv.debet,tv.kredit,tv.kd_costcenter,tv.keterangan_detail,tv.ref_detail", FALSE);        
        $this->db->join("acc.t_akun ta","tv.kd_akun=ta.kd_akun");
        $this->db->where("tv.kd_voucher", $search);
        $this->db->order_by("tv.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_voucher_detail tv");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

        

        return $rows;
    }
    
    public function get_saldo_bb($kdakun = NULL,$thbl=NULL,$kd_cabang=NULL) {
        $this->db->select("saldo");               
        $this->db->where("thbl <=", $thbl);        
        $this->db->where("kd_akun", $kdakun);      
        $this->db->where("kd_cabang", $kd_cabang);
        $this->db->order_by("thbl", "desc");
        $query = $this->db->get("acc.t_bukubesar_saldo");

        $rows = array();
        $retval=0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval=$rows[0]->saldo;
            
        }

        

        return $retval;
    }
    
    public function get_saldo_bb_after($kdakun = NULL,$thbl=NULL,$kd_cabang=NULL) {
        $this->db->select("thbl,saldo");               
        $this->db->where("thbl >", $thbl);        
        $this->db->where("kd_akun", $kdakun);      
        $this->db->where("kd_cabang", $kd_cabang);
        $this->db->order_by("thbl", "asc");
        $query = $this->db->get("acc.t_bukubesar_saldo");

        $rows = array();
        $retval=0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
//            $retval=$rows[0]->saldo;
            
        }

        

        return $rows;
    }
    
    public function get_saldo_bb_exists($kdakun = NULL,$thbl=NULL,$kd_cabang=NULL) {
        $this->db->select("saldo");               
        $this->db->where("thbl", $thbl);        
        $this->db->where("kd_akun", $kdakun);      
        $this->db->where("kd_cabang", $kd_cabang);
        $query = $this->db->get("acc.t_bukubesar_saldo");

//        $rows = array();
        $retval=FALSE;
        if ($query->num_rows() > 0) {
//            $rows = $query->result();
            $retval=TRUE;            
        }

        

        return $retval;
    }
    
    public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}

   public function update_row($datawhere = NULL, $data = NULL) {
        $this->db->where('kd_voucher',$datawhere);
        return $this->db->update('acc.t_voucher', $data);
		// print_r($this->db->last_query());
    }
    
    public function update_row_bb($dbname, $data = NULL, $where) {
//        $this->db->where('thbl',$id2);
//        $this->db->where('kd_akun',$id1);        
//        $this->db->where('kd_cabang',$id3);
        return $this->db->update($dbname, $data,$where);
		// print_r($this->db->last_query());
    }
}

?>
