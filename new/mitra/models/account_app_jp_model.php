<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_app_jp_model
 *
 * @author faroq
 */
class account_app_jp_model extends MY_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    //put your code here

    public function get_rows($search = "", $offset, $length) {
        $this->db->select("CASE WHEN tv.aktif=1 THEN 0 ELSE 1 END as approval, 
  tv.kd_postingjp, 
  tv.tgl_posting, 
  tv.kd_transaksi, 
  tt.nama_transaksi, 
  tv.keterangan, 
  tv.referensi", FALSE);
        if ($search != "") {
            $sql_search = "(lower(tv.kd_postingjp) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->join("acc.t_mjurnalpenutup tt", "tv.kd_transaksi=tt.kd_transaksi");
        $this->db->where("tv.aktif", 1);
        $this->db->order_by("tv.kd_postingjp", "asc");
        $query = $this->db->get("acc.t_jurnalpenutup tv", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(tv.kd_postingjp) LIKE '%" . strtolower($search) . "%' or lower(tt.nama_transaksi) LIKE '%" . strtolower($search) . "%' or lower(tv.keterangan) LIKE '%" . strtolower($search) . "%' or lower(tv.referensi) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->join("acc.t_mjurnalpenutup tt", "tv.kd_transaksi=tt.kd_transaksi");
        $query = $this->db->get("acc.t_jurnalpenutup tv");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_rows_akun($search = "") {
        $this->db->select("tv.kd_postingjp, 
  tv.kd_akun, 
  ta.nama, 
  tv.dk_akun, 
  tv.dk_transaksi, 
  tv.debet,tv.kredit", FALSE);
        $this->db->join("acc.t_akun ta", "tv.kd_akun=ta.kd_akun");
        $this->db->where("tv.kd_postingjp", $search);
        $this->db->order_by("tv.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_jurnalpenutup_detail tv");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_rows_akun_loop($search = "") {
        $this->db->select("tv.kd_postingjp, 
  tv.kd_akun, 
  ta.nama, 
  tv.dk_akun, 
  trim(tv.dk_transaksi) as dk_transaksi, 
  tv.debet,tv.kredit", FALSE);
        $this->db->join("acc.t_akun ta", "tv.kd_akun=ta.kd_akun");
        $this->db->where("tv.kd_postingjp", $search);
        $this->db->order_by("tv.dk_transaksi", "asc");
        $query = $this->db->get("acc.t_jurnalpenutup_detail tv");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
            $total = $query->num_rows();
        }



        return $rows;
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

    public function get_saldo_bb_exists($kdakun = NULL, $thbl = NULL) {
        $this->db->select("saldo", FALSE);
        $this->db->where("thbl", $thbl);
        $this->db->where("kd_akun", $kdakun);
        $query = $this->db->get("acc.t_bukubesar_saldo");

//        $rows = array();
        $retval = FALSE;
        if ($query->num_rows() > 0) {
//            $rows = $query->result();
            $retval = TRUE;
        }



        return $retval;
    }

    public function insert_row($dbname = '', $data = NULL) {
        return $this->db->insert($dbname, $data);
    }

    public function update_row($datawhere = NULL, $data = NULL) {
        $this->db->where('kd_postingjp', $datawhere);
        return $this->db->update('acc.t_jurnalpenutup', $data);
        // print_r($this->db->last_query());
    }

    public function update_row_bb($dbname, $id1 = NULL, $id2 = NULL, $data = NULL) {
        $this->db->where('thbl', $id2);
        $this->db->where('kd_akun', $id1);
        return $this->db->update($dbname, $data);
        // print_r($this->db->last_query());
    }

}

?>
