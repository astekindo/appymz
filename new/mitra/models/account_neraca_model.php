<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_neraca_model
 *
 * @author miyzan
 */
class account_neraca_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getHeader($dk) {
        $sqlselect = "SELECT kd_akun, parent_kd_akun, nama,null as groupname, 
(CASE WHEN header_status IS TRUE THEN 1 ELSE 0 END) as header_status, 
concat( 
	(CASE WHEN type_akun='N' THEN '1' else '0' END)
	, '-', 
	(CASE WHEN type_akun='N' THEN 'NERACA' ELSE '' END)
) as groupakun 
FROM acc.t_akun WHERE aktif = '1' AND (case when parent_kd_akun='' then null else parent_kd_akun end) is NULL AND header_status is TRUE and type_akun='N' 
and dk='$dk'
order by kd_akun";
        $query = $this->db->query($sqlselect);
        $rows = array();
//        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }
    public function getchild($dk) {
        $sqlselect = "select concat( 
	(CASE WHEN type_akun='N' THEN '1' else '0' END)
	, '-', 
	(CASE WHEN type_akun='N' THEN 'NERACA' ELSE '' END)
) as groupakun ,
            n.kd_akun,null as groupname,
            n.parent_kd_akun,
            n.nama, 
            CASE WHEN n.header_status IS TRUE THEN 1 ELSE 0 END header_status            
from acc.t_akun n
WHERE parent_kd_akun IS NOT NULL and type_akun='N' and dk='$dk' order by kd_akun";

        $query = $this->db->query($sqlselect);

        $rows = array();
//        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    
    public function getchild_detail($dk) {
        $sqlselect = "select kd_akun from acc.t_akun n 
            WHERE parent_kd_akun IS NOT NULL and header_status IS FALSE 
            and type_akun='N' and dk='$dk' order by kd_akun";

        $query = $this->db->query($sqlselect);

        $rows = array();
//        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    public function getchild_saldo($kd_akun, $thbl,$kd_cabang) {
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
    public function get_cabang($kd=NULL) {
        $this->db->select("kd_cabang");                       
        if($kd){
            $this->db->where("kd_cabang",$kd);                       
        }
        $query = $this->db->get("mst.t_cabang");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        

        return $rows;
    }
    public function getSaldo($child, $thbl,$kd_cabang) {
        $sa = array();
        foreach ($child as $v) {
            $rows = array();           
            $saldo=0;
            $cbg= array();
            if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);
            }else{
                $cbg=$this->get_cabang();
            }
            foreach ($cbg as $vcabang) {
                $rows = $this->getchild_saldo($v->kd_akun, $thbl,$vcabang->kd_cabang);
                if (count($rows) > 0) {
                    $saldo += $rows[0]->saldo;
                }
            }
            array_push($sa, array("kd_akun" => $v->kd_akun, "saldo" => $saldo));
//             echo ('test');   
//             $rows = $this->getchild_saldo($v->kd_akun, $thbl,$kd_cabang);
//               
//                if (count($rows) > 0) {
//                    array_push($sa, array("kd_akun" => $v->kd_akun, "saldo" => $rows[0]->saldo));
//                } else {
//                    array_push($sa, array("kd_akun" => $v->kd_akun, "saldo" => "0"));
//                }
            
        }
        return $sa;
    }
    public function get_periode_thbl($thbl,$v) {       
        $dt=substr($thbl, 0, 4).'-'.substr($thbl, 4, 2).'-01';        
        $current_date = date('Y-m-d',strtotime($dt));
        return date('Ym', strtotime($v.' month', strtotime($current_date)));
    }
    public function get_child_rugilaba() {
        $sqlselect="select kd_akun,dk from acc.t_akun where labarugi is true and header_status is false";
        $query = $this->db->query($sqlselect);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    public function get_rugilaba_thberjalan($thbl,$kd_cabang) {
        $child =$this->get_child_rugilaba();
        $saldo_k=0;
        $saldo_d=0;
        $thbl=$this->get_periode_thbl($thbl, "-1");
        if(count($child)>0){
            $rows=array();
            $cbg= array();
            if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);
            }else{
                $cbg=$this->get_cabang();
            }
            foreach ($cbg as $vcabang) {
                foreach ($child as $v) {
                    $rows=$this->getchild_saldo($v->kd_akun, $thbl,$vcabang->kd_cabang);
                    if($v->dk=='D'){
                        $saldo_d +=$rows[0]->saldo;
                    }
                    if($v->dk=='K'){
                        $saldo_k +=$rows[0]->saldo;
                    }
                }
            }
            

        }
        
        
        return $saldo_k-$saldo_d;
    }
    public function get_rugilaba_blberjalan($thbl,$kd_cabang) {
        if ($kd_cabang){
            $sqlselect = "select sum(case when c.dk='K' then a.jumlah else a.jumlah*-1 end) as jumlah from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal 
INNER JOIN acc.t_akun c
on c.kd_akun=a.kd_akun
where c.labarugi is TRUE and to_char(b.tgl_transaksi,'YYYYMM')='$thbl' and b.kd_cabang='$kd_cabang'";
        }else{
            $sqlselect = "select sum(case when c.dk='K' then a.jumlah else a.jumlah*-1 end) as jumlah from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal 
INNER JOIN acc.t_akun c
on c.kd_akun=a.kd_akun
where c.labarugi is TRUE and to_char(b.tgl_transaksi,'YYYYMM')='$thbl'";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
//        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows[0]->jumlah;
    }
    
    public function insert_row($db,$data = NULL) {
        return $this->db->insert($db, $data);
		// print_r($this->db->last_query());
    }
}

?>
