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
class account_entry_voucher extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_entry_voucher_model', 'evr_acc_model');
        $this->load->model('account_app_voucher_model', 'apvr_acc_model');
    }

    public function get_form() {
        $no_do = 'EV-';
        $sequence = '00000';
//        $thbl = date('Ym', strtotime(date('Y-m-d')));
//        $sequence = $this->evr_acc_model->get_kode_sequence($no_do, 3);        
        echo '{"success":true,
				"data":{
					"kd_voucher":"' . $no_do . $sequence . '",
					"tgl_transaksi":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function get_kdvoucher($tgltrx) {
        $no_do = 'EV-';
        $thbl = date('Ym', strtotime($tgltrx));
        $head = $no_do . $thbl . '-';
        $sequence = $this->evr_acc_model->get_kode_sequence($head, 5);

        return $head . $sequence;
    }

    public function get_cabang() {

        $result = $this->evr_acc_model->get_cabang();
        echo $result;
    }

    public function get_search_akun() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->evr_acc_model->get_search_akun($search, $start, $limit);

        echo $result;
    }

    public function get_rows_akun() {
//		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
//		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->evr_acc_model->get_rows_akun($search);

        echo $result;
    }

    public function get_header_transaksi() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->evr_acc_model->get_header_transaksi($search);

        echo $result;
    }

    public function get_kdjurnal($tgltrx) {
        $no_do = 'JR-';
        $thbl = date('Ym', strtotime($tgltrx));
        $head = $no_do . $thbl . '-';
        $sequence = $this->evr_acc_model->get_kode_sequence($head, 5);

        return $head . $sequence;
    }
    public function set_bb_loop($bbek,$bbin){
        foreach ($bbek as $key => $value) {
                            if ($value['kd_akun'] == $bbin['kd_akun'] && $value['thbl']==$bbin['thbl'] && $value['kd_cabang'] == $bbin['kd_cabang']){                                                   
                                $jml = $value['jumlah'];
                                $jml = $jml + $bbin['jumlah'];
                                $bbek[$key]['jumlah']= $jml;
                                return $bbek;
                            }
                        }
                        array_push($bbek, $bbin) ;
                        return $bbek;
    }
    public function set_posting($data_in,$data_akun) {
        $ret_value='';
        $tglapproval=$data_in['tgl_transaksi'];
        $kd_cabang=$data_in['kd_cabang'];
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
            $hjurnal['keterangan'] = $data_in['keterangan'];
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
            $arrrec=$data_akun;
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
                $djurnal['keterangan_detail'] = $obj->keterangan_detail;
                $kdakun=$djurnal['kd_akun']; 

                if ($this->apvr_acc_model->insert_row('acc.t_jurnal_detail', $djurnal)) {
                    $result++;
                }
                $bbin=array('thbl' => $thbl, 'kd_cabang' => $kd_cabang,'kd_akun' =>$kdakun, 'jumlah' => $jumlah);
                $bbek=$this->set_bb_loop($bbek, $bbin);
            }
//        }
        
        foreach ($bbek as $value){
            $ret_saldobb=$this->apvr_acc_model->get_saldo_bb_exists($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
            if ($ret_saldobb) {
                    $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                    $saldobb = $saldobb+$value['jumlah'];
                    $bbsaldo['saldo'] = $saldobb;
                    $where = array('kd_akun'=>$value['kd_akun'], 'thbl'=>$value['thbl'], 'kd_cabang'=>$value['kd_cabang']);
                    $retval=$this->apvr_acc_model->update_row_bb('acc.t_bukubesar_saldo', $bbsaldo,$where);
                    if($retval){
                    $result++;    
                    }

                } else {
                    $saldobb = $this->apvr_acc_model->get_saldo_bb($value['kd_akun'], $value['thbl'], $value['kd_cabang']);
                    if(!$saldobb){
                        $saldobb = 0;
                    }
                    
                    $saldobb = $saldobb+$value['jumlah'];
                    
                    $bbsaldo['saldo'] = $saldobb;
                    $bbsaldo['thbl'] = $value['thbl'];
                    $bbsaldo['kd_akun'] = $value['kd_akun'];
                    
                    $bbsaldo['saldo'] = $saldobb;
                    $bbsaldo['kd_cabang'] = $value['kd_cabang'];
                    $retval=$this->apvr_acc_model->insert_row('acc.t_bukubesar_saldo', $bbsaldo);
                    
                    if($retval){
                    $result++;    
                    }

                }
        }
        
        $this->db->trans_complete();
    }

    public function update_row() {
        $header_do['kd_voucher'] = isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher', TRUE)) : FALSE;
        $header_do['referensi'] = isset($_POST['referensi']) ? $this->db->escape_str($this->input->post('referensi', TRUE)) : FALSE;
        $header_do['tgl_transaksi'] = isset($_POST['tgl_transaksi']) ? $this->db->escape_str($this->input->post('tgl_transaksi', TRUE)) : FALSE;
        $header_do['kd_transaksi'] = isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : NULL;
        $header_do['keterangan'] = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : FALSE;
        $header_do['print_title'] = isset($_POST['print_title']) ? $this->db->escape_str($this->input->post('print_title', TRUE)) : '';
        $header_do['created_by'] = $this->session->userdata('username');
        $header_do['created_date'] = date('Y-m-d H:i:s');
//        $header_do['kd_costcenter'] == isset($_POST['kd_costcenter']) ? $this->db->escape_str($this->input->post('kd_costcenter', TRUE)) : FALSE;
        $header_do['kd_cabang'] = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : FALSE;
        $ismaster = isset($_POST['ismaster']) ? $this->db->escape_str($this->input->post('ismaster', TRUE)) : FALSE;
        $data_akun_evr = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $header_do['diterima_oleh'] = isset($_POST['diterima_oleh']) ? $this->db->escape_str($this->input->post('diterima_oleh', TRUE)) : NULL;
        $header_do['no_giro_cheque'] = isset($_POST['no_giro_cheque']) ? $this->db->escape_str($this->input->post('no_giro_cheque', TRUE)) : NULL;
        $header_do['approval1'] = isset($_POST['approval1']) ? $this->db->escape_str($this->input->post('approval1', TRUE)) : FALSE;
        $header_do['approval2'] = isset($_POST['approval2']) ? $this->db->escape_str($this->input->post('approval2', TRUE)) : FALSE;
        $header_do['approval3'] = isset($_POST['approval3']) ? $this->db->escape_str($this->input->post('approval3', TRUE)) : FALSE;
        $header_do['type_transaksi'] = isset($_POST['type_transaksi']) ? $this->db->escape_str($this->input->post('type_transaksi', TRUE)) : '';
        $header_do['kd_jenis_voucher'] = isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : '';
        $header_do['auto_posting_voucher'] = isset($_POST['autopost']) ? $this->db->escape_str($this->input->post('autopost', TRUE)) : FALSE;

        if ($header_do['approval1'] === 'on') {
            $header_do['approval1'] = 1;
        } else {
            $header_do['approval1'] = 0;
        }
//        echo "<script>console.log('".$header_do['approval1'].")</script>";
        if ($header_do['approval2'] == 'on') {
            $header_do['approval2'] = 1;
        } else {
            $header_do['approval2'] = 0;
        }

        if ($header_do['approval3'] == 'on') {
            $header_do['approval3'] = 1;
        } else {
            $header_do['approval3'] = 0;
        }
       
        if ($header_do['auto_posting_voucher' == 'on']) {
            $header_do['auto_posting_voucher'] = 1;
        } else {
            $header_do['auto_posting_voucher'] = 0;
        }
        
        $approvalstatus=TRUE;
        if ($header_do['approval1'] == 0 && $header_do['approval2'] == 0 && $header_do['approval3'] == 0) {
            $approvalstatus=FALSE;
            $header_do['auto_posting_voucher'] = 1;
        }
        
        $header_result = FALSE;
        $detail_result = 0;

        if ($header_do['tgl_transaksi']) {
            $header_do['tgl_transaksi'] = date('Y-m-d', strtotime($header_do['tgl_transaksi']));
        }

        $header_do['kd_voucher'] = $this->get_kdvoucher($header_do['tgl_transaksi']);

        if ($header_do['kd_voucher']) {
            if (count($data_akun_evr) > 0) {

                $this->db->trans_start();
                $header_result = $this->evr_acc_model->insert_row('acc.t_voucher', $header_do);

                foreach ($data_akun_evr as $obj) {
                    unset($detail_do);
                    if ($ismaster) {
                        $detail_do['kd_voucher'] = $header_do['kd_voucher'];
                        $detail_do['kd_akun'] = $obj->kd_akun;
                        $detail_do['dk_akun'] = $obj->dk_akun;
                        $detail_do['dk_transaksi'] = $obj->dk_transaksi;
                        $detail_do['kd_costcenter'] = $obj->costcenter;
                        $detail_do['debet'] = $obj->debet;
                        $detail_do['kredit'] = $obj->kredit;
                        $detail_do['keterangan_detail'] = $obj->keterangan_detail;
                    } else {
                        $detail_do['kd_voucher'] = $header_do['kd_voucher'];
                        $detail_do['kd_akun'] = $obj->kd_akun;
                        $detail_do['dk_akun'] = $obj->dk_akun;
                        if ($obj->debet > $obj->kredit) {
                            $detail_do['dk_transaksi'] = 'd';
                        } elseif ($obj->kredit > $obj->debet) {
                            $detail_do['dk_transaksi'] = 'k';
                        }
                        $detail_do['kd_costcenter'] = $obj->costcenter;
                        $detail_do['keterangan_detail'] = $obj->keterangan_detail;
                        $detail_do['debet'] = $obj->debet;
                        $detail_do['kredit'] = $obj->kredit;
                    }


                    $detail_result = $this->evr_acc_model->insert_row('acc.t_voucher_detail', $detail_do);
                }
                
                
            }
            if (!$approvalstatus) {
                    //auto posting
//                    echo 'tekan auto post';
                    if ($header_do['auto_posting_voucher']== 1) {
//                        echo 'tekan auto post sama dengan siji';
                        $this->set_posting($header_do,$data_akun_evr);
                    }
                }
            $this->db->trans_complete();
                
                $title = 'Entry Voucher';
                if ($header_result && $detail_result > 0) {

                    $result = '{"success":true,"errMsg":""}';
                    //$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_receive_order/print_form/" . $no_do . "/" . $title) . '"}';
                } else {
                    $result = '{"success":false,"errMsg":"Process Failed.' . count($data_akun_evr) . '."}';
                }
                echo $result;
        }
    }

}

?>
