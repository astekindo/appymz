<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_entry_voucher
 *
 * @author faroq
 */
class account_app_voucher extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('account_app_voucher_model', 'apvr_acc_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : '';

        $result = $this->apvr_acc_model->get_rows_approval1($search, $start, $limit, $kd_cabang);

        echo $result;
    }

    public function get_rows_approval2() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : '';

        $result = $this->apvr_acc_model->get_rows_approval2($search, $start, $limit, $kd_cabang);

        echo $result;
    }
    public function get_rows_approval4() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : '';

        $result = $this->apvr_acc_model->get_rows_approval4($search, $start, $limit, $kd_cabang);

        echo $result;
    }

    public function get_rows_approval3() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : '';

        $result = $this->apvr_acc_model->get_rows_approval3($search, $start, $limit, $kd_cabang);

        echo $result;
    }

    public function get_rows_akun() {

        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->apvr_acc_model->get_rows_akun($search);

        echo $result;
    }

    public function get_kdjurnal($tgltrx) {
        $no_do = 'JR-';
        $thbl = date('Ymd', strtotime($tgltrx));
        $head = $no_do . $thbl . '-';
        $sequence = $this->apvr_acc_model->get_kode_sequence($head, 5);

        return $head . $sequence;
    }

    public function set_bb_loop($bbek, $bbin) {
        foreach ($bbek as $key => $value) {
            if ($value['kd_akun'] == $bbin['kd_akun'] && $value['thbl'] == $bbin['thbl'] && $value['kd_cabang'] == $bbin['kd_cabang']) {
                $jml = $value['jumlah'];
                $jml = $jml + $bbin['jumlah'];
                $bbek[$key]['jumlah'] = $jml;
                return $bbek;
            }
        }
        array_push($bbek, $bbin);
        return $bbek;
    }

    public function set_posting($data_in, $data_akun) {
        $ret_value = '';
        $tglapproval = $data_in['tgl_transaksi'];
        $kd_cabang = $data_in['kd_cabang'];
        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }
        $bbek = array();
        $bbin = array();
        $kdakun = "";

        $thbl = 0;
        $jumlah = 0;
        $result = 0;
        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }

        $result = 0;
        $this->db->trans_start();
//        foreach ($data_in as $v) {
        $idjurnal = $this->get_kdjurnal($tglapproval);
        unset($hvoucher);
        $hvoucher['posting_by'] = $this->session->userdata('username');
        $hvoucher['posting_date'] = $tglapproval;
        $hvoucher['aktif'] = 2;
        $hvoucher['status_posting'] = 1;
        $result = $this->apvr_acc_model->update_row($data_in['kd_voucher'], $hvoucher);
//            if ($result){
//                $ret_value=$ret_value.'-setvoucher '.$data_in['kd_voucher'];
//            }

        $hjurnal['idjurnal'] = $idjurnal;
        $hjurnal['tgl_transaksi'] = $tglapproval;
        $hjurnal['kd_transaksi'] = $data_in['kd_transaksi'];
        $hjurnal['referensi'] = $data_in['referensi'];
        $hjurnal['keterangan'] = strtoupper($data_in['keterangan']);
        $hjurnal['created_by'] = $hvoucher['posting_by'];
        $hjurnal['created_date'] = $hvoucher['posting_date'];
        $hjurnal['typepost'] = 'voucher';
        $hjurnal['idpost'] = $data_in['kd_voucher'];
        $hjurnal['kd_cabang'] = $kd_cabang;

        if ($this->apvr_acc_model->insert_row('acc.t_jurnal', $hjurnal)) {
            $result++;
        }

        $thbltrx = explode("-", $data_in['tgl_transaksi']);
        $thbl = $thbltrx[0] . $thbltrx[1];


//            $arrrec = $this->apvr_acc_model->get_rows_akun_loop($v->kd_voucher);
        $arrrec = $data_akun;
        foreach ($arrrec as $obj) {
            $fak = 1;
            if (strtolower($obj->dk_akun) == strtolower($obj->dk_transaksi)) {
                $fak = 1;
            } else {
                $fak = -1;
            }

            $jumlah = ($obj->debet + $obj->kredit) * $fak;

            $djurnal['idjurnal'] = $idjurnal;
            $djurnal['kd_akun'] = $obj->kd_akun;
            $djurnal['dk_akun'] = $obj->dk_akun;
            $djurnal['dk_transaksi'] = $obj->dk_transaksi;
            $djurnal['faktor'] = $fak;
            $djurnal['jumlah'] = $jumlah;
            $djurnal['debet'] = $obj->debet;
            $djurnal['kredit'] = $obj->kredit;
            $djurnal['kd_costcenter'] = $obj->kd_costcenter;
            $djurnal['keterangan_detail'] = strtoupper($obj->keterangan_detail);
            $djurnal['ref_detail'] = strtoupper($obj->ref_detail);
            $kdakun = $djurnal['kd_akun'];

            if ($this->apvr_acc_model->insert_row('acc.t_jurnal_detail', $djurnal)) {
                $result++;
            }
            $bbin = array('thbl' => $thbl, 'kd_cabang' => $kd_cabang, 'kd_akun' => $kdakun, 'jumlah' => $jumlah);
            $bbek = $this->set_bb_loop($bbek, $bbin);
        }
//        }

        foreach ($bbek as $value) {
            $bbsaldo=array();
            $ret_saldobb = $this->apvr_acc_model->get_saldo_bb_exists($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
            if ($ret_saldobb) {
                $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                $saldobb = $saldobb + $value['jumlah'];
                $bbsaldo['saldo'] = $saldobb;
                $where = array('kd_akun' => $value['kd_akun'], 'thbl' => $value['thbl'], 'kd_cabang' => $value['kd_cabang']);
                $retval = $this->apvr_acc_model->update_row_bb('acc.t_bukubesar_saldo', $bbsaldo, $where);
                if ($retval) {
                    $result++;
                }
            } else {
                $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                if (!$saldobb) {
                    $saldobb = 0;
                }

                $saldobb = $saldobb + $value['jumlah'];

//                $bbsaldo['saldo'] = $saldobb;
                $bbsaldo['thbl'] = $value['thbl'];
                $bbsaldo['kd_akun'] = $value['kd_akun'];

                $bbsaldo['saldo'] = $saldobb;
                $bbsaldo['kd_cabang'] = $value['kd_cabang'];
                $retval = $this->apvr_acc_model->insert_row('acc.t_bukubesar_saldo', $bbsaldo);

                if ($retval) {
                    $result++;
                }
            }
            $bbsaldo=array();
            $ret_saldobb_after=$this->apvr_acc_model->get_saldo_bb_after($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
               if(count($ret_saldobb_after)>0){
                   foreach ($ret_saldobb_after as $vafter) {
                        $saldobb = $vafter->saldo + $value['jumlah'];
                        $bbsaldo['saldo'] = $saldobb;
                        $where = array('kd_akun'=>$value['kd_akun'], 'thbl'=>$vafter->thbl, 'kd_cabang'=>$value['kd_cabang']);
                        $retval=$this->apvr_acc_model->update_row_bb('acc.t_bukubesar_saldo', $bbsaldo,$where);
                   }
               }
        }

        $this->db->trans_complete();
    }

    public function update_row() {
        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $tglapproval = isset($_POST['tglapproval']) ? $this->db->escape_str($this->input->post('tglapproval', TRUE)) : false;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : false;
//        $nd= 'JR-';
//        $thbl=
//        $seqq = $this->apvr_acc_model->get_kode_sequence($nd, 3); 
        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }

        $hlevel = array();
        $autopost = FALSE;

        $result = 0;
        $this->db->trans_start();
        foreach ($data_in as $v) {
//            $idjurnal=$this->get_kdjurnal($tglapproval);
            $autopost = FALSE;
            $ret_applevel = $this->apvr_acc_model->get_approval_level($v->kd_voucher, $hvoucher);
            foreach ($ret_applevel as $obj) {
                $hlevel['approval1'] = $obj->approval1;
                $hlevel['approval2'] = $obj->approval2;
                $hlevel['approval3'] = $obj->approval3;
                $hlevel['auto_posting_voucher'] = $obj->auto_posting_voucher;
            }

            if ($hlevel['auto_posting_voucher'] == 1 && $hlevel['approval2'] == 0 && $hlevel['approval3'] == 0) {
                $autopost = TRUE;
            }
            unset($hvoucher);
            $hvoucher['approval_by'] = $this->session->userdata('username');
            $hvoucher['approval_date'] = $tglapproval;
            if ($hlevel['approval2'] == 0) {
                $hvoucher['status_apv2'] = 1;
            }
            if ($hlevel['approval3'] == 0) {
                $hvoucher['status_apv3'] = 1;
            }
            $hvoucher['aktif'] = 2;
            $result = $this->apvr_acc_model->update_row($v->kd_voucher, $hvoucher);

            if ($autopost) {
                $data_akun = $this->apvr_acc_model->get_rows_akun_loop($v->kd_voucher);
                unset($headerdo);
                $headerdo['tgl_transaksi'] = $tglapproval;
                $headerdo['kd_voucher'] = $v->kd_voucher;
                $headerdo['kd_cabang'] = $kd_cabang;
                $this->set_posting($headerdo, $data_akun);
            }
        }
        $this->db->trans_complete();
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":""}';
        } else {
            $retval = '{"success":false,"errMsg":"Process Failed ' . $result . '"}';
        }
        echo $retval;
    }

    public function update_row2() {
        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $tglapproval = isset($_POST['tglapproval']) ? $this->db->escape_str($this->input->post('tglapproval', TRUE)) : false;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : false;
        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }

        $hlevel = array();
        $autopost = FALSE;

        $result = 0;
        $this->db->trans_start();
        foreach ($data_in as $v) {
//            $idjurnal=$this->get_kdjurnal($tglapproval);
            $autopost = FALSE;
            $ret_applevel = $this->apvr_acc_model->get_approval_level($v->kd_voucher);
            foreach ($ret_applevel as $obj) {
                $hlevel['approval1'] = $obj->approval1;
                $hlevel['approval2'] = $obj->approval2;
                $hlevel['approval3'] = $obj->approval3;
                $hlevel['auto_posting_voucher'] = $obj->auto_posting_voucher;
            }
            if ($hlevel['auto_posting_voucher'] == 1 && $hlevel['approval2'] == 0 && $hlevel['approval3'] == 0) {
                $autopost = TRUE;
            }
            unset($hvoucher);
            $hvoucher['approval2_by'] = $this->session->userdata('username');
            $hvoucher['approval2_date'] = $tglapproval;
            $hvoucher['aktif'] = 2;
            $hvoucher['status_apv2'] = 1;
            if ($hlevel['approval3'] == 0) {
                $hvoucher['status_apv3'] = 1;
            }
            $result = $this->apvr_acc_model->update_row($v->kd_voucher, $hvoucher);


            if ($autopost) {
                $data_akun = $this->apvr_acc_model->get_rows_akun_loop($v->kd_voucher);
                unset($headerdo);
                $headerdo['tgl_transaksi'] = $tglapproval;
                $headerdo['kd_voucher'] = $v->kd_voucher;
                $headerdo['kd_cabang'] = $kd_cabang;
                $this->set_posting($headerdo, $data_akun);
            }
        }
        $this->db->trans_complete();
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":""}';
        } else {
            $retval = '{"success":false,"errMsg":"Process Failed ' . $result . '"}';
        }
        echo $retval;
    }

    public function update_row4() {
        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $tglapproval = isset($_POST['tglapproval']) ? $this->db->escape_str($this->input->post('tglapproval', TRUE)) : false;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : false;
        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }

        $hlevel = array();
        $autopost = FALSE;

        $result = 0;
        $this->db->trans_start();
        foreach ($data_in as $v) {
//            $idjurnal=$this->get_kdjurnal($tglapproval);
            $autopost = FALSE;
            $ret_applevel = $this->apvr_acc_model->get_approval_level($v->kd_voucher);
            foreach ($ret_applevel as $obj) {
                $hlevel['approval1'] = $obj->approval1;
                $hlevel['approval2'] = $obj->approval2;
                $hlevel['approval3'] = $obj->approval3;
                $hlevel['auto_posting_voucher'] = $obj->auto_posting_voucher;
            }
            if ($hlevel['auto_posting_voucher'] == 1) {
                $autopost = TRUE;
            }
            unset($hvoucher);
            $hvoucher['approval3_by'] = $this->session->userdata('username');
            $hvoucher['approval3_date'] = $tglapproval;
            $hvoucher['aktif'] = 2;
            $hvoucher['status_apv3'] = 1;
            
            $result = $this->apvr_acc_model->update_row($v->kd_voucher, $hvoucher);


            if ($autopost) {
                $data_akun = $this->apvr_acc_model->get_rows_akun_loop($v->kd_voucher);
                unset($headerdo);
                $headerdo['tgl_transaksi'] = $tglapproval;
                $headerdo['kd_voucher'] = $v->kd_voucher;
                $headerdo['kd_cabang'] = $kd_cabang;
                $this->set_posting($headerdo, $data_akun);
            }
        }
        $this->db->trans_complete();
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":""}';
        } else {
            $retval = '{"success":false,"errMsg":"Process Failed ' . $result . '"}';
        }
        echo $retval;
    }
    
    public function update_row3() {
        $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $tglapproval = isset($_POST['tglapproval']) ? $this->db->escape_str($this->input->post('tglapproval', TRUE)) : false;
        $kd_cabang = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : false;

        if ($tglapproval) {
            $tglapproval = date('Y-m-d', strtotime($tglapproval));
        }
        $bbek = array();
        $bbin = array();
        $kdakun = "";

        $thbl = 0;
        $jumlah = 0;
        $result = 0;
        $this->db->trans_start();
        foreach ($data_in as $v) {
            $idjurnal = $this->get_kdjurnal($tglapproval);
            unset($hvoucher);
            $hvoucher['posting_by'] = $this->session->userdata('username');
            $hvoucher['posting_date'] = $tglapproval;
            $hvoucher['aktif'] = 2;
            $hvoucher['status_posting'] = 1;
            $result = $this->apvr_acc_model->update_row($v->kd_voucher, $hvoucher);


            $hjurnal['idjurnal'] = $idjurnal;
            $hjurnal['tgl_transaksi'] = $tglapproval;
            $hjurnal['kd_transaksi'] = $v->kd_transaksi;
            $hjurnal['referensi'] = $v->referensi;
            $hjurnal['keterangan'] = strtoupper($v->keterangan);
            $hjurnal['created_by'] = $hvoucher['posting_by'];
            $hjurnal['created_date'] = date("Y-m-d");//$hvoucher['posting_date'];
            $hjurnal['typepost'] = 'voucher';
            $hjurnal['idpost'] = $v->kd_voucher;
            $hjurnal['kd_cabang'] = $kd_cabang;

            if ($this->apvr_acc_model->insert_row('acc.t_jurnal', $hjurnal)) {
                $result++;
            }

            $thbltrx = explode("-", $tglapproval);
            $thbl = $thbltrx[0] . $thbltrx[1];


            $arrrec = $this->apvr_acc_model->get_rows_akun_loop($v->kd_voucher);
            foreach ($arrrec as $obj) {
                $fak = 1;
                if (strtolower($obj->dk_akun) == strtolower($obj->dk_transaksi)) {
                    $fak = 1;
                } else {
                    $fak = -1;
                }

                $jumlah = ($obj->debet + $obj->kredit) * $fak;

                $djurnal['idjurnal'] = $idjurnal;
                $djurnal['kd_akun'] = $obj->kd_akun;
                $djurnal['dk_akun'] = $obj->dk_akun;
                $djurnal['dk_transaksi'] = $obj->dk_transaksi;
                $djurnal['faktor'] = $fak;
                $djurnal['jumlah'] = $jumlah;
                $djurnal['debet'] = $obj->debet;
                $djurnal['kredit'] = $obj->kredit;
                $djurnal['kd_costcenter'] = $obj->kd_costcenter;
                $djurnal['keterangan_detail'] = strtoupper($obj->keterangan_detail);
                $djurnal['ref_detail'] = strtoupper($obj->ref_detail);
                $kdakun = $obj->kd_akun;
                $result++;

                if ($this->apvr_acc_model->insert_row('acc.t_jurnal_detail', $djurnal)) {
                    $result++;
                }

                $bbin = array('thbl' => $thbl, 'kd_cabang' => $kd_cabang, 'kd_akun' => $kdakun, 'jumlah' => $jumlah);
                $bbek = $this->set_bb_loop($bbek, $bbin);
            }
        }
        foreach ($bbek as $value) {
            $bbsaldo=array();
            $ret_saldobb = $this->apvr_acc_model->get_saldo_bb_exists($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
            if ($ret_saldobb) {
                $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                $saldobb = $saldobb + $value['jumlah'];
                $bbsaldo['saldo'] = $saldobb;
                $where = array('kd_akun' => $value['kd_akun'], 'thbl' => $value['thbl'], 'kd_cabang' => $value['kd_cabang']);
                $retval = $this->apvr_acc_model->update_row_bb('acc.t_bukubesar_saldo', $bbsaldo, $where);
                if ($retval) {
                    $result++;
                }
            } else {
                $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                if (!$saldobb) {
                    $saldobb = 0;
                }

                $saldobb = $saldobb + $value['jumlah'];

                $bbsaldo['saldo'] = $saldobb;
                $bbsaldo['thbl'] = $value['thbl'];
                $bbsaldo['kd_akun'] = $value['kd_akun'];

                $bbsaldo['saldo'] = $saldobb;
                $bbsaldo['kd_cabang'] = $value['kd_cabang'];
                $retval = $this->apvr_acc_model->insert_row('acc.t_bukubesar_saldo', $bbsaldo);

                if ($retval) {
                    $result++;
                }
            }
            $bbsaldo=array();
            $ret_saldobb_after=$this->apvr_acc_model->get_saldo_bb_after($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
               if(count($ret_saldobb_after)>0){
                   foreach ($ret_saldobb_after as $vafter) {
                        $saldobb = $vafter->saldo + $value['jumlah'];
                        $bbsaldo['saldo'] = $saldobb;
                        $where = array('kd_akun'=>$value['kd_akun'], 'thbl'=>$vafter->thbl, 'kd_cabang'=>$value['kd_cabang']);
                        $retval=$this->apvr_acc_model->update_row_bb('acc.t_bukubesar_saldo', $bbsaldo,$where);
                   }
               }
        }


        $this->db->trans_complete();
        if ($result > 0) {
            $retval = '{"success":true,"errMsg":' . json_encode($bbek) . '}';
        } else {
            $retval = '{"success":false,"errMsg":"Process Failed ' . $result . '"}';
        }
        echo $retval;
    }

}

?>
