<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_edit_voucher_model
 *
 * @author miyzan
 */
class account_edit_voucher_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    //put your code here
    public function get_rows_header($search=null, $offset, $length){
    if($search){
        $this->db->where("(tv.kd_voucher like '%$search%' or tv.keterangan like '%$search%' or tj.title like '%$search%')");
    }
    $this->db->select("
                tv.kd_voucher,
                tv.tgl_transaksi,
                tv.kd_transaksi,
                tt.nama_transaksi,
                tv.keterangan,
                tv.kd_cabang,
                tc.nama_cabang,
                tv.type_transaksi,
                tv.diterima_oleh,
                tv.no_giro_cheque,
                tv.kd_jenis_voucher,
                tj.title as jenis_voucher,
                tv.tgl_jttempo,
                tv.approval1,
                tv.approval2,
                tv.approval3,
                tv.auto_posting_voucher"
                , FALSE);
        $this->db->join('acc.t_transaksi tt', 'tt.kd_transaksi=tv.kd_transaksi','left');
        $this->db->join('mst.t_cabang tc', 'tc.kd_cabang=tv.kd_cabang','left');
        $this->db->join('acc.t_jenis_voucher tj', 'tj.kd_jenis_voucher=tv.kd_jenis_voucher'); 
        $this->db->where('tv.aktif',1);
        $this->db->where('tv.status_close is false');
        $query = $this->db->get("acc.t_voucher tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if($search){
            $this->db->where("(tv.kd_voucher like '%$search%' or tv.keterangan like '%$search%' or tj.title like '%$search%')");
        }     
        $this->db->join('acc.t_transaksi tt', 'tt.kd_transaksi=tv.kd_transaksi','left');
        $this->db->join('mst.t_cabang tc', 'tc.kd_cabang=tv.kd_cabang','left');
        $this->db->join('acc.t_jenis_voucher tj', 'tj.kd_jenis_voucher=tv.kd_jenis_voucher');   
        $this->db->where('tv.aktif',1);
        $this->db->where('tv.status_close is null');
        $query = $this->db->get("acc.t_voucher tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_detail($search=''){    
    $this->db->select("
                tv.kd_akun,
                ta.nama,
                tv.dk_akun,
                tv.dk_transaksi,
                tv.debet,
                tv.kredit,
                tv.ref_detail,
                tv.kd_costcenter as costcenter,
                tc.nama_costcenter,
                tv.keterangan_detail"
                , FALSE);
        $this->db->join('acc.t_akun ta', 'ta.kd_akun=tv.kd_akun');        
        $this->db->join('acc.t_costcenter tc', 'tc.kd_costcenter=tv.kd_costcenter','left'); 
        $this->db->where('tv.kd_voucher',$search);
        $query = $this->db->get("acc.t_voucher_detail tv");

        $rows = array();
        $total = 0;
        
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total=$query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_revke($kd_voucher=''){
        $this->db->select("(case when revisike is null then 0 else revisike end)+1 as revisike");
        $this->db->where('kd_voucher',$kd_voucher);
        $query = $this->db->get("acc.t_voucher");
        $rows = array();
        $retval=0;
        if ($query->num_rows() > 0) {
            $rows = $query->row();
            $retval=$rows->revisike;
        }
        return $retval;
    }
    public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}
     public function update_row($dbname, $data, $where) {
        return $this->db->update($dbname, $data,$where);     
     }
     
     public function update_voucher($dbname, $data, $where) {
         $this->db->where('kd_voucher',$where);
        return $this->db->update($dbname, $data);     
     }
     
     public function delete_row($dbname,$where) {
        return $this->db->delete($dbname,$where);     
     }
}

?>
