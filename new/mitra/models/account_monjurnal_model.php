<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_monjurnal_model
 *
 * @author faroq
 */
class account_monjurnal_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function get_akun() {
        $this->db->select("kd_akun,nama", FALSE);
        $this->db->where('header_status is FALSE');
        $this->db->where("aktif", '1');
        $query = $this->db->get("acc.t_akun");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_view($akun = null, $tglawal = null, $tglakhir = null, $kd_cabang=NULL, $dk=NULL, $offset, $length) {
        $sqlwheretgl = "";
        $sqlwhereakun = "";
        $sqlwheredk="";
        if ($akun) {
            $sqlwhereakun = " and t_jurnal_detail.kd_akun = '$akun'";
        }
        if($dk){
            if($dk=='dk'){
                $sqlwheredk="";
            }
            if($dk=='d'){
                $sqlwheredk = " and t_jurnal_detail.debet > 0";
            }
            if($dk=='k'){
                $sqlwheredk = " and t_jurnal_detail.kredit > 0";
            }
        }
        //if ($akun) {
            $sqlwheretgl = " and t_jurnal.tgl_transaksi between '$tglawal' and '$tglakhir'";
       // }
        if($kd_cabang){
            $sqlwherecabang = " and t_jurnal.kd_cabang='$kd_cabang'";
        }

        $sqlbase = "SELECT 
  t_jurnal.idjurnal, 
  t_jurnal.tgl_transaksi, 
  t_jurnal.kd_transaksi, 
  t_transaksi.nama_transaksi, 
  t_jurnal.referensi, 
  t_jurnal.keterangan, 
  t_jurnal_detail.kd_akun,
  t_akun.nama,
  t_jurnal_detail.dk_akun, 
  t_jurnal_detail.dk_transaksi, 
  t_jurnal_detail.faktor, 
  t_jurnal_detail.jumlah, 
  t_jurnal_detail.debet, 
  t_jurnal_detail.kredit, 
  t_jurnal_detail.kd_costcenter,
  t_costcenter.nama_costcenter,
  t_jurnal_detail.keterangan_detail, 
  t_jurnal_detail.ref_detail, 
  t_jurnal.kd_cabang,
  mst.t_cabang.nama_cabang,
  t_jurnal.created_by, 
  t_jurnal.created_date,t_jurnal.typepost,t_jurnal.idpost
FROM 
  acc.t_jurnal 
  inner join acc.t_transaksi on t_jurnal.kd_transaksi = t_transaksi.kd_transaksi 
  inner join acc.t_jurnal_detail on t_jurnal.idjurnal = t_jurnal_detail.idjurnal 
  inner join mst.t_cabang on mst.t_cabang.kd_cabang=t_jurnal.kd_cabang 
  inner join acc.t_akun on t_jurnal_detail.kd_akun=t_akun.kd_akun 
  left join acc.t_costcenter on t_costcenter.kd_costcenter=t_jurnal_detail.kd_costcenter   
WHERE replace(t_jurnal.kd_transaksi,'',null) is not null  
  ".$sqlwhereakun.$sqlwheretgl.$sqlwherecabang.$sqlwheredk.
"union all ".
"SELECT 
  t_jurnal.idjurnal, 
  t_jurnal.tgl_transaksi, 
  t_jurnal.kd_transaksi, 
  NULL as nama_transaksi, 
  t_jurnal.referensi, 
  t_jurnal.keterangan, 
  t_jurnal_detail.kd_akun,
  t_akun.nama,
  t_jurnal_detail.dk_akun, 
  t_jurnal_detail.dk_transaksi, 
  t_jurnal_detail.faktor, 
  t_jurnal_detail.jumlah, 
  t_jurnal_detail.debet, 
  t_jurnal_detail.kredit, 
  t_jurnal_detail.kd_costcenter,
  t_costcenter.nama_costcenter,
  t_jurnal_detail.keterangan_detail, 
  t_jurnal_detail.ref_detail, 
  t_jurnal.kd_cabang,
  mst.t_cabang.nama_cabang,
  t_jurnal.created_by, 
  t_jurnal.created_date,t_jurnal.typepost,t_jurnal.idpost
FROM 
  acc.t_jurnal
  inner join acc.t_jurnal_detail on t_jurnal.idjurnal = t_jurnal_detail.idjurnal 
  inner join mst.t_cabang on mst.t_cabang.kd_cabang=t_jurnal.kd_cabang 
  inner join acc.t_akun on t_jurnal_detail.kd_akun=t_akun.kd_akun 
  left join acc.t_costcenter on acc.t_costcenter.kd_costcenter=t_jurnal_detail.kd_costcenter
WHERE    
  replace(t_jurnal.kd_transaksi,'',null) is null 
  ".$sqlwhereakun.$sqlwheretgl.$sqlwherecabang.$sqlwheredk.
"union all
 SELECT 
  t_jurnal.idjurnal, 
  t_jurnal.tgl_transaksi, 
  t_jurnal.kd_transaksi, 
  t_mjurnalpenutup.nama_transaksi, 
  t_jurnal.referensi, 
  t_jurnal.keterangan, 
  t_jurnal_detail.kd_akun,
  t_akun.nama,
  t_jurnal_detail.dk_akun, 
  t_jurnal_detail.dk_transaksi, 
  t_jurnal_detail.faktor, 
  t_jurnal_detail.jumlah, 
  t_jurnal_detail.debet, 
  t_jurnal_detail.kredit, 
  null kd_costcenter,
  null nama_costcenter,
  null keterangan_detail, 
  null ref_detail, 
  t_jurnal.kd_cabang,
  mst.t_cabang.nama_cabang,
  t_jurnal.created_by, 
  t_jurnal.created_date,t_jurnal.typepost,t_jurnal.idpost
FROM 
  acc.t_jurnal, 
  acc.t_mjurnalpenutup, 
  acc.t_jurnal_detail,acc.t_akun,
  mst.t_cabang
WHERE 
  t_jurnal.kd_transaksi = t_mjurnalpenutup.kd_transaksi AND
  t_jurnal.idjurnal = t_jurnal_detail.idjurnal and 
  mst.t_cabang.kd_cabang=t_jurnal.kd_cabang and 
  t_jurnal_detail.kd_akun=t_akun.kd_akun"
  .$sqlwhereakun.$sqlwheretgl.$sqlwherecabang.$sqlwheredk." order by tgl_transaksi,idjurnal,dk_transaksi ";
 
//echo $sqlbase;
//        $sqlbase="select * from acc.t_jurnal";
        $query= $this->db->query($sqlbase."LIMIT $length OFFSET $offset");
        
        $rows = array();
        $total=0;
		if($query->num_rows() > 0){
			$rows = $query->result();
//                        $total=$query->num_rows();
		}
                
        $this->db->flush_cache();        
	$query= $this->db->query($sqlbase);	
        if($query->num_rows() > 0){
            $total=$query->num_rows();
        }
        $results = '{success:true,record:'.$total.',data:'.json_encode($rows).'}';
                
        return $results;        
    }
    
   

}

?>
