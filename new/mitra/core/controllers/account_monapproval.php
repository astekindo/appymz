<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_monapproval
 *
 * @author miyzan
 */
class account_monapproval extends MY_Controller{ 
    public function __construct() {
        parent::__construct();
        $this->load->model('account_monapproval_model','monapproval_model');
    }
    //put your code here
    
    public function get_rows(){
        $tglawal = isset($_POST['tglawal']) ? $this->db->escape_str($this->input->post('tglawal', TRUE)) : null;
        $tglakhir = isset($_POST['tglakhir']) ? $this->db->escape_str($this->input->post('tglakhir', TRUE)) : null;
        $approval1 = isset($_POST['approval1']) ? $this->db->escape_str($this->input->post('approval1', TRUE)) : null;
        $approval2 = isset($_POST['approval2']) ? $this->db->escape_str($this->input->post('approval2', TRUE)) : null;
        $approval3 = isset($_POST['approval3']) ? $this->db->escape_str($this->input->post('approval3', TRUE)) : null;
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : null;        
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        
        $result =  $this->monapproval_model->get_rows($tglawal,$tglakhir,$approval1,$approval2,$approval3,$search,$start, $limit);
        echo $result;
    }
    
    public function print_form($kd_voucher = '') {
//		$this->psj_model->setCetakKe($nno_sj);

        $data = $this->monapproval_model->get_data_print($kd_voucher);
        //var_dump($data); die();
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/CetakVoucher.php');
        $pdf = new CetakVoucher(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, "ZZZZZZ", true, 'UTF-8', false);
        
        $pdf->setKertas();        
        $pdf->privateData($data['header'], $data['detail']);        
        $pdf->Output("catakvoucher","I");
//        exit;
    }
}

?>
