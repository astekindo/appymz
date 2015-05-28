<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Konsinyasi_create_request extends MY_Controller {
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('konsinyasi_create_request_model', 'kcr_model');
        $this->load->model('pembelian_create_request_model', 'pcr_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form(){
        // $no_ro = 'KR' . date('Ymd') . '-';
        // $sequence = $this->kcr_model->get_kode_sequence($no_ro, 3);
        echo '{"success":true,
                "data":{
                    "no_ro":"",
                    "user_peruntukan":"'. $this->session->userdata('user_peruntukan') .'",
                    "tgl_ro":"' . date('d-M-Y'). '"
                }
            }';
    }

     function record_sort($records, $field, $reverse=false){
        $hash = array();

        foreach($records as $record){
            $hash[$record[$field]] = $record;
        }

        ($reverse)? krsort($hash) : ksort($hash);

        $records = array();

        foreach($hash as $record){
            $records []= $record;
        }

        return $records;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row(){
        // $no_ro = isset($_POST['no_ro']) ? $this->db->escape_str($this->input->post('no_ro',TRUE)) : FALSE;
        $subject = isset($_POST['subject']) ? $this->db->escape_str($this->input->post('subject',TRUE)) : FALSE;
        $tgl_ro = isset($_POST['tgl_ro']) ? $this->db->escape_str($this->input->post('tgl_ro',TRUE)) : FALSE;
        $current_date = date('Ymd', strtotime($tgl_ro));
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : FALSE;
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail',TRUE)) : array();

        $header_result = FALSE;
        $detail_result = 0;
        unset($search_array);

        if(count($detail) > 0){
            //cegah kode produk ganda
            foreach ($detail as $key => $value) {
                $input_ref[$key] = $value->kd_produk;
            }
            $input_unique = array_unique($input_ref);
            if( count($input_unique) !== count($input_ref) ) {
                echo '{"success":false,"errMsg":"Kode produk ganda, cek kembali inputan produk."}';
                exit;
            }

            if($tgl_ro){
                $tgl_ro = date('Y-m-d', strtotime($tgl_ro));
            }
            $this->db->trans_begin();

            $top_temp = 0;
            $no_ro_success = '';

            foreach($detail as $obj){
                unset($detail_kr);
                if($obj->kd_produk != '' && $obj->qty != ''){ //yg diinsert di detail ga boleh kosong

                    $detail_kr['kd_produk'] = $obj->kd_produk;
                    $detail_kr['qty'] = $obj->qty;
                    $detail_kr['qty_adj'] = $obj->qty;
                    $detail_kr['status'] = '0';
                    $detail_kr['qty_po'] = 0;
                    $detail_kr['created_by'] = $this->session->userdata('username');
                    $detail_kr['created_date'] = date('Y-m-d H:i:s');
                    $detail_kr['updated_by'] = $this->session->userdata('username');
                    $detail_kr['updated_date'] = date('Y-m-d H:i:s');
                    if($obj->qty+$obj->jml_stok > $obj->max_stok){
                        echo '{"success":false,"errMsg":"Qty Order + Jml Stok tidak boleh lebih besar dari Max. Stok"}';
                        $this->db->trans_rollback();
                        exit;
                    }
                    if($obj->qty+$obj->jml_stok < $obj->min_stok){
                        echo '{"success":false,"errMsg":"Qty Order + Jml Stok tidak boleh lebih kecil dari Min. Stok"}';
                        $this->db->trans_rollback();
                        exit;
                    }

                    if($obj->is_kelipatan_order == 'YA'){
                        if($obj->qty < $obj->min_order){
                            echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                        if(($obj->qty % $obj->min_order) != 0){
                            echo '{"success":false,"errMsg":"Qty Order harus kelipatan dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }
                    }else{
                        if($obj->qty < $obj->min_order){
                            echo '{"success":false,"errMsg":"Qty Order tidak boleh lebih kecil dari Min. Order"}';
                            $this->db->trans_rollback();
                            exit;
                        }

                    }

                    if($obj->waktu_top != $top_temp){

                        if (array_key_exists($obj->waktu_top, $search_array)) {
                            $no_ro = $search_array[$obj->waktu_top];
                        }else{
                            $no_ro = 'KR' . $current_date . '-';
                            $sequence = $this->pcr_model->get_kode_sequence($no_ro, 3);
                            $no_ro = $no_ro . $sequence;

                            $header_kr['no_ro'] = $no_ro;
                            $header_kr['subject'] = $subject;
                            $header_kr['status'] = '0';
                            $header_kr['tgl_ro'] = $tgl_ro;
                            $header_kr['close_ro'] = 0;
                            $header_kr['kd_supplier'] = $kd_supplier;
                            $header_kr['konsinyasi'] = 1;
                            $header_kr['created_by'] = $this->session->userdata('username');
                            $header_kr['created_date'] = date('Y-m-d H:i:s');
                            $header_kr['updated_by'] = $this->session->userdata('username');
                            $header_kr['updated_date'] = date('Y-m-d H:i:s');
                            $header_kr['waktu_top'] = $obj->waktu_top;
                            $header_kr['kd_peruntukan'] = $kd_peruntukan;
                            $header_result = $this->kcr_model->insert_row('purchase.t_purchase_request', $header_kr);


                            $search_array[$obj->waktu_top] = $no_ro;
                            $no_ro_success = $no_ro_success . ' , ' . $no_ro;
                        }
                        $top_temp = $obj->waktu_top;
                    }

                    $detail_kr['no_ro'] = $no_ro;
                    if($this->kcr_model->insert_row('purchase.t_dtl_purchase_request', $detail_kr)){
                        $detail_result++;
                    }
                }
            }
            $this->db->trans_commit();
            unset($search_array);
        }

        if ($header_result && $detail_result > 0) {
            $result = '{"success":true,"errMsg":"Pembuatan KR berhasil, Listing KR : '. $no_ro_success . '","printUrl":"' . site_url("konsinyasi_create_request/print_form/" . $no_ro) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_all_produk($search_by = ""){
        $keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
        $result = $this->kcr_model->get_all_produk($search_by, $keyword);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row_produk(){
        $search_by = isset($_POST['search_by']) ? $this->db->escape_str($this->input->post('search_by',TRUE)) : "";
        $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
        $result = $this->kcr_model->get_row_produk($search_by, $id);

        echo '{success:true,data:'.json_encode($result).'}';
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

        $result = $this->kcr_model->search_supplier($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function search_produk_by_supplier(){
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';

        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
        $action = isset($_POST['action']) ? $this->db->escape_str($this->input->post('action',TRUE)) : '';
        $sender = isset($_POST['sender']) ? $this->db->escape_str($this->input->post('sender',TRUE)) : '';

        $results = $this->kcr_model->search_produk_by_supplier($kd_supplier, $sender,$search, $start, $limit);

        $result = '{"success":true,"data":'.json_encode($results).'}';
        //harusnya ambil dari form untuk yang statusnya all.
        $kd_peruntukkan = $this->session->userdata('user_peruntukan');

        if($action == 'validate'){
            $validate = $this->kcr_model->validate_pr_by_kd_produk($kd_produk,$kd_peruntukkan);

            if(array_key_exists('kr', $validate) && $validate['kr']->sum != 0){
                $result = '{"success":false,"errMsg":"Ada Outstanding KR dengan Kode Produk '.$kd_produk.' sebanyak '.$validate['pr']->sum.'"}';
            }
//            if($validate['peruntukan']->harga_jual == '' or $validate['peruntukan']->harga_jual == 0){
//                $result = '{"success":false,"errMsg":"Harga Jual Untuk Kode Produk '.$kd_produk.' masih kosong"}';
//            }

        } else if($action == 'validate_ps') {
            $validate = $this->kcr_model->validate_pr_on_po($kd_produk,$kd_peruntukkan);

            if($validate['ps']->sum != 0){
                $result = '{"success":true,"errMsg":"Ada Outstanding PS dengan Kode Produk '.$kd_produk.' sebanyak '.$validate['po']->sum.'"}';
            }

        }
        echo $result;
    }

    public function print_form($no_ro = ''){

        $this->pcr_model->setCetakKe($no_ro);

        $data = $this->kcr_model->get_data_print($no_ro);
        if(!$data) show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembelianCreateRequestPrint.php');
        $pdf = new PembelianCreateRequestPrint(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'],$data['detail']);
        $pdf->Output();
        exit;
    }
}