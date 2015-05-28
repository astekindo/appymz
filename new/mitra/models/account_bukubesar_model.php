<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_bukubesar_model
 *
 * @author faroq
 */
class account_bukubesar_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_saldo_bb($kdakun = NULL, $thbl = NULL) {
        $this->db->select("saldo", FALSE);
        $this->db->where("thbl", $thbl);
        $this->db->where("kd_akun", $kdakun);
        $query = $this->db->get("acc.t_bukubesar_saldo");

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->saldo;
        }



        return $retval;
    }

    public function get_saldo_bb_min($kdakun = NULL, $thbl = NULL) {
        $this->db->select("thbl,sum(saldo) saldo", FALSE);
        $this->db->where("thbl <", $thbl);
        $this->db->where("kd_akun", $kdakun);
        $this->db->group_by("thbl");
        $this->db->order_by("thbl", "desc");
        $query = $this->db->get("acc.t_bukubesar_saldo", 1, 0);

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->saldo;
        }



        return $retval;
    }

    public function get_saldo_bb_min_cabang($kdakun = NULL, $thbl = NULL, $kd_cabang = NULL) {
        $this->db->select("saldo", FALSE);
        $this->db->where("thbl <", $thbl);
        $this->db->where("kd_akun", $kdakun);
        $this->db->where("kd_cabang", $kd_cabang);
        $this->db->order_by("thbl", "desc");
        $query = $this->db->get("acc.t_bukubesar_saldo", 1, 0);

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->saldo;
        }



        return $retval;
    }

    public function get_trx_bb_min($kdakun = NULL, $tglawal = NULL) {
        $thbl = date('Ym', strtotime($tglawal));
        $sqltrx = "select sum(b.jumlah) as jumlah from acc.t_jurnal a inner join acc.t_jurnal_detail b 
            on  a.idjurnal=b.idjurnal 
            where to_char(a.tgl_transaksi, 'YYYYMM')='$thbl' 
            and a.tgl_transaksi < '$tglawal' 
            and b.kd_akun='$kdakun'";
        $query = $this->db->query($sqltrx);

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->jumlah;
        }



        return $retval;
    }

    public function get_trx_bb_min_cabang($kdakun = NULL, $tglawal = NULL, $kd_cabang = NULL) {
        $thbl = date('Ym', strtotime($tglawal));
        $sqltrx = "select sum(b.jumlah) as jumlah from acc.t_jurnal a inner join acc.t_jurnal_detail b 
            on  a.idjurnal=b.idjurnal 
            where to_char(a.tgl_transaksi, 'YYYYMM')='$thbl' 
            and a.tgl_transaksi < '$tglawal' 
            and b.kd_akun='$kdakun'
            and a.kd_cabang='$kd_cabang'";
        $query = $this->db->query($sqltrx);

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->jumlah;
        }



        return $retval;
    }

    public function get_trx_bb($kdakun = NULL, $tglawal = NULL, $tglakhir = null, $kd_cabang = NULL) {
        if ($kd_cabang) {
            $sqltrx = "select sum(b.jumlah) as jumlah from acc.t_jurnal a inner join acc.t_jurnal_detail b 
            on  a.idjurnal=b.idjurnal 
            where a.tgl_transaksi between '$tglawal'  and '$tglakhir'
            and b.kd_akun='$kdakun' and a.kd_cabang='$kd_cabang'";
        } else {
            $sqltrx = "select sum(b.jumlah) as jumlah from acc.t_jurnal a inner join acc.t_jurnal_detail b 
            on  a.idjurnal=b.idjurnal 
            where a.tgl_transaksi between '$tglawal'  and '$tglakhir'
            and b.kd_akun='$kdakun'";
        }
        $query = $this->db->query($sqltrx);

        $rows = array();
        $retval = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $retval = $rows[count($rows) - 1]->jumlah;
        }



        return $retval;
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
    
    public function get_saldoawal($akun = null, $tglawal = null, $kd_cabang = NULL) {
        $thbl = date('Ym', strtotime($tglawal));
        $saldoawal = 0;
        $saldoawal_plus = 0;
        if ($kd_cabang) {
            $saldoawal = $this->get_saldo_bb_min_cabang($akun, $thbl, $kd_cabang);
            if(!$saldoawal){
                $saldoawal=0;
            }
            $saldoawal_plus = $this->get_trx_bb_min_cabang($akun, $tglawal, $kd_cabang);
             if(!$saldoawal_plus){
                $saldoawal_plus=0;
            }
        } else {
            $cbg= array();
                $saldon=0;                
                $cbg=$this->get_cabang();
                foreach ($cbg as $v) {
                    $saldon= $this->get_saldo_bb_min_cabang($akun, $thbl, $v->kd_cabang);
                    if($saldon){
                        $saldoawal +=$saldon;
                    }
                }
//            ==========================================
//            $saldoawal = $this->get_saldo_bb_min($akun, $thbl);
//            if(!$saldoawal){
//                $saldoawal=0;
//            }
            $saldoawal_plus = $this->get_trx_bb_min($akun, $tglawal);
            if(!$saldoawal_plus){
                $saldoawal_plus=0;
            }
        }

//        echo "saldoawalnya:".$saldoawal."\n";
//        echo "saldoawalnya1:".$saldoawal_plus."\n";
        $saldoawal = $saldoawal + $saldoawal_plus;
        return $saldoawal;
    }

    public function get_view($akun = null, $tglawal = null, $tglakhir = null, $kd_cabang = null) {
        $saldoawal = 0;

        $saldoawal = $this->get_saldoawal($akun, $tglawal);
        
        $qtglawal = date('Y-m-01', strtotime($tglawal));
        $sql_kdcabang="";
        if ($kd_cabang){
            $sql_kdcabang="and acc.t_jurnal.kd_cabang='$kd_cabang'";
        }
        
        
        $sqlsaldoawal = "SELECT 0 as nomor,
            DATE('$tglawal') as tgl_transaksi,
            'Saldo Awal' keterangan,'' as keterangan_detail,'-' costcenter,
            acc.t_akun.kd_akun,
            acc.t_akun.nama,
            case when acc.t_akun.dk='D' and $saldoawal<0 THEN 'K' ELSE case when acc.t_akun.dk='K' and $saldoawal>0 THEN 'K' ELSE acc.t_akun.dk END END as dk_transaksi,
            $saldoawal jumlah
            FROM acc.t_akun where kd_akun='$akun'";
        
            $sqltransaksi = " union all 
            SELECT 1 as nomor,
            acc.t_jurnal.tgl_transaksi,
            acc.t_jurnal.keterangan,acc.t_jurnal_detail.keterangan_detail as keterangan_detail,acc.t_costcenter.nama_costcenter as costcenter,
            acc.t_jurnal_detail.kd_akun,
            acc.t_akun.nama,
            upper(acc.t_jurnal_detail.dk_transaksi) dk_transaksi,
            acc.t_jurnal_detail.jumlah
            FROM
            acc.t_jurnal
            INNER JOIN acc.t_jurnal_detail ON acc.t_jurnal.idjurnal = acc.t_jurnal_detail.idjurnal
            INNER JOIN acc.t_akun ON acc.t_akun.kd_akun = acc.t_jurnal_detail.kd_akun
            LEFT JOIN acc.t_costcenter ON acc.t_jurnal_detail.kd_costcenter = acc.t_costcenter.kd_costcenter
            where acc.t_jurnal.tgl_transaksi between '$tglawal' and '$tglakhir' 
            and acc.t_jurnal_detail.kd_akun='$akun' $sql_kdcabang";
         

        $jmltrx = 0;
        $jmltrx = $this->get_trx_bb($akun, $tglawal, $tglakhir, $kd_cabang);
        $saldoakhir = 0;
        $saldoakhir = $saldoawal + $jmltrx;
        $sqlsaldoakhir = " union all 
            SELECT 2 as nomor,
            DATE('$tglakhir') as tgl_transaksi,
            'Saldo Akhir' keterangan,'' as keterangan_detail,'-' costcenter,
            acc.t_akun.kd_akun,
            acc.t_akun.nama,
            case when acc.t_akun.dk='D' and $saldoakhir<0 THEN 'K' ELSE case when acc.t_akun.dk='K' and $saldoakhir>0 THEN 'K' ELSE 'D' END END as dk_transaksi,
            $saldoakhir jumlah
            FROM acc.t_akun where kd_akun='$akun'";
        ;
        $sql = $sqlsaldoawal . $sqltransaksi . $sqlsaldoakhir;
        $sql='select * from ('.$sql.') a order by nomor';
//       $sql= $sqlsaldoakhir;
        $query = $this->db->query($sql);
        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
    
    
    public function get_view2($akun = null, $tglawal = null, $tglakhir = null, $kd_cabang = null) {
        $saldoawal = 0;

        $saldoawal = $this->get_saldoawal($akun, $tglawal);
        
        $qtglawal = date('Y-m-01', strtotime($tglawal));
        $sql_kdcabang="";
        if ($kd_cabang){
            $sql_kdcabang="and acc.t_jurnal.kd_cabang='$kd_cabang'";
        }
        
        
        $sqlsaldoawal = "SELECT 0 as nomor,
            DATE('$tglawal') as tgl_transaksi,
                NULL as idjurnal,NULL as novoucher,
            'Saldo Awal' keterangan,'' as keterangan_detail,NULL costcenter,NULL cabang,
            acc.t_akun.kd_akun,
            acc.t_akun.nama,
            acc.t_akun.dk,
            case when acc.t_akun.dk='D' and $saldoawal<0 THEN 'K' ELSE case when acc.t_akun.dk='K' and $saldoawal>0 THEN 'K' ELSE acc.t_akun.dk END END as dk_transaksi,
            $saldoawal jumlah
            FROM acc.t_akun where kd_akun='$akun'";
        
            $sqltransaksi = " union all 
            SELECT 1 as nomor,
            acc.t_jurnal.tgl_transaksi,
            acc.t_jurnal.idjurnal,acc.t_jurnal.idpost as novoucher,
            acc.t_jurnal.keterangan,acc.t_jurnal_detail.keterangan_detail as keterangan_detail,acc.t_costcenter.nama_costcenter as costcenter,mst.t_cabang.nama_cabang as cabang,
            acc.t_jurnal_detail.kd_akun,
            acc.t_akun.nama,
            acc.t_akun.dk,
            upper(acc.t_jurnal_detail.dk_transaksi) dk_transaksi,
            acc.t_jurnal_detail.jumlah
            FROM
            acc.t_jurnal
            INNER JOIN acc.t_jurnal_detail ON acc.t_jurnal.idjurnal = acc.t_jurnal_detail.idjurnal
            INNER JOIN acc.t_akun ON acc.t_akun.kd_akun = acc.t_jurnal_detail.kd_akun
            INNER JOIN mst.t_cabang ON acc.t_jurnal.kd_cabang = mst.t_cabang.kd_cabang
            LEFT JOIN acc.t_costcenter ON acc.t_jurnal_detail.kd_costcenter = acc.t_costcenter.kd_costcenter            
            where acc.t_jurnal.tgl_transaksi between '$tglawal' and '$tglakhir' 
            and acc.t_jurnal_detail.kd_akun='$akun' $sql_kdcabang";
         

        $jmltrx = 0;
        $jmltrx = $this->get_trx_bb($akun, $tglawal, $tglakhir, $kd_cabang);
        $saldoakhir = 0;
        $saldoakhir = $saldoawal + $jmltrx;
        $sqlsaldoakhir = " union all 
            SELECT 2 as nomor,
            DATE('$tglakhir') as tgl_transaksi,
            NULL as idjurnal,NULL as novoucher,
            'Saldo Akhir' keterangan,'' as keterangan_detail,NULL costcenter,NULL cabang,
            acc.t_akun.kd_akun,
            acc.t_akun.nama,
            acc.t_akun.dk,
            case when acc.t_akun.dk='D' and $saldoakhir<0 THEN 'K' ELSE case when acc.t_akun.dk='K' and $saldoakhir>0 THEN 'K' ELSE 'D' END END as dk_transaksi,
            $saldoakhir jumlah
            FROM acc.t_akun where kd_akun='$akun'";
        ;
        $sql = $sqlsaldoawal . $sqltransaksi . $sqlsaldoakhir;
        $sql='select * from ('.$sql.') a order by kd_akun, nomor,tgl_transaksi';
//       $sql= $sqlsaldoakhir;
        $query = $this->db->query($sql);
        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

//        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
    //put your code here
    
    public function get_akun_child() {
        $this->db->select("kd_akun,nama");
        $this->db->where("aktif","1");
        $this->db->where("header_status is false");  
        $this->db->order_by("kd_akun","asc");  
        $query = $this->db->get("acc.t_akun");

        $rows = array();
        
        if ($query->num_rows() > 0) {            
            $rows = $query->result();
        }
        
//        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $rows;
    }
}

?>
