<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaksi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi_model');
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

        $result = $this->transaksi_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_rows_akun() {
//		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
//		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->transaksi_model->get_rows_akun($search);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'POST')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->transaksi_model->get_row($id);

            return $result;
        }
    }
    
    public function get_rows_jenisvoucher(){
        $result = $this->transaksi_model->get_rows_jenisvoucher();
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_transaksi = isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : FALSE;
        $nama_transaksi = isset($_POST['nama_transaksi']) ? $this->db->escape_str($this->input->post('nama_transaksi', TRUE)) : FALSE;

        $approval1 = isset($_POST['approval1']) ? $this->db->escape_str($this->input->post('approval1', TRUE)) : FALSE;
        $limit_apv1 = isset($_POST['limit1']) ? $this->db->escape_str($this->input->post('limit1', TRUE)) : '0';
        $end_limit_apv1 = isset($_POST['endlimit1']) ? $this->db->escape_str($this->input->post('endlimit1', TRUE)) : '0';

        $approval2 = isset($_POST['approval2']) ? $this->db->escape_str($this->input->post('approval2', TRUE)) : FALSE;
        $limit_apv2 = isset($_POST['limit2']) ? $this->db->escape_str($this->input->post('limit2', TRUE)) : '0';
        $end_limit_apv2 = isset($_POST['endlimit2']) ? $this->db->escape_str($this->input->post('endlimit2', TRUE)) : '0';
        
        $approval3 = isset($_POST['approval3']) ? $this->db->escape_str($this->input->post('approval3', TRUE)) : FALSE;
        $limit_apv3 = isset($_POST['limit3']) ? $this->db->escape_str($this->input->post('limit3', TRUE)) : '0';
        $end_limit_apv3 = isset($_POST['endlimit3']) ? $this->db->escape_str($this->input->post('endlimit3', TRUE)) : '0';

        $kd_jenis_voucher = isset($_POST['kd_jenis_voucher']) ? $this->db->escape_str($this->input->post('kd_jenis_voucher', TRUE)) : '';
        $costcenter = isset($_POST['costcenter']) ? $this->db->escape_str($this->input->post('costcenter', TRUE)) : FALSE;

        $type_transaksi= isset($_POST['type_transaksi']) ? $this->db->escape_str($this->input->post('type_transaksi', TRUE)) : '';
        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : '0';
        $data_akun = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $retval = 0;
        
        if ($approval1=='on'){
            $approval1=1;
        }else{
            $approval1=0;
        }
        
        if ($approval2=='on'){
            $approval2=1;
        }else{
            $approval2=0;
        }
        if ($approval3=='on'){
            $approval3=1;
        }else{
            $approval3=0;
        }
        
        if ($costcenter=='on'){
            $costcenter=1;
        }else{
            $costcenter=0;
        }
        
            
        if (!$kd_transaksi) { //save   
            $kd_transaksi = 'TR-' . $this->transaksi_model->get_kode_sequence('TR-', 3);
            $data = array(
                'kd_transaksi' => $kd_transaksi,
                'nama_transaksi' => $nama_transaksi,
                'aktif' => '1',
                'approval1' => $approval1,
                'approval2' => $approval2,
                'limit_apv1' => $limit_apv1,
                'end_limit_apv1' => $end_limit_apv1,
                'limit_apv2' => $limit_apv2,
                'end_limit_apv2' => $end_limit_apv2,
                'costcenter' => $costcenter,
                'kd_jenis_voucher' => $kd_jenis_voucher,
                'type_transaksi'=> $type_transaksi,
                'approval3' => $approval3,
                'limit_apv3' => $limit_apv3,
                'end_limit_apv3' => $end_limit_apv3
            );
            $this->db->trans_start();
            if ($this->transaksi_model->insert_row('acc.t_transaksi', $data)) {
                $retval++;
                if (count($data_akun) > 0) {
                    foreach ($data_akun as $obj) {

                        $data_detail = array(
                            'kd_transaksi' => $kd_transaksi,
                            'kd_akun' => $obj->kd_akun,
                            'dk_akun' => $obj->dk_akun,
                            'dk_transaksi' => $obj->dk_transaksi
                        );
                        if ($this->transaksi_model->insert_row('acc.t_transaksi_detail', $data_detail)) {
                            $retval++;
                        }
                        unset($data_detail);
                    }
                }
            } else {
                $retval = 0;
            }
            $this->db->trans_complete();
        } else { //edit      			
//            $updated_by = $this->session->userdata('username');
//            $updated_date = date('Y-m-d H:i:s');
            $datau = array(
                'kd_transaksi' => $kd_transaksi,
                'nama_transaksi' => $nama_transaksi,
                'aktif' => '1',
                'approval1' => $approval1,
                'approval2' => $approval2,
                'limit_apv1' => $limit_apv1,
                'end_limit_apv1' => $end_limit_apv1,
                'limit_apv2' => $limit_apv2,
                'end_limit_apv2' => $end_limit_apv2,
                'costcenter' => $costcenter,
                'kd_jenis_voucher' => $kd_jenis_voucher,
                'type_transaksi'=> $type_transaksi,
                'approval3' => $approval3,
                'limit_apv3' => $limit_apv3,
                'end_limit_apv3' => $end_limit_apv3
            );

            $this->db->trans_start();
            if ($this->transaksi_model->update_row('acc.t_transaksi', $kd_transaksi, $datau)) {
                $retval++;
                if (count($data_akun) > 0) {
                    if ($this->transaksi_model->cek_exists_rowakun($kd_transaksi) > 0) {
                        $this->transaksi_model->delete_rowAll($kd_transaksi);
                    }

                    foreach ($data_akun as $obj) {

                        $data_detail = array(
                            'kd_transaksi' => $kd_transaksi,
                            'kd_akun' => $obj->kd_akun,
                            'dk_akun' => $obj->dk_akun,
                            'dk_transaksi' => $obj->dk_transaksi
                        );

                        if ($this->transaksi_model->insert_row('acc.t_transaksi_detail', $data_detail)) {
                            $recdataakun = "insert row sucses";
                            $retval++;
                        }
                        unset($data_detail);
                    }
                } else {
                    if ($this->transaksi_model->cek_exists_rowakun($kd_transaksi) > 0) {
                        $this->transaksi_model->delete_rowAll($kd_transaksi);
                    }
                }
            } else {
                $retval = 0;
            }
            $this->db->trans_complete();
        }
        if ($retval > 0) {
            $result = '{"success":true,"errMsg":""}';
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

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row() {
        $kd_transaksi = isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : FALSE;

        $data = array(
            'aktif' => '0'
        );

        if ($this->transaksi_model->delete_row($kd_transaksi, $data)) {
            $result = '{"success":true,"errMsg":""}';
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
    public function get_all() {
        $result = $this->transaksi_model->get_all();

        echo $result;
    }
    
    public function get_dk() {
        
        $result = '{success:true,record:2,data:[{"nama":"Debet","nilai":"D"},{"nama":"Kredit","nilai":"K"},{"nama":"Test","nilai":"T"}]}';

        echo $result;
    }
    
    public function get_dk_akunvoucher() {
        $kdvoucher=isset($_POST['kdvoucher']) ? $this->db->escape_str($this->input->post('kdvoucher', TRUE)) : FALSE;
        $kdakun=isset($_POST['kdakun']) ? $this->db->escape_str($this->input->post('kdakun', TRUE)) : FALSE;
        $result = $this->transaksi_model->cek_exists_akunvoucher($kdvoucher,$kdakun);

        echo $result;
    }

}