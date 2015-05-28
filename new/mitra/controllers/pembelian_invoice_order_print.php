<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pembelian_invoice_order_print extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('pembelian_invoice_order_print_model', 'piop_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        $kd_peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->piop_model->get_rows($kd_supplier, $kd_peruntukan, $search, $start, $limit);

        echo $result;
    }

    public function get_rows_detail($no_invoice = '') {
        $hasil = $this->piop_model->get_rows_detail($no_invoice);
        $results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
                        $diskon1 = '0%';
			$diskon2 = '0%';
			$diskon3 = '0%';
			$diskon4 = '0%';
                        if($result->disk_amt_supp1 > 0)
				{
                                    $diskon1 = number_format($result->disk_amt_supp1, 0,',','.');
				}	
				else
				{
					//$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                                    $diskon1 = $result->disk_persen_supp1 . '%';
				}
				if($result->disk_amt_supp2 > 0)
				{
                                    $diskon2 = number_format($result->disk_amt_supp2, 0,',','.');
                                }	
				else
				{
					$diskon2 = $result->disk_persen_supp2 . '%';	
				}
				if($result->disk_amt_supp3 > 0)
				{
                                    $diskon3 = number_format($result->disk_amt_supp3, 0,',','.');
                                    			
				}	
				else
				{
					$diskon3 = $result->disk_persen_supp3 . '%';	
				}
				if($result->disk_amt_supp4 > 0)
				{
                                    $diskon4 = number_format($result->disk_amt_supp4, 0,',','.');
                                    			
				}	
				else
				{
					$diskon4 = $result->disk_persen_supp4 . '%';	
				}
			$diskon5 = number_format($result->disk_amt_supp5, 0,',','.');
			//diskon Rp
			$result->disk_grid_supp1 = $diskon1;
			$result->disk_grid_supp2 = $diskon2;
			$result->disk_grid_supp3 = $diskon3;
			$result->disk_grid_supp4 = $diskon4;
			$result->disk_grid_supp5 = $diskon5;
			
			$harga_net = $result->harga_supplier - $result->rp_total_diskon;
                        $result->harga_net= $harga_net;
                        
                        $harga_net_ect = $harga_net / 1.1;
                        $result->harga_net_ect= $harga_net_ect;
                        
			$dpp_po = ($result->dpp_po) * $result->qty_terima;
			$rp_total_po = $dpp_po;
			$result->rp_total_po = $rp_total_po;
			$results[] = $result;
                        //print_r($results[]);
		}
		echo '{success:true,data:'.json_encode($results).'}';
        //echo $result;
    }
    public function print_form($no_invoice = ''){
		$data = $this->piop_model->get_data_print($no_invoice);
		if(!$data) show_404('page');
				
		$this->output->set_content_type("application/pdf");
		//require_once(APPPATH . 'libraries/PembelianInvoiceOrderPrint.php');
                require_once(APPPATH . 'libraries/PembelianCreateInvoicePrint.php');
		$pdf = new PembelianCreateInvoicePrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setKertas();
		$pdf->privateData($data['header'],$data['detail']);
		$pdf->Output();	
		exit;
    }
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
}
