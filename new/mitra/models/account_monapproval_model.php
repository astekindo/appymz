<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_monapproval_model
 *
 * @author miyzan
 */
class account_monapproval_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function get_rows($all = null, $kdcabang=null,$tglawal = NULL, $tglakhir = NULL, $approval1 = NULL, $approval2 = NULL, $approval3 = NULL, $sb = NULL, $search = NULL, $offset, $length,$stclose=NULL) {
        $sqlwheretgl = "";
        $sqlwherecabang="";
        $sqlwhere = "";
        $sqlwhereapproval = "";
        $sqlsearch = "";
        $sqlstatus="";
        if($stclose){
            if($stclose=='true'){
                $sqlstatus=" acc.t_voucher.status_close is true ";
            }else{
                $sqlstatus="";
//                $sqlstatus=" acc.t_voucher.status_close is false ";
            }
                
            
        }
        if ($tglawal && $tglakhir) {
            $sqlwheretgl = "acc.t_voucher.tgl_transaksi between '$tglawal' and '$tglakhir'";
        }
        if($kdcabang){
            $sqlwherecabang="acc.t_voucher.kd_cabang='$kdcabang'";
        }
        if (!$all) {
            if ($sb=='true') {
                if ($approval1=='false' && $approval2=='false' && $approval3=='false') {
                    $sqlwhereapproval = "(
                        CASE WHEN acc.t_voucher.aktif =1 THEN
                                    CASE WHEN status_posting=1 THEN 2 
                                    ELSE 
                                         CASE WHEN status_apv2=1 THEN 
                                            CASE WHEN  acc.t_voucher.approval2=1 THEN 2
                                            ELSE acc.t_voucher.aktif END 
                                         ELSE 
                                            CASE WHEN status_apv3=1 THEN 
                                                    CASE WHEN  acc.t_voucher.approval3=1 THEN 2 
                                                    ELSE acc.t_voucher.aktif END 
                                            ELSE acc.t_voucher.aktif END 
                                         END 
                                    END
                            ELSE 
                                    acc.t_voucher.aktif		
                            END  =2 	
                         OR
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END =1 
                         OR
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END =1) ";
                }
            } else {
                if ($approval1=='false' && $approval2=='false' && $approval3=='false') {
                     $sqlwhereapproval ="
                         CASE WHEN acc.t_voucher.aktif =1 THEN
                                    CASE WHEN status_posting=1 THEN 2 
                                    ELSE 
                                         CASE WHEN status_apv2=1 THEN 
                                            CASE WHEN  acc.t_voucher.approval2=1 THEN 2
                                            ELSE acc.t_voucher.aktif END 
                                         ELSE 
                                            CASE WHEN status_apv3=1 THEN 
                                                    CASE WHEN  acc.t_voucher.approval3=1 THEN 2 
                                                    ELSE acc.t_voucher.aktif END 
                                            ELSE acc.t_voucher.aktif END 
                                         END 
                                    END
                            ELSE 
                                    acc.t_voucher.aktif		
                            END  =1 	
                         AND
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END IS NULL
                         AND
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END IS NULL";
                }
            }
            if ($approval1=='true') {
                if ($sb=='true') {
                    $sqlwhereapproval = "
                        CASE WHEN acc.t_voucher.aktif =1 THEN
                                    CASE WHEN status_posting=1 THEN 2 
                                    ELSE 
                                         CASE WHEN status_apv2=1 THEN 
                                            CASE WHEN  acc.t_voucher.approval2=1 THEN 2
                                            ELSE acc.t_voucher.aktif END 
                                         ELSE 
                                            CASE WHEN status_apv3=1 THEN 
                                                    CASE WHEN  acc.t_voucher.approval3=1 THEN 2 
                                                    ELSE acc.t_voucher.aktif END 
                                            ELSE acc.t_voucher.aktif END 
                                         END 
                                    END
                            ELSE 
                                    acc.t_voucher.aktif		
                            END  =2 ";
                } else {
                    $sqlwhereapproval = "
                        CASE WHEN acc.t_voucher.aktif =1 THEN
                            CASE WHEN status_posting=1 THEN 2 
                            ELSE 
                                 CASE WHEN status_apv2=1 THEN 
                                    CASE WHEN  acc.t_voucher.approval2=1 THEN 2
                                    ELSE acc.t_voucher.aktif END 
                                 ELSE 
                                    CASE WHEN status_apv3=1 THEN 
                                            CASE WHEN  acc.t_voucher.approval3=1 THEN 2 
                                            ELSE acc.t_voucher.aktif END 
                                    ELSE acc.t_voucher.aktif END 
                                 END 
                            END
                    ELSE 
                            acc.t_voucher.aktif		
                    END =1 ";
                }
            }
            if ($approval2=='true') {
                if (strlen($sqlwhereapproval) > 0) {
                    if ($sb=='true') {
                        $sqlwhereapproval = $sqlwhereapproval . " AND 
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END  =1";
                    } else {
                        $sqlwhereapproval = $sqlwhereapproval . " AND
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END IS NULL";
                    }
                } else {
                    if ($sb=='true') {
                        $sqlwhereapproval = "
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END  =1";
                    } else {
                        $sqlwhereapproval = "
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END IS NULL";
                    }
                }
            }
            if ($approval3=='true') {
                if (strlen($sqlwhereapproval) > 0) {
                    if ($sb=='true') {
                        $sqlwhereapproval = $sqlwhereapproval . " AND
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END =1 ";
                    } else {
                        $sqlwhereapproval = $sqlwhereapproval . " AND
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END IS NULL ";
                    }
                } else {
                    if ($sb=='true') {
                        $sqlwhereapproval = "
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END =1 ";
                    } else {
                        $sqlwhereapproval = "
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END IS NULL ";
                    }
                }
            }
        }

        if ($search) {
            $search = strtolower($search);
            $sqlsearch = "((lower(acc.t_voucher.kd_voucher) like '%$search%') or (lower(acc.t_voucher.referensi) like '%$search%') or (lower(acc.t_voucher.keterangan) like '%$search%') or (lower(acc.t_jenis_voucher.title) like '%$search%') or (lower(acc.t_transaksi.nama_transaksi) like '%$search%'))";
        }
        if (strlen($sqlwheretgl) > 0) {
            $sqlwhere = "where " . $sqlwheretgl;
        }
        if(strlen($sqlwherecabang)>0){
            if (strlen($sqlwhere) > 0) {
               $sqlwhere =  $sqlwhere . " and " .$sqlwherecabang;
            }else{
                $sqlwhere = "where " . $sqlwherecabang;
            }
        }
        if (strlen($sqlwhereapproval) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere = $sqlwhere . " and " . $sqlwhereapproval;
            } else {
                $sqlwhere = "where " . $sqlwhereapproval;
            }
        }
        if (strlen($sqlsearch) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere = $sqlwhere . " and " . $sqlsearch;
            } else {
                $sqlwhere = "where " . $sqlsearch;
            }
        }
        if (strlen($sqlstatus) > 0) {
            if (strlen($sqlwhere) > 0) {
                $sqlwhere = $sqlwhere . " and " . $sqlstatus;
            }else {
                $sqlwhere = "where " . $sqlstatus;
            }
        }

        







        $sqlbase = "SELECT
                acc.t_voucher.tgl_transaksi,
                acc.t_voucher.kd_voucher,
                acc.t_voucher.kd_transaksi,
                acc.t_transaksi.nama_transaksi,
                acc.t_voucher.kd_jenis_voucher,
                acc.t_jenis_voucher.title,
                acc.t_voucher.referensi,
                acc.t_voucher.keterangan,
                acc.t_voucher.approval1,                
                CASE WHEN acc.t_voucher.aktif =1 THEN
                                    CASE WHEN status_posting=1 THEN 2 
                                    ELSE 
                                         CASE WHEN status_apv2=1 THEN 
                                            CASE WHEN  acc.t_voucher.approval2=1 THEN 1
                                            ELSE 0 END 
                                         ELSE 
                                            CASE WHEN status_apv3=1 THEN 
                                                    CASE WHEN  acc.t_voucher.approval3=1 THEN 1 
                                                    ELSE 0 END 
                                            ELSE 0 END 
                                         END 
                                    END
                            ELSE 
                                   case when acc.t_voucher.aktif =2 then 1 else 0 end
                            END AS status_apv1,
                            CASE WHEN status_apv2 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1
                                            ELSE 
                                                    CASE WHEN status_apv3 = 1 THEN
                                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN 1
                                                            ELSE NULL
                                                            END
                                                    ELSE NULL
                                                    END
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval2 = 1 THEN
                                                    status_apv2
                                            ELSE NULL 
                                            END 
                                    END 
                            END AS status_apv2,
                            CASE WHEN status_apv3 IS NULL THEN
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE 
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL 
                                            END 
                                    END
                            ELSE
                                    CASE WHEN status_posting = 1 THEN 1 
                                    ELSE
                                            CASE WHEN acc.t_voucher.approval3 = 1 THEN
                                                    status_apv3
                                            ELSE NULL
                                            END
                                    END
                            END AS status_apv3,
                acc.t_voucher.approval_by,
                acc.t_voucher.approval_date,
                acc.t_voucher.approval2,                
                acc.t_voucher.approval2_by,
                acc.t_voucher.approval2_date,
                acc.t_voucher.approval3,                
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
                mst.t_cabang.nama_cabang,
                case when acc.t_voucher.status_close is true then 1 else 2 end as status_close,
                acc.t_voucher.close_by,
                acc.t_voucher.close_date,
                rj.count_reject
                FROM
                acc.t_voucher
                LEFT JOIN acc.t_transaksi ON acc.t_voucher.kd_transaksi = acc.t_transaksi.kd_transaksi
                INNER JOIN acc.t_jenis_voucher ON acc.t_voucher.kd_jenis_voucher = acc.t_jenis_voucher.kd_jenis_voucher
		LEFT JOIN acc.t_jurnal ON acc.t_voucher.kd_voucher = acc.t_jurnal.idpost and acc.t_voucher.kd_cabang=acc.t_jurnal.kd_cabang 
                INNER JOIN mst.t_cabang on acc.t_voucher.kd_cabang=mst.t_cabang.kd_cabang 
                LEFT JOIN (select kd_voucher,count(*) count_reject from acc.t_histo_voucher GROUP BY kd_voucher) rj on rj.kd_voucher=acc.t_voucher.kd_voucher "
                . $sqlwhere . " order by acc.t_voucher.tgl_transaksi ";

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
    
    
    public function set_cetakke($kd_voucher = '',$by='',$dt=null) {
        $sql="update acc.t_voucher 
            set cetakke=(case when cetakke is null then 0 else cetakke end)+1,
            cetakby='$by',tglcetak='$dt' 
            where kd_voucher='$kd_voucher'
        ";
        $result=  $this->db->query($sql);
        return $result;
    }

    public function get_dataheader($kd_voucher = '') {
        $sqlbase = "SELECT
                acc.t_voucher.tgl_transaksi,
                acc.t_voucher.kd_voucher,
                acc.t_voucher.kd_transaksi,
                acc.t_transaksi.nama_transaksi,
                acc.t_voucher.kd_jenis_voucher,
                acc.t_jenis_voucher.title,
                acc.t_voucher.referensi,
                acc.t_voucher.keterangan,
                acc.t_voucher.approval1,
                CASE WHEN acc.t_voucher.aktif=2 THEN 1 ELSE 0 END AS status_apv1,
                acc.t_voucher.approval_by,
                uapp1.nama_jabatan as app1jabatan,
                acc.t_voucher.approval_date,
                acc.t_voucher.approval2,
                acc.t_voucher.status_apv2,
                acc.t_voucher.approval2_by,
                uapp2.nama_jabatan as app2jabatan,
                acc.t_voucher.approval2_date,
                acc.t_voucher.approval3,
                acc.t_voucher.status_apv3,
                acc.t_voucher.approval3_by,
                uapp3.nama_jabatan as app3jabatan,
                acc.t_voucher.approval3_date,
                acc.t_voucher.auto_posting_voucher,
                acc.t_voucher.status_posting,
                acc.t_voucher.posting_by,
                acc.t_voucher.posting_date,
                acc.t_jurnal.idjurnal,
                acc.t_voucher.created_date,
                acc.t_voucher.created_by,
                acc.t_voucher.diterima_oleh,
                acc.t_voucher.no_giro_cheque,
                acc.t_voucher.tgl_jttempo,
                acc.t_voucher.cetakke,
                acc.t_voucher.cetakby,
                acc.t_voucher.tglcetak,
                acc.t_voucher.revisike,
                acc.t_voucher.revisi_by,
                acc.t_voucher.revisi_date                
                FROM
                acc.t_voucher
                LEFT JOIN acc.t_transaksi ON acc.t_voucher.kd_transaksi = acc.t_transaksi.kd_transaksi
                INNER JOIN acc.t_jenis_voucher ON acc.t_voucher.kd_jenis_voucher = acc.t_jenis_voucher.kd_jenis_voucher
		LEFT JOIN acc.t_jurnal ON acc.t_voucher.kd_voucher = acc.t_jurnal.idpost and acc.t_voucher.kd_cabang=acc.t_jurnal.kd_cabang 
                LEFT JOIN (SELECT secman.t_user.username,secman.t_jabatan.nama_jabatan,secman.t_divisi.nama_divisi 
                    from secman.t_user 
                    INNER JOIN secman.t_jabatan ON secman.t_user.kd_jabatan = secman.t_jabatan.kd_jabatan 
                    INNER JOIN secman.t_divisi ON secman.t_jabatan.kd_divisi = secman.t_divisi.kd_divisi 
                    ) as uapp1 on acc.t_voucher.created_by=uapp1.username 
                LEFT JOIN (SELECT secman.t_user.username,secman.t_jabatan.nama_jabatan,secman.t_divisi.nama_divisi 
                    from secman.t_user 
                    INNER JOIN secman.t_jabatan ON secman.t_user.kd_jabatan = secman.t_jabatan.kd_jabatan 
                    INNER JOIN secman.t_divisi ON secman.t_jabatan.kd_divisi = secman.t_divisi.kd_divisi 
                ) as uapp2 on acc.t_voucher.approval2_by=uapp2.username 
                LEFT JOIN (SELECT secman.t_user.username,secman.t_jabatan.nama_jabatan,secman.t_divisi.nama_divisi 
                    from secman.t_user
                    INNER JOIN secman.t_jabatan ON secman.t_user.kd_jabatan = secman.t_jabatan.kd_jabatan 
                    INNER JOIN secman.t_divisi ON secman.t_jabatan.kd_divisi = secman.t_divisi.kd_divisi 
                ) as uapp3 on acc.t_voucher.approval3_by=uapp3.username 
                "
                . "WHERE acc.t_voucher.kd_voucher = '$kd_voucher'";

//echo $sqlbase;
//        $sqlbase="select * from acc.t_jurnal";
        $query = $this->db->query($sqlbase);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();

        return $rows;
    }

    public function get_datadetail($kd_voucher = '') {
        $this->db->select("tv.kd_voucher, 
            tv.kd_akun, 
            ta.nama,             
            tc.nama_costcenter as costcenter,tv.keterangan_detail,
            tv.debet,tv.kredit", FALSE);
        $this->db->join("acc.t_akun ta", "tv.kd_akun=ta.kd_akun");
        $this->db->join("acc.t_costcenter tc", "tv.kd_costcenter=tc.kd_costcenter", "left");
        $this->db->where("tv.kd_voucher", $kd_voucher);
        $this->db->where("(tv.debet > 0 or tv.kredit >0)");
//        $this->db->where_or("tv.kredit >", 0);
        $this->db->order_by("tv.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_voucher_detail tv");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $this->db->flush_cache();
        return $rows;
    }

    public function get_data_print($kd_voucher = '') {

        $dataprint['header'] = $this->get_dataheader($kd_voucher);
        $dataprint['detail'] = $this->get_datadetail($kd_voucher);
        return $dataprint;
    }

}

?>
