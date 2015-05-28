<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_rugilaba_model
 *
 * @author faroq
 */
class account_rugilaba_model extends MY_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getheader(){
//        SELECT
//acc.t_akun.kd_akun
//FROM
//acc.t_akun where labarugi is true
//WHERE parent_kd_akun is NULL and header_status is TRUE
//order by kd_akun
        $sqlselect="SELECT kd_akun, parent_kd_akun,dk,type_akun, nama,null as groupname, (CASE WHEN header_status IS TRUE THEN 1 ELSE 0 END) as header_status, concat((CASE WHEN type_akun='P' THEN '4' else '5' END), '-', (CASE WHEN type_akun='P' THEN 'PENDAPATAN' ELSE 'BIAYA / BEBAN' END)) as groupakun FROM acc.t_akun WHERE labarugi is true AND (case when parent_kd_akun='' then null else parent_kd_akun end) is NULL AND header_status is TRUE            
            ORDER BY type_akun desc";
//        $this->db->select("kd_akun, parent_kd_akun, nama, (CASE WHEN header_status IS TRUE THEN 1 ELSE 0 END) as header_status, concat((CASE WHEN type_akun='P' THEN '4' else '5' END),'-',(CASE WHEN type_akun='P' THEN 'PENDAPATAN' ELSE 'BIAYA / BEBAN' END)) as groupakun");
//        $this->db->where('labarugi is true');
//        $this->db->where('parent_kd_akun is NULL');
//        $this->db->where('header_status is TRUE');
//        $this->db->order_by('type_akun','desc');
//        $query=$this->db->get('acc.t_akun');
        $query=$this->db->query($sqlselect);
        $rows=array();
        $total=0;
        if ($query->num_rows() > 0){
            $rows=$query->result();
        }
        
        
        return $rows;
        
    }
    public function get_periode_thbl($thbl,$v) {       
        $dt=substr($thbl, 0, 4).'-'.substr($thbl, 4, 2).'-01';        
        $current_date = date('Y-m-d',strtotime($dt));
        return date('Ym', strtotime($v.' month', strtotime($current_date)));
    }
//    case when n.dk='D'  then 
//	case WHEN n.type_akun='P' then r.saldo*-1 Else r.saldo End	
//else 
//	case WHEN n.type_akun='P' then r.saldo Else r.saldo*-1 End		
//end saldo,
    public function getchild_bln_berjalan($thbl=NULL,$kd_cabang=NULL){
        if($kd_cabang){
            $sqlselect="select 
            concat(case WHEN n.type_akun='P' then '4' else '5' end,'-',case WHEN n.type_akun='P' then 'PENDAPATAN' ELSE 'BIAYA / BEBAN' END) as groupakun,
            n.kd_akun,null as groupname,
            n.parent_kd_akun,n.nama,             
            n.dk,n.type_akun,q.jumlah2 ,
            o.jumlah,r.saldo,
            CASE WHEN n.header_status IS TRUE THEN 1 ELSE 0 END header_status
            from acc.t_akun n
            left join (
            select sum(a.jumlah) as jumlah,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='$thbl' and kd_cabang='$kd_cabang' 
            GROUP BY a.kd_akun 
            ) o 
            on n.kd_akun=o.kd_akun
            left join (
            select sum(a.jumlah) as jumlah2,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='".$this->get_periode_thbl($thbl,'-1')."' and kd_cabang='$kd_cabang' 
            GROUP BY a.kd_akun 
            ) q 
            on n.kd_akun=q.kd_akun
            left join (
            select saldo as saldo,kd_akun from acc.t_bukubesar_saldo 
            where thbl='$thbl' and kd_cabang='$kd_cabang'             
            ) r 
            on n.kd_akun=r.kd_akun
            WHERE n.labarugi is TRUE and parent_kd_akun IS NOT NULL order by kd_akun";
        }else{
        $sqlselect="select 
            concat(case WHEN n.type_akun='P' then '4' else '5' end,'-',case WHEN n.type_akun='P' then 'PENDAPATAN' ELSE 'BIAYA / BEBAN' END) as groupakun,
            n.kd_akun,null as groupname,
            n.parent_kd_akun,n.nama,             
            n.dk,n.type_akun,q.jumlah2  ,
            o.jumlah,r.saldo,
            CASE WHEN n.header_status IS TRUE THEN 1 ELSE 0 END header_status
            from acc.t_akun n
            left join (
            select sum(a.jumlah) as jumlah,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='$thbl' 
            GROUP BY a.kd_akun 
            ) o 
            on n.kd_akun=o.kd_akun
            left join (
            select sum(a.jumlah) as jumlah2,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='".$this->get_periode_thbl($thbl,'-1')."' 
            GROUP BY a.kd_akun 
            ) q 
            on n.kd_akun=q.kd_akun
            left join (
            select saldo,kd_akun from acc.t_bukubesar_saldo 
            where thbl='$thbl'
            ) r 
            on n.kd_akun=r.kd_akun
            WHERE n.labarugi is TRUE and parent_kd_akun IS NOT NULL order by kd_akun";
        }
        $query=$this->db->query($sqlselect);
        
        $rows=array();
        $total=0;
        if ($query->num_rows() > 0){
            $rows=$query->result();
        }
        
        
        return $rows;
    }


    public function getchild(){
       $sqlselect="select kd_akun,parent_kd_akun,nama,CASE WHEN type_akun='3' THEN 'PENDAPATAN' ELSE 'BIAYA_BEBAN' END type_akun
             from acc.t_akun where labarugi is true and parent_kd_akun is not NULL order by kd_akun";
//        $this->db->select($sqlselect);
//        $this->db->where('labarugi is true');
//        $this->db->where('parent_kd_akun is not NULL');
////        $this->db->where('header_status is TRUE');
//        $this->db->order_by('kd_akun');
        $query=$this->db->query($sqlselect);
        
        $rows=array();
        $total=0;
        if ($query->num_rows() > 0){
            $rows=$query->result();
        }
        
        
        return $rows;
        
    }
    public function get_cabang() {
        $this->db->select("kd_cabang");                       
        $query = $this->db->get("mst.t_cabang");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        

        return $rows;
    }
    
    public function getchild_saldo_value($kd_akun, $thbl,$kd_cabang) {
        if ($kd_cabang){
            $sqlselect = "select a.kd_akun, a.saldo  
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<=$thbl  and a.kd_cabang='$kd_cabang' order by thbl desc limit 1 offset 0";
        }else{
            $sqlselect = "select a.kd_akun, a.saldo  
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<=$thbl order by thbl desc limit 1 offset 0";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
//        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    
    public function getchild_saldo($child,$thbl,$kd_cabang){
//     $this->rl_model->   
//        $thbln=$this->get_periode_thbl($thbl,'-1');
        foreach ($child as $v) {
            $saldo=0;
            $rows=array();
            if($v->header_status==0){
                if($kd_cabang){
                    $rows=$this->getchild_saldo_value($v->kd_akun, $thbl, $kd_cabang);
                    if(count($rows)>0){
                        $saldo=$rows[0]->saldo;
                    }
                }else{
                    $cbg=$this->get_cabang();
                    $saldo=0;
                    foreach ($cbg as $vcabang) {
                        $rows=$this->getchild_saldo_value($v->kd_akun, $thbl, $vcabang->kd_cabang);
                        if(count($rows)>0){
                            $saldo +=$rows[0]->saldo;
                        }
                    }
                }
                
                
                $v->saldo=$saldo;
            }
        }
         
//        return $child;
    }
    
    public function insert_row($db,$data = NULL) {
        return $this->db->insert($db, $data);
		// print_r($this->db->last_query());
    }
     
    //put your code here
}

?>
