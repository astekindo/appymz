<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_edit_voucher
 *
 * @author miyzan
 */
class account_edit_voucher extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_edit_voucher_model', 'edit_acc_model');
//        $this->load->model('account_entry_voucher_model', 'entry_acc_model');        
    }
    //put your code here
    
    public function get_form() {
//        $no_do = 'EV-';
//        $sequence = '00000';
//        $thbl = date('Ym', strtotime(date('Y-m-d')));
//        $sequence = $this->evr_acc_model->get_kode_sequence($no_do, 3);        
        echo '{"success":true,
				"data":{
					"kd_voucher":"",
					"tgl_transaksi":"' . date('Y-m-d') . '"
				}
			}';
    }
    
    public function  get_rows_header(){
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        
        $result = $this->edit_acc_model->get_rows_header($search, $start, $limit);

        echo $result;
        
    }
    
    public function  get_rows_detail(){        
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $result = $this->edit_acc_model->get_rows_detail($search);

        echo $result;
        
    }
    
//    approval1	on
//    autopost	off
//    data	[{"kd_akun":"140.0001","nama":"PIUTANG USAHA","dk_akun":"D","dk_transaksi":"K ","debet":0,"kredit":100000,"ref_detail":"","costcenter":"-","nama_costcenter":"","keterangan_detail":"-"},{"kd_akun":"110.0001","nama":"KAS BESAR","dk_akun":"D","dk_transaksi":"D ","debet":100000,"kredit":0,"ref_detail":"","costcenter":"-","nama_costcenter":"","keterangan_detail":"-"}]
//    diterima_oleh	
//    kd_cabang	001
//    kd_jenis_voucher	JV-01
//    kd_voucher	EV-201402-00008
//    keterangan	rerer
//    no_giro_cheque	
//    t_debet	100,000
//    t_kredit	100,000
//    t_selisih	0
//    tgl_jttempo	
//    tgl_transaksi	2014-02-13
//    type_transaksi	Cash In
    public function update_row() {
        $header_where['kd_voucher'] = isset($_POST['kd_voucher']) ? $this->db->escape_str($this->input->post('kd_voucher', TRUE)) : FALSE;
        $header_do['referensi'] = isset($_POST['referensi']) ? $this->db->escape_str($this->input->post('referensi', TRUE)) : NULL;
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
        $header_do['tgl_jttempo'] = isset($_POST['tgl_jttempo']) ? $this->db->escape_str($this->input->post('tgl_jttempo', TRUE)) : NULL;
        $header_do['keterangan'] =strtoupper($header_do['keterangan'] );
        if($header_do['tgl_jttempo']==''){
            $header_do['tgl_jttempo']=NULL;
        }
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

        $approvalstatus = TRUE;
//        if ($header_do['approval1'] == 0 && $header_do['approval2'] == 0 && $header_do['approval3'] == 0) {
//            $approvalstatus = FALSE;
//            $header_do['auto_posting_voucher'] = 1;
//        }

        $header_result = FALSE;
        $detail_result = 0;

        if ($header_do['tgl_transaksi']) {
            $header_do['tgl_transaksi'] = date('Y-m-d', strtotime($header_do['tgl_transaksi']));
        }

//        $header_do['kd_voucher'] = $this->get_kdvoucher($header_do['tgl_transaksi']);
        $header_result=0;
        if ($header_where['kd_voucher']) {
                $revke=$this->edit_acc_model->get_revke($header_where['kd_voucher']);
                $header_do['revisike']=$revke;
                $header_do['revisi_date']=date('Y-m-d');
                $header_do['revisi_by']=$this->session->userdata('username');
            if (count($data_akun_evr) > 0) {
                
                
                $this->db->trans_start();
                $header_result = $this->edit_acc_model->update_voucher('acc.t_voucher', $header_do,$header_where['kd_voucher']);
                $header_result = $this->edit_acc_model->delete_row('acc.t_voucher_detail', array('kd_voucher' => $header_where['kd_voucher']));

                foreach ($data_akun_evr as $obj) {
                    unset($detail_do);
                    if ($ismaster) {
                        $detail_do['kd_voucher'] = $header_where['kd_voucher'];
                        $detail_do['kd_akun'] = $obj->kd_akun;
                        $detail_do['dk_akun'] = $obj->dk_akun;
                        $detail_do['dk_transaksi'] = $obj->dk_transaksi;
                        $detail_do['kd_costcenter'] = $obj->costcenter;
                        $detail_do['debet'] = $obj->debet;
                        $detail_do['kredit'] = $obj->kredit;
                        $detail_do['keterangan_detail'] = strtoupper($obj->keterangan_detail);
                        $detail_do['ref_detail'] = strtoupper($obj->ref_detail);
                        
                    } else {
                        $detail_do['kd_voucher'] = $header_do['kd_voucher'];
                        $detail_do['kd_akun'] = $obj->kd_akun;
                        $detail_do['dk_akun'] = $this->getDKakun($obj->kd_akun);
                        if ($obj->debet > $obj->kredit) {
                            $detail_do['dk_transaksi'] = 'D';
                        } elseif ($obj->kredit > $obj->debet) {
                            $detail_do['dk_transaksi'] = 'K';
                        }
                        $detail_do['kd_costcenter'] = $obj->costcenter;
                        $detail_do['keterangan_detail'] = strtoupper($obj->keterangan_detail);
                        $detail_do['ref_detail'] = strtoupper($obj->ref_detail);
                        $detail_do['debet'] = $obj->debet;
                        $detail_do['kredit'] = $obj->kredit;
                    }


                    $detail_result = $this->edit_acc_model->insert_row('acc.t_voucher_detail', $detail_do);
                }
            }
//            if (!$approvalstatus) {
                //auto posting
//                    echo 'tekan auto post';
                $this->load->library('../controllers/account_entry_voucher');                
                $evr = new account_entry_voucher();
                if ($header_do['auto_posting_voucher'] == 1) {
                    $header_do['kd_voucher']=$header_where['kd_voucher'];
//                        echo 'tekan auto post sama dengan siji';
                    $evr->set_posting($header_do, $data_akun_evr);
                }
//            }
            $this->db->trans_complete();

            $title = 'Entry Voucher';
            $msg=  json_encode($header_do);
            if ($header_result && $detail_result > 0) {

                $result = '{"success":true,"errMsg":""}';
                //$result = '{"success":true,"errMsg":"","printUrl":"' . site_url("pembelian_receive_order/print_form/" . $no_do . "/" . $title) . '"}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed. ' . count($data_akun_evr) . '."}';
            }
            echo $result;
        }else{
            $result = '{"success":false,"errMsg":"'.$header_where['kd_voucher'].'"}';
        }
            
    }
}

?>
