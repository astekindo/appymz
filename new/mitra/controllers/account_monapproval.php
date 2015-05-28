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
        $sbapproval = isset($_POST['sbapproval']) ? $this->db->escape_str($this->input->post('sbapproval', TRUE)) : null;
        $kdcabang = isset($_POST['kdcabang']) ? $this->db->escape_str($this->input->post('kdcabang', TRUE)) : null;
        $all = isset($_POST['all']) ? $this->db->escape_str($this->input->post('all', TRUE)) : null;
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : null;        
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $stclose=isset($_POST['stclose']) ? $this->db->escape_str($this->input->post('stclose', TRUE)) : null;
        $result =  $this->monapproval_model->get_rows($all,$kdcabang,$tglawal,$tglakhir,$approval1,$approval2,$approval3,$sbapproval,$search,$start, $limit,$stclose);
        echo $result;
    }
    
    public function print_form($kd_voucher = '') {
        //sek cetakke
        $res=$this->monapproval_model->set_cetakke($kd_voucher,$this->session->userdata('username'),date('Y-m-d'));
        if (!$res){
            return;
        }
        $data = $this->monapproval_model->get_data_print($kd_voucher);
        if (!$data)
            show_404('page');

//        $this->load->library('CetakVoucher_pdf');
//        $pdf = new CetakVoucher_pdf('P','mm',array(241,279));
//        $pdf->AliasNbPages();
//        $pdf->SetFont('courier', '', 14);
//        $pdf->SetMargins(5,5,5);
//        $pdf->setCetak(array('cetakke'=>$data['header'][0]->cetakke,'tglcetak'=>$data['header'][0]->tglcetak,'cetakby'=>$data['header'][0]->cetakby));
//        $pdf->setRevisi(array('revisike'=>$data['header'][0]->revisike,'revisi_date'=>$data['header'][0]->revisi_date,'revisi_by'=>$data['header'][0]->revisi_by));
//        $pdf->AddPage('P');
//        $pdf->create_pdf($data['header'], $data['detail']);
//        $pdf->Output("cetakvoucher", "I");

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/CetakVoucher_pdf.php');
        $pdf = new CetakVoucher_pdf(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS_1/2', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->setCetak(array('cetakke'=>$data['header'][0]->cetakke,'tglcetak'=>$data['header'][0]->tglcetak,'cetakby'=>$data['header'][0]->cetakby));
        $pdf->setRevisi(array('revisike'=>$data['header'][0]->revisike,'revisi_date'=>$data['header'][0]->revisi_date,'revisi_by'=>$data['header'][0]->revisi_by));
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }
}

?>
