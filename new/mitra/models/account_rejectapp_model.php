<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_rejectapp_model
 *
 * @author miyzan
 */
class account_rejectapp_model  extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function get_rows_reject($kdvoucher='',$kdcabang=''){
        $this->db->select(
                "approval_reject,           
                reason,    
                approval_by,
                reject_by, 
                approval_date,
                reject_date,
                reject_level"
                );
        $this->db->where('kd_voucher',$kdvoucher);
        $this->db->where('kd_cabang',$kdcabang);
        $this->db->order_by('reject_date', "desc");
        $this->db->order_by('reject_level', "asc");
        
        $query = $this->db->get('acc.t_histo_voucher');            

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total=$query->num_rows();
        }        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    public function get_rows_all($kdcabang=null,$tglawal = NULL, $tglakhir = NULL,$search = NULL, $offset, $length,$user=NULL) {
        $sqlwheretgl = "";
        $sqlwherecabang="";
        $sqlwhere = "";        
        $sqlsearch = "";
        //---------------------------------- descript where
        if ($tglawal && $tglakhir) {
            $sqlwheretgl = "acc.t_voucher.tgl_transaksi between '$tglawal' and '$tglakhir'";
        }
        if($kdcabang){
            $sqlwherecabang="acc.t_voucher.kd_cabang='$kdcabang'";
        }
        if ($search) {
            $search = strtolower($search);
            $sqlsearch = "((lower(acc.t_voucher.kd_voucher) like '%$search%') or (lower(acc.t_voucher.referensi) like '%$search%') or (lower(acc.t_voucher.keterangan) like '%$search%') or (lower(acc.t_jenis_voucher.title) like '%$search%') or (lower(acc.t_transaksi.nama_transaksi) like '%$search%'))";
        }
        //---------------------------------- setting where
        
        $sqlwhere = "WHERE acc.t_voucher.status_posting is null and ( acc.t_voucher.approval_by='$user' or acc.t_voucher.approval2_by='$user' or acc.t_voucher.approval3_by='$user') ";
        if (strlen($sqlwheretgl) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere =  $sqlwhere . " and " .$sqlwheretgl;
            }else{
                $sqlwhere = "where " . $sqlwheretgl;
            }            
        }
        if(strlen($sqlwherecabang)>0){
            if (strlen($sqlwhere) > 0) {
               $sqlwhere =  $sqlwhere . " and " .$sqlwherecabang;
            }else{
                $sqlwhere = "where " . $sqlwherecabang;
            }
        }
        
        if (strlen($sqlsearch) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere = $sqlwhere . " and " . $sqlsearch;
            } else {
                $sqlwhere = "where " . $sqlsearch;
            }
        }
        //---------------------------------- implement query
        $sqlbase ="SELECT
                acc.t_voucher.tgl_transaksi,
                acc.t_voucher.kd_voucher,
                acc.t_voucher.kd_transaksi,
                acc.t_transaksi.nama_transaksi,
                acc.t_voucher.kd_jenis_voucher,
                acc.t_jenis_voucher.title,
                acc.t_voucher.referensi,
                acc.t_voucher.keterangan,
                acc.t_voucher.approval1,
                CASE WHEN acc.t_voucher.aktif=2 THEN 1 ELSE 0 END as status_apv1,
                acc.t_voucher.approval_by,
                acc.t_voucher.approval_date,
                acc.t_voucher.approval2,
                acc.t_voucher.status_apv2,
                acc.t_voucher.approval2_by,
                acc.t_voucher.approval2_date,
                acc.t_voucher.approval3,
                acc.t_voucher.status_apv3,
                acc.t_voucher.approval3_by,
                acc.t_voucher.approval3_date,
                acc.t_voucher.auto_posting_voucher,                
                acc.t_voucher.status_posting,
                acc.t_voucher.posting_by,
                acc.t_voucher.posting_date,
		acc.t_jurnal.idjurnal,
                acc.t_voucher.diterima_oleh,
                acc.t_voucher.no_giro_cheque,
                acc.t_voucher.tgl_jttempo,
                acc.t_voucher.kd_cabang,
                mst.t_cabang.nama_cabang
                FROM
                acc.t_voucher
                LEFT JOIN acc.t_transaksi ON acc.t_voucher.kd_transaksi = acc.t_transaksi.kd_transaksi
                INNER JOIN acc.t_jenis_voucher ON acc.t_voucher.kd_jenis_voucher = acc.t_jenis_voucher.kd_jenis_voucher
		LEFT JOIN acc.t_jurnal ON acc.t_voucher.kd_voucher = acc.t_jurnal.idpost and acc.t_voucher.kd_cabang=acc.t_jurnal.kd_cabang 
                INNER JOIN mst.t_cabang on acc.t_voucher.kd_cabang=mst.t_cabang.kd_cabang "
                . $sqlwhere . " order by acc.t_voucher.tgl_transaksi,acc.t_voucher.kd_voucher ";
        $query = $this->db->query($sqlbase . "LIMIT $length OFFSET $offset");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $query = $this->db->query("select count(*) as total from ($sqlbase) as ts");
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total=$row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    public function get_rows_params($kdcabang=null,$tglawal = NULL, $tglakhir = NULL,$sapproval=NULL,$search = NULL, $offset, $length,$user=NULL) {
        $sqlwheretgl = "";
        $sqlwherecabang="";
        $sqlwhere = "";        
        $sqlsearch = "";
        //---------------------------------- descript where
        if ($tglawal && $tglakhir) {
            $sqlwheretgl = "acc.t_voucher.tgl_transaksi between '$tglawal' and '$tglakhir'";
        }
        if($kdcabang){
            $sqlwherecabang="acc.t_voucher.kd_cabang='$kdcabang'";
        }
        if ($search) {
            $search = strtolower($search);
            $sqlsearch = "((lower(acc.t_voucher.kd_voucher) like '%$search%') or (lower(acc.t_voucher.referensi) like '%$search%') or (lower(acc.t_voucher.keterangan) like '%$search%') or (lower(acc.t_jenis_voucher.title) like '%$search%') or (lower(acc.t_transaksi.nama_transaksi) like '%$search%'))";
        }
        $sqlapproval="";
        if($sapproval==1){
            $sqlapproval="(case when t_voucher.approval3=0 then 0 else status_apv3 end =0 or status_apv3 is null)  
                and (case when t_voucher.approval2=0 then 0 else status_apv2 end =0 or status_apv2 is null)
                and t_voucher.aktif=2 
                and t_voucher.approval1=1 
                and approval_by='$user' ";
            
        }
        if($sapproval==2){
            $sqlapproval="(case when t_voucher.approval3=0 then 0 else status_apv3 end =0 or status_apv3 is null)                
                and t_voucher.status_apv2=1 
                and t_voucher.approval2=1 
                and approval2_by='$user' ";
            
        }
        if($sapproval==3){
            $sqlapproval="                 
                t_voucher.status_apv3=1 
                and t_voucher.approval3=1 
                and approval3_by='$user' ";
            
        }
        //---------------------------------- setting where
        
        $sqlwhere = "WHERE acc.t_voucher.status_posting is null and ".$sqlapproval;
        
            
        if (strlen($sqlwheretgl) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere =  $sqlwhere . " and " .$sqlwheretgl;
            }else{
                $sqlwhere = "where " . $sqlwheretgl;
            }            
        }
        if(strlen($sqlwherecabang)>0){
            if (strlen($sqlwhere) > 0) {
               $sqlwhere =  $sqlwhere . " and " .$sqlwherecabang;
            }else{
                $sqlwhere = "where " . $sqlwherecabang;
            }
        }
        
        if (strlen($sqlsearch) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere = $sqlwhere . " and " . $sqlsearch;
            } else {
                $sqlwhere = "where " . $sqlsearch;
            }
        }
        //---------------------------------- implement query
        $sqlbase ="SELECT
                acc.t_voucher.tgl_transaksi,
                acc.t_voucher.kd_voucher,
                acc.t_voucher.kd_transaksi,
                acc.t_transaksi.nama_transaksi,
                acc.t_voucher.kd_jenis_voucher,
                acc.t_jenis_voucher.title,
                acc.t_voucher.referensi,
                acc.t_voucher.keterangan,
                acc.t_voucher.approval1,
                CASE WHEN acc.t_voucher.aktif=2 THEN 1 ELSE 0 END as status_apv1,
                acc.t_voucher.approval_by,
                acc.t_voucher.approval_date,
                acc.t_voucher.approval2,
                acc.t_voucher.status_apv2,
                acc.t_voucher.approval2_by,
                acc.t_voucher.approval2_date,
                acc.t_voucher.approval3,
                acc.t_voucher.status_apv3,
                acc.t_voucher.approval3_by,
                acc.t_voucher.approval3_date,
                acc.t_voucher.auto_posting_voucher,                
                acc.t_voucher.status_posting,
                acc.t_voucher.posting_by,
                acc.t_voucher.posting_date,
		acc.t_jurnal.idjurnal,
                acc.t_voucher.diterima_oleh,
                acc.t_voucher.no_giro_cheque,
                acc.t_voucher.tgl_jttempo,
                acc.t_voucher.kd_cabang,
                mst.t_cabang.nama_cabang
                FROM
                acc.t_voucher
                LEFT JOIN acc.t_transaksi ON acc.t_voucher.kd_transaksi = acc.t_transaksi.kd_transaksi
                INNER JOIN acc.t_jenis_voucher ON acc.t_voucher.kd_jenis_voucher = acc.t_jenis_voucher.kd_jenis_voucher
		LEFT JOIN acc.t_jurnal ON acc.t_voucher.kd_voucher = acc.t_jurnal.idpost and acc.t_voucher.kd_cabang=acc.t_jurnal.kd_cabang 
                INNER JOIN mst.t_cabang on acc.t_voucher.kd_cabang=mst.t_cabang.kd_cabang "
                . $sqlwhere . " order by acc.t_voucher.tgl_transaksi,acc.t_voucher.kd_voucher ";
        $query = $this->db->query($sqlbase . "LIMIT $length OFFSET $offset");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $query = $this->db->query("select count(*) as total from ($sqlbase) as ts");
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total=$row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
     public function insert_row($dbname='',$data = NULL){
		return $this->db->insert($dbname, $data);
	}
     public function update_row($dbname, $data = NULL, $where) {
        return $this->db->update($dbname, $data,$where);     
     }
     
     public function delete_row($dbname,$where) {
        return $this->db->delete($dbname,$where);     
     }
}

?>
