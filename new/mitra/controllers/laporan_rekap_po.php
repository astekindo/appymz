<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan_rekap_po extends MY_Controller {
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        //$this->load->model('laporan_penerimaan_barang_model', 'lpb_model');
                $this->load->model('laporan_rekap_po_model');

    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_supplier(){
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

        $result = $this->laporan_rekap_po_model->search_supplier($search, $start, $limit);

        echo $result;
    }


    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($kd_supplier = ''){

        $data = $this->laporan_rekap_po_model->get_data_print($kd_supplier);
        if(!$data) show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/LaporanRekapPOPrint.php');
        $pdf = new LaporanRekapPOPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['detail']);
        $pdf->Output();
        exit;
    }

    public function get_report() {
        $params = array(
            'creator'       => $this->session->userdata('username'),
            'tgl_min'       => $this->form_data('dari_tgl','2014-01-01'),
            'tgl_max'       => $this->form_data('sampai_tgl','2014-01-31'),
            'supplier'      => $this->form_data('kd_supplier'),
        );

        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/laporan_rekap_po.rptdesign&__parameterpage=false';

        foreach ($params as $key => $value) {
            if($key === 'tgl_min' || $key === 'tgl_max') $value = date('Y-m-d', strtotime($value));
            if(!is_null($value) || !empty($value)) $reportURL .= "&$key=$value";
        }

        echo json_encode(array(
            'success' => true,
            'successMsg' => 'Siapkan kertas A3 (Continuous Form)',
            'printUrl' => $reportURL
        ));
    }
}
