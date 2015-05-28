<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_neracalajur_model
 *
 * @author faroq
 */
class account_neracalajur_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function getHeader() {
        $sqlselect = "select * from (
SELECT kd_akun, parent_kd_akun, nama,null as groupname, 
(CASE WHEN header_status IS TRUE THEN 1 ELSE 0 END) as header_status, 
concat( 
	(CASE WHEN type_akun='N' THEN '1' else (CASE WHEN type_akun='P' THEN '4' else '5' END) END)
	, '-', 
	(CASE WHEN type_akun='P' THEN 'PENDAPATAN' ELSE (CASE WHEN type_akun='N' THEN 'NERACA' ELSE 'BIAYA / BEBAN' END) END)
) as groupakun 
FROM acc.t_akun WHERE aktif = '1' AND (case when parent_kd_akun='' then null else parent_kd_akun end) is NULL AND header_status is TRUE            
) as ar order by groupakun,kd_akun";
        $query = $this->db->query($sqlselect);
        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        return $rows;
    }

    public function getchild() {
        $sqlselect = "select concat( 
	(CASE WHEN type_akun='N' THEN '1' else (CASE WHEN type_akun='P' THEN '4' else '5' END) END)
	, '-', 
	(CASE WHEN type_akun='P' THEN 'PENDAPATAN' ELSE (CASE WHEN type_akun='N' THEN 'NERACA' ELSE 'BIAYA / BEBAN' END) END)
) as groupakun ,
            n.kd_akun,null as groupname,
            n.parent_kd_akun,
            n.nama, 
            CASE WHEN n.header_status IS TRUE THEN 1 ELSE 0 END header_status            
from acc.t_akun n
WHERE parent_kd_akun IS NOT NULL order by kd_akun";

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }

    public function getchild_detail() {
        $sqlselect = "select kd_akun,CASE WHEN n.labarugi IS TRUE THEN 1 ELSE 0 END labarugi,
            CASE WHEN n.neraca IS TRUE THEN 1 ELSE 0 END neraca					
from acc.t_akun n
WHERE parent_kd_akun IS NOT NULL and header_status IS FALSE order by kd_akun";

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
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
    
    public function getchild_saldoawal($kd_akun, $thbl,$kd_cabang) {
        $sqlselect="";
        if ($kd_cabang){
            $sqlselect = "select a.kd_akun,CASE WHEN b.dk ='D' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldod,
						CASE WHEN b.dk ='K' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldok 
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<$thbl and a.kd_cabang='$kd_cabang' order by thbl desc limit 1 offset 0";
        }else{
            $sqlselect = "select a.kd_akun,CASE WHEN b.dk ='D' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldod,
						CASE WHEN b.dk ='K' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldok 
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and thbl<$thbl order by thbl desc limit 1 offset 0";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }

    public function getchild_saldoakhir($kd_akun, $thbl,$kd_cabang) {
        if ($kd_cabang){
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldok 
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<=$thbl  and a.kd_cabang='$kd_cabang' order by thbl desc limit 1 offset 0";
        }else{
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN 
							CASE WHEN a.saldo >=0 THEN  a.saldo ELSE 0 END
						ELSE CASE WHEN a.saldo <0 THEN  abs(a.saldo) ELSE 0 END END as saldok 
from acc.t_bukubesar_saldo a
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and thbl<=$thbl order by thbl desc limit 1 offset 0";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }

    public function getchild_saldoakhir_neraca($kd_akun, $thbl,$kd_cabang) {
        if ($kd_cabang){
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN a.saldo ELSE 0 END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN a.saldo ELSE 0 END as saldok 
                                                from acc.t_bukubesar_saldo a 
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<=$thbl  and a.kd_cabang='$kd_cabang' order by thbl desc limit 1 offset 0";
        }else{
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN a.saldo ELSE 0 END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN a.saldo ELSE 0 END as saldok 
                                                from acc.t_bukubesar_saldo a 
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and thbl<=$thbl order by thbl desc limit 1 offset 0";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    
    public function getchild_saldoawal_new($kd_akun, $thbl,$kd_cabang) {
        if ($kd_cabang){
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN a.saldo ELSE 0 END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN a.saldo ELSE 0 END as saldok 
                                                from acc.t_bukubesar_saldo a 
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and a.thbl<$thbl  and a.kd_cabang='$kd_cabang' order by thbl desc limit 1 offset 0";
        }else{
            $sqlselect = "select a.kd_akun,CASE WHEN upper(b.dk) ='D' THEN a.saldo ELSE 0 END as saldod,
						CASE WHEN upper(b.dk) ='K' THEN a.saldo ELSE 0 END as saldok 
                                                from acc.t_bukubesar_saldo a 
inner join acc.t_akun b
on a.kd_akun=b.kd_akun
WHERE a.kd_akun= '$kd_akun' and thbl<$thbl order by thbl desc limit 1 offset 0";
        }
        

        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
    
    public function getSaldo($opt, $child, $thbl,$kd_cabang) {
        $sa = array();
        foreach ($child as $v) {
            $rows = array();
            if ($opt == 0) {
                $cbg= array();
                $saldod=0;
                $saldok=0;
                if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);
//                    $rows = $this->getchild_saldoawal($v->kd_akun, $thbl,$kd_cabang);
//                    if (count($rows) > 0) {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => $rows[0]->saldod, "saldok" => $rows[0]->saldok));
//                    } else {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => "0", "saldok" => "0"));
//                    }
                }else{
                    $cbg=$this->get_cabang();
                }
                foreach ($cbg as $vcabang) {
                        $rows = $this->getchild_saldoawal_new($v->kd_akun, $thbl,$vcabang->kd_cabang);
                        if (count($rows) > 0) {
                            $saldod +=$rows[0]->saldod;
                            $saldok +=$rows[0]->saldok;
                        }
                    }
                    array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => $saldod, "saldok" => $saldok));
                
            }
            if ($opt == 1) {
//                $rows = $this->getchild_saldoakhir($v->kd_akun, $thbl,$kd_cabang);
                $cbg= array();
                $saldod=0;
                $saldok=0;
                if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);                  
                }else{
                    $cbg=$this->get_cabang();
                }
                foreach ($cbg as $vcabang) {
                        $rows = $this->getchild_saldoakhir_neraca($v->kd_akun, $thbl,$vcabang->kd_cabang);
                        if (count($rows) > 0) {
                            $saldod +=$rows[0]->saldod;
                            $saldok +=$rows[0]->saldok;
                        }
                    }
                array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => $saldod, "saldok" => $saldok));
                
//                        $rows = $this->getchild_saldoakhir_neraca($v->kd_akun, $thbl,$kd_cabang);
//                    if (count($rows) > 0) {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => $rows[0]->saldod, "saldok" => $rows[0]->saldok));
//                    } else {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "saldod" => "0", "saldok" => "0"));
//                    }
                
                
            }
            if ($opt == 2) {
                $cbg= array();
                $saldod=0;
                $saldok=0;
                if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);                  
                }else{
                    $cbg=$this->get_cabang();
                }
                foreach ($cbg as $vcabang) {
                    if ($v->labarugi == 1) {
                        $rows = $this->getchild_saldoakhir($v->kd_akun, $thbl,$vcabang->kd_cabang);
                        if (count($rows) > 0) {
                            $saldod +=$rows[0]->saldod;
                            $saldok +=$rows[0]->saldok;
                        }                       
                    }
                }
                array_push($sa, array("kd_akun" => $v->kd_akun, "labarugid" => $saldod, "labarugik" => $saldok));
                
//                if($kd_cabang){
//                    if ($v->labarugi == 1) {
//                        $rows = $this->getchild_saldoakhir($v->kd_akun, $thbl,$kd_cabang);
//                    }
//                    if (count($rows) > 0) {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "labarugid" => $rows[0]->saldod, "labarugik" => $rows[0]->saldok));
//                    } else {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "labarugid" => "0", "labarugik" => "0"));
//                    }
//                }
                
            }
            if ($opt == 3) {
                $cbg= array();
                $saldod=0;
                $saldok=0;
                if($kd_cabang){
                    $cbg=$this->get_cabang($kd_cabang);                  
                }else{
                    $cbg=$this->get_cabang();
                }
                foreach ($cbg as $vcabang) {
                    if ($v->neraca == 1) {
                        $rows = $this->getchild_saldoakhir_neraca($v->kd_akun, $thbl,$vcabang->kd_cabang);
                        if (count($rows) > 0) {
                            $saldod +=$rows[0]->saldod;
                            $saldok +=$rows[0]->saldok;
                        }                       
                    }
                }
                array_push($sa, array("kd_akun" => $v->kd_akun, "neracad" => $saldod, "neracak" => $saldok));
                
//                if($kd_cabang){
//                    if ($v->neraca == 1) {
//                        $rows = $this->getchild_saldoakhir_neraca($v->kd_akun, $thbl,$kd_cabang);
//                    }
//                    if (count($rows) > 0) {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "neracad" => $rows[0]->saldod, "neracak" => $rows[0]->saldok));
//                    } else {
//                        array_push($sa, array("kd_akun" => $v->kd_akun, "neracad" => "0", "neracak" => "0"));
//                    }
//                }
                
            }
        }
        return $sa;
    }

    public function getMutasi($thbl,$kd_cabang) {
         
        if($kd_cabang){
            $sqlselect = "select a.kd_akun,case when b.debet IS NULL then 0 ELSE b.debet end as mutasid,case when b.kredit IS NULL then 0 ELSE b.kredit end as mutasik 
from acc.t_akun a
left join (
            select sum(a.debet) as debet,sum(kredit) as kredit,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='$thbl' and b.kd_cabang='$kd_cabang' 
            GROUP BY a.kd_akun 
            ) b
on a.kd_akun=b.kd_akun WHERE a.parent_kd_akun IS NOT NULL and a.header_status IS FALSE order by a.kd_akun";
        }else{
            $sqlselect = "select a.kd_akun,case when b.debet IS NULL then 0 ELSE b.debet end as mutasid,case when b.kredit IS NULL then 0 ELSE b.kredit end as mutasik 
from acc.t_akun a
left join (
            select sum(a.debet) as debet,sum(kredit) as kredit,a.kd_akun from acc.t_jurnal_detail a INNER JOIN acc.t_jurnal b 
            on a.idjurnal=b.idjurnal where to_char(b.tgl_transaksi,'YYYYMM')='$thbl' 
            GROUP BY a.kd_akun 
            ) b
on a.kd_akun=b.kd_akun WHERE a.parent_kd_akun IS NOT NULL and a.header_status IS FALSE order by a.kd_akun";
        }
        
        $query = $this->db->query($sqlselect);

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }


        return $rows;
    }
public function insert_row($db,$data = NULL) {
        return $this->db->insert($db, $data);
		// print_r($this->db->last_query());
    }
    //put your code here
}

?>
