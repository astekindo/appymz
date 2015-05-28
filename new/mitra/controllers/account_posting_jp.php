<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_posting_jp
 *
 * @author faroq
 */
class account_posting_jp extends MY_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_posting_jp_model','pjp_acc_model');
    }
    
    public function get_form() {
        $no_do = 'JP-';
        $sequence = $this->pjp_acc_model->get_kode_sequence($no_do, 3);        
        echo '{"success":true,
				"data":{
					"kd_postingjp":"' . $no_do . $sequence . '",
					"tgl_posting":"' . date('d-M-Y') . '"
				}
			}';
    }
    
    
    public function get_search_akun() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pjp_acc_model->get_search_akun($search, $start, $limit);

        echo $result;
    }
    
    public function get_rows_akun() {
//		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
//		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->pjp_acc_model->get_rows_akun($search);

        echo $result;
    }
    public function update_row(){
        $header_do['kd_postingjp']=isset($_POST['kd_postingjp']) ? $this->db->escape_str($this->input->post('kd_postingjp', TRUE)) : FALSE;   
        $header_do['referensi']=isset($_POST['referensi']) ? $this->db->escape_str($this->input->post('referensi', TRUE)) : FALSE;           
        $header_do['tgl_posting']=isset($_POST['tgl_posting']) ? $this->db->escape_str($this->input->post('tgl_posting', TRUE)) : FALSE;   
        $header_do['kd_transaksi']=isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : FALSE;   
        $header_do['keterangan']=isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : FALSE;   
        $header_do['created_by']=$this->session->userdata('username');
        $header_do['created_date']=date('Y-m-d H:i:s');
        
        $data_akun_evr = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        
        $header_result = FALSE;
        $detail_result = 0;
        
        if ($header_do['kd_postingjp']) {
            if (count($data_akun_evr) > 0) {
                if ($header_do['tgl_posting']) {
                    $header_do['tgl_posting'] = date('Y-m-d', strtotime($header_do['tgl_posting']));
                }
                $this->db->trans_start();
                $header_result = $this->pjp_acc_model->insert_row('acc.t_jurnalpenutup', $header_do);
                
                foreach ($data_akun_evr as $obj) {
                    unset($detail_do);
                    $detail_do['kd_postingjp'] = $header_do['kd_postingjp'];
                    $detail_do['kd_akun'] = $obj->kd_akun;
                    $detail_do['dk_akun'] = $obj->dk_akun;
                    $detail_do['dk_transaksi'] = $obj->dk_transaksi;
                    $detail_do['debet'] = $obj->debet;
                    $detail_do['kredit'] = $obj->kredit;
                    
                    $detail_result = $this->pjp_acc_model->insert_row('acc.t_jurnalpenutup_detail', $detail_do);                    
                }
                $this->db->trans_complete();
                
                $title = 'Entry Jurnal Penutup';
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
}

?>
